// tests/utils/getUrlsFromSitemap.js
import fetch from 'node-fetch';
import { parseStringPromise } from 'xml2js';
import https from 'https';

const httpsAgent = new https.Agent({
  rejectUnauthorized: false, // allow self-signed local certs
});

/**
 * Fetch all sitemap paths from a WordPress sitemap index.
 *
 * @param {string} indexUrl - URL to the main sitemap index (e.g. /sitemap.xml)
 * @param {Object} options
 * @param {boolean} options.includePosts - Include post URLs if true
 * @returns {Promise<string[]>} - Array of paths (e.g. "/about/")
 */
export async function getUrlsFromSitemap(indexUrl, { includePosts = true } = {}) {
  const pages = [];

  try {
    const indexRes = await fetch(indexUrl, { agent: httpsAgent });
    const indexXml = await indexRes.text();
    const indexParsed = await parseStringPromise(indexXml);

    const sitemapUrls = indexParsed.sitemapindex?.sitemap
      ?.map(entry => entry.loc?.[0])
      ?.filter(Boolean)
      ?.filter(url => includePosts || !url.includes('post-sitemap.xml')) || [];

    for (const sitemapUrl of sitemapUrls) {
      try {
        const res = await fetch(sitemapUrl, { agent: httpsAgent });
        const xml = await res.text();
        const parsed = await parseStringPromise(xml);
        const urls = parsed.urlset?.url || [];

        urls.forEach(entry => {
          const loc = entry.loc?.[0];
          if (loc) {
            const path = new URL(loc).pathname;
            pages.push(path);
          }
        });
      } catch (err) {
        console.warn(`⚠️ Could not load child sitemap ${sitemapUrl}:`, err.message);
      }
    }

  } catch (err) {
    console.error(`❌ Failed to fetch index sitemap: ${indexUrl}`, err.message);
  }

  return pages;
}
