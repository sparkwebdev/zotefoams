import { writeFileSync } from 'fs';
import path from 'path';

/**
 * Generate HTML report for Visual Regression Testing results
 * @param {Array} results - Array of test result objects
 * @param {string} outputDir - Directory where report and images are stored
 * @param {Object} options - Optional configuration including bail-out info
 * @returns {string} Path to generated report file
 */
export function generateReport(results, outputDir, options = {}) {
  const reportPath = path.join(outputDir, 'vrc-report.html');
  
  // Calculate summary statistics
  const stats = results.reduce((acc, result) => {
    acc[result.status] = (acc[result.status] || 0) + 1;
    acc.total++;
    return acc;
  }, { pass: 0, diff: 0, error: 0, total: 0 });

  const successRate = stats.total > 0 ? ((stats.pass / stats.total) * 100).toFixed(1) : 0;
  
  const html = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zotefoams VRC Report</title>
  <style>
    * { box-sizing: border-box; }
    body { 
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
      margin: 0; 
      padding: 20px; 
      background: #f5f5f5; 
    }
    .container { max-width: 1400px; margin: 0 auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .header { background: #1e40af; color: white; padding: 30px; }
    .header h1 { margin: 0; font-size: 28px; font-weight: 700; }
    .header p { margin: 8px 0 0; opacity: 0.9; font-size: 16px; }
    .header .subtitle { font-size: 14px; opacity: 0.8; margin-top: 5px; }
    .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 20px; padding: 30px; }
    .stat { text-align: center; padding: 25px; border-radius: 8px; }
    .stat-pass { background: #dcfce7; color: #15803d; border: 2px solid #bbf7d0; }
    .stat-diff { background: #fef3c7; color: #d97706; border: 2px solid #fed7aa; }
    .stat-error { background: #fecaca; color: #dc2626; border: 2px solid #fca5a5; }
    .stat-total { background: #e5e7eb; color: #374151; border: 2px solid #d1d5db; }
    .stat-number { font-size: 32px; font-weight: bold; margin-bottom: 8px; }
    .stat-label { font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
    .results { padding: 0 30px 30px; }
    .result { margin-bottom: 30px; padding: 25px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fafafa; }
    .result-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .result-title { font-size: 20px; font-weight: 600; color: #1f2937; }
    .result-url { font-size: 14px; color: #6b7280; margin-top: 6px; font-family: 'Monaco', 'Menlo', monospace; background: #f3f4f6; padding: 4px 8px; border-radius: 4px; }
    .status { padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-pass { background: #dcfce7; color: #15803d; }
    .status-diff { background: #fef3c7; color: #d97706; }
    .status-error { background: #fecaca; color: #dc2626; }
    .images { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 20px; }
    .image-container { text-align: center; background: white; padding: 15px; border-radius: 6px; border: 1px solid #e5e7eb; }
    .image-label { font-size: 13px; color: #6b7280; margin-bottom: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; }
    .image { max-width: 100%; height: auto; border: 1px solid #e5e7eb; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
    .no-image { padding: 50px; background: #f9fafb; color: #6b7280; font-size: 14px; border: 2px dashed #d1d5db; border-radius: 6px; }
    .error-message { background: #fef2f2; color: #991b1b; padding: 16px; border-radius: 6px; font-size: 14px; margin-top: 15px; border: 1px solid #fca5a5; }
    .diff-info { background: #fef3c7; color: #92400e; padding: 12px 16px; border-radius: 6px; font-size: 14px; margin-top: 15px; border: 1px solid #fed7aa; font-weight: 500; }
    .filter-buttons { padding: 25px 30px; border-bottom: 1px solid #e5e7eb; background: #f9fafb; }
    .filter-btn { margin-right: 12px; padding: 10px 18px; border: 1px solid #d1d5db; background: white; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; transition: all 0.2s; }
    .filter-btn.active { background: #1e40af; color: white; border-color: #1e40af; }
    .filter-btn:hover { background: #f3f4f6; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .filter-btn.active:hover { background: #1d4ed8; }
    .success-rate { background: #f0f9ff; border: 1px solid #bae6fd; color: #0c4a6e; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 600; }
    .bail-out-notice { background: #fef3c7; border: 1px solid #fed7aa; color: #92400e; padding: 15px; border-radius: 6px; margin-bottom: 20px; text-align: center; font-weight: 600; }
    .zotefoams-logo { float: right; opacity: 0.1; font-size: 18px; font-weight: bold; margin-top: 5px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="zotefoams-logo">ZOTEFOAMS</div>
      <h1>Visual Regression Test Report</h1>
      <p>Generated: ${new Date().toLocaleString()}</p>
      <div class="subtitle">Comparing Development vs Reference versions</div>
    </div>
    
    ${options.bailedOut ? `
    <div class="bail-out-notice">
      ⚠️ <strong>Test execution stopped early</strong> - Reached maximum failures limit (${options.maxFailures}). 
      Some tests may not have been executed. Consider fixing failing tests before running full suite.
    </div>
    ` : ''}
    
    <div class="success-rate">
      Test Success Rate: ${successRate}% (${stats.pass}/${stats.total} tests passed)
    </div>
    
    <div class="stats">
      <div class="stat stat-total">
        <div class="stat-number">${stats.total}</div>
        <div class="stat-label">Total Tests</div>
      </div>
      <div class="stat stat-pass">
        <div class="stat-number">${stats.pass}</div>
        <div class="stat-label">Passed</div>
      </div>
      <div class="stat stat-diff">
        <div class="stat-number">${stats.diff || 0}</div>
        <div class="stat-label">Differences</div>
      </div>
      <div class="stat stat-error">
        <div class="stat-number">${stats.error || 0}</div>
        <div class="stat-label">Errors</div>
      </div>
    </div>

    <div class="filter-buttons">
      <button class="filter-btn active" onclick="filterResults('all')">All Tests</button>
      <button class="filter-btn" onclick="filterResults('diff')">Differences Only</button>
      <button class="filter-btn" onclick="filterResults('error')">Errors Only</button>
      <button class="filter-btn" onclick="filterResults('pass')">Passed Only</button>
    </div>
    
    <div class="results">
      ${results.map(result => `
        <div class="result" data-status="${result.status}">
          <div class="result-header">
            <div>
              <div class="result-title">${result.name} - ${result.device}</div>
              <div class="result-url">${result.devUrl}</div>
            </div>
            <span class="status status-${result.status}">${result.status}</span>
          </div>
          
          ${result.status === 'error' ? `
            <div class="error-message">
              <strong>Error:</strong> ${result.errorMessage || 'Unknown error occurred'}
            </div>
          ` : ''}
          
          ${result.status === 'diff' ? `
            <div class="diff-info">
              <strong>${result.diffPixels} pixels different</strong> - Visual differences detected between versions
            </div>
            <div class="images">
              <div class="image-container">
                <div class="image-label">Development Version</div>
                <img class="image" src="${result.devScreenshotFile}" alt="Development screenshot" loading="lazy" />
              </div>
              <div class="image-container">
                <div class="image-label">Reference Version</div>
                <img class="image" src="${result.liveScreenshotFile}" alt="Reference screenshot" loading="lazy" />
              </div>
              <div class="image-container">
                <div class="image-label">Difference Highlight</div>
                <img class="image" src="${result.diffScreenshotFile}" alt="Difference screenshot" loading="lazy" />
              </div>
            </div>
          ` : ''}
          
          ${result.status === 'pass' ? `
            <div style="color: #15803d; font-size: 16px; font-weight: 500;">✅ Visual test passed - no differences detected</div>
          ` : ''}
        </div>
      `).join('')}
    </div>
  </div>

  <script>
    function filterResults(status) {
      const buttons = document.querySelectorAll('.filter-btn');
      const results = document.querySelectorAll('.result');
      
      // Update active button
      buttons.forEach(btn => btn.classList.remove('active'));
      event.target.classList.add('active');
      
      // Filter results
      results.forEach(result => {
        if (status === 'all' || result.dataset.status === status) {
          result.style.display = 'block';
        } else {
          result.style.display = 'none';
        }
      });
    }
  </script>
</body>
</html>`;

  writeFileSync(reportPath, html);
  return reportPath;
}