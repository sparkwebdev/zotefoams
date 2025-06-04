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

const includedDevices = ['desktop', 'tablet', 'mobile'];

const devBase = 'https://zotefoams-phase-2.local';
const liveBase = 'https://zotefoams-live-ref.local';
const outputDir = path.resolve('tests/vrc/results');

const pages = [...pagesLevel1];

// Collection for all test results for the final report
const allTestResults = [];

// Ensure output directory exists once
if (!existsSync(outputDir)) {
  mkdirSync(outputDir, { recursive: true });
}

async function preparePage(context, url, pageName, deviceIdentifier) {
  const page = await context.newPage();
  console.log(`[${pageName}-${deviceIdentifier}] Navigating to ${url}`);
  try {
    // Using 'networkidle' can be more reliable than 'load' and a fixed timeout,
    // as it waits for network activity to subside.
    // Increased timeout for goto itself, as some pages might be slow.
    await page.goto(url, { waitUntil: 'networkidle', timeout: 90000 }); // 90s for page navigation
    // The fixed page.waitForTimeout(2000) has been removed.
    // If 'networkidle' isn't enough, consider specific element waits or a very short explicit wait.
  } catch (e) {
    console.error(`[${pageName}-${deviceIdentifier}] Error navigating to ${url}: ${e.message}`);
    await page.close(); // Clean up page if goto fails
    throw e; // Re-throw to be caught by the test
  }

  await page.evaluate(() => {
    document.querySelectorAll('.cky-overlay, .cky-consent-container, cky-modal, .site-header, .site-footer').forEach(el => {
        if (el instanceof HTMLElement) el.style.display = 'none';
    });
    document.querySelectorAll('.swiper').forEach((el) => {
      // @ts-ignore // Assuming 'swiper' is a custom property or from a library not typed here
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
  return page;
}

// Dynamically create a test for each page and device combination
for (const device of includedDevices) {
  const viewport = deviceConfigs[device];

  for (const { path: pagePath, name: pageName } of pages) {
    test(`VRT: ${pageName} on ${device}`, async ({ browser }, testInfo) => {
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
        context = await browser.newContext({ viewport });

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
        allTestResults.push(resultEntry);

      } catch (error) {
        console.error(`❌ Error in test [${pageName}-${device}]: ${error.message}\n${error.stack || ''}`);
        resultEntry.status = 'error';
        resultEntry.errorMessage = error.message;
        
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
  console.log('All visual tests completed. Generating report...');
  
  // Deduplicate results in case of retries or complex error paths (keeps the latest attempt for a unique page-device combo)
  const finalResultsMap = new Map();
  for (const result of allTestResults) {
    const key = `${result.name}-${result.device}`;
    finalResultsMap.set(key, result); // Map ensures only the last entry for a key is kept
  }
  const finalUniqueResults = Array.from(finalResultsMap.values());

  if (finalUniqueResults.length > 0) {
    try {
      const reportPath = generateReport(finalUniqueResults, outputDir); // Ensure generateReport can handle the new structure
      console.log(`Report generated at: ${reportPath}`);
      await open(reportPath); // open can be async
      console.log('Report opened in browser.');
    } catch (reportError) {
        console.error(`Failed to generate or open report: ${reportError.message}`);
    }
  } else {
    console.warn("No test results recorded. Check for early script termination or configuration issues.");
  }
});