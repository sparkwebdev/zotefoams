import { test } from '@playwright/test';
import { mkdirSync, writeFileSync, existsSync } from 'fs';
import path from 'path';
import sharp from 'sharp';
import pixelmatch from 'pixelmatch';
import { PNG } from 'pngjs';
import { generateReport } from './generateReport.js';
import open from 'open';

const deviceConfigs = {
  desktop: { width: 1600, height: 900 },
  tablet: { width: 768, height: 1024 },
  mobile: { width: 375, height: 812 }
};

const includedDevices = ['desktop'];

const devBase = 'https://zotefoams-phase-2.local';
const liveBase = 'https://zotefoams.com';
const outputDir = path.resolve('tests/vrc/results');

const pages = [
  { path: '/', name: 'home' },
  { path: '/who-we-are/', name: 'about' },
  { path: '/contact-us/', name: 'contact' },
  { path: '/404-xyz/', name: '404' }
];

async function preparePage(context, url) {
  const page = await context.newPage();
  console.log(`Navigating to ${url}`);
  await page.goto(url, { waitUntil: 'load' });
  await page.waitForTimeout(2000);

  await page.evaluate(() => {
    document.querySelectorAll('.cky-overlay, .cky-consent-container, cky-modal, .site-header, .site-footer').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.swiper').forEach((el) => {
      const swiper = el.swiper;
      if (swiper?.autoplay) swiper.autoplay.stop();
      swiper?.slideTo(0, 0);
    });
  });
  return page;
}

test('Visual Regression: Multi-Device, Multi-Page', async ({ browser }) => {
  if (!existsSync(outputDir)) mkdirSync(outputDir, { recursive: true });

  const results = [];

  for (const device of includedDevices) {
    const viewport = deviceConfigs[device];
    let context;
  
    try {
      context = await browser.newContext({ viewport });
  
      for (const { path: pagePath, name } of pages) {
        const devUrl = devBase + pagePath;
        const liveUrl = liveBase + pagePath;
  
        let devPage, livePage;
  
        try {
          devPage = await preparePage(context, devUrl);
          livePage = await preparePage(context, liveUrl);
        } catch (err) {
          console.error(`❌ Failed to load ${name} (${device}): ${err.message}`);
          results.push({
            name,
            device,
            url: pagePath,
            devUrl,
            liveUrl,
            status: 'error',
            diffPixels: 0
          });
          continue;
        }
  
        const devFile = `${name}_${device}_dev.png`;
        const liveFile = `${name}_${device}_live.png`;
        const diffFile = `${name}_${device}_diff.png`;
  
        const devPath = path.join(outputDir, devFile);
        const livePath = path.join(outputDir, liveFile);
        const diffPath = path.join(outputDir, diffFile);
  
        await devPage.screenshot({ path: devPath, fullPage: true });
        await livePage.screenshot({ path: livePath, fullPage: true });
  
        try {
          const devSharp = sharp(devPath);
          const liveSharp = sharp(livePath);
          const devMeta = await devSharp.metadata();
          const liveMeta = await liveSharp.metadata();
  
          const minWidth = Math.min(devMeta.width, liveMeta.width);
          const minHeight = Math.min(devMeta.height, liveMeta.height);
  
          const devBuffer = await devSharp.extract({ left: 0, top: 0, width: minWidth, height: minHeight }).png().toBuffer();
          const liveBuffer = await liveSharp.extract({ left: 0, top: 0, width: minWidth, height: minHeight }).png().toBuffer();
  
          const devImg = PNG.sync.read(devBuffer);
          const liveImg = PNG.sync.read(liveBuffer);
          const diff = new PNG({ width: minWidth, height: minHeight });
  
          const diffPixels = pixelmatch(
            devImg.data,
            liveImg.data,
            diff.data,
            minWidth,
            minHeight,
            { threshold: 0.1 }
          );
  
          if (diffPixels > 0) {
            writeFileSync(diffPath, PNG.sync.write(diff));
            results.push({
              name,
              device,
              url: pagePath,
              devUrl,
              liveUrl,
              status: 'diff',
              diffPixels
            });
          } else {
            results.push({
              name,
              device,
              url: pagePath,
              devUrl,
              liveUrl,
              status: 'pass',
              diffPixels
            });
  
            // Clean up images for passes
            [devPath, livePath].forEach(p => {
              try { require('fs').unlinkSync(p); } catch {}
            });
          }
        } catch (err) {
          console.error(`❌ Image processing failed for ${name} (${device}): ${err.message}`);
          results.push({
            name,
            device,
            url: pagePath,
            devUrl,
            liveUrl,
            status: 'error',
            diffPixels: 0
          });
        }
      }
    } catch (err) {
      console.error(`❌ Device context failed (${device}): ${err.message}`);
      for (const { name, path: pagePath } of pages) {
        results.push({
          name,
          device,
          url: pagePath,
          devUrl: devBase + pagePath,
          liveUrl: liveBase + pagePath,
          status: 'error',
          diffPixels: 0
        });
      }
    } finally {
      try {
        if (context) await context.close();
      } catch (err) {
        console.warn(`⚠️ Could not close context for ${device}: ${err.message}`);
      }
    }
  }
  

  const reportPath = generateReport(results, outputDir);
  await open(reportPath);
});
