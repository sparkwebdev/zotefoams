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

const pages2 = [
  { path: '/news-centre/', name: 'news-centre' },
  { path: '/investors/directors/gary-mcgrath/', name: 'gary-mcgrath' },
  { path: '/investors/directors/douglas-robertson-2/', name: 'douglas-robertson-2' },
  { path: '/investors/directors/jonathan-carling-2/', name: 'jonathan-carling-2' },
  { path: '/investors/directors/catherine-wall/', name: 'catherine-wall' },
  { path: '/investors/directors/ronan-cox/', name: 'ronan-cox' },
  { path: '/our-brands/', name: 'our-brands' },
  { path: '/investors/directors/malcolm-swift/', name: 'malcolm-swift' },
  { path: '/markets/', name: 'markets' },
  { path: '/investors/directors/lynn-drummond/', name: 'lynn-drummond' }
];

const pages3 = [
  { path: '/who-we-are/', name: 'who-we-are' },
  { path: '/investors/regulatory-news/', name: 'regulatory-news' },
  { path: '/investors/share-price/', name: 'share-price' },
  { path: '/markets/automotive/', name: 'automotive' },
  { path: '/markets/mass-transportation/', name: 'mass-transportation' },
  { path: '/markets/medical/', name: 'medical' },
  { path: '/markets/product-protection/', name: 'product-protection' },
  { path: '/markets/sports-leisure/', name: 'sports-leisure' },
  { path: '/markets/construction-insulation/', name: 'construction-insulation' },
  { path: '/our-brands/azote/', name: 'azote' }
];

const pages4 = [
  { path: '/our-brands/azote/evazote/', name: 'evazote' },
  { path: '/our-brands/azote/plastazote/', name: 'plastazote' },
  { path: '/our-brands/azote/supazote/', name: 'supazote' },
  { path: '/our-brands/zotek/', name: 'zotek' },
  { path: '/our-brands/zotek/zotek-f-osu-ht-aviation-aerospace/', name: 'zotek-f-osu-ht-aviation-aerospace' },
  { path: '/our-brands/zotek/zotek-t/', name: 'zotek-t' },
  { path: '/our-brands/ecozote-2/', name: 'ecozote-2' },
  { path: '/our-brands/t-fit/', name: 't-fit' },
  { path: '/knowledge-hub/', name: 'knowledge-hub' },
  { path: '/investors/directors/', name: 'directors' }
];

const pages5 = [
  { path: '/contact-us/', name: 'contact-us' },
  { path: '/legal/', name: 'legal' },
  { path: '/legal/gender-pay-gap/', name: 'gender-pay-gap' },
  { path: '/legal/modern-slavery-statement/', name: 'modern-slavery-statement' },
  { path: '/legal/conditions-of-sale/', name: 'conditions-of-sale' },
  { path: '/legal/terms-of-use/', name: 'terms-of-use' },
  { path: '/legal/policy-compliance-statement/', name: 'policy-compliance-statement' },
  { path: '/governance/', name: 'governance' },
  { path: '/work-with-us/', name: 'work-with-us' },
  { path: '/investors/', name: 'investors' }
];

const pages6 = [
  { path: '/investors/financial-updates/', name: 'financial-updates' },
  { path: '/our-brands/zotek/zotek-f/', name: 'zotek-f' },
  { path: '/who-we-are/sustainability/', name: 'sustainability' },
  { path: '/markets/aviation-aerospace/', name: 'aviation-aerospace' },
  { path: '/knowledge-hub/technical-literature/', name: 'technical-literature' },
  { path: '/knowledge-hub/videos/', name: 'videos' },
  { path: '/knowledge-hub/marketing-literature/', name: 'marketing-literature' },
  { path: '/knowledge-hub/statements-certificates/', name: 'statements-certificates' },
  { path: '/knowledge-hub/technical-literature/safety-information-sheets/', name: 'safety-information-sheets' },
  { path: '/knowledge-hub/technical-literature/technical-information-sheets/', name: 'technical-information-sheets' }
];

const pages7 = [
  { path: '/knowledge-hub/technical-literature/product-information-sheets/', name: 'product-information-sheets' },
  { path: '/news-centre/case-studies/', name: 'case-studies' },
  { path: '/news-centre/events/', name: 'events' },
  { path: '/news-centre/news/', name: 'news' },
  { path: '/news-centre/uncategorised/', name: 'uncategorised' },
  { path: '/news-centre/videos/', name: 'videos' },
  { path: '/documents-category/application-related-statements/', name: 'application-related-statements' }
];

const pages8 = [
  { path: '/zotefoams-confirms-its-commitment-to-sustainability-plastics-recycling-and-resource-management-with-recoup-membership/', name: 'recoup-membership' },
  { path: '/trading-update-and-group-ceo-succession/', name: 'ceo-succession' },
  { path: '/zotefoams-and-shincell-global-alliance/', name: 'shincell-global-alliance' },
  { path: '/zotefoams-enters-exclusivity-agreement-with-d30/', name: 'exclusivity-with-d30' },
  { path: '/zotefoams-announces-licensing-agreement-with-censco-for-manufacture-of-microcellular-foaming-equipment/', name: 'licensing-with-censco' },
  { path: '/zotefoams-presents-a-more-sustainable-future-for-beverage-cartons-with-rezorce-mono-material-barrier-packaging/', name: 'rezorce-barrier-packaging' },
  { path: '/rezorce-wins-german-packaging-award/', name: 'german-packaging-award' },
  { path: '/foam-expo-north-america-24-26-june-2025-novi-michigan-usa-stand-1302/', name: 'foam-expo-usa-2025' },
  { path: '/pmec-india-25-27-november-2025-india-expo-centre-greater-noida-delhi-ncr-india-stand-rhc43/', name: 'pmec-india-2025' },
  { path: '/lightweight-luxury-mgr-foamtexs-softwall-nextgen-is-50-lighter-thanks-to-zotek-f-osu-xr/', name: 'mgr-foamtex-softwall' }
];

const pages9 = [
  { path: '/weight-of-business-jet-components-reduced-by-50-with-zotek-f-high-performance-foam/', name: 'jet-components-zotek-f' },
  { path: '/ssc-record-impressive-energy-savings-using-zotefoams-lightweight-foam/', name: 'ssc-energy-savings' },
  { path: '/zotefoams-annual-general-meeting-broadcast-2022/', name: 'agm-2022' },
  { path: '/official-opening-of-zotefoams-plant-in-brzeg-poland-2021/', name: 'brzeg-poland-plant' },
  { path: '/zotefoams-zotek-and-azote-foams-are-the-ultimate-lightweight-material-for-aircraft-interiors/', name: 'aircraft-interiors' },
  { path: '/zotefoams-unique-three-stage-manufacturing-process/', name: 'three-stage-process' },
  { path: '/zotefoams-h1-interim-results-2024/', name: 'h1-results-2024' },
  { path: '/1264-2/', name: '1264-2' },
  { path: '/preliminary-results-unaudited-for-the-year-ended-31-december-2023/', name: 'preliminary-results-2023' },
  { path: '/performance-benefits-of-aviation-foam/', name: 'aviation-foam-benefits' }
];

const pages10 = [
  { path: '/capital-markets-day-18-march-2025-peel-hunt-office-london/', name: 'capital-markets-day-2025' },
  { path: '/lorem-ipsum-sustainable-foam-innovations/', name: 'sustainable-foam-innovations' },
  { path: '/full-year-trading-update-and-notice-of-capital-markets-day/', name: 'full-year-trading-update' },
  { path: '/zotefoams-unveils-sustainable-seating-material-at-aix-2025/', name: 'aix-2025-seating-material' },
  { path: '/k-show-8-15-october-2025-messe-dusseldorf-germany-stand-5a24/', name: 'k-show-2025' },
  { path: '/the-battery-show-3-5-june-2025-messe-stuttgart-germany-stand-7-g41/', name: 'battery-show-2025' },
  { path: '/interfoam-2-4-july-2025-hall-4-sniec-shanghai-china-stand-g09/', name: 'interfoam-2025' },
  { path: '/china-dairy-show-23-25-may-2025-nanjing-international-exhibition-and-conference-center-jiangsu-china-stand-tbc/', name: 'china-dairy-show-2025' }
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
  
          if (diffPixels > 200) {
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
