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
