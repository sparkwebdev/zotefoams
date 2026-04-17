# Plugin Review – Critical Issues + Solutions (High Priority)

## 🚨 1. Analytics – Consolidate, Enqueue Properly & Add Consent Gating

GA and LinkedIn scripts are hardcoded in `inc/analytics.php` while ACF fields (`google_analytics_measurement_id`, `linkedin_partner_id`) also exist but are likely unused. Scripts load unconditionally for all visitors with no consent check.

**Problems:**
- Hardcoded scripts bypass the enqueue system (no dependency management, cache busting, or version control)
- ACF fields exist but aren't driving the output — duplication and no real admin control
- Tracking fires without user consent — legal risk (GDPR / UK compliance)

**Fix:**
- Audit whether ACF fields are wired up (likely not)
- Remove hardcoded scripts, use ACF options as single source of truth
- Load via `wp_enqueue_script()` + `wp_add_inline_script()`, only if IDs are set
- Integrate a consent tool (e.g. CookieYes, Complianz) and gate script loading on opt-in

**Outcome:**
- One clean analytics system, fully configurable via admin
- Properly enqueued, consent-compliant tracking

---

## 📋 TODO – Fix Mailchimp Script Loading

Mailchimp scripts are currently injected via `wp_footer` with raw `<script>` tags and rely on jQuery being available implicitly. This is fragile and can break depending on load order or other plugins.

**Fix:**
- Replace inline `<script>` output with proper `wp_enqueue_script()`
- Declare `['jquery']` as a dependency (do not assume it's already loaded)
- Move inline config to `wp_add_inline_script()`
- Use full `https://` URL (no protocol-relative URLs)
- Optionally only load on pages where the form exists

**Outcome:**
Reliable script loading, correct dependency handling, and improved compatibility with other plugins and optimisations.

---

## 🚨 2. ACF Data Not Validated
Example:
$ga_tracking_id = get_field(...)

Impact:
- Potential XSS if admin compromised

Solution:
- Sanitize on save:

  add_filter('acf/update_value/name=ga_tracking_id', function($value) {
      return sanitize_text_field($value);
  });

- Escape on output (already partially done):
  esc_attr(), esc_js(), esc_html()

---
---

# Secondary Improvements – Non-Critical Issues & Optimisations

These are lower-severity issues that don't block production but will improve performance, maintainability, and robustness.

---

## 1. Swiper Dependency Handling

Issue:
- Swiper is assumed to exist globally (new Swiper(...))
- No explicit dependency management

Risk:
- JS errors if Swiper fails to load or is deferred
- Fragile with optimisation plugins

Fix:
- Enqueue Swiper via wp_enqueue_script and declare as a dependency of your bundle
- OR import it into your build (preferred long-term)
- Optionally guard usage:
  if (typeof Swiper === 'undefined') return;

Recommendation:
- Add a single guard at the top of `carousel-init.js`: `if (typeof Swiper === 'undefined') return;`
- All 7 `new Swiper()` calls are in this file — one guard covers all of them

Outcome:
- Stable carousel initialisation and fewer runtime failures

---

## 2. Multiple Global Click Handlers

Issue:
- Repeated document.addEventListener('click', ...) across components

Risk:
- Performance overhead
- Harder debugging and event conflicts

Fix:
- Consolidate into a single delegated handler where possible
- Or scope handlers to component containers instead of document

Recommendation:
- `file-list.js` and `section-list.js` both attach `document.addEventListener('click', ...)` inside a `forEach` loop — one handler is created per component instance on the page
- Move the listener outside the loop and identify the target container from the event
- `our-history.js` also attaches one global click listener per popup marker — same pattern, same fix

Outcome:
- Cleaner event architecture and improved performance

---

## 3. Repeated DOM Queries

Issue:
- Frequent use of document.querySelector inside loops/events

Risk:
- Unnecessary DOM lookups
- Reduced performance on large pages

Fix:
- Cache selectors outside loops
- Reuse references where possible

Recommendation:
- `our-history.js` runs `document.getElementById()` inside a scroll handler on every scroll event — cache references once on init
- `carousel-init.js` runs `querySelectorAll('.animate__animated')` inside a Swiper `slideChangeTransitionStart` callback — runs on every slide change, cache on init instead

Outcome:
- More efficient DOM interactions

---

## 4. Inline Style Manipulation

Issue:
- Direct style manipulation (display, opacity, etc.)

Risk:
- Hard to maintain
- Conflicts with CSS
- Causes layout/reflow issues

Fix:
- Replace with class-based toggling:
  element.classList.add('is-visible');

Recommendation:
- 46+ inline style assignments across `accordion.js`, `file-list.js`, `section-list.js`, `carousel-init.js`
- `ZotefoamsAnimationUtils` already exists in `src/utils/dom-utilities.js` for exactly this purpose — it's just not being used in these components
- Swap `.style.display`, `.style.opacity`, `.style.maxHeight` for class toggles and utility calls
- Note: fixing this also resolves most of issue 8 (accessibility) as a byproduct

Outcome:
- Cleaner separation of concerns and easier styling

---

## 5. Event Listener Lifecycle

Issue:
- Event listeners added but never removed

Risk:
- Memory leaks in long-lived pages
- Duplicate bindings if components reinitialise

Fix:
- Use once: true where appropriate
- Add teardown logic for dynamic components

Recommendation:
- Zero `removeEventListener` calls exist anywhere in the source
- `our-history.js` attaches a scroll listener (`window.addEventListener('scroll', updateScrollProgress)`) that never cleans up
- `video-modal.js` attaches a keydown listener without `{ once: true }` — attaches multiple if modal initialises more than once
- `file-list.js` and `section-list.js` accumulate click listeners per instance (see issue 2)
- Apply `{ once: true }` to one-time listeners; add teardown logic to scroll/resize handlers

Outcome:
- More predictable behaviour and better performance

---

## 6. Inconsistent Use of Utility Layer

Issue:
- Custom utilities exist but raw DOM APIs are still used in places

Risk:
- Inconsistent patterns
- Harder to maintain

Fix:
- Standardise usage of utility functions across components

Recommendation:
- `ZotefoamsAnimationUtils`, `ZotefoamsClassUtils`, and `ZotefoamsAccessibilityUtils` all exist in `src/utils/dom-utilities.js`
- `accordion.js`, `file-list.js`, `section-list.js`, and `carousel-init.js` all bypass these and do raw DOM manipulation instead
- Mostly a consistency sweep — swap inline style manipulation for the utility calls that already exist
- Fixing this and issue 4 together resolves most of issue 8 as a byproduct

Outcome:
- More consistent, maintainable codebase

---

## 7. Global Namespace Exposure

Issue:
- Some globals still used (e.g. window assignments)

Risk:
- Potential conflicts with plugins or future scripts

Fix:
- Minimise globals
- Keep logic scoped inside modules/IIFE

Recommendation:
- Actually clean — no `window.*` assignments found in source files
- All exports use proper ES module imports/exports
- No action needed

Outcome:
- Safer integration with third-party code

---

## 8. Minor Accessibility Gaps

Issue:
- Some components lack full ARIA/state handling

Risk:
- Reduced accessibility compliance
- Poor keyboard/screen reader experience

Fix:
- Ensure aria-expanded updates correctly
- Manage focus in modals/dropdowns
- Support keyboard interaction consistently

Recommendation:
- Navigation (`navigation.js`) and video modal (`video-modal.js`) handle ARIA correctly — good reference implementations
- `accordion.js` hides content via `display:none` without setting `aria-hidden` — screen readers still see it
- `file-list.js` and `section-list.js` toggle visibility without any ARIA updates
- `accordion.js` scrolls into view on open but doesn't move focus to the revealed content
- `ZotefoamsAccessibilityUtils` already exists for ARIA management — largely the same fix as issues 4 and 6

Outcome:
- Improved accessibility and UX

---
---

# Low Priority Sweep – Minor Improvements & Nice-to-Haves

These are non-critical refinements that improve code quality, consistency, and long-term maintainability. None are urgent, but collectively they raise the overall standard of the theme.

---

## 1. Consistent Naming & Structure

Issue:
- Some inconsistency in naming conventions (functions, variables, components)

Impact:
- Harder onboarding and readability over time

Fix:
- Standardise naming (e.g. prefix all theme functions consistently)
- Align JS component naming patterns

Recommendation:
- Three PHP functions in `inc/admin-editor.php` are unprefixed: `change_post_menu_label`, `change_post_object_label`, and `zoatfoams_allowed_block_types` (note typo: `zoatfoams` not `zotefoams`) — rename all to `zotefoams_`
- `add_preload_to_google_fonts` in `inc/assets.php` is also unprefixed — rename to `zotefoams_add_preload_to_google_fonts`
- JS is consistent: utils use `Zotefoams` class names, components use `init*` functions — different patterns but intentional given the module structure, no changes needed

Outcome:
- More predictable and readable codebase

---

## 2. File Organisation

Issue:
- Some functionality grouped broadly (e.g. integrations, utilities)

Impact:
- Can become harder to navigate as the codebase grows

Fix:
- Consider splitting by responsibility:
  - analytics
  - integrations
  - components
  - utilities

Recommendation:
- `inc/` is already well-split — no major changes needed
- Minor flag: `require_video_overlay` and `insert_video_overlay` in `inc/template-functions.php` feel more like component concerns than template utilities — could move to a dedicated `inc/components.php` or `inc/video.php`
- JS `src/utils/` is clean — no utility logic buried in components

Outcome:
- Clearer separation of concerns

---

## 3. Dead / Legacy Code Risk

Issue:
- Refactors (e.g. analytics, Mailchimp, ACF) may leave unused code behind

Impact:
- Confusion and maintenance overhead

Fix:
- Audit for:
  - unused functions
  - unused constants
  - unused ACF fields

Recommendation:
- `functions-original-backup.php` is already gone — good
- No commented-out function bodies found in `inc/`
- Main risk is after the planned analytics and Mailchimp refactors (see issues-high.md) — audit for orphaned functions at that point
- Unused ACF fields (e.g. `linkedin_partner_id` if never wired up) should be removed after the analytics consolidation

Outcome:
- Leaner, cleaner codebase

---

## 4. Magic Numbers & Hardcoded Values

Issue:
- Values like timeouts, offsets, animation delays hardcoded in JS

Impact:
- Harder to tweak globally

Fix:
- Extract into constants at top of file

Recommendation:
- Widespread `setTimeout` with hardcoded values throughout:
  - `navigation-keyboard.js:84` — `200ms`
  - `navigation.js:134` — `0ms`
  - `file-list.js:52, 84` — multiple values
  - `utility-search.js:53, 68` — `100ms`
  - `carousel-init.js:44, 53, 58, 104, 136` — multiple values
  - `section-list.js:40`
- Extract each to a named constant at the top of the file, e.g. `const FOCUS_DELAY_MS = 200;`

Outcome:
- Easier tuning and consistency

---

## 5. Console Logging in Production

Issue:
- Some console.error / console.log usage remains

Impact:
- Noise in production console

Fix:
- Wrap in debug check or remove:
  if (window.DEBUG) console.log(...);

Recommendation:
- `navigation.js:23` — `console.log('[Navigation v1.0.1] Script loaded')` — remove entirely
- `navigation.js:387` — `console.error('[Navigation] Error:', error)` — keep but wrap in a debug check
- `video-modal.js:22` — `console.error('Invalid YouTube URL:', url)` — acceptable in error handling, keep
- `dom-utilities.js:497` — `console.error('Ready callback error:', error)` — fine in a catch block, keep
- Rollup already has `drop_debugger: true` in production config but does not drop `console.*` — add `drop_console: true` in production terser config if all console calls should be stripped automatically

Outcome:
- Cleaner production output

---

## 6. Defensive Programming Gaps

Issue:
- Some DOM assumptions without null checks

Impact:
- Potential edge-case JS errors

Fix:
- Add guards where missing:
  if (!element) return;

Recommendation:
- 73 `querySelector`/`querySelectorAll` calls across 11 component files — highest risk in `carousel-init.js` (16 calls), `our-history.js` (10), and `accordion.js` (11)
- Most components do guard at the top with an early return if the root container isn't found, which covers most cases
- Worth a targeted pass on chained queries (e.g. `element.querySelector(...)` where `element` itself could be null)

Outcome:
- More resilient frontend code

---

## 7. CSS Coupling

Issue:
- JS tightly coupled to specific class names

Impact:
- Fragile when CSS changes

Fix:
- Prefer data attributes for JS hooks:
  data-js="component-name"

Recommendation:
- 73 querySelector/querySelectorAll calls directly reference CSS class strings — no `data-js` hook pattern is used anywhere
- For a theme this size, full migration to `data-js` attributes would be a significant refactor — not recommended wholesale
- Pragmatic approach: adopt `data-js` for any new components going forward, and migrate existing ones opportunistically during other refactors
- At minimum, document the convention so it's consistent from here on

Outcome:
- Safer refactoring between CSS and JS

---

## 8. Reusable Component Patterns

Issue:
- Some repeated logic across components (e.g. popup handling)

Impact:
- Duplication

Fix:
- Abstract shared behaviours into reusable helpers

Recommendation:
- Popup open/close logic is duplicated across `our-history.js`, `video-modal.js`, and `interactive-image.js` — similar show/hide + ARIA + focus patterns
- Dropdown toggle logic is near-identical between `file-list.js` and `section-list.js`
- A small shared `popup.js` or `dropdown.js` utility in `src/utils/` would cover both cases
- Note: `ZotefoamsAnimationUtils` in `dom-utilities.js` already handles visibility — the gap is the ARIA + focus management layer on top

Outcome:
- Less duplication, easier updates

---

## 9. Minor Performance Tweaks

Issue:
- Some scroll/resize handlers not fully optimised

Impact:
- Slight performance overhead

Fix:
- Ensure throttling/debouncing is applied consistently

Recommendation:
- No `window.addEventListener('scroll'` or `'resize'` calls found in `src/components/` — they may be in `src/critical/` or handled via Swiper internally
- A `debounce` utility already exists in `dom-utilities.js:121` but confirm it's being used on any scroll/resize handlers in `our-history.js` and `critical/navigation.js`
- If not, wire it up — it's already there

Outcome:
- Smoother performance on complex pages

---

## 10. Documentation Gaps

Issue:
- Some areas lack inline explanation or usage notes

Impact:
- Harder for future developers

Fix:
- Add short comments for:
  - complex logic
  - non-obvious decisions

Recommendation:
- No specific gaps identified in this audit — `inc/` files are generally well-commented
- Main area to document proactively: any magic number constants extracted as part of issue 4 above, and any new `data-js` hook conventions adopted from issue 7

Outcome:
- Better maintainability and onboarding

---

## 11. Consistent Hook Usage (WordPress)

Issue:
- Mix of different hook styles/locations

Impact:
- Slight inconsistency

Fix:
- Standardise:
  - enqueue hooks
  - init hooks
  - admin vs frontend separation

Recommendation:
- Several raw `<script>` outputs via `wp_head`/`wp_footer` instead of the enqueue system:
  - `analytics.php` — GA and LinkedIn output as raw HTML (covered in issues-high.md)
  - `integrations.php` — Mailchimp output as raw HTML (covered in issues-high.md)
  - `assets.php:73` — critical JS inlined as raw `<script>` (intentional, documented)
  - `template-functions.php:207` — `insert_video_overlay` outputs raw HTML via `wp_footer` — worth reviewing whether this should use a proper template include instead
- Once analytics and Mailchimp are migrated (issues-high.md), hook usage will be much cleaner

Outcome:
- Cleaner WP integration patterns

---

## 12. Versioning Strategy

Issue:
- Script/style versioning may rely only on theme version

Impact:
- Cache invalidation may be too broad or too narrow

Fix:
- Consider file-based versioning (filemtime) during development

Recommendation:
- Current strategy: `_S_VERSION` for `zotefoams-style` and `zotefoams-bundle`, `null` for CDN assets, hardcoded `'4.1.1'` for Animate.css (fine)
- No `filemtime()` used anywhere — during development, a cache bust requires bumping the theme version number
- Simple improvement: use `filemtime(get_template_directory() . '/js/bundle.js')` as the version for `zotefoams-bundle` in non-production environments:

  $version = defined('WP_DEBUG') && WP_DEBUG
      ? filemtime(get_template_directory() . '/js/bundle.js')
      : _S_VERSION;

Outcome:
- More reliable cache busting

---

## Summary

These are polish-level improvements:
- No breaking issues
- No urgent fixes
- Focus on consistency, clarity, and maintainability

Addressing them gradually will move the theme from "solid" to "high quality".
