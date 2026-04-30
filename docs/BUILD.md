# Build System

Split architecture — Rollup for JS, Dart SASS + PostCSS for CSS.

## Commands

| Command | Purpose |
|---|---|
| `npm run start` | Development server — Rollup watch + SASS watch + PostCSS watch + BrowserSync |
| `npm run build` | Production build (JS + CSS) |
| `npm run build:sass` | CSS only — recompile SASS + PostCSS |
| `npm run lint` | ESLint + Stylelint + Prettier (auto-fix) |
| `npm run bundle` | Production zip for deployment (`../zotefoams.zip`) — runs lint + build first |
| `npm run test` | Visual regression tests — desktop, level 1 pages |
| `npm run test:all` | Visual regression tests — desktop, all pages, bail after 20 failures |

Development server proxies at `https://localhost:3001`.

## Source → Output

```
src/**/*.js           → js/critical.js  (head, ~20KB)
                      → js/bundle.js    (footer, ~68KB)
src/sass/**/*.scss    → style.css
```

```
src/
├── main.js               # Main bundle entry point
├── critical.js           # Critical bundle entry point (navigation)
├── components/           # Component JS modules
├── critical/             # Scripts loaded in <head>
├── utils/                # Shared utilities
└── sass/
    ├── style.scss        # Entry point
    ├── abstracts/        # Variables, mixins, functions
    ├── base/             # Base styles, typography
    └── design/           # Component and layout styles
```

## Config Files

- `rollup.config.js` — two bundles (critical + bundle), Babel, Terser, source maps
- `postcss.config.js` — Autoprefixer, targets last 2 versions / >1% share / no IE11