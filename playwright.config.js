// playwright.config.js
import { defineConfig } from "@playwright/test";

export default defineConfig({
  testDir: "./tests",
  timeout: 60 * 1000,
  fullyParallel: true,
  workers: 8,
  expect: {
    timeout: 5000,
    toHaveScreenshot: { threshold: 0.3 }, // tweak sensitivity
  },
  use: {
    baseURL: "https://zotefoamslive.local",
    browserName: "chromium",
    headless: true,
    viewport: { width: 1600, height: 900 },
    screenshot: "off", // or 'on' if you want all screenshots
  },
});
