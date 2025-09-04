// =============================================================================
// Rollup Configuration - Unified JS/CSS Build System
// =============================================================================

import { defineConfig } from 'rollup';
import { nodeResolve } from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import terser from '@rollup/plugin-terser';
import scss from 'rollup-plugin-scss';
import { writeFileSync } from 'fs';
import postcss from 'postcss';
import autoprefixer from 'autoprefixer';
import cssnano from 'cssnano';

const isProduction = false; // Development mode - minification disabled for debugging

export default defineConfig([
	// Critical bundle configuration - loads first in head
	{
		input: 'src/critical.js',
		
		output: {
			file: 'js/critical.js',
			format: 'iife',
			name: 'ZotefoamsCritical',
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

		// Watch mode configuration
		watch: {
			include: ['src/critical/**', 'src/utils/**'],
			exclude: ['node_modules/**']
		}
	},
	
	// Main bundle configuration - loads in footer
	{
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
			output: async function (styles, styleNodes) {
				// PostCSS plugins configuration
				const plugins = [
					autoprefixer({
						overrideBrowserslist: [
							'last 2 versions',
							'> 1%',
							'not dead',
							'not ie 11'
						]
					})
				];

				// Add minification in production
				if (isProduction) {
					plugins.push(cssnano({
						preset: 'default'
					}));
				}

				// Process CSS with PostCSS
				const result = await postcss(plugins).process(styles, { 
					from: undefined,
					map: !isProduction ? { inline: false } : false
				});
				
				// Force output to root directory
				writeFileSync('style.css', result.css);
				
				// Generate source map in development
				if (!isProduction && result.map) {
					writeFileSync('style.css.map', result.map.toString());
				}
			},
			outputStyle: isProduction ? 'compressed' : 'expanded',
			sourceMap: !isProduction,
			includePaths: ['src/sass/'],
			watch: ['src/sass/**/*.scss']
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
			exclude: ['node_modules/**', 'src/critical/**']
		}
	}
]);