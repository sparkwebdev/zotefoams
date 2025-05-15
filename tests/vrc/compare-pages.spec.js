import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';
import { PNG } from 'pngjs';
import pixelmatch from 'pixelmatch';
import { createRequire } from 'module';
const require = createRequire(import.meta.url);
const sharp = require('sharp');
const open = require('open').default;

const { getUrlsFromSitemap } = await import('./utils/getUrlsFromSitemap.js');

// 🔧 Settings
const useSitemap = process.env.USE_SITEMAP === '1';
const maxPages = parseInt(process.env.MAX_PAGES || '10', 10);
const sitemapUrl = process.env.SITEMAP_URL || 'https://zotefoams-phase-2.local/sitemap_index.xml';
const includePosts = process.env.INCLUDE_POSTS === '1';
const depthLimit = parseInt(process.env.SITEMAP_DEPTH || '1', 10);
const forceRefresh = process.env.FORCE_REFRESH === '1';

const staticPages = [
  { path: '/', name: 'home' },
  { path: '/about/', name: 'about' },
  { path: '/investors/', name: 'investors' },
];

const outputDir = path.resolve('tests/vrc/results');
const reportPath = path.join(outputDir, 'report.html');

// 🧹 Clear output directory before running
if (fs.existsSync(outputDir)) {
  fs.rmSync(outputDir, { recursive: true, force: true });
}
fs.mkdirSync(outputDir, { recursive: true });

test('Compare pages between dev and live', async ({ browser }) => {
  const allPages = useSitemap
    ? (await getUrlsFromSitemap(sitemapUrl, {
        includePosts,
        depthLimit,
        forceRefresh
      }))
        .slice(0, maxPages)
        .map((url) => ({
          path: url,
          name: url.replace(/\W+/g, '_').replace(/^_+|_+$/g, '') || 'home',
        }))
    : staticPages;

  const viewport = { width: 1600, height: 900 };

  const currentDate = new Date().toLocaleString('en-GB', {
    dateStyle: 'full',
    timeStyle: 'medium',
  });

  let report = `
    <html><head><title>Visual Report</title>
    <style>
      body { font-family: sans-serif; padding: 2rem; }
      img { max-width: 100%; margin-bottom: 1rem; border: 1px solid #ccc; }
      h2 { margin-top: 3rem; }
      .fail { color: red; }
      .pass { color: green; }
    </style></head><body><h1>Visual Regression Report - ${currentDate}</h1>
    <p>Compared ${allPages.length} page(s)</p>
  `;

  const devContext = await browser.newContext({ viewport });
  const liveContext = await browser.newContext({ viewport });
  const devPage = await devContext.newPage();
  const livePage = await liveContext.newPage();

  for (const { path: pagePath, name } of allPages) {
    const devUrl = `https://zotefoams-phase-2.local${pagePath}`;
    const liveUrl = `https://zotefoams.com${pagePath}`;

    console.log(`🧪 Comparing: ${pagePath}`);

    try {
      const timeout = 30000;

      await devPage.goto(devUrl, { waitUntil: 'networkidle', timeout });
      await devPage.evaluate(() => {
        document.querySelectorAll('.swiper').forEach((el) => {
          const swiper = el.swiper;
          if (swiper?.autoplay) swiper.autoplay.stop();
          swiper?.slideTo(0, 0);
        });
        window.scrollTo(0, 0);
      });
      await devPage.waitForTimeout(800);

      await livePage.goto(liveUrl, { waitUntil: 'networkidle', timeout });
      await livePage.evaluate(() => {
        document.querySelectorAll('.swiper').forEach((el) => {
          const swiper = el.swiper;
          if (swiper?.autoplay) swiper.autoplay.stop();
          swiper?.slideTo(0, 0);
        });
        window.scrollTo(0, 0);
      });
      await livePage.waitForTimeout(800);

      const devPath = path.join(outputDir, `${name}_dev.png`);
      const livePath = path.join(outputDir, `${name}_live.png`);
      const diffPath = path.join(outputDir, `${name}_diff.png`);

      await devPage.screenshot({ path: devPath, fullPage: true });
      await livePage.screenshot({ path: livePath, fullPage: true });

      const devMeta = await sharp(devPath).metadata();
      const liveMeta = await sharp(livePath).metadata();

      if (devMeta.width !== liveMeta.width || devMeta.height !== liveMeta.height) {
        const resizedPath = path.join(outputDir, `${name}_live_temp.png`);
        await sharp(livePath)
          .resize(devMeta.width, devMeta.height, { fit: 'cover' })
          .toFile(resizedPath);
        fs.renameSync(resizedPath, livePath);
      }

      const devPNG = PNG.sync.read(fs.readFileSync(devPath));
      const livePNG = PNG.sync.read(fs.readFileSync(livePath));
      const diff = new PNG({ width: devPNG.width, height: devPNG.height });

      const mismatch = pixelmatch(
        devPNG.data,
        livePNG.data,
        diff.data,
        devPNG.width,
        devPNG.height,
        { threshold: 0.2 }
      );

      fs.writeFileSync(diffPath, PNG.sync.write(diff));
      const status = mismatch < 500 ? 'pass' : 'fail';
      console.log(`  ➤ ${name}: ${mismatch} px mismatch (${status})`);

      report += `
        <h2 class="${status}">${name}</h2>
        <p><strong>URL:</strong> ${pagePath}</p>
        <div style="display:flex;gap:2rem;">
          <div><h3>Dev <a href="${devUrl}" target="_blank">🔗</a></h3><img src="${name}_dev.png" /></div>
          <div><h3>Live <a href="${liveUrl}" target="_blank">🔗</a></h3><img src="${name}_live.png" /></div>
          <div><h3>Diff</h3><img src="${name}_diff.png" /></div>
        </div>
        <p><strong>Pixel difference:</strong> ${mismatch.toLocaleString()}</p>
      `;
    } catch (err) {
      console.warn(`❌ Error comparing ${pagePath}: ${err.message}`);
      report += `<h2 class="fail">${name}</h2><p><strong>URL:</strong> ${pagePath}</p><p><strong>Error:</strong> ${err.message}</p>`;
    }
  }

  await devContext.close();
  await liveContext.close();

  report += '</body></html>';
  fs.writeFileSync(reportPath, report);
  console.log(`📄 Visual report saved: ${reportPath}`);
  await open(reportPath);
});
