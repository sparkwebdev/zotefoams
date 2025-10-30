// =============================================================================
// PostCSS Configuration - CSS Post-Processing Pipeline
// =============================================================================
//
// This runs AFTER Dart SASS compilation to add vendor prefixes.
// Part of the split build system (Rollup handles JS, SASS CLI handles CSS).
//
// Why split from Rollup?
// - Dart SASS CLI is faster than rollup-plugin-scss
// - More reliable watch mode for SASS changes
// - Better source maps for debugging
// - Clearer separation of concerns in build pipeline
//
// Build flow: SCSS → Dart SASS → PostCSS → style.css
//
// =============================================================================

export default {
	plugins: {
		autoprefixer: {
			overrideBrowserslist: [
				'last 2 versions',  // Last 2 versions of all browsers
				'> 1%',             // Browsers with >1% market share
				'not dead',         // Exclude browsers without updates for 24 months
				'not ie 11'         // Exclude IE11 (no longer supported)
			]
		}
	}
};
