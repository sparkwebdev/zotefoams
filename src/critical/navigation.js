/**
 * Navigation.js - Simplified navigation with accessibility support
 * @version 0.13 - Mobile nav closes on Tab from last item
 */

import {
    setAriaExpanded,
    handleMegaMenuKeyboard,
    handleMenuItemKeyboard,
    getFocusableElements
} from './navigation-keyboard.js';

import { ZotefoamsDeviceUtils } from '../utils/dom-utilities.js';

(() => {
    try {
        // CRITICAL: Initialize touch detection FIRST, before any navigation logic
        // This must run before initNavigation() checks for touch-device class
        if (document.body) {
            ZotefoamsDeviceUtils.initTouchSupport();
        }

        console.log('[Navigation v1.0.0] Script loaded');

        function initNavigation() {
            const siteNav = document.querySelector("[data-js-nav='main']");
            if (!siteNav || siteNav.hasAttribute('data-critical-nav-initialized')) {
                return false;
            }

            const button = document.querySelector("[data-js-nav='toggle']");
            const menu = document.querySelector("[data-js-nav='menu']");
            const utilityMenu = document.querySelector("[data-js-nav='utility']");

            if (!button || !menu) {
                if (!menu && button) button.style.display = "none";
                return false;
            }

            // Check if touch-device class exists (added by dom-utilities.js)
            const isTouchDevice = document.body.classList.contains("touch-device");
            const megaNavMode = isTouchDevice ? "click" : "hover";
            const HOVER_DELAY_MS = 200;

            // Prevent accidental hover activation on page load (when mouse is already over nav)
            let allowHover = false;
            window.addEventListener("mousemove", () => { allowHover = true; }, { once: true });

            // Single timer for hide delays
            let hideTimer = null;

            // =========================================================================
            // UTILITY FUNCTIONS
            // =========================================================================
            /**
             * Close all mega menus
             */
            const closeMegaMenus = () => {
                if (hideTimer) {
                    clearTimeout(hideTimer);
                    hideTimer = null;
                }

                document.querySelectorAll(".mega-menu[aria-hidden='false']")
                    .forEach(menu => {
                        menu.setAttribute('aria-hidden', 'true');

                        const menuId = menu.getAttribute('id');
                        const triggerLink = document.querySelector(`[aria-controls="${menuId}"]`);
                        if (triggerLink) {
                            setAriaExpanded(triggerLink, false);
                        }
                    });
            };

            /**
             * Close all utility menu dropdowns
             */
            const closeUtilityMenus = () => {
                document.querySelectorAll("[data-js-nav='utility'] a[aria-expanded='true']")
                    .forEach(link => {
                        setAriaExpanded(link, false);
                        const submenuId = link.getAttribute('aria-controls');
                        const submenu = document.getElementById(submenuId);
                        if (submenu) {
                            submenu.setAttribute('aria-hidden', 'true');
                        }
                    });
            };

            /**
             * Close all menus
             */
            const closeAll = () => {
                if (hideTimer) {
                    clearTimeout(hideTimer);
                    hideTimer = null;
                }
                closeMegaMenus();
                closeUtilityMenus();
            };

            // =========================================================================
            // HAMBURGER MENU TOGGLE
            // =========================================================================
            button.addEventListener("click", () => {
                const isExpanded = siteNav.classList.toggle("toggled");
                setAriaExpanded(button, isExpanded);
                document.body.classList.toggle("no-scroll", isExpanded);
            });

            // Mobile nav keyboard handling - close on Tab from last item
            siteNav.addEventListener("keydown", (e) => {
                if (e.key === "Tab" && !e.shiftKey) {
                    // Only when nav is open
                    if (siteNav.classList.contains("toggled")) {
                        const focusable = getFocusableElements(siteNav);
                        const lastFocusable = focusable[focusable.length - 1];

                        // If we're on the last focusable element
                        if (document.activeElement === lastFocusable) {
                            e.preventDefault();

                            // Close nav
                            siteNav.classList.remove("toggled");
                            setAriaExpanded(button, false);
                            document.body.classList.remove("no-scroll");

                            // Focus hamburger button after DOM updates
                            setTimeout(() => button.focus(), 0);
                        }
                    }
                }
            });

            // =========================================================================
            // DROPDOWN TOGGLE BUTTONS (Mobile sub-menus)
            // =========================================================================
            const setupDropdownToggles = (container) => {
                container.querySelectorAll(".dropdown-toggle").forEach((toggle, index) => {
                    const submenu = toggle.parentNode.querySelector(':scope > ul');

                    if (submenu) {
                        const submenuId = `mobile-submenu-${index}`;
                        submenu.setAttribute('id', submenuId);
                        toggle.setAttribute('aria-controls', submenuId);
                        toggle.setAttribute('aria-expanded', 'false');

                        toggle.addEventListener("click", (e) => {
                            e.stopPropagation();
                            e.preventDefault();

                            const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                            setAriaExpanded(toggle, !isExpanded);
                        });
                    }
                });
            };

            // =========================================================================
            // MEGA MENU SETUP
            // =========================================================================
            const setupMegaMenu = (link) => {
                const controlId = link.getAttribute("aria-controls");
                const megaMenu = document.getElementById(controlId);

                if (!megaMenu || link.hasAttribute('data-mega-nav-initialized')) {
                    return;
                }
                link.setAttribute('data-mega-nav-initialized', 'true');

                megaMenu.setAttribute('aria-hidden', 'true');

                const menuItem = link.parentNode;

                if (megaNavMode === "hover") {
                    // DESKTOP: Hover mode
                    const showMenu = () => {
                        if (!allowHover) return;

                        if (hideTimer) {
                            clearTimeout(hideTimer);
                            hideTimer = null;
                        }

                        closeMegaMenus();
                        closeUtilityMenus();

                        megaMenu.setAttribute('aria-hidden', 'false');
                        setAriaExpanded(link, true);
                    };

                    const hideMenu = () => {
                        hideTimer = setTimeout(() => {
                            megaMenu.setAttribute('aria-hidden', 'true');
                            setAriaExpanded(link, false);
                            hideTimer = null;
                        }, HOVER_DELAY_MS);
                    };

                    const clearTimer = () => {
                        if (hideTimer) {
                            clearTimeout(hideTimer);
                            hideTimer = null;
                        }
                    };

                    menuItem.addEventListener("mouseenter", showMenu);
                    menuItem.addEventListener("mouseleave", hideMenu);
                    megaMenu.addEventListener("mouseenter", clearTimer);
                    megaMenu.addEventListener("mouseleave", hideMenu);

                } else {
                    // TOUCH: Click mode - toggle mega menu open/closed (desktop only)
                    link.addEventListener("click", (e) => {
                        // Only prevent default if mega menu is visible (not hidden on mobile)
                        if (megaMenu.offsetParent !== null) {
                            e.preventDefault();
                            const isOpen = megaMenu.getAttribute('aria-hidden') === 'false';

                            if (isOpen) {
                                megaMenu.setAttribute('aria-hidden', 'true');
                                setAriaExpanded(link, false);
                            } else {
                                closeAll();
                                megaMenu.setAttribute('aria-hidden', 'false');
                                setAriaExpanded(link, true);
                            }
                        }
                        // On mobile (megaMenu.offsetParent === null), link works as normal navigation
                    });
                }

                // Keyboard support (works for both hover and touch modes)
                link.addEventListener("keydown", (e) => {
                    handleMenuItemKeyboard(e, link, megaMenu, closeAll);
                });

                megaMenu.addEventListener("keydown", (e) => {
                    handleMegaMenuKeyboard(e, megaMenu, link);
                });
            };

            // =========================================================================
            // UTILITY MENU
            // =========================================================================
            const setupUtilityMenu = () => {
                if (!utilityMenu) return;

                const topLevelItems = utilityMenu.querySelectorAll(':scope > ul > li');

                topLevelItems.forEach((menuItem, index) => {
                    const link = menuItem.querySelector(':scope > a');
                    const submenu = menuItem.querySelector(':scope > ul');

                    if (link && submenu) {
                        const submenuId = `utility-submenu-${index}`;
                        submenu.setAttribute('id', submenuId);
                        submenu.setAttribute('aria-hidden', 'true');
                        link.setAttribute('aria-controls', submenuId);
                        link.setAttribute('aria-expanded', 'false');

                        if (megaNavMode === "hover") {
                            const showSubmenu = () => {
                                closeUtilityMenus();
                                setAriaExpanded(link, true);
                                submenu.setAttribute('aria-hidden', 'false');
                            };

                            const hideSubmenu = () => {
                                setAriaExpanded(link, false);
                                submenu.setAttribute('aria-hidden', 'true');
                            };

                            menuItem.addEventListener("mouseenter", showSubmenu);
                            menuItem.addEventListener("mouseleave", hideSubmenu);

                        } else {
                            link.addEventListener("click", (e) => {
                                e.preventDefault();
                                e.stopPropagation();

                                const isOpen = link.getAttribute('aria-expanded') === 'true';

                                if (isOpen) {
                                    // Close this menu
                                    setAriaExpanded(link, false);
                                    submenu.setAttribute('aria-hidden', 'true');
                                } else {
                                    // Close all utility menus, then open this one
                                    closeUtilityMenus();
                                    setAriaExpanded(link, true);
                                    submenu.setAttribute('aria-hidden', 'false');
                                }
                            });
                        }

                        // Keyboard support (works for both hover and touch modes)
                        link.addEventListener("keydown", (e) => {
                            handleMenuItemKeyboard(e, link, submenu, closeUtilityMenus);
                        });

                        submenu.addEventListener("keydown", (e) => {
                            handleMegaMenuKeyboard(e, submenu, link);
                        });
                    }
                });
            };

            // =========================================================================
            // INITIALIZE ALL MENUS
            // =========================================================================

            // Setup mega menus (only within main navigation)
            menu.querySelectorAll("[aria-controls]").forEach(setupMegaMenu);

            // Setup dropdown toggles for mobile navigation
            setupDropdownToggles(siteNav);

            // Setup utility menu (hover for desktop, click for touch)
            setupUtilityMenu();

            // =========================================================================
            // DESKTOP SAFETY: Ensure hover menus close properly when leaving navigation
            // =========================================================================
            if (megaNavMode === "hover") {
                // When the mouse leaves the entire navigation area
                siteNav.addEventListener("mouseleave", () => {
                    closeAll();
                });

                // Also close if user switches tabs or windows mid-hover
                window.addEventListener("blur", closeAll);
            }


            // Make menu labels clickable
            // if (isTouchDevice) {
                document.querySelectorAll('.menu-label').forEach(label => {
                    const dropdownToggle = label.nextElementSibling;
                    if (dropdownToggle && dropdownToggle.classList.contains('dropdown-toggle')) {
                        label.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            dropdownToggle.click();
                        });
                    }
                });
            // }

            // =========================================================================
            // GLOBAL CLICK OUTSIDE TO CLOSE
            // =========================================================================

            document.addEventListener("click", (e) => {
                const clickedInsideNav = siteNav.contains(e.target);
                const clickedInsideUtility = utilityMenu && utilityMenu.contains(e.target);

                if (!clickedInsideNav && !clickedInsideUtility) {
                    closeAll();
                    siteNav.classList.remove("toggled");
                    setAriaExpanded(button, false);
                    document.body.classList.remove("no-scroll");
                }
            });

            // Mark as initialized
            siteNav.setAttribute('data-critical-nav-initialized', 'true');
            return true;
        }

        // Try to initialize immediately (script inlined in footer, after navigation HTML)
        // If navigation doesn't exist yet, wait for DOMContentLoaded as fallback
        const hasNav = document.querySelector("[data-js-nav='main']");

        if (hasNav) {
            // Navigation exists, initialize immediately (before images load)
            initNavigation();
        } else if (document.readyState === 'loading') {
            // Fallback: wait for DOMContentLoaded
            document.addEventListener('DOMContentLoaded', initNavigation);
        } else {
            // DOM ready but nav missing, try init anyway
            initNavigation();
        }

    } catch (error) {
        console.error('[Navigation] Error:', error);
    }
})();
