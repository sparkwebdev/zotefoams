# Development Tracker

## Pending Issues (To Fix)

### a11y issues

- [ ] **[med] Accessibility gaps — `file-list.js` / `section-list.js`** — visibility toggles have no ARIA updates; screen readers unaware of state changes. Also: `accordion.js` opens content but does not move focus to the revealed panel.
- [ ] **Knowledge Hub menu drobdown keyboard controls** - Enter should link Knowledge Hub
- [ ] **Split Carousel keyboard controls** - Keyboard navigation could be improved for better accessibility
- [ ] **Step Slider keyboard interaction** - Keyboard navigation could be improved for better accessibility
- [ ] **Screen reader ARIA labels** - Test and improve ARIA labels for carousel announcements, button labels, form fields, and dynamic content updates
- [ ] **Utility menu ARIA roles** - Add `role="menuitem"` to top links and `role="menu"` to sub-`ul`s for enhanced screen reader clarity
- [ ] **ARIA live region feedback** - Add hidden `<div aria-live="polite">` that announces menu state changes (open/close) to screen reader users
- [ ] **Reduced motion support** - Disable CSS transitions when user has `prefers-reduced-motion: reduce` media query set for accessibility compliance
- [ ] **Panel Switcher** - Look to improve aria attributes; Ttry to sync active tab across mobile/desktop
- [ ] **Mega Nav** - Visually label external link items
- [ ] **Video modal focus trap** — focus lands on close button on open (correct) and returns to trigger on close (correct), but no focus trap exists. Tab from close button escapes the modal. YouTube iframe is cross-origin so focus cannot be moved into player controls programmatically — solution is a focus trap that cycles between close button and iframe only, with the iframe itself requiring a click to activate YouTube keyboard controls.
- [ ] **Semantic HTML containers** - Change component wrapper `<div>` to `<section>` elements

### Other issues
- [ ] **Git publish to sites pipeline** - Auto deploy on PR
- [ ] **[high] Analytics — consolidate, enqueue properly, add consent gating** — see notes below
- [ ] **[high] Mailchimp script loading — replace raw `<script>` output with `wp_enqueue_script()`** — see notes below
- [ ] **Industries page title** - Update page picker to handle Markets/Industries title change
- [ ] **Hardcoded parent ID in dual_carousel.php** - Line 14 has hardcoded parent ID 11 for arrow color - should use page title/slug lookup instead
- [ ] **Video Knowledge Hub** - Update to accept video files instead of just documents
- [ ] **Missing paragraph spacing in components** - Multiple paragraphs in component content areas render without vertical spacing between them (visible across several components)
- [ ] **Image Banner autoplay pause** - Hovering on Next button should pause slide autoplay and stop the progress ring animation
- [ ] **Remove .section-list functionality** - Not used, can be removed from codebase
- [ ] **Button icon inconsistency** - `specs_accordion` default CTA uses an inline SVG chevron; other buttons use CSS `::after` with background SVG image files. Consider standardising — inline SVG is arguably the better approach (uses `currentColor`, no extra file, scales cleanly) so the fix may be to migrate existing `::after` icon buttons to inline SVG rather than the other way around
- [ ] **Our History — tooltip single-open enforcement** — user can click to open a tooltip, then hover to open a second; only one should be open at a time. Fix: close all other visible popups before opening a new one.
- [ ] **[low] Our Locations Map — overlapping points** — map pins overlap in dense areas; consider clustering or offset logic.

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