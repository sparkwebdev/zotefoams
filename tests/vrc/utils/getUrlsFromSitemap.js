import fetch from 'node-fetch';
import { parseStringPromise } from 'xml2js';
import fs from 'fs';
import path from 'path';
import https from 'https';
import { fileURLToPath } from 'url';

// ESM __dirname polyfill
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const cacheDir = path.resolve(__dirname, '../.cache');
const cacheFilePath = path.join(cacheDir, 'sitemap.json');

if (!fs.existsSync(cacheDir)) {
  fs.mkdirSync(cacheDir, { recursive: true });
}


if (!fs.existsSync(cacheDir)) {
  fs.mkdirSync(cacheDir, { recursive: true });
}

const httpsAgent = new https.Agent({
  rejectUnauthorized: false, // For local dev
});

/**
 * Fetch and cache sitemap URLs.
 */
export async function getUrlsFromSitemap(indexUrl, {
  includePosts = false,
  depthLimit = 1,
  forceRefresh = false,
} = {}) {
  let allUrls = [];

  if (!forceRefresh && fs.existsSync(cacheFilePath)) {
    try {
      const cached = JSON.parse(fs.readFileSync(cacheFilePath, 'utf-8'));
      console.log('📁 Loaded sitemap data from cache');
      allUrls = cached;
    } catch (err) {
      console.warn('⚠️ Failed to read sitemap cache:', err.message);
    }
  }

  if (allUrls.length === 0) {
    console.log('🌐 Fetching sitemap from:', indexUrl);
    try {
      const sitemapRes = await fetch(indexUrl, { agent: httpsAgent });
      const sitemapXml = await sitemapRes.text();
      const sitemapData = await parseStringPromise(sitemapXml);

      const sitemapUrls = sitemapData.sitemapindex?.sitemap?.map(s => s.loc[0]) || [];

      for (const sitemapUrl of sitemapUrls) {
        if (!includePosts && sitemapUrl.includes('post-sitemap')) continue;

        try {
          const res = await fetch(sitemapUrl, { agent: httpsAgent });
          const xml = await res.text();
          const parsed = await parseStringPromise(xml);

          const urls = parsed.urlset?.url?.map(entry => {
            const loc = entry.loc[0];
            const urlPath = new URL(loc).pathname;
            return urlPath.endsWith('/') ? urlPath : urlPath + '/';
          }) || [];

          allUrls.push(...urls);
        } catch (err) {
          console.warn(`❌ Failed to fetch or parse sitemap: ${sitemapUrl}`, err.message);
        }
      }

      fs.writeFileSync(cacheFilePath, JSON.stringify(allUrls, null, 2));
      console.log(`💾 Cached ${allUrls.length} URLs`);
    } catch (err) {
      console.error('❌ Could not parse index sitemap:', err.message);
      return [];
    }
  }

  // Filter by depth
  const filtered = allUrls.filter((url) => {
    const depth = url.split('/').filter(Boolean).length;
    return depth <= depthLimit;
  });

  console.log(`✅ ${filtered.length} page(s) returned (depth ≤ ${depthLimit})`);
  return filtered;
}
