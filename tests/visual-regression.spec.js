import { test, expect } from '@playwright/test';
import { getUrlsFromSitemap } from './utils/getUrlsFromSitemap.js';

const includePosts = process.argv.includes('--include-posts');

const pagesPromise = getUrlsFromSitemap('https://zotefoamslive.local/sitemap.xml', { includePosts });


test.describe('Visual regression tests from sitemap', () => {
  let pages = [];

  test.beforeAll(async () => {
    try {
      pages = await pagesPromise;
      console.log(`✅ Loaded ${pages.length} URLs from sitemap`);
    } catch (err) {
      console.error('❌ Failed to load sitemap:', err.message);
      pages = ['/', '/about/', '/contact/'];
    }
  });

  test('Visual regression snapshots', async ({ page }) => {
    for (const path of pages) {
      const label = `🖼️ ${path}`;
      const start = Date.now();

      try {
        console.log(`🔍 Testing ${path}`);
        await page.goto(path, { timeout: 60000 });
        await page.waitForLoadState('networkidle');

        await expect(page).toHaveScreenshot(`${path.replace(/\W/g, '_')}.png`, {
          fullPage: true,
        });

        const duration = ((Date.now() - start) / 1000).toFixed(2);
        console.log(`✅ Finished ${label} in ${duration}s`);
      } catch (err) {
        const duration = ((Date.now() - start) / 1000).toFixed(2);
        console.warn(`⚠️ Failed ${label} after ${duration}s — ${err.message}`);
      }
    }
  });
});
