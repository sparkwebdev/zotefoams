/**
 * Navigation.js - Handles navigation menu functionality with accessibility support
 * Fixed version addressing mega menu display issues in Chrome v140 and Edge
 * @version 0.01
 */

(() => {
    // Global error handler to catch any script errors
    try {
        console.log('[Navigation v0.03] Script loaded');

    function initNavigation() {

        // Enhanced touch device detection with override check
        // Some browsers (Chrome v140+) incorrectly report maxTouchPoints on desktop
        const hasTouch = "ontouchstart" in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
        const hasPointerFine = window.matchMedia && window.matchMedia("(pointer: fine)").matches;
        const isLikelyDesktop = hasPointerFine && screen.width >= 1024;
        
        // Prefer hover mode on desktop even if touch is detected
        const isTouchDevice = hasTouch && !isLikelyDesktop;

        if (isTouchDevice && document.body) {
            document.body.classList.add("touch-device");
        }

        const siteNav = document.getElementById("site-navigation");

        if (!siteNav) {
            return false;
        }

        // Check if already initialized to prevent duplicate setup
        if (siteNav.hasAttribute('data-critical-nav-initialized')) {
            return true;
        }

        const button = siteNav.querySelector("button");
        const menu = siteNav.querySelector("ul");
        const utilityMenu = document.querySelector(".utility-menu");

        // Use "click" mode on touch devices, "hover" otherwise
        const megaNavMode = isTouchDevice ? "click" : "hover";
        const hoverDelay = 200; // ms

        // Early returns for missing elements
        if (!button || !menu) {
            if (!menu && button) {
                button.style.display = "none";
            }
            return false;
        }

        menu.classList.add("nav-menu");

        // Store hide timers in a Map for better tracking
        const hideTimers = new Map();

        // Helper function: get focusable elements within a container
        const getFocusable = (container) =>
            Array.from(
                container.querySelectorAll(
                    'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
                )
            );

        // Helper functions to close mega menus and utility menus
        const closeMegaMenus = () => {
            document
                .querySelectorAll(".mega-menu.active")
                .forEach((el) => el.classList.remove("active"));
            
            // Clear any pending hide timers
            hideTimers.forEach((timer, key) => {
                if (key.startsWith('mega-')) {
                    clearTimeout(timer);
                    hideTimers.delete(key);
                }
            });
            
            // Use setTimeout to ensure DOM has updated before checking
            setTimeout(() => {
                const container = document.querySelector(".mega-menu-container");
                if (container && !container.querySelector(".mega-menu.active")) {
                    container.classList.remove("active");
                }
            }, 10);
            
            // Reset aria-expanded on links that control mega menus
            document.querySelectorAll("[aria-controls]").forEach((link) => {
                const controlId = link.getAttribute("aria-controls");
                const controlledEl = controlId && document.getElementById(controlId);
                if (controlledEl && controlledEl.classList.contains("mega-menu")) {
                    link.setAttribute("aria-expanded", "false");
                }
            });
        };

        const closeUtilityMenus = () => {
            document
                .querySelectorAll(".utility-menu .dropdown-active")
                .forEach((el) => el.classList.remove("dropdown-active"));
        };

        // Global closeAll function
        const closeAll = () => {
            // Clear all timers
            hideTimers.forEach((timer) => clearTimeout(timer));
            hideTimers.clear();
            
            document
                .querySelectorAll(".dropdown-active, .mega-menu.active")
                .forEach((el) => el.classList.remove("dropdown-active", "active"));
            
            setTimeout(() => {
                const container = document.querySelector(".mega-menu-container");
                if (container) {
                    container.classList.remove("active");
                }
            }, 10);
            
            document
                .querySelectorAll("[aria-controls]")
                .forEach((link) => link.setAttribute("aria-expanded", "false"));
        };

        // Keyboard navigation within mega menus
        const handleMegaKeyNav = (e) => {
            const megaMenu = e.currentTarget;
            if (e.key === "Escape") {
                e.preventDefault();
                megaMenu.classList.remove("active");
                
                setTimeout(() => {
                    const container = document.querySelector(".mega-menu-container");
                    if (container && !container.querySelector(".mega-menu.active")) {
                        container.classList.remove("active");
                    }
                }, 10);
                
                if (megaMenu._topLink) {
                    megaMenu._topLink.focus();
                }
                megaMenu.removeEventListener("keydown", handleMegaKeyNav);
            } else if (e.key === "Tab") {
                const focusable = getFocusable(megaMenu);
                const currentIndex = focusable.indexOf(document.activeElement);
                if (
                    (e.shiftKey && currentIndex === 0) ||
                    (!e.shiftKey && currentIndex === focusable.length - 1)
                ) {
                    e.preventDefault();
                    megaMenu.classList.remove("active");
                    const topLinks = Array.from(
                        document.querySelectorAll("#menu-primary > li > a")
                    );
                    const index = topLinks.indexOf(megaMenu._topLink);
                    const targetLink = e.shiftKey
                        ? index > 0
                            ? topLinks[index - 1]
                            : megaMenu._topLink
                        : index < topLinks.length - 1
                            ? topLinks[index + 1]
                            : megaMenu._topLink;
                    targetLink.focus();
                    megaMenu.removeEventListener("keydown", handleMegaKeyNav);
                }
            }
        };

        // Set up dropdown/mega menu handlers
        const setupDropdowns = (menuElement) => {
            // Attach click event to .dropdown-toggle buttons to toggle submenus.
            menuElement.querySelectorAll(".dropdown-toggle").forEach((toggle) => {
                toggle.addEventListener("click", (e) => {
                    e.stopPropagation();
                    e.preventDefault();
                    const menuItem = toggle.parentNode;
                    menuItem.classList.toggle("dropdown-active");
                });
            });

            // Process anchor links that have children.
            menuElement
                .querySelectorAll(".menu-item-has-children > a, [aria-controls]")
                .forEach((link) => {
                    const controlId = link.getAttribute("aria-controls");
                    
                if (controlId) {
                    // Mega menu branch
                    const megaMenu = document.getElementById(controlId);
                    if (!megaMenu) {
                        return;
                    }

                    // Prevent duplicate initialization
                    if (link.hasAttribute('data-mega-nav-initialized')) {
                        return;
                    }
                    link.setAttribute('data-mega-nav-initialized', 'true');

                    if (megaNavMode === "hover") {
                        // For hover-enabled devices, attach hover and keyboard events.
                        
                        const menuItem = link.parentNode;
                        const timerId = `mega-${controlId}`;
                        
                        const clearTimer = () => {
                            if (hideTimers.has(timerId)) {
                                clearTimeout(hideTimers.get(timerId));
                                hideTimers.delete(timerId);
                            }
                        };

                        const showMenu = () => {
                            clearTimer();
                            
                            // Close other mega menus first
                            document.querySelectorAll(".mega-menu.active").forEach((el) => {
                                if (el !== megaMenu) {
                                    el.classList.remove("active");
                                    const otherLink = document.querySelector(`[aria-controls="${el.id}"]`);
                                    if (otherLink) {
                                        otherLink.setAttribute("aria-expanded", "false");
                                    }
                                }
                            });
                            
                            closeUtilityMenus();
                            
                            megaMenu.classList.add("active");
                            const container = document.querySelector(".mega-menu-container");
                            if (container) {
                                container.classList.add("active");
                            }
                            link.setAttribute("aria-expanded", "true");
                            megaMenu._topLink = link;
                            megaMenu.addEventListener("keydown", handleMegaKeyNav);
                        };

                        const hideMenu = () => {
                            const timer = setTimeout(() => {
                                megaMenu.classList.remove("active");
                                
                                setTimeout(() => {
                                    const container = document.querySelector(".mega-menu-container");
                                    if (container && !container.querySelector(".mega-menu.active")) {
                                        container.classList.remove("active");
                                    }
                                }, 10);
                                
                                link.setAttribute("aria-expanded", "false");
                                megaMenu.removeEventListener("keydown", handleMegaKeyNav);
                                hideTimers.delete(timerId);
                            }, hoverDelay);
                            hideTimers.set(timerId, timer);
                        };

                        menuItem.addEventListener("mouseenter", showMenu);
                        menuItem.addEventListener("mouseleave", hideMenu);
                        megaMenu.addEventListener("mouseenter", clearTimer);
                        megaMenu.addEventListener("mouseleave", hideMenu);

                        // Allow regular clicks to navigate to the link URL
                        link.addEventListener("click", (e) => {
                            // Don't prevent default - let the link navigate
                            // The hover handlers will manage the mega menu display
                        });

                        // Keyboard navigation for mega menus.
                        link.addEventListener("keydown", (e) => {
                            if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
                                e.preventDefault();
                                const isActive = megaMenu.classList.contains("active");
                                if (isActive) {
                                    if (e.key === "ArrowDown") {
                                        const firstFocusable = getFocusable(megaMenu)[0];
                                        if (firstFocusable) {
                                            firstFocusable.focus();
                                        }
                                        return;
                                    }
                                    megaMenu.classList.remove("active");
                                    
                                    setTimeout(() => {
                                        const container = document.querySelector(".mega-menu-container");
                                        if (container && !container.querySelector(".mega-menu.active")) {
                                            container.classList.remove("active");
                                        }
                                    }, 10);
                                    
                                    link.setAttribute("aria-expanded", "false");
                                    megaMenu.removeEventListener("keydown", handleMegaKeyNav);
                                } else {
                                    closeMegaMenus();
                                    closeUtilityMenus();
                                    megaMenu.classList.add("active");
                                    const container = document.querySelector(".mega-menu-container");
                                    if (container) {
                                        container.classList.add("active");
                                    }
                                    link.setAttribute("aria-expanded", "true");
                                    megaMenu._topLink = link;
                                    const heading = megaMenu.querySelector(".mega-menu-intro > h2");
                                    if (heading) {
                                        heading.setAttribute("tabindex", "-1");
                                        setTimeout(() => heading.focus(), 200);
                                    }
                                    megaMenu.addEventListener("keydown", handleMegaKeyNav);
                                }
                            } else if (e.key === "Escape") {
                                closeAll();
                                link.focus();
                            }
                        });
                    } else {
                        // For touch devices in click mode, do not attach a click handler to the link,
                        // so clicking the link will perform its default navigation.
                        link.addEventListener("keydown", (e) => {
                            if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
                                // Optional keyboard toggling behavior can be added here if desired.
                            } else if (e.key === "Escape") {
                                closeAll();
                                link.focus();
                            }
                        });
                    }
                } else {
                    // Regular dropdown (typically utility menus).
                    // For utility menu items: behavior depends on device type
                    const isUtilityMenu = link.closest('.utility-menu');
                    const hasDropdownToggle = link.parentNode.querySelector('.dropdown-toggle');
                    
                    // On touch devices or when there's no dropdown toggle, prevent navigation and toggle submenu
                    // On desktop with dropdown toggle, allow natural navigation
                    if (isUtilityMenu && !hasDropdownToggle) {
                        // Utility menu items without dropdown toggle (like Knowledge Hub)
                        // Need different behavior for touch vs non-touch
                        if (isTouchDevice) {
                            // On mobile/touch: click should toggle submenu
                            link.addEventListener("click", (e) => {
                                const submenu = link.parentNode.querySelector('ul');
                                if (submenu) {
                                    e.preventDefault();
                                    const menuItem = link.parentNode;
                                    menuItem.classList.toggle("dropdown-active");
                                }
                            });
                        }
                        // On desktop: clicks navigate naturally (no preventDefault)
                    }
                    link.addEventListener("keydown", (e) => {
                        if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
                            e.preventDefault();
                            const menuItem = link.parentNode;
                            menuItem.classList.toggle("dropdown-active");
                            if (
                                e.key === "ArrowDown" &&
                                menuItem.classList.contains("dropdown-active")
                            ) {
                                const submenu = menuItem.querySelector("ul");
                                if (submenu) {
                                    const firstLink = submenu.querySelector("a");
                                    if (firstLink) {
                                        firstLink.focus();
                                    }
                                }
                            }
                        } else if (e.key === "Escape") {
                            closeAll();
                            link.focus();
                        }
                    });
                }
            });
        };

        // Set up event listeners for main navigation and utility menu
        button.addEventListener("click", () => {
            siteNav.classList.toggle("toggled");
            const isExpanded = button.getAttribute("aria-expanded") === "true";
            button.setAttribute("aria-expanded", (!isExpanded).toString());
            
            // Toggle no-scroll class on body for mobile menu scrolling
            if (siteNav.classList.contains("toggled")) {
                document.body.classList.add("no-scroll");
            } else {
                document.body.classList.remove("no-scroll");
            }
        });

        setupDropdowns(siteNav);
        if (utilityMenu) {
            setupDropdowns(utilityMenu);
        }

        // On mobile/touch devices, make menu-labels clickable to toggle their submenus
        if (isTouchDevice) {
            document.querySelectorAll('.menu-label').forEach((label) => {
                const menuItem = label.parentNode;
                const dropdownToggle = menuItem.querySelector('.dropdown-toggle');
                if (dropdownToggle) {
                    label.style.cursor = 'pointer';
                    label.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        // Trigger the dropdown toggle button
                        dropdownToggle.click();
                    });
                }
            });
        }

        // Close menus when clicking outside
        document.addEventListener("click", (e) => {
            if (
                !siteNav.contains(e.target) &&
                !(utilityMenu && utilityMenu.contains(e.target))
            ) {
                closeAll();
                siteNav.classList.remove("toggled");
                button.setAttribute("aria-expanded", "false");
                document.body.classList.remove("no-scroll");
            }
        });

        // Handle keyboard for standard links without aria-controls
        document.querySelectorAll(".menu-item-has-children > a").forEach((link) => {
            if (!link.getAttribute("aria-controls")) {
                // Check if this has a dropdown toggle button (mobile menu) - if so, don't interfere
                const hasDropdownToggle = link.parentNode.querySelector('.dropdown-toggle');
                if (!hasDropdownToggle) {
                    link.addEventListener("keydown", (e) => {
                        const menuItem = link.parentNode;
                        if (
                            (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") &&
                            (menuItem.classList.contains("menu-item-has-children") ||
                                menuItem.querySelector("ul"))
                        ) {
                            e.preventDefault();
                            menuItem.classList.toggle("dropdown-active");
                            if (
                                e.key === "ArrowDown" &&
                                menuItem.classList.contains("dropdown-active")
                            ) {
                                const submenu = menuItem.querySelector("ul");
                                if (submenu) {
                                    const firstLink = submenu.querySelector("a");
                                    if (firstLink) {
                                        firstLink.focus();
                                    }
                                }
                            }
                        } else if (e.key === "Escape") {
                            closeAll();
                            link.focus();
                        }
                    });
                }
            }
        });
        
        // Mark navigation as initialized to prevent duplicate setup
        siteNav.setAttribute('data-critical-nav-initialized', 'true');

        return true;
    }

    // Try immediately, then use polling fallback for critical loading
    if (!initNavigation()) {
        let attempts = 0;
        const maxAttempts = 50; // 5 seconds max

        const pollForNav = setInterval(function() {
            attempts++;

            if (initNavigation() || attempts >= maxAttempts) {
                clearInterval(pollForNav);
            }
        }, 100); // Every 100ms
    }

    } catch (error) {
        console.error('[Navigation] ✗ FATAL ERROR:', error);
        console.error('[Navigation] Error details:', error.message, error.stack);
    }
})();