# Development Tracker

## Pending Issues (To Fix)

- [ ] **[high] iOS — search input causes page to scroll up on keypress** — when scrolled down the page, opening the utility search and typing causes the page to scroll up with each keypress. iOS Safari scrolls to keep the focused input in view. Attempted: `keydown`+`setTimeout` restore, `scroll` event intercept — neither reliable. Likely needs a CSS approach (`position: fixed` on body while input is focused) or a scroll-lock library. **Fix ready on `wip/ios-safari-fixes`** (body scroll-lock on touch devices, plus iOS carousel animation replay) — needs an iPhone Safari test before merging.
- [ ] **[high] iOS — accordion scroll on open** — opening an accordion item on iOS causes the page to scroll unexpectedly.
- [ ] **Git publish to sites pipeline** - Auto deploy on PR. Planning doc on `wip/deployment-pipeline`; ties in with removing the `.git` folder FTP'd into the live theme directory.
- [ ] **[high] Analytics — consolidate, enqueue properly, add consent gating** — see notes below
- [ ] **[high] Mailchimp script loading — replace raw `<script>` output with `wp_enqueue_script()`** — see notes below
- [x] **Video modal focus trap** — done in `main`: native `<dialog>` rebuild (a11y B4) plus the video-overlay memory-leak fix.
- [ ] **Panel Switcher desktop→mobile sync** — When a desktop tab is selected then viewport narrows to mobile, accordion doesn't reflect the active panel. Needs resize/breakpoint handler to align accordion `aria-expanded` with checked radio.
- [ ] **Audit `section-list.js`** — the component is used on the Knowledge Hub technical page (`content-knowledge-hub-section-technical.php`, with its "show all" button); check which parts of the filter logic are actually reachable before removing anything, and look for other orphaned JS while there.
- [x] **Industries page title** - Page lookups try `'Markets'` then fall back to `'Industries'` (`class-theme-helper.php`, `dual_carousel.php`).
- [ ] **Hardcoded parent ID in dual_carousel.php** - Line 14 has hardcoded parent ID 11 for arrow color - should use page title/slug lookup instead
- [ ] **Video Knowledge Hub** - Update to accept video files instead of just documents. Draft on `wip/knowledge-hub-videos` (YouTube items via oEmbed, Aug 2025).
- [ ] **Button icon inconsistency** - `specs_accordion` default CTA uses an inline SVG chevron; other buttons use CSS `::after` with background SVG image files. Consider standardising — inline SVG is arguably the better approach (uses `currentColor`, no extra file, scales cleanly) so the fix may be to migrate existing `::after` icon buttons to inline SVG rather than the other way around
- [ ] **Our History — tooltip single-open enforcement** — user can click to open a tooltip, then hover to open a second; only one should be open at a time. Fix: close all other visible popups before opening a new one.
- [ ] **[low] Our Locations Map — overlapping points** — map pins overlap in dense areas; consider clustering or offset logic.
- [x] Check all carousels allow for multiple on a page — each carousel is scoped to its own instance (`95aec88`).
- [ ] Ocassional intermittent scrollbars on bir_widgets (investors/regulatory-news/)
- [ ] **[low] BackWPup `session_start()` notice** — PHP Notice: `session_start(): Ignoring session_start() because a session is already active` (`backwpup/src/Infrastructure/Restore/commons.php:485` in 5.7.8) on admin requests. Caused by the `zotefoams-assistant` plugin starting a session on every request; not harmful, no user-facing impact. Raise with WRD.
- [ ] **[low] Semantic HTML containers** - Change component wrapper `<div>` to `<section>` elements
- [x] **[low] Add `:focus-visible` styles** — done in `main` as the project-wide `:focus-visible` baseline (a11y S5).
- [ ] **Swiper loaded from CDN with an unpinned version** — `inc/assets.php` loads `cdn.jsdelivr.net/npm/swiper@11/…`, so any new 11.x release reaches the site unannounced. Pin an exact version, or bundle Swiper locally.
- [ ] **Put the `zotefoams-component-library` plugin under version control** — it has no git history; its only copies are the local site and live.

## Pending Optimizations

- [ ] **WebP output** - Live's GD supports WebP and AVIF; consider generating WebP sub-sizes via the `image_editor_output_format` filter.
- [ ] **Inline Style Extraction** - Extract `style=` patterns in 30 template files to CSS classes
- [ ] **Shared popup/dropdown helper** - Show/hide, ARIA and focus logic is duplicated across `our-history.js`, `video-modal.js`, `interactive-image.js`, `locations-map.js`, `file-list.js` and `section-list.js`. A small shared helper in `src/utils/` would stop accessibility fixes being repeated per component and makes the Our History single-open item straightforward.
- [ ] **JS hooks via `data-js`** - 61 class-string `querySelector` calls remain in `src/components/` alongside 65 `data-js` hooks. Use `data-js` for new work and migrate existing selectors opportunistically.
- [ ] **WRD component assets outside the build** - `callout_banner`, `zoteiq_cta`, `hero_banner`, `table` and `applications_slider` each load their own `style.css` (and `applications_slider` a `script.js`) via `wp_enqueue_*` in the template, versioned `1.0.0`. WP Fastest Cache combines the CSS on cached pages, so performance is fine; the issues are stale browser caches when a file changes and CSS that bypasses the SCSS variables, linting and build. Quick win: version with `filemtime()`. Proper fix: move the CSS into `src/sass/design/components/` partials and the JS into `src/components/`, and agree the convention with WRD.
- [ ] **[low] Video overlay output** - `insert_video_overlay()` is unprefixed, prints raw HTML via `wp_footer`, and lives in `inc/template-functions.php`. Rename with the `zotefoams_` prefix and move to a template include next time the overlay is touched.

## Accessibility

WCAG 2.2 AA audit (Jul–Aug 2026). Phase 1 shipped to live in Sept 2026 (PR #4): B1, B3, B4, S2, S3, S5 and M1–M4, M6–M12 are fixed. IDs below refer to that audit; the full audit doc is in commit `12761d4` (also on `wip/a11y-23` and `wip/a11y-24`).

- [ ] **S1 Link blue fails contrast** — `#3b82f6` is 3.67:1 on white (needs 4.5:1), 28+ instances per page. The brand colour is locked, so this needs a design decision: a separate darker link colour (e.g. the palette's `#0056b3`) or permanently underlined links.
- [ ] **B2 Hero carousel pause control** — autoplay can only be paused by mouse hover (WCAG 2.2.2, level A). On hold on `wip/a11y-24-carousel-pause`, pending design.
- [ ] **S6 Carousel slide announcements + inert off-screen slides** — on hold on `wip/a11y-23-carousel-live-region`.
- [ ] **M5 Reduced motion** — global catch-all on `wip/a11y-26-reduced-motion`, pulled from phase 1 as too fragile. An opt-in approach (animate only under `prefers-reduced-motion: no-preference`) is cleaner but a larger refactor.
- [ ] **S4 "View" links on the financial documents picker** — every link reads just "View"; add `aria-label="View <document title> (opens in new tab)"`. The Knowledge Hub lists already do this.
- [ ] **[low] New-tab warning** — extend the mega-nav external-link icon and screen-reader text to the other `target="_blank"` links (`footer.php`, `events_details.php`, `webinars_details.php`).
- [ ] **[low] Mega-menu landmarks** — the five closed mega-menu panels keep `role="region"`, cluttering the landmark list; consider dropping the role and keeping `aria-labelledby`.
- [ ] **Manual checks never done** — 320 px reflow, 200% zoom, and focus not hidden under the sticky header (WCAG 2.4.11).

---

## High Priority Notes

### Analytics — Consolidate, Enqueue Properly & Add Consent Gating

GA and LinkedIn scripts are hardcoded in `inc/analytics.php`. ACF fields (`google_analytics_measurement_id`, `linkedin_partner_id`) exist and sanitize filters are now in place (`acf-config.php`) but the fields are not yet driving the script output — hardcoded values still used.

- Remove hardcoded scripts; use ACF options as single source of truth
- Load via `wp_enqueue_script()` + `wp_add_inline_script()`, only if IDs are set
- Integrate a consent tool (e.g. CookieYes, Complianz) and gate script loading on opt-in
- The hard-coded Google Tag Manager snippet in `header.php` needs the same treatment: the October 2026 PHP 8.4 test found GTM, LinkedIn and ZoomInfo loading before any cookie choice
- Once consolidated, remove any ACF field left unused (e.g. `linkedin_partner_id` if it is never wired up)
- Resolves legal risk (GDPR / UK compliance) and gives admin full control

### Mailchimp Script Loading

Mailchimp scripts injected via `wp_footer` with raw `<script>` tags; relies on jQuery being available implicitly.

- Replace with `wp_enqueue_script()` and declare `['jquery']` as a dependency
- Move inline config to `wp_add_inline_script()`
- Use full `https://` URL (no protocol-relative URLs)
- Optionally only load on pages where the form exists