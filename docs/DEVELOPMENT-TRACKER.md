# Development Tracker

## Pending Issues (To Fix)

### a11y issues

- [ ] **Knowledge Hub menu drobdown keyboard controls** - Space/Enter should show dropdown (like main menu)
- [ ] **Split Carousel keyboard controls** - Keyboard navigation could be improved for better accessibility
- [ ] **Step Slider keyboard interaction** - Keyboard navigation could be improved for better accessibility
- [ ] **Screen reader ARIA labels** - Test and improve ARIA labels for carousel announcements, button labels, form fields, and dynamic content updates
- [ ] **Utility menu ARIA roles** - Add `role="menuitem"` to top links and `role="menu"` to sub-`ul`s for enhanced screen reader clarity
- [ ] **ARIA live region feedback** - Add hidden `<div aria-live="polite">` that announces menu state changes (open/close) to screen reader users
- [ ] **Reduced motion support** - Disable CSS transitions when user has `prefers-reduced-motion: reduce` media query set for accessibility compliance
- [ ] **Panel Switcher** - Look to improve aria attributes; Ttry to sync active tab across mobile/desktop
- [ ] **Mega Nav** - Visually label external link items

### Other issues

- [ ] **Image Banner autoplay pause** - Hovering on Next button should pause slide autoplay
- [ ] **iPad Pro menu display mismatch** - Desktop view displays on ipad Pro but menu interactions are mobile
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