// =============================================================================
// Zotefoams Theme - Main Entry Point
// Modern ES module system with unified build process
// =============================================================================

// Import main stylesheet
import './sass/style.scss';

// Import core utilities (must load first)
import './utils/dom-utilities.js';
import './utils/site-utilities.js';

// Import all components
import './components/navigation.js';
import './components/carousel-init.js';
import './components/tabbed-split.js';
import './components/locations-map.js';
import './components/data-points.js';
import './components/file-list.js';
import './components/section-list.js';
import './components/video-modal.js';
import './components/accordion.js';
import './components/utility-search.js';

// Theme initialization
console.log('Zotefoams Theme loaded - ES Module System with all components');