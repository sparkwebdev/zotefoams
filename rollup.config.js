// =============================================================================
// Rollup Configuration - Unified JS/CSS Build System
// =============================================================================

import { defineConfig } from 'rollup';
import { nodeResolve } from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import terser from '@rollup/plugin-terser';
import scss from 'rollup-plugin-scss';
import { writeFileSync } from 'fs';

const isProduction = false; // Always use development mode

export default defineConfig({
	input: 'src/main.js',
	
	output: {
		file: 'js/bundle.js',
		format: 'iife', // Immediately Invoked Function Expression for WordPress compatibility
		name: 'ZotefoamsTheme',
		sourcemap: !isProduction
	},

	plugins: [
		// Resolve node modules
		nodeResolve({
			browser: true,
			preferBuiltins: false
		}),

		// Convert CommonJS modules to ES6
		commonjs(),

		// Process SCSS files
		scss({
			output: function (styles, styleNodes) {
				// Force output to root directory
				writeFileSync('style.css', styles);
				
				// Generate source map in development
				if (!isProduction && styleNodes && styleNodes.map) {
					writeFileSync('style.css.map', JSON.stringify(styleNodes.map));
				}
			},
			outputStyle: 'compressed',
			sourceMap: !isProduction,
			includePaths: ['src/sass/'],
			watch: ['src/sass/**/*.scss'],
			
			// PostCSS processing
			processor: css => {
				// Here we could add autoprefixer, etc. in the future
				return css;
			}
		}),

		// Copy static assets if needed (add rollup-plugin-copy when required)
		// copy({
		//   targets: [
		//     { src: 'src/assets/images/*', dest: 'assets/images' },
		//     { src: 'src/assets/fonts/*', dest: 'assets/fonts' }
		//   ]
		// }),

		// Minify in production
		isProduction && terser({
			compress: {
				drop_console: false,
				drop_debugger: true
			},
			mangle: {
				keep_fnames: true
			},
			format: {
				comments: false
			}
		})
	].filter(Boolean),

	// External dependencies (if any global libraries exist)
	external: [
		// Add any global libraries that shouldn't be bundled
		// e.g., 'jquery' if using WordPress jQuery
	],

	// Watch mode configuration
	watch: {
		include: ['src/**', 'sass/**'],
		exclude: ['node_modules/**']
	}
});