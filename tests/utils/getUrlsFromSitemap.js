// tests/utils/getUrlsFromSitemap.js
import fetch from 'node-fetch';
import { parseStringPromise } from 'xml2js';
import https from 'https';

const httpsAgent = new https.Agent({
  rejectUnauthorized: false, // allow self-signed certs from Local
});

export async function getUrlsFromSitemap(indexUrl, { includePosts = true } = {}) {
  const urls = [];
  const fetch = (await import('node-fetch')).default; // if you're still using ESM

  const httpsAgent = new (await import('https')).Agent({ rejectUnauthorized: false });

  // Fetch and parse the index sitemap
  const indexRes = await fetch(indexUrl, { agent: httpsAgent });
  const indexXml = await indexRes.text();
  const indexData = await parseStringPromise(indexXml);

  const sitemapUrls = indexData.sitemapindex.sitemap
    .map(entry => entry.loc[0])
    .filter(url => includePosts || !url.includes('post-sitemap.xml'));

  // Fetch each child sitemap and extract <loc> entries
  for (const sitemapUrl of sitemapUrls) {
    try {
      const res = await fetch(sitemapUrl, { agent: httpsAgent });
      const xml = await res.text();
      const parsed = await parseStringPromise(xml);
      const entries = parsed.urlset.url || [];

      for (const entry of entries) {
        const fullUrl = entry.loc[0];
        const path = new URL(fullUrl).pathname;
        urls.push(path);
      }
    } catch (err) {
      console.error(`⚠️ Failed to fetch or parse sitemap: ${sitemapUrl}`, err.message);
    }
  }

  return urls;
}

