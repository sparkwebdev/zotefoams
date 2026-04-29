# Development Tracker

## Pending Issues (To Fix)

- [ ] **[high] iOS — search input causes page to scroll up on keypress** — when scrolled down the page, opening the utility search and typing causes the page to scroll up with each keypress. iOS Safari scrolls to keep the focused input in view. Attempted: `keydown`+`setTimeout` restore, `scroll` event intercept — neither reliable. Likely needs a CSS approach (`position: fixed` on body while input is focused) or a scroll-lock library.
- [ ] **[high] iOS — accordion scroll on open** — opening an accordion item on iOS causes the page to scroll unexpectedly.
- [ ] **Git publish to sites pipeline** - Auto deploy on PR
- [ ] **[high] Analytics — consolidate, enqueue properly, add consent gating** — see notes below
- [ ] **[high] Mailchimp script loading — replace raw `<script>` output with `wp_enqueue_script()`** — see notes below
- [x] **Video modal focus trap** — `<dialog>`-based refactor in stash `a11y-fix-4-video-modal-dialog`. Needs revisiting — focus escapes browser on close.
- [x] **Screen reader ARIA labels** — Carousel live region in stash `a11y-fix-8-carousel-live-region`.
- [x] **Reduced motion support** — Global catch-all in stash `a11y-fix-10-reduced-motion`. Note: opt-in approach (animate under `no-preference`) is architecturally cleaner but requires larger refactor.
- [ ] **Panel Switcher desktop→mobile sync** — When a desktop tab is selected then viewport narrows to mobile, accordion doesn't reflect the active panel. Needs resize/breakpoint handler to align accordion `aria-expanded` with checked radio.
- [ ] **Section List unused remove?** — Audit and remove section-list.js filter logic — the filter UI is never rendered by the current template; check for other orphaned JS while there.
- [ ] **Industries page title** - Update page picker to handle Markets/Industries title change
- [ ] **Hardcoded parent ID in dual_carousel.php** - Line 14 has hardcoded parent ID 11 for arrow color - should use page title/slug lookup instead
- [ ] **Video Knowledge Hub** - Update to accept video files instead of just documents
- [ ] **Missing paragraph spacing in components** - Multiple paragraphs in component content areas render without vertical spacing between them (visible across several components)
- [ ] **Remove .section-list functionality** - Not used, can be removed from codebase
- [ ] **Button icon inconsistency** - `specs_accordion` default CTA uses an inline SVG chevron; other buttons use CSS `::after` with background SVG image files. Consider standardising — inline SVG is arguably the better approach (uses `currentColor`, no extra file, scales cleanly) so the fix may be to migrate existing `::after` icon buttons to inline SVG rather than the other way around
- [ ] **Our History — tooltip single-open enforcement** — user can click to open a tooltip, then hover to open a second; only one should be open at a time. Fix: close all other visible popups before opening a new one.
- [ ] **[low] Our Locations Map — overlapping points** — map pins overlap in dense areas; consider clustering or offset logic.
- [ ] Check all carousels allow for multiple on a page
- [ ] Ocassional intermittent scrollbars on bir_widgets (investors/regulatory-news/)
- [ ] **Linguise translation not working (staging)** — translation not functioning on staging; suspected billing/API key issue. Unresolved — check Linguise account.
- [ ] **Bold headline styling discrepancy (staging)** — headline bold styling differs between `/news-centre/events/` and `/news-centre/blog/`. Needs CSS investigation.
- [ ] **Language flag/picker alignment (staging)** — language picker flag alignment is off; possibly related to wider popup introduced post-Linguise update.
- [ ] **[low] BackWPup `session_start()` notice** — PHP Notice: `session_start(): Ignoring session_start() because a session is already active` (`backwpup/src/Infrastructure/Restore/commons.php:395`) on every page load. BackWPup plugin bug — not harmful, no user-facing impact.
- [ ] **[low] Semantic HTML containers** - Change component wrapper `<div>` to `<section>` elements
- [ ] **[low] Add `:focus-visible` styles** — improves keyboard navigation accessibility without showing focus rings on mouse click.

## Pending Optimizations

- [ ] **Query Caching** - Add transient caching to all `get_posts()` calls to reduce database queries by 60-80%
- [ ] **Critical CSS** - Extract and inline above-the-fold CSS for improved perceived performance
- [ ] **WebP Image Support** - Implement automatic WebP generation with fallbacks for older browsers
- [ ] **Inline Style Extraction** - Extract 24 files with `style=` patterns to CSS classes
- [ ] **Component Organization** - Organize 29 components into subdirectories (layout/, interactive/, content/)
- [ ] **Component Unit Testing** - Implement PHPUnit testing framework for complex components
- [ ] **REST API Migration** - Consider for dynamic content loading to improve perceived performance
- [ ] **Nonce Verification** - Implement for any future AJAX handlers (none currently identified)

---

## High Priority Notes

### Analytics — Consolidate, Enqueue Properly & Add Consent Gating

GA and LinkedIn scripts are hardcoded in `inc/analytics.php`. ACF fields (`google_analytics_measurement_id`, `linkedin_partner_id`) exist and sanitize filters are now in place (`acf-config.php`) but the fields are not yet driving the script output — hardcoded values still used.

- Remove hardcoded scripts; use ACF options as single source of truth
- Load via `wp_enqueue_script()` + `wp_add_inline_script()`, only if IDs are set
- Integrate a consent tool (e.g. CookieYes, Complianz) and gate script loading on opt-in
- Resolves legal risk (GDPR / UK compliance) and gives admin full control

Completing this also resolves the **[low] Consistent hook usage** tracker item as a byproduct.

### Mailchimp Script Loading

Mailchimp scripts injected via `wp_footer` with raw `<script>` tags; relies on jQuery being available implicitly.

- Replace with `wp_enqueue_script()` and declare `['jquery']` as a dependency
- Move inline config to `wp_add_inline_script()`
- Use full `https://` URL (no protocol-relative URLs)
- Optionally only load on pages where the form exists