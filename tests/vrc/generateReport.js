import { writeFileSync } from "fs";
import path from "path";

export function generateReport(results, outputDir) {
  const now = new Date().toLocaleString("en-GB", {
    weekday: "long",
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
    second: "2-digit",
  });

  const failed = results.filter((r) => r.status === "error");
  const passed = results.filter((r) => r.status === "pass");
  const diffs = results.filter((r) => r.status === "diff");

  const makeList = (arr) =>
    arr.length
      ? `<ul>${arr.map((r) => `<li>${r.name} (${r.device})</li>`).join("")}</ul>`
      : `<p><em>None</em></p>`;

  const imageSections = diffs
    .map(({ name, device, url, devUrl, liveUrl, diffPixels }) => {
      return `
<h2 class="fail">${name} (${device})</h2>
<p><strong>URL:</strong> ${url}</p>
<div style="display:flex;gap:2rem;">
  <div>
    <h3>Dev <a href="${devUrl}" target="_blank">🔗</a></h3>
    <img src="${name}_${device}_dev.png" />
  </div>
  <div>
    <h3>Live <a href="${liveUrl}" target="_blank">🔗</a></h3>
    <img src="${name}_${device}_live.png" />
  </div>
  <div>
    <h3>Diff</h3>
    <img src="${name}_${device}_diff.png" />
  </div>
</div>
<p><strong>Pixel difference:</strong> ${diffPixels.toLocaleString()}</p>
`;
    })
    .join("\n");

  const html = `
<html>
  <head>
    <title>Visual Report</title>
    <style>
      body { font-family: sans-serif; padding: 2rem; }
      img { max-width: 100%; margin-bottom: 1rem; border: 1px solid #ccc; }
      h2 { margin-top: 3rem; }
      .fail { color: red; }
      .pass { color: green; }
    </style>
  </head>
  <body>
    <h1>Visual Regression Report - ${now}</h1>
    <p>Compared ${results.length} case(s)</p>

    <h2 class="fail">❌ Failed Pages</h2>
    ${makeList(failed)}

    <h2 class="pass">✅ Passed with No Visual Differences</h2>
    ${makeList(passed)}

    <h2 class="fail">🔍 Visual Differences</h2>
    ${imageSections}
  </body>
</html>
`;

  const reportPath = path.join(outputDir, "report.html");
  writeFileSync(reportPath, html);
  return reportPath;
}
