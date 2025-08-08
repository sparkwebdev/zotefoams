import { test } from '@playwright/test';
import { mkdirSync, writeFileSync, existsSync, unlinkSync } from 'fs';
import path from 'path';
import sharp from 'sharp';
import pixelmatch from 'pixelmatch';
import { PNG } from 'pngjs';
import { generateReport } from './generateReport.js';
import open from 'open';
import { pages as pagesLevel1 } from './src/pages-level-1.js';
import { pages as pagesLevel2 } from './src/pages-level-2.js';
import { pages as pagesLevel3 } from './src/pages-level-3.js';

const deviceConfigs = {
  desktop: { width: 1600, height: 900 },
  tablet: { width: 768, height: 1024 },
  mobile: { width: 375, height: 812 }
};

// Configure which devices to test (can be overridden with VRC_DEVICES env var)
const deviceSelection = process.env.VRC_DEVICES || 'all';
const includedDevices = deviceSelection === 'desktop-only' 
  ? ['desktop'] 
  : deviceSelection === 'all' 
    ? ['desktop', 'tablet', 'mobile']
    : deviceSelection.split(',').map(d => d.trim());

// Configure your URLs here - update these for your specific environment
const devBase = 'https://zotefoams-phase-2.local';
const liveBase = 'https://zoteliveref.local';
const outputDir = path.resolve('tests/vrc/results');

// Choose which page level to test (1 = main pages, 2 = extended, 3 = all)
const testLevel = process.env.VRC_LEVEL || '1';
const maxFailures = parseInt(process.env.VRC_MAX_FAILURES || '5');

let pages = [];
switch (testLevel) {
  case '3':
    pages = [...pagesLevel1, ...pagesLevel2, ...pagesLevel3];
    break;
  case '2':
    pages = [...pagesLevel1, ...pagesLevel2];
    break;
  case '1':
  default:
    pages = [...pagesLevel1];
    break;
}

// Create all test combinations
const testCombinations = [];
for (const device of includedDevices) {
  for (const page of pages) {
    testCombinations.push({ device, page });
  }
}

// Ensure output directory exists
if (!existsSync(outputDir)) {
  mkdirSync(outputDir, { recursive: true });
}

async function preparePage(context, url, pageName, deviceIdentifier) {
  const page = await context.newPage();
  console.log(`[${pageName}-${deviceIdentifier}] Navigating to ${url}`);
  
  try {
    await page.goto(url, { waitUntil: 'networkidle', timeout: 120000 });
    
    // Hide elements that might cause false positives in VRC
    await page.evaluate(() => {
      document.querySelectorAll('[data-testid="current-time"], .timestamp, .current-date').forEach(el => {
        if (el instanceof HTMLElement) el.style.display = 'none';
      });
      
      document.querySelectorAll('.share-price-widget, .live-counter, .timestamp').forEach(el => {
        if (el instanceof HTMLElement) el.style.display = 'none';
      });
      
      document.querySelectorAll('.cky-overlay, .cky-consent-container, .cky-modal, .cookie-notice').forEach(el => {
        if (el instanceof HTMLElement) el.style.display = 'none';
      });
      
      const style = document.createElement('style');
      style.textContent = `
        *, *::before, *::after {
          animation-duration: 0s !important;
          animation-delay: 0s !important;
          transition-duration: 0s !important;
          transition-delay: 0s !important;
          scroll-behavior: auto !important;
        }
        
        .swiper-button-next,
        .swiper-button-prev,
        .swiper-pagination {
          opacity: 0 !important;
        }
        
        .swiper-wrapper {
          transform: translateX(0px) !important;
        }
      `;
      document.head.appendChild(style);
      
      document.querySelectorAll('.swiper').forEach((el) => {
        const swiperInstance = el.swiper;
        if (swiperInstance) {
          if (swiperInstance.autoplay && typeof swiperInstance.autoplay.stop === 'function') {
            swiperInstance.autoplay.stop();
          }
          if (typeof swiperInstance.slideTo === 'function') {
            swiperInstance.slideTo(0, 0);
          }
        }
      });
    });
    
    await page.waitForLoadState('networkidle');
    
    // Wait for page stability before screenshots
    await page.evaluate(() => {
      return new Promise(resolve => {
        // Wait for any pending reflows/repaints
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            resolve();
          });
        });
      });
    });
    
    await page.waitForTimeout(500);
    
  } catch (e) {
    console.error(`[${pageName}-${deviceIdentifier}] Error navigating to ${url}: ${e.message}`);
    await page.close();
    throw e;
  }

  return page;
}

async function runSingleComparison(browser, device, page) {
  const { path: pagePath, name: pageName } = page;
  const viewport = deviceConfigs[device];
  
  const resultEntry = {
    name: pageName,
    device,
    url: pagePath,
    devUrl: `${devBase}${pagePath}`,
    liveUrl: `${liveBase}${pagePath}`,
    status: 'error',
    diffPixels: 0,
    errorMessage: null,
    devScreenshotFile: `${pageName}_${device}_dev.png`,
    liveScreenshotFile: `${pageName}_${device}_live.png`,
    diffScreenshotFile: `${pageName}_${device}_diff.png`
  };

  let context = null;
  let devPage = null;
  let livePage = null;

  try {
    context = await browser.newContext({ 
      viewport,
      ignoreHTTPSErrors: true,
    });

    const devUrl = resultEntry.devUrl;
    const liveUrl = resultEntry.liveUrl;

    try {
      devPage = await preparePage(context, devUrl, pageName, `${device}-dev`);
      livePage = await preparePage(context, liveUrl, pageName, `${device}-live`);
    } catch (pageLoadError) {
      console.error(`[${pageName}-${device}] Failed to load pages: ${pageLoadError.message}`);
      resultEntry.errorMessage = `Page load failed: ${pageLoadError.message}`;
      return resultEntry; // Don't close here, let finally handle cleanup
    }

    const devPath = path.join(outputDir, resultEntry.devScreenshotFile);
    const livePath = path.join(outputDir, resultEntry.liveScreenshotFile);
    const diffPath = path.join(outputDir, resultEntry.diffScreenshotFile);

    console.log(`[${pageName}-${device}] Taking screenshots.`);
    
    // Take screenshots with error handling
    try {
      await devPage.screenshot({ 
        path: devPath, 
        fullPage: true,
        timeout: 30000 // 30 second timeout for screenshots
      });
    } catch (screenshotError) {
      throw new Error(`Dev screenshot failed: ${screenshotError.message}`);
    }
    
    try {
      await livePage.screenshot({ 
        path: livePath, 
        fullPage: true,
        timeout: 30000 // 30 second timeout for screenshots
      });
    } catch (screenshotError) {
      throw new Error(`Live screenshot failed: ${screenshotError.message}`);
    }

    console.log(`[${pageName}-${device}] Comparing images.`);
    const devSharp = sharp(devPath);
    const liveSharp = sharp(livePath);
    const devMeta = await devSharp.metadata();
    const liveMeta = await liveSharp.metadata();

    if (!devMeta.width || !devMeta.height || !liveMeta.width || !liveMeta.height) {
        throw new Error('Could not get metadata for one or both images. Ensure images were saved correctly.');
    }
    
    const minWidth = Math.min(devMeta.width, liveMeta.width);
    const minHeight = Math.min(devMeta.height, liveMeta.height);

    const devBuffer = await devSharp.extract({ left: 0, top: 0, width: minWidth, height: minHeight }).png().toBuffer();
    const liveBuffer = await liveSharp.extract({ left: 0, top: 0, width: minWidth, height: minHeight }).png().toBuffer();

    const devImg = PNG.sync.read(devBuffer);
    const liveImg = PNG.sync.read(liveBuffer);
    const diffImg = new PNG({ width: minWidth, height: minHeight });

    const diffPixelsValue = pixelmatch(
      devImg.data, liveImg.data, diffImg.data,
      minWidth, minHeight, { threshold: 0.1, includeAA: true }
    );
    resultEntry.diffPixels = diffPixelsValue;

    if (diffPixelsValue > 200) {
      writeFileSync(diffPath, PNG.sync.write(diffImg));
      resultEntry.status = 'diff';
      console.warn(`[${pageName}-${device}] Visual difference found: ${diffPixelsValue} pixels. Diff image: ${resultEntry.diffScreenshotFile}`);
    } else {
      resultEntry.status = 'pass';
      console.log(`[${pageName}-${device}] Visual check passed.`);
      // Clean up images for passes
      [devPath, livePath].forEach(p => {
        if (existsSync(p)) {
          try {
            unlinkSync(p);
          } catch (e) {
            console.warn(`[${pageName}-${device}] Could not delete ${p}: ${e.message}`);
          }
        }
      });
    }

  } catch (error) {
    console.error(`❌ Error in test [${pageName}-${device}]: ${error.message}`);
    resultEntry.status = 'error';
    resultEntry.errorMessage = error.message;
  } finally {
    console.log(`[${pageName}-${device}] Cleaning up test resources.`);
    try {
      if (devPage && !devPage.isClosed()) {
        await devPage.close();
      }
    } catch (e) {
      console.warn(`[${pageName}-${device}] Error closing dev page: ${e.message}`);
    }
    
    try {
      if (livePage && !livePage.isClosed()) {
        await livePage.close();
      }
    } catch (e) {
      console.warn(`[${pageName}-${device}] Error closing live page: ${e.message}`);
    }
    
    try {
      if (context && !context._closed) {
        await context.close();
      }
    } catch (e) {
      console.warn(`[${pageName}-${device}] Error closing context: ${e.message}`);
    }
  }

  return resultEntry;
}

// Generate report function
async function generateFinalReport(testResults, bailedOut = false, failureCount = 0) {
  console.log('\n🎯 Generating VRC report...');
  
  const finalResultsMap = new Map();
  for (const result of testResults) {
    const key = `${result.name}-${result.device}`;
    finalResultsMap.set(key, result);
  }
  const finalUniqueResults = Array.from(finalResultsMap.values());

  if (finalUniqueResults.length > 0) {
    try {
      const reportPath = generateReport(finalUniqueResults, outputDir, {
        bailedOut,
        maxFailures,
        failureCount
      });
      console.log(`📊 Report generated at: ${reportPath}`);
      
      const stats = finalUniqueResults.reduce((acc, result) => {
        acc[result.status] = (acc[result.status] || 0) + 1;
        acc.total++;
        return acc;
      }, { pass: 0, diff: 0, error: 0, total: 0 });
      
      console.log(`\n📈 Test Summary:`);
      console.log(`   Total: ${stats.total}`);
      console.log(`   ✅ Passed: ${stats.pass}`);
      console.log(`   ⚠️  Differences: ${stats.diff || 0}`);
      console.log(`   ❌ Errors: ${stats.error || 0}`);
      console.log(`   Success Rate: ${((stats.pass / stats.total) * 100).toFixed(1)}%\n`);
      
      await open(reportPath);
      console.log('🌐 Report opened in browser.');
      return reportPath;
    } catch (reportError) {
      console.error(`Failed to generate or open report: ${reportError.message}`);
    }
  } else {
    console.warn("No test results recorded. Check for early script termination or configuration issues.");
  }
}

// Single test that runs all comparisons in parallel
test('VRC - All Page Comparisons', async ({ browser }, testInfo) => {
  // Set a longer timeout for this comprehensive test
  testInfo.setTimeout(600000); // 10 minutes total
  console.log(`\n🚀 Starting VRC tests for ${testCombinations.length} page-device combinations...`);
  console.log(`📊 Testing ${pages.length} pages across ${includedDevices.length} device(s): ${includedDevices.join(', ')}`);
  console.log(`⚡ Running with ${testCombinations.length} parallel workers\n`);

  // Run all comparisons in parallel with controlled concurrency
  const concurrentLimit = 2; // Reduce to 2 for screenshot stability
  const results = [];
  let failureCount = 0;
  let bailedOut = false;

  for (let i = 0; i < testCombinations.length; i += concurrentLimit) {
    if (bailedOut) break;
    
    const batch = testCombinations.slice(i, i + concurrentLimit);
    console.log(`\n📦 Processing batch ${Math.floor(i / concurrentLimit) + 1}/${Math.ceil(testCombinations.length / concurrentLimit)} (${batch.length} tests)`);
    
    const batchPromises = batch.map(({ device, page }) => 
      runSingleComparison(browser, device, page).catch(error => {
        console.error(`Batch error for ${page.name}-${device}: ${error.message}`);
        return {
          name: page.name,
          device,
          status: 'error',
          errorMessage: error.message,
          diffPixels: 0,
          url: page.path,
          devUrl: `${devBase}${page.path}`,
          liveUrl: `${liveBase}${page.path}`,
          devScreenshotFile: `${page.name}_${device}_dev.png`,
          liveScreenshotFile: `${page.name}_${device}_live.png`,
          diffScreenshotFile: `${page.name}_${device}_diff.png`
        };
      })
    );
    
    const batchResults = await Promise.allSettled(batchPromises);
    const validResults = batchResults
      .filter(result => result.status === 'fulfilled')
      .map(result => result.value);
    
    results.push(...validResults);
    
    // Check for failures in this batch
    const batchFailures = validResults.filter(r => r.status === 'diff' || r.status === 'error').length;
    failureCount += batchFailures;
    
    if (failureCount >= maxFailures) {
      bailedOut = true;
      console.warn(`⚠️  Reached maximum failures (${maxFailures}). Stopping test execution.`);
      break;
    }
    
    // Small delay between batches to prevent overwhelming the system
    await new Promise(resolve => setTimeout(resolve, 100));
  }

  // Generate final report
  await generateFinalReport(results, bailedOut, failureCount);
  
  console.log(`\n✅ VRC testing completed. Processed ${results.length} comparisons.`);
  
  // Fail the test if there were any failures
  const failures = results.filter(r => r.status === 'diff' || r.status === 'error');
  if (failures.length > 0) {
    throw new Error(`VRC failed with ${failures.length} failures out of ${results.length} tests`);
  }
});