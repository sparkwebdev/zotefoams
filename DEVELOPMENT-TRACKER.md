# Development Tracker

## Pending Issues (To Fix)

- [ ] **Image Banner autoplay pause** - Hovering on Next button should pause slide autoplay
- [ ] **Split Carousel keyboard controls** - Keyboard navigation could be improved for better accessibility
- [ ] **Accordion/Show-Hide plus/minus icons** - Plus/minus icons display incorrectly when using keyboard navigation
- [ ] **Step Slider keyboard interaction** - Keyboard navigation could be improved for better accessibility
- [ ] **File list filter visibility** - Filter dropdown shouldn't show when there's only one category
- [ ] **Screen reader ARIA labels** - Test and improve ARIA labels for carousel announcements, button labels, form fields, and dynamic content updates
- [ ] **data-clickable-url function** - Should respect shift-click for opening in new tab
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

## Change History

### Interactive Image Component Updates
- Added light/dark theme toggle support with ACF field `interactive_image_light_theme`
- Implemented smart popup positioning for edge cases (30%/70% thresholds)
- Enhanced numbered markers with white border and improved drop shadow
- Added automatic bold formatting for first line when content contains `<br>` tags
- Improved responsive font sizing with rem units for mobile displays
- Fixed popup width and positioning for better content display

### Admin and Configuration Fixes
- Fixed LinkedIn Analytics and Mailchimp scripts registration
- Restored original ACF field population for associated_brands (pages hierarchy, not taxonomy)
- Fixed Google Analytics field name consistency (google_analytics_measurement_id)
- Added search rewrite rules for /search/ URL handling
- Restored original image sizes configuration (small, thumbnail-product)
- Fixed navigation menu label formatting
- Removed unused admin column styles for Knowledge Hub

### Comprehensive Optimization and Modernization
- Modern build system: Rollup + PostCSS pipeline with Autoprefixer and cssnano
- Package.json streamlined: Complex scripts → 4 essential commands (`start`, `test`, `test:all`, `lint`)
- JavaScript optimization: ES modules, 75% bundle size reduction (55KB → 13.5KB)
- Safe code refactoring: Variable alignment, comment standardization, consistent escaping
- News feed improvements: CSS Grid spacer elimination, title logic consolidation
- Image optimization: Large → medium images where appropriate
- Component code quality: Consistent patterns across all 31 components
- Zero visual regressions: All changes verified with 100% VRC test success rates

### Functions.php Modularization
- Extracted 715-line functions.php into 9 logical modules for better organization
- Created modular structure: theme-setup.php, assets.php, acf-config.php, analytics.php, etc.
- Functions.php reduced from 715 lines to 70 lines (90% reduction)
- Improved code maintainability and follows modern WordPress development practices
- All existing functionality preserved with zero breaking changes

### Component Migration Project
- All 31 components migrated with safe helper functions and explicit CSS classes
- Comprehensive VRC testing shows perfect results (177/177 tests passed)
- All carousel components fully functional
- All standard components using consistent patterns for better maintainability
- Helper classes now actively used across components
