// =============================================================================
// Rollup Configuration - JavaScript Build Pipeline
// =============================================================================
//
// This config handles ONLY JavaScript compilation (critical.js + bundle.js).
// CSS is compiled separately via Dart SASS + PostCSS for better performance.
//
// See BUILD.md for full build system documentation and rationale.
//
// =============================================================================

import { defineConfig } from 'rollup';
import { nodeResolve } from '@rollup/plugin-node-resolve';
import commonjs from '@rollup/plugin-commonjs';
import terser from '@rollup/plugin-terser';
import babel from '@rollup/plugin-babel';

const isProduction = process.env.NODE_ENV === 'production'; // Dynamic production mode based on environment

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

			// Transpile to ES5 for enterprise browser compatibility (IE11, old Edge)
			babel({
				babelHelpers: 'bundled',
				exclude: 'node_modules/**',
				presets: [
					['@babel/preset-env', {
						targets: '> 0.5%, last 2 versions, not dead',
						modules: false,
						bugfixes: true
					}]
				]
			}),

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
			include: ['src/**'],
			exclude: ['node_modules/**', 'src/critical/**']
		}
	}
]);