# Development Tracker

## Pending Issues (To Fix)

### a11y issues

- [ ] **Knowledge Hub menu drobdown keyboard controls** - Enter should link Knowledge Hub
- [ ] **Split Carousel keyboard controls** - Keyboard navigation could be improved for better accessibility
- [ ] **Step Slider keyboard interaction** - Keyboard navigation could be improved for better accessibility
- [ ] **Screen reader ARIA labels** - Test and improve ARIA labels for carousel announcements, button labels, form fields, and dynamic content updates
- [ ] **Utility menu ARIA roles** - Add `role="menuitem"` to top links and `role="menu"` to sub-`ul`s for enhanced screen reader clarity
- [ ] **ARIA live region feedback** - Add hidden `<div aria-live="polite">` that announces menu state changes (open/close) to screen reader users
- [ ] **Reduced motion support** - Disable CSS transitions when user has `prefers-reduced-motion: reduce` media query set for accessibility compliance
- [ ] **Panel Switcher** - Look to improve aria attributes; Ttry to sync active tab across mobile/desktop
- [ ] **Mega Nav** - Visually label external link items
- [ ] **Accordion aria-expanded** - All accordion `<button>` elements lack `aria-expanded`; screen readers cannot determine whether a section is open or closed. Fix: set `aria-expanded="false"` in PHP on each `.accordion-header` and toggle it in `accordion.js` alongside the existing `open` class

### Other issues

- [ ] **[high] Analytics — consolidate, enqueue properly, add consent gating** — see notes below
- [ ] **[high] Mailchimp script loading — replace raw `<script>` output with `wp_enqueue_script()`** — see notes below
- [ ] **[med] Repeated DOM queries — `carousel-init.js`** — `querySelectorAll('.animate__animated')` runs inside `slideChangeTransitionStart` on every slide change; cache references on init instead. (`our-history.js` already resolved.)
- [ ] **[med] Accessibility gaps — `file-list.js` / `section-list.js`** — visibility toggles have no ARIA updates; screen readers unaware of state changes. Also: `accordion.js` opens content but does not move focus to the revealed panel.
- [ ] **[med] Multiple global click handlers** — `file-list.js` and `section-list.js` attach `document.addEventListener('click', ...)` inside a `forEach` loop, creating one handler per instance. `our-history.js` does the same per popup marker. Move listeners outside loops and identify the target container from the event.
- [ ] **[med] Inline style manipulation** — 46+ `.style.*` assignments across `accordion.js`, `file-list.js`, `section-list.js`, `carousel-init.js`. `ZotefoamsAnimationUtils` already exists for this — swap inline style manipulation for class toggles and utility calls. Fixing this resolves most of the ARIA/accessibility gaps (M6) as a byproduct.
- [ ] **[med] Event listener lifecycle** — zero `removeEventListener` calls anywhere. `our-history.js` scroll listener never cleans up; `video-modal.js` keydown listener missing `{ once: true }` — attaches multiple times if modal reinitialises; `file-list.js`/`section-list.js` accumulate click listeners per instance.
- [ ] **[med] Inconsistent utility layer** — `ZotefoamsAnimationUtils`, `ZotefoamsClassUtils`, `ZotefoamsAccessibilityUtils` exist in `dom-utilities.js` but `file-list.js`, `section-list.js`, and `carousel-init.js` still do raw DOM manipulation. Largely the same sweep as inline style fix above.
- [ ] **[low] CSS/JS coupling — adopt `data-js` hook convention**
- [ ] **[low] Reusable component patterns** — Popup open/close logic duplicated across `our-history.js`, `video-modal.js`, `interactive-image.js`; dropdown toggle near-identical in `file-list.js` and `section-list.js`. A small `popup.js`/`dropdown.js` utility in `src/utils/` would cover both. `ZotefoamsAnimationUtils` already handles visibility — gap is the ARIA + focus management layer on top.
- [ ] **[low] Consistent hook usage** — `template-functions.php:207` `insert_video_overlay` outputs raw HTML via `wp_footer`; review whether this should use a proper template include. Analytics and Mailchimp raw `<script>` outputs tracked separately in issues.md (H1/H-todo).
- [ ] **[low] Versioning strategy — use `filemtime` in dev** — `_S_VERSION` used for all assets; cache bust requires a version bump. Use `filemtime(get_template_directory() . '/js/bundle.js')` when `WP_DEBUG` is true for automatic cache busting during development.

- [ ] **Remove unused WordPress image sizes** — `1536x1536` and `2048x2048` are generated automatically by WordPress 5.3+ but are not used anywhere in the theme. Add `remove_image_size('1536x1536')` and `remove_image_size('2048x2048')` to `inc/theme-setup.php` to stop WP generating two extra copies of every uploaded image.

- [ ] **Button icon inconsistency** - `specs_accordion` default CTA uses an inline SVG chevron; other buttons use CSS `::after` with background SVG image files. Consider standardising — inline SVG is arguably the better approach (uses `currentColor`, no extra file, scales cleanly) so the fix may be to migrate existing `::after` icon buttons to inline SVG rather than the other way around

- [ ] **Git publish to sites pipeline** - Auto deploy on PR
- [ ] **Image Banner autoplay pause** - Hovering on Next button should pause slide autoplay
- [ ] **File list filter visibility** - Filter dropdown shouldn't show when there's only one category
- [ ] **Hardcoded parent ID in dual_carousel.php** - Line 14 has hardcoded parent ID 11 for arrow color - should use page title/slug lookup instead
- [ ] **Remove .section-list functionality** - Not used, can be removed from codebase
- [ ] **Knowledge Hub video support** - Implement video modal for YouTube links (code stashed as 'knowledge-hub-videos')
- [ ] **Components Overview page** - Create overview page for testing all components
- [ ] **ACF fields reorganization** - Tidy field layouts, add conditional logic
- [ ] **Contact Form Captcha** - Add captcha protection to contact form
- [ ] **Semantic HTML containers** - Change component wrapper `<div>` to `<section>` elements
- [ ] **Fallback CSS** - Implement fallback styles for older browsers
- [ ] **Remove video_two component** - Unused component that can be deleted
- [ ] **Rename video_one component** - Change to media_one for better clarity
- [ ] **Industries page title** - Update page picker to handle Markets/Industries title change
- [ ] **Video Knowledge Hub** - Update to accept video files instead of just documents
- [ ] **Filter dropdown refactor** - Add click-outside to close, hide when single category, improve UX
- [ ] **Split Carousel shared state bug** - Left/right navigation controls all instances simultaneously instead of scoping to the individual carousel
- [ ] **Multi Item Carousel shared state bug** - Same issue as Split Carousel; navigation controls affect all instances rather than scoping to individual carousel. Fix pattern: `closest()` to the container, then `querySelector()` to get scoped DOM elements — pass elements directly to Swiper `nextEl`/`prevEl`/`el` instead of CSS strings. Already applied to Multi Item Gallery Carousel.
- [ ] **Multi Item Carousel `totalSlides` dead code** — `Math.max(2, Math.min(2, totalSlides))` always evaluates to `2`; the variable and math serve no purpose. Simplify breakpoints to `slidesPerView: 2` directly
- [ ] **Dual Carousel empty `slideChangeTransitionEnd` callback** — Handler body is a comment only (`// Animation now happens on start, not end`). Dead code; remove the callback entirely
- [ ] **Tabbed Split shared state bug** - Clicking tabs on one instance changes all other instances; not scoped to individual component
- [ ] **Step Slider overline positioning** - Overline title (`step_slider_slide_overline`) renders outside the dark slider area when JS doesn't fully initialise (e.g. component library); on live site it positions correctly within the component
- [ ] **Calendar Carousel shared state bug** - Same issue; navigation controls affect all instances rather than scoping to individual carousel
- [ ] **Missing paragraph spacing in components** - Multiple paragraphs in component content areas render without vertical spacing between them (visible across several components)
- [x] **data_points.php function redeclaration** - `getDecimalPlaces()` declared at file scope causes fatal error when template is included multiple times. Fixed with `function_exists()` check
- [x] **document_list.php function redeclaration** - `get_category_data()` and `create_document_entry()` declared at file scope cause fatal error when template is included multiple times. Fixed with `function_exists()` checks

## Pending Optimizations

- [ ] **Query Caching** - Add transient caching to all `get_posts()` calls to reduce database queries by 60-80%
- [ ] **Critical CSS** - Extract and inline above-the-fold CSS for improved perceived performance
- [ ] **WebP Image Support** - Implement automatic WebP generation with fallbacks for older browsers
- [ ] **Inline Style Extraction** - Extract 24 files with `style=` patterns to CSS classes
- [ ] **Component Organization** - Organize 29 components into subdirectories (layout/, interactive/, content/)
- [ ] **SEO & Structured Data** - Add JSON-LD structured data, Open Graph, and Twitter Cards
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