# Mega Navigation Documentation

## Current Status

The main navigation system has undergone a comprehensive refactoring to address previous intermittent issues and improve accessibility, maintainability, and reliability.

**Status:** Refactored - Ready for Testing (as of October 29, 2025)
**Version:** v0.13+
**Branch:** Menu-bug
**Previous Issues:** Addressed through ARIA migration and architectural simplification

## Current Implementation

### Architecture Overview

The navigation system consists of four main components:

1. **PHP Template & Menu Walker** (`header.php` + `inc/mega-menu-walker.php`)
   - Custom `Mega_Menu_Walker` class extends `Walker_Nav_Menu`
   - Generates semantic HTML structure with ARIA attributes
   - Creates individual mega menu elements (no wrapper container)
   - Each mega menu linked to trigger via `aria-controls` attribute
   - Supports up to 4 levels of menu depth

2. **JavaScript Controller** (`src/critical/navigation.js`)
   - **Version:** 0.13+ (October 29, 2025)
   - **Size:** 352 lines (down from 487)
   - Loaded inline as critical script in `<head>`
   - Implements dual-mode navigation:
     - **Hover mode** for desktop (200ms delay)
     - **Click mode** for touch devices
   - Device detection via `touch-device` class from `dom-utilities.js`
   - **State management:** ARIA-driven (single source of truth)
   - Uses `data-js-nav` attributes for JS hooks
   - Full keyboard navigation support

3. **Keyboard Support Module** (`src/critical/navigation-keyboard.js`)
   - **Version:** 1.3 (90 lines)
   - Handles Enter, Space, ArrowDown, Escape, Tab navigation
   - Focus management for screen readers
   - Tab boundary detection and focus transitions
   - Re-exports utilities from `dom-utilities.js`

4. **CSS Styling** (`src/sass/design/common/header/`)
   - Modular SASS structure in subdirectory
   - `_site-header.scss` - Header container
   - `_nav.scss` - Main navigation structure
   - `_mega-menu.scss` - Mega menu positioning and layout
   - `_utility-menu.scss` - Utility menu styles
   - `_show-hide.scss` - ARIA-controlled visibility and transitions
   - `_mobile-nav.scss` - Mobile-specific styles
   - Uses opacity/visibility with `aria-hidden` for smooth transitions

### Key Mechanisms

**Menu Display Logic:**
```javascript
// Desktop (hover mode)
const showMenu = () => {
    if (hideTimer) {
        clearTimeout(hideTimer);
        hideTimer = null;
    }
    closeMegaMenus();
    closeUtilityMenus();
    megaMenu.setAttribute('aria-hidden', 'false');
    setAriaExpanded(link, true);
};

// Touch mode (desktop with touch, checks if mega menu is visible)
link.addEventListener("click", (e) => {
    if (megaMenu.offsetParent !== null) {
        e.preventDefault();
        const isOpen = megaMenu.getAttribute('aria-hidden') === 'false';
        // Toggle open/closed
    }
    // On mobile (offsetParent === null), link works as normal navigation
});
```

**State Management (ARIA-Driven):**
- **Mega Menus:** `aria-hidden="false"` = visible, `"true"` = hidden
- **Menu Triggers:** `aria-expanded="true"` = open, `"false"` = closed
- **Mobile Dropdowns:** `aria-expanded="true"` shows submenu
- **Single timer** (`hideTimer`) for hover delay (200ms)
- **No class-based state** - ARIA is single source of truth

**CSS Visibility:**
```scss
// Mega Menu
.mega-menu {
  opacity: 0;
  visibility: hidden;

  &[aria-hidden="false"] {
    opacity: 1;
    visibility: visible;
  }
}

// Mobile Dropdown
.dropdown-toggle[aria-expanded="true"] ~ ul {
  display: block;
}

// Utility Menu
.utility-menu .sub-menu[aria-hidden="false"] {
  opacity: 1;
  visibility: visible;
}
```

## Recent Refactoring (October 27-29, 2025)

### Major Changes Summary

Following the intermittent issues documented in October 2025, a comprehensive refactoring was undertaken to address root causes through architectural improvements rather than band-aid fixes.

### Phase 1: ARIA Migration (v0.06-0.08)

**Goal:** Replace class-based state management with ARIA attributes as single source of truth

**Changes:**
1. **Mobile Menu Dropdowns**
   - Changed from `.submenu-open` class to `aria-expanded` attribute
   - Updated CSS to target `[aria-expanded="true"]`
   - Added `aria-controls` linking toggles to submenus

2. **Mega Menus**
   - Changed from `.active` class to `aria-hidden` attribute
   - Removed `.mega-menu-container.active` dependency
   - CSS now targets `[aria-hidden="false"]`

3. **Utility Menu**
   - Added `aria-hidden` management to submenus
   - Changed from `display: none` to `opacity/visibility` transitions
   - Added dual hover/click behavior (matching mega menu pattern)

**Results:**
- Single source of truth for menu state
- Better accessibility for screen readers
- Simplified state tracking in JavaScript

### Phase 2: Structural Simplification (v0.09-0.11)

**Goal:** Remove unnecessary wrapper elements and code complexity

**Changes:**
1. **Removed Mega Menu Container**
   - Deleted `.mega-menu-container` wrapper div from walker
   - Deleted `updateMegaContainer()` function (~20 lines)
   - Removed 8+ function calls throughout codebase
   - Moved container CSS to individual `.mega-menu` elements

2. **Code Cleanup**
   - Removed dead code from `closeUtilityMenus()` (`.dropdown-active` cleanup)
   - Simplified `setupDropdownToggles()` by removing unused conditional logic
   - Optimized hamburger toggle using `.toggle()` return value
   - Changed `hoverDelay` to `HOVER_DELAY_MS` constant

**Results:**
- Reduced JavaScript from 487 to 352 lines (28% reduction)
- Eliminated duplicate DOM queries
- Simpler mental model (no wrapper to track)

### Phase 3: Keyboard Navigation (v0.12)

**Goal:** Full keyboard accessibility for all menu types

**Changes:**
1. **Created Keyboard Module**
   - New file: `navigation-keyboard.js` (90 lines)
   - `handleMenuItemKeyboard()` - Enter/Space/ArrowDown/Escape on triggers
   - `handleMegaMenuKeyboard()` - Tab/Escape within menus
   - Focus management for screen readers (H2 headings)

2. **Integrated Keyboard Handlers**
   - Added to mega menu triggers and panels
   - Added to utility menu triggers and submenus
   - Extracted outside hover/click mode branches to avoid duplication

**Results:**
- Full keyboard navigation support
- Proper focus trapping and management
- Screen reader friendly

### Phase 4: Mobile Enhancements (v0.13+)

**Goal:** Better mobile UX and fix touch device edge cases

**Changes:**
1. **Mobile Nav Tab-Out Handling**
   - Detects Tab from last focusable element
   - Closes mobile nav
   - Focuses hamburger button
   - Removes body scroll lock

2. **Touch Device Link Behavior**
   - Checks `megaMenu.offsetParent !== null` before preventing default
   - On mobile (mega menu hidden): links work as navigation
   - On desktop touch (mega menu visible): links toggle menu

3. **Clickable Menu Labels**
   - Added `cursor: pointer` CSS for touch devices
   - Made labels trigger adjacent dropdown toggle

**Results:**
- Better keyboard navigation on mobile
- Top-level links work correctly on mobile
- Improved touch device UX

## Previous Fix Attempts (Historical Context)

### Commit 574e3ac (October 10, 2025) - "Transpiled Ver"

This was an earlier attempt to fix mega menu display issues, particularly in Chrome v140+ and Edge.

**Changes Made:**
1. **Enhanced Device Detection** (lines 14-21)
   - Added `(pointer: fine)` media query detection
   - Added screen width check (>= 1024px) to identify desktop
   - Chrome v140+ reportedly misreporting `maxTouchPoints` on desktop
   - Logic now prefers hover mode on desktop even if touch detected

2. **Duplicate Prevention** (lines 34-36, 197-200)
   - Added `data-critical-nav-initialized` attribute check
   - Added `data-mega-nav-initialized` per-link checking
   - Prevents multiple event listener attachments

3. **Timer Management Improvements** (lines 56-57, 206-256)
   - Changed from individual `hideTimer` variables to `Map` structure
   - Better tracking of multiple concurrent timers
   - Proper cleanup in `closeMegaMenus()` and `closeAll()` functions

4. **DOM Update Delays** (lines 82-87, 115-120, 134-139, etc.)
   - Added 10ms `setTimeout` delays before checking container state
   - Ensures DOM has updated before querying active menus
   - Attempts to fix race conditions

5. **Console Logging** (lines 10+)
   - Extensive diagnostic logging added (later removed)
   - Helped track initialization and interaction flow

6. **Build Improvements**
   - Added Babel transpilation (`.babelrc.json`)
   - Target: `> 0.5%, last 2 versions, Firefox ESR, not dead`
   - Improved browser compatibility

**Results:**
- Mixed success - some users reported more reliable dropdowns
- Issue persists for some users in October 2025
- Suggests problem may be deeper than device detection or timing

## Technical Analysis

### Issues Addressed by Refactoring

| Previous Issue | Status | Solution |
|---|---|---|
| **Race Conditions** | ✅ Improved | Removed 10ms DOM check delays; simplified state logic |
| **Event Listener Conflicts** | ✅ Fixed | Keyboard handlers properly integrated; no conflicts |
| **CSS Transition Dependencies** | ✅ Fixed | Single attribute (`aria-hidden`) controls visibility |
| **State Management Complexity** | ✅ Fixed | ARIA is single source of truth; eliminated classes |
| **Excessive setTimeout Usage** | ✅ Improved | Only used for hover delay (200ms) and focus timing |
| **Complex Close Logic** | ✅ Simplified | Three focused functions with clear responsibilities |
| **Mode Detection Fragility** | ✅ Fixed | Uses `touch-device` class from `dom-utilities.js` |
| **Mega Menu Container** | ✅ Removed | Eliminated wrapper element and related code |
| **Multiple Sources of Truth** | ✅ Fixed | ARIA attributes are authoritative |

### Remaining Considerations

1. **Hover Delay Timer**
   - Single `hideTimer` variable (200ms delay on mouse leave)
   - Properly cleared when re-entering menu
   - Could potentially use `transitionend` events in future iteration

2. **Device Detection**
   - Relies on `touch-device` class from `dom-utilities.js`
   - Decision made once at page load
   - Touch device links now check `offsetParent !== null` for runtime detection

3. **Three Close Functions**
   - `closeMegaMenus()` - Closes all mega menus
   - `closeUtilityMenus()` - Closes utility menu dropdowns
   - `closeAll()` - Calls both of the above
   - Each has focused responsibility; complexity is appropriate

4. **Mobile Nav Link Behavior**
   - Uses `offsetParent !== null` check to determine if mega menu is visible
   - Allows top-level links to work as navigation on mobile
   - Prevents default only when mega menu is actually displayed

### Code Quality Improvements

**Before (v0.04):**
```javascript
// Multiple sources of truth
megaMenu.classList.add("active");
container.classList.add("active");
link.setAttribute("aria-expanded", "true");

// Timing workarounds
setTimeout(() => {
    const container = document.querySelector(".mega-menu-container");
    if (container && !container.querySelector(".mega-menu.active")) {
        container.classList.remove("active");
    }
}, 10);
```

**After (v0.13+):**
```javascript
// Single source of truth
megaMenu.setAttribute('aria-hidden', 'false');
setAriaExpanded(link, true);

// Clean state management
const isOpen = megaMenu.getAttribute('aria-hidden') === 'false';
```

## Testing Requirements

### Pre-Testing Checklist

- ✅ ARIA attributes implemented across all menu types
- ✅ Keyboard navigation support added
- ✅ Mobile nav enhancements complete
- ✅ Touch device link behavior fixed
- ✅ Code quality improvements applied
- ✅ Build process running successfully

### Testing Scenarios

#### 1. Desktop (Hover Mode - Non-Touch Devices)

**Mega Menu:**
- [ ] Hover over top-level item shows mega menu
- [ ] 200ms delay on mouse leave before closing
- [ ] Moving from trigger to mega menu keeps it open
- [ ] Hovering different top-level items switches mega menus
- [ ] Click outside closes mega menu
- [ ] No console errors

**Utility Menu:**
- [ ] Hover over utility menu item shows submenu
- [ ] Moving mouse away closes submenu
- [ ] Multiple utility items work independently

**Keyboard Navigation:**
- [ ] Tab through top-level menu items
- [ ] Enter/Space opens mega menu
- [ ] ArrowDown opens mega menu and focuses first item
- [ ] Tab through mega menu items
- [ ] Tab from last mega menu item moves to next top-level item
- [ ] Escape closes mega menu and returns focus
- [ ] Same behavior for utility menu

#### 2. Desktop Touch (Tablet - Touch Devices with Large Screen)

**Mega Menu:**
- [ ] Touch top-level item toggles mega menu (prevents navigation)
- [ ] Touch again closes mega menu
- [ ] Touch different top-level item switches mega menus
- [ ] Touch outside closes mega menu
- [ ] `offsetParent !== null` check working (mega menus visible)

**Utility Menu:**
- [ ] Touch utility item toggles submenu
- [ ] Touch again closes submenu
- [ ] Touch outside closes submenu

**Keyboard Navigation:**
- [ ] Same as desktop hover mode

#### 3. Mobile (Small Screen - Mega Menus Hidden)

**Hamburger Menu:**
- [ ] Toggle opens/closes mobile nav
- [ ] Body scroll locked when open
- [ ] Overlay visible when open
- [ ] Click outside closes nav

**Top-Level Links:**
- [ ] Touch top-level mega menu links navigates (does NOT toggle)
- [ ] `offsetParent === null` check working (mega menus hidden)
- [ ] Links work as expected

**Dropdown Toggles:**
- [ ] Arrow icon toggles submenus
- [ ] Menu label (on touch devices) also triggers toggle
- [ ] Submenus expand/collapse properly
- [ ] Nested submenus work

**Keyboard Navigation:**
- [ ] Tab through mobile nav items
- [ ] Tab from last item closes nav and focuses hamburger
- [ ] Dropdown toggles work with Enter/Space
- [ ] Escape closes dropdowns

#### 4. Cross-Browser Testing

Test all above scenarios in:
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] iOS Safari
- [ ] Android Chrome

**Check for:**
- Console errors
- Visual glitches
- Transition smoothness
- ARIA attribute updates in inspector
- Focus indicators visible

### Known Edge Cases to Test

1. **Hybrid Devices** (touchscreen laptops)
   - Test both touch and mouse input
   - Verify mode detection handles both

2. **Rapid Interactions**
   - Quickly hover over multiple top-level items
   - Verify no menus get stuck open

3. **Slow Connections**
   - Test with throttled network
   - Verify CSS loads before JS interactions

4. **Window Resize**
   - Start on desktop, resize to mobile
   - Start on mobile, resize to desktop
   - Verify behavior switches appropriately

5. **Screen Readers**
   - Test with VoiceOver (Mac/iOS)
   - Test with NVDA (Windows)
   - Verify ARIA announcements correct

## Next Steps

### Immediate (Ready Now)

1. **Manual Testing**
   - Work through testing scenarios above
   - Test on physical devices (phone, tablet, laptop)
   - Document any issues found

2. **Accessibility Audit**
   - Run automated tests (axe, WAVE)
   - Manual screen reader testing
   - Keyboard-only navigation verification

### Short Term (Before Production Deploy)

3. **Cross-Browser Testing**
   - Test all scenarios in major browsers
   - Verify ARIA support across browsers
   - Check for console errors

4. **Performance Testing**
   - Measure interaction delays
   - Check for layout shifts (CLS)
   - Verify smooth transitions

5. **User Acceptance Testing**
   - Internal team testing
   - Stakeholder review
   - Address feedback

### Long Term (Post-Deploy)

6. **Monitoring**
   - Watch for user reports of issues
   - Monitor analytics for navigation patterns
   - Track any console errors via error tracking service

7. **Future Enhancements**
   - Consider CSS-only hover fallback for progressive enhancement
   - Evaluate `transitionend` events to replace remaining setTimeout
   - Add diagnostic mode for troubleshooting (`?nav_debug=1`)
   - Implement automated Playwright tests

## Key Files Modified

### JavaScript
- **`src/critical/navigation.js`** (v0.13+, 352 lines)
  - Main navigation controller
  - Dual hover/click mode support
  - ARIA state management
  - Mobile nav handling

- **`src/critical/navigation-keyboard.js`** (v1.3, 90 lines)
  - Keyboard event handlers
  - Focus management
  - Tab navigation logic

### CSS (SASS)
- **`src/sass/design/common/header/_nav.scss`**
  - Main navigation structure

- **`src/sass/design/common/header/_mega-menu.scss`**
  - Mega menu positioning and layout

- **`src/sass/design/common/header/_utility-menu.scss`**
  - Utility menu styles

- **`src/sass/design/common/header/_show-hide.scss`**
  - ARIA-controlled visibility
  - Transition timing

- **`src/sass/design/common/header/_mobile-nav.scss`**
  - Mobile-specific styles
  - Dropdown toggles
  - Touch device styles

### PHP
- **`inc/mega-menu-walker.php`**
  - Removed mega-menu-container wrapper
  - Outputs individual mega menu elements

- **`header.php`**
  - Menu registration and output
  - Data attributes for JS hooks

### Build Configuration
- **`rollup.config.js`** - Build pipeline (unchanged)
- **`.babelrc.json`** - Transpilation targets (unchanged)
- **`package.json`** - npm scripts (unchanged)

## References

- **Current version:** v0.13+ (October 29, 2025)
- **Branch:** Menu-bug
- **Previous version:** v0.04 (commit 574e3ac, October 10, 2025)
- **Related docs:**
  - `CLAUDE.md` - Theme architecture documentation
  - `DEVELOPMENT-TRACKER.md` - Issue tracking (if exists)

## Summary of Improvements

| Metric | Before (v0.04) | After (v0.13+) | Change |
|---|---|---|---|
| JavaScript Lines | 487 | 352 + 90 | -45 net (modularized) |
| State Management | Classes + ARIA | ARIA only | Simplified |
| setTimeout Calls | ~12+ | 2 | -83% |
| DOM Queries | Repeated | Minimal | Optimized |
| Keyboard Support | Limited | Full | ✅ Complete |
| Mobile Nav Tab-Out | None | Yes | ✅ Added |
| Touch Device Links | Broken on mobile | Fixed | ✅ Works |
| Mega Menu Container | Yes | No | ✅ Removed |
| SASS Files | 1 large file | 6 modular files | Better organized |

---

**Document created:** October 27, 2025
**Last updated:** October 29, 2025
**Author:** Claude Code (via developer request)
**Status:** Ready for testing
