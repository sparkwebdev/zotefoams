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
// Options: 'desktop', 'tablet', 'mobile' or 'desktop-only'
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
// Configure bail-out behavior (stop after X failures)
const maxFailures = parseInt(process.env.VRC_MAX_FAILURES || '5');
let failureCount = 0;
let bailedOut = false;
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

// Collection for all test results for the final report
const allTestResults = [];

// Generate report function for reuse
async function generateCurrentReport() {
  console.log('\n🎯 Generating VRC report...');
  
  // Deduplicate results in case of retries
  const finalResultsMap = new Map();
  for (const result of allTestResults) {
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
      
      // Show summary in console
      const stats = finalUniqueResults.reduce((acc, result) => {
        acc[result.status] = (acc[result.status] || 0) + 1;
        acc.total++;
        return acc;
      }, { pass: 0, diff: 0, error: 0, total: 0 });
      
      console.log(`\n📈 Test Summary:`)
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

// Ensure output directory exists once
if (!existsSync(outputDir)) {
  mkdirSync(outputDir, { recursive: true });
}

async function preparePage(context, url, pageName, deviceIdentifier) {
  const page = await context.newPage();
  console.log(`[${pageName}-${deviceIdentifier}] Navigating to ${url}`);
  
  try {
    // Wait for the page to load completely
    await page.goto(url, { waitUntil: 'networkidle', timeout: 120000 });
    
    // Hide elements that might cause false positives in VRC
    await page.evaluate(() => {
      // Hide dynamic content that might change between captures
      document.querySelectorAll('[data-testid="current-time"], .timestamp, .current-date').forEach(el => {
        if (el instanceof HTMLElement) el.style.display = 'none';
      });
      
      // Hide any real-time widgets or counters
      document.querySelectorAll('.share-price-widget, .live-counter, .timestamp').forEach(el => {
        if (el instanceof HTMLElement) el.style.display = 'none';
      });
      
      // Hide cookie banners and overlays
      document.querySelectorAll('.cky-overlay, .cky-consent-container, .cky-modal, .cookie-notice').forEach(el => {
        if (el instanceof HTMLElement) el.style.display = 'none';
      });
      
      // Stop any animations or transitions that might cause inconsistency
      const style = document.createElement('style');
      style.textContent = `
        *, *::before, *::after {
          animation-duration: 0s !important;
          animation-delay: 0s !important;
          transition-duration: 0s !important;
          transition-delay: 0s !important;
          scroll-behavior: auto !important;
        }
        
        /* Hide swiper navigation and pagination for consistent screenshots */
        .swiper-button-next,
        .swiper-button-prev,
        .swiper-pagination {
          opacity: 0 !important;
        }
        
        /* Ensure carousels show first slide consistently */
        .swiper-wrapper {
          transform: translateX(0px) !important;
        }
      `;
      document.head.appendChild(style);
      
      // Reset all carousels to first slide
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
    
    // Wait for fonts and images to load
    await page.waitForLoadState('networkidle');
    
    // Additional wait for any remaining async content
    await page.waitForTimeout(1000);
    
  } catch (e) {
    console.error(`[${pageName}-${deviceIdentifier}] Error navigating to ${url}: ${e.message}`);
    await page.close();
    throw e;
  }

  return page;
}

// Dynamically create a test for each page and device combination
for (const device of includedDevices) {
  const viewport = deviceConfigs[device];

  for (const { path: pagePath, name: pageName } of pages) {
    test(`VRT: ${pageName} on ${device}`, async ({ browser }, testInfo) => {
      // Check if we should bail out due to too many failures
      if (bailedOut || failureCount >= maxFailures) {
        test.skip(`Bailed out after ${maxFailures} failures`);
        return;
      }

      // You can set a specific timeout for these VRT tests if needed,
      // e.g., if 60s (default) is too short for a single page comparison.
      // This will override the global timeout for this specific test.
      testInfo.setTimeout(120000); // 2 minutes per page-device combination

      const resultEntry = {
        name: pageName,
        device,
        url: pagePath,
        devUrl: `${devBase}${pagePath}`,
        liveUrl: `${liveBase}${pagePath}`,
        status: 'error', // Default, will be updated
        diffPixels: 0,
        errorMessage: null,
        devScreenshotFile: `${pageName}_${device}_dev.png`,
        liveScreenshotFile: `${pageName}_${device}_live.png`,
        diffScreenshotFile: `${pageName}_${device}_diff.png`
      };

      let context;
      let devPage;
      let livePage;

      try {
        context = await browser.newContext({ 
          viewport,
          ignoreHTTPSErrors: true, // For local development with self-signed certs
        });

        const devUrl = resultEntry.devUrl;
        const liveUrl = resultEntry.liveUrl;

        try {
          devPage = await preparePage(context, devUrl, pageName, `${device}-dev`);
          livePage = await preparePage(context, liveUrl, pageName, `${device}-live`);
        } catch (pageLoadError) {
          console.error(`[${pageName}-${device}] Failed to load pages: ${pageLoadError.message}`);
          resultEntry.errorMessage = `Page load failed: ${pageLoadError.message}`;
          allTestResults.push(resultEntry);
          if (devPage && !devPage.isClosed()) await devPage.close();
          if (livePage && !livePage.isClosed()) await livePage.close();
          if (context) await context.close();
          throw pageLoadError; // Propagate error to fail the test
        }

        const devPath = path.join(outputDir, resultEntry.devScreenshotFile);
        const livePath = path.join(outputDir, resultEntry.liveScreenshotFile);
        const diffPath = path.join(outputDir, resultEntry.diffScreenshotFile);

        console.log(`[${pageName}-${device}] Taking screenshots.`);
        await devPage.screenshot({ path: devPath, fullPage: true });
        await livePage.screenshot({ path: livePath, fullPage: true });

        console.log(`[${pageName}-${device}] Comparing images.`);
        const devSharp = sharp(devPath);
        const liveSharp = sharp(livePath);
        const devMeta = await devSharp.metadata();
        const liveMeta = await liveSharp.metadata();

        // Ensure metadata was retrieved (images were valid)
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
          minWidth, minHeight, { threshold: 0.1, includeAA: true } // includeAA can help with anti-aliased text/edges
        );
        resultEntry.diffPixels = diffPixelsValue;

        if (diffPixelsValue > 200) { // Your threshold for difference
          writeFileSync(diffPath, PNG.sync.write(diffImg));
          resultEntry.status = 'diff';
          failureCount++;
          console.warn(`[${pageName}-${device}] Visual difference found: ${diffPixelsValue} pixels. Diff image: ${resultEntry.diffScreenshotFile}`);
          
          // Check if we've hit the failure limit
          if (failureCount >= maxFailures) {
            bailedOut = true;
            console.warn(`⚠️  Reached maximum failures (${maxFailures}). Stopping test execution.`);
            // Generate report immediately when bailing out
            await generateCurrentReport();
          }
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
        allTestResults.push(resultEntry);

      } catch (error) {
        console.error(`❌ Error in test [${pageName}-${device}]: ${error.message}\n${error.stack || ''}`);
        resultEntry.status = 'error';
        resultEntry.errorMessage = error.message;
        failureCount++;
        
        // Check if we've hit the failure limit
        if (failureCount >= maxFailures) {
          bailedOut = true;
          console.warn(`⚠️  Reached maximum failures (${maxFailures}). Stopping test execution.`);
          // Generate report immediately when bailing out
          await generateCurrentReport();
        }
        
        // Ensure result is pushed if not already (e.g. error before explicit push)
        const existingResultIndex = allTestResults.findIndex(r => r.name === pageName && r.device === device);
        if (existingResultIndex > -1) {
            allTestResults[existingResultIndex] = { ...allTestResults[existingResultIndex], ...resultEntry};
        } else {
            allTestResults.push(resultEntry);
        }
        throw error; // Re-throw to ensure Playwright marks test as failed
      } finally {
        console.log(`[${pageName}-${device}] Cleaning up test resources.`);
        if (devPage && !devPage.isClosed()) await devPage.close();
        if (livePage && !livePage.isClosed()) await livePage.close();
        if (context) await context.close();
      }
    });
  }
}

test.afterAll(async () => {
  console.log('\n🎯 All Zotefoams VRC tests completed.');
  await generateCurrentReport();
});