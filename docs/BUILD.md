# Build System Documentation

## Overview

The Zotefoams theme uses a **split build system** where JavaScript and CSS are compiled separately for better performance and reliability.

### Build Architecture

```
JavaScript Pipeline:
  src/**/*.js → Rollup → Babel → Terser → js/bundle.js + js/critical.js

CSS Pipeline:
  src/sass/**/*.scss → Dart SASS → PostCSS (Autoprefixer) → style.css

Development Server:
  BrowserSync → Live reload on file changes
```

## Why Split Build System?

**Historical Context:** Originally (July 2025), Rollup handled both JS and CSS via `rollup-plugin-scss`. This was changed in October 2025 during navigation refactoring work.

**Reasons for splitting:**

1. **More Reliable Watch Mode** - `rollup-plugin-scss` can miss SASS changes or require full rebuilds
2. **Faster SASS Compilation** - Dart SASS CLI is significantly faster than the Rollup plugin
3. **Better Source Maps** - Separate compilation provides better debugging experience
4. **Clearer Separation** - Easier to debug which part of build is slow or failing
5. **PostCSS Control** - Separate `postcss.config.js` gives more control over CSS processing

**Tradeoff:** More complexity in package.json scripts, but hidden behind simple commands.

## NPM Scripts

### Development

```bash
npm run start
```
**What it does:**
- Starts 4 concurrent processes:
  1. **Rollup Watch** - Watches `src/**/*.js` and rebuilds JS bundles on change
  2. **SASS Watch** - Watches `src/sass/**/*.scss` and recompiles CSS on change
  3. **PostCSS Watch** - Watches `style.css` and applies autoprefixer on change
  4. **BrowserSync** - Proxies local site at port 3001 with HTTPS and live reload

**When to use:** Day-to-day development with live reload

**Files watched:**
- `src/sass/**/*.scss` → Triggers CSS rebuild
- `src/**/*.js` → Triggers JS rebuild
- `**/*.php` → Triggers browser reload (no rebuild needed)

**Access development server at:** `https://localhost:3001`

### Production Build

```bash
npm run build
```
**What it does:**
1. Runs `rollup -c` → Compiles and minifies JS (critical.js + bundle.js)
2. Runs `npm run build:sass` → Compiles SASS + runs PostCSS

**When to use:** Before deploying to production, testing final build output

**Output:**
- `js/critical.js` - Critical JS loaded in `<head>` (navigation, etc.)
- `js/bundle.js` - Main JS loaded in footer
- `style.css` - Compiled and prefixed CSS

### CSS-Only Build

```bash
npm run build:sass
```
**What it does:**
1. Compiles `src/sass/style.scss` to `style.css` (expanded format, no source map)
2. Runs PostCSS to add vendor prefixes

**When to use:** When you only changed SASS files and don't need JS rebuild

### Linting

```bash
npm run lint
```
**What it does:**
1. Runs ESLint on all JavaScript files (`src/**/*.js`) with auto-fix
2. Runs Stylelint on all SASS files with auto-fix
3. Runs Prettier to format SASS files

**When to use:** Before committing code changes, fixing style and quality issues

**ESLint Configuration:**
- Uses WordPress coding standards (`.eslintrc`)
- Warns on console statements, unused variables, and code quality issues
- Auto-fixes formatting and style issues

### Theme Bundling

```bash
npm run bundle
```
**What it does:**
1. Creates a production-ready theme package at `../zotefoams.zip`
2. Excludes all development files and dependencies
3. Includes only essential theme files for distribution

**When to use:** Creating deployable theme packages for production sites

**Excluded from bundle:**
- Source files (`src/` directory)
- Development dependencies (`node_modules`, config files)
- Development documentation (`docs/`, `tests/`)
- Build artifacts (source maps, etc.)

**Bundle size:** ~13MB (production-optimized)

## Build Configuration Files

### `rollup.config.js`
- Handles JavaScript bundling only
- Two separate bundles: `critical.js` (head) and `bundle.js` (footer)
- Babel transpilation for browser compatibility
- Terser minification in production mode
- Source maps in development

**Key settings:**
- `isProduction: true` - Currently set to always minify
- ES6 → ES5 transpilation via Babel
- Targets: `> 0.5%, last 2 versions, not dead`

### `postcss.config.js`
- Autoprefixer configuration
- Runs after Dart SASS compilation
- Adds vendor prefixes for browser compatibility

**Browser targets:**
- Last 2 versions of all browsers
- Browsers with >1% market share
- Excludes "dead" browsers (no updates for 24 months)
- Excludes IE11

### `package.json` Scripts Breakdown

```json
{
  "build": "rollup -c && npm run build:sass",
  "build:sass": "sass src/sass/style.scss:style.css --style=expanded --no-source-map && postcss style.css -o style.css",
  "watch:sass": "sass src/sass/style.scss:style.css --watch --style=expanded --no-source-map",
  "watch:postcss": "postcss style.css -o style.css --watch",
  "lint": "stylelint 'src/sass/**/*.scss' --fix && prettier --write 'src/sass/**/*.scss'",
  "start": "concurrently \"rollup -c --watch\" \"npm run watch:sass\" \"npm run watch:postcss\" \"browser-sync start --proxy 'https://zotefoams-phase-2.local/' --port 3001 --https --files 'style.css' --files 'js/bundle.js' --files '**/*.php' --ignore 'node_modules'\""
}
```

**Why `concurrently`?**
- Runs multiple watch processes in parallel
- Each process has its own output stream
- Terminates all processes when one fails or when user stops

## File Structure

### Source Files

```
src/
├── main.js                 # Main bundle entry point
├── critical.js             # Critical bundle entry point (navigation)
├── components/             # Component-specific JS modules
│   ├── accordion.js
│   ├── carousel.js
│   ├── video-modal.js
│   └── ...
├── critical/               # Critical scripts loaded in <head>
│   ├── navigation.js
│   └── navigation-keyboard.js
├── utils/                  # Shared utilities
│   ├── dom-utilities.js
│   └── site-utilities.js
└── sass/                   # SASS source files
    ├── style.scss          # Main SASS entry point
    ├── abstracts/          # Variables, mixins, functions
    ├── base/               # Base styles, typography
    └── design/             # Component and layout styles
```

### Compiled Output

```
js/
├── critical.js         # Critical JS bundle (~13KB minified)
├── critical.js.map     # Source map for critical.js (dev only)
├── bundle.js           # Main JS bundle (~13.5KB minified)
└── bundle.js.map       # Source map for bundle.js (dev only)

style.css               # Compiled CSS (~150KB)
style.css.map           # Source map (dev only, if enabled)
```

## Development Workflow

### Typical Development Session

1. **Start development server:**
   ```bash
   npm run start
   ```

2. **Make changes:**
   - Edit SASS files in `src/sass/` → Auto-recompiles CSS
   - Edit JS files in `src/` → Auto-recompiles JS
   - Edit PHP files → Auto-reloads browser

3. **Test in browser:**
   - View changes at `https://localhost:3001`
   - Changes appear automatically (live reload)

4. **Before committing:**
   ```bash
   npm run lint           # Fix SASS formatting
   npm run build         # Test production build
   ```

### Troubleshooting

**Issue:** SASS changes not appearing
- **Solution:** Check terminal for SASS compilation errors
- **Check:** Is watch:sass process still running?
- **Try:** Stop and restart `npm run start`

**Issue:** JS changes not appearing
- **Solution:** Check terminal for Rollup compilation errors
- **Check:** Clear browser cache (Cmd+Shift+R)
- **Try:** Check browser console for JS errors

**Issue:** Browser not reloading
- **Solution:** Check BrowserSync is running on port 3001
- **Check:** Are you viewing `localhost:3001` (not the Local site URL)?
- **Try:** Manually refresh browser

**Issue:** "Port 3001 already in use"
- **Solution:** Kill existing BrowserSync process: `lsof -ti:3001 | xargs kill`
- **Try:** Change port in package.json if needed

**Issue:** Multiple concurrent processes overwhelming
- **Solution:** Run individual watch commands in separate terminals:
  ```bash
  # Terminal 1
  npm run watch:sass

  # Terminal 2
  npx rollup -c --watch

  # Terminal 3
  npm run watch:postcss
  ```

## Testing

### Visual Regression Testing

```bash
npm run test         # Desktop only, level 1 pages
npm run test:all     # Desktop only, all pages, bail after 20 failures
```

Uses Playwright for visual regression tests. See `tests/vrc/` for configuration.

## Performance Notes

### Build Times (Approximate)

- **Initial Rollup build:** ~2-3 seconds
- **SASS compilation:** ~1 second
- **PostCSS processing:** <0.5 seconds
- **Hot reload (BrowserSync):** <1 second

### Bundle Sizes

- `critical.js`: ~13KB minified
- `bundle.js`: ~13.5KB minified
- `style.css`: ~150KB (unminified, autoprefixed)

## Future Considerations

### Potential Optimizations

1. **CSS Minification** - Consider adding cssnano to PostCSS pipeline for production
2. **Source Maps in Production** - Currently disabled; could enable for staging
3. **Cache Busting** - Add version hashes to filenames in production
4. **CSS Purging** - Use PurgeCSS to remove unused styles
5. **Critical CSS** - Inline critical CSS in `<head>` for faster LCP

### Potential Simplifications

If you want to reduce complexity:

1. **Combine watch processes** - Create single chained watch script
2. **Remove PostCSS watch** - Run PostCSS only on SASS compilation complete
3. **Revert to Rollup-only** - Use `rollup-plugin-scss` again (but may have watch issues)

## Related Documentation

- **`CLAUDE.md`** - Theme architecture and component system
- **`meganav.md`** - Navigation system documentation
- **`rollup.config.js`** - Rollup configuration with inline comments
- **`postcss.config.js`** - PostCSS configuration with inline comments

---

**Last Updated:** October 30, 2025
**Build System Version:** Split architecture (since Oct 2025)
**Previous Version:** Unified Rollup build (July-Oct 2025)
