// =============================================================================
// Zotefoams Theme - Main Entry Point
// Modern ES module system with unified build process
// =============================================================================

// NOTE: SASS is now compiled separately via npm run watch:sass
// See package.json for the build configuration

// Import non-critical utilities
import './utils/site-utilities.js';

// Import all non-critical components
import './components/carousel-init.js';
import './components/tabbed-split.js';
import './components/locations-map.js';
import './components/interactive-image.js';
import './components/data-points.js';
import './components/file-list.js';
import './components/section-list.js';
import './components/video-modal.js';
import './components/accordion.js';
import './components/panel-switcher.js';
import './components/utility-search.js';
import './components/our-history.js';

// Theme initialization