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

import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';

const isProduction = process.env.NODE_ENV === 'production';

const ensureTrailingNewline = {
	postcssPlugin: 'ensure-trailing-newline',
	OnceExit( root ) {
		if ( ! root.raws.after?.endsWith( '\n' ) ) {
			root.raws.after = ( root.raws.after || '' ) + '\n';
		}
	},
};

export default {
	plugins: [
		autoprefixer( {
			overrideBrowserslist: [
				'last 2 versions',
				'> 1%',
				'not dead',
				'not ie 11',
			],
		} ),
		...( isProduction ? [ cssnano() ] : [] ),
		ensureTrailingNewline,
	],
};
