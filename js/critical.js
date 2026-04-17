(function () {
	'use strict';

	// =============================================================================
	// Zotefoams DOM Utilities - Core DOM manipulation utilities (ES Module)
	// =============================================================================


	/**
	 * CSS Class Utilities - Centralized class manipulation patterns
	 */
	const ZotefoamsClassUtils = {
	  /**
	   * Toggle class on element safely
	   * @param element
	   * @param className
	   * @param force
	   */
	  toggle(element, className, force = undefined) {
	    if (!element) {
	      return false;
	    }
	    return element.classList.toggle(className, force);
	  },
	  /**
	   * Add class to element safely
	   * @param          element
	   * @param {...any} classNames
	   */
	  add(element, ...classNames) {
	    if (!element) {
	      return;
	    }
	    element.classList.add(...classNames);
	  },
	  /**
	   * Remove class from element safely
	   * @param          element
	   * @param {...any} classNames
	   */
	  remove(element, ...classNames) {
	    if (!element) {
	      return;
	    }
	    element.classList.remove(...classNames);
	  },
	  /**
	   * Toggle class on multiple elements
	   * @param elements
	   * @param className
	   * @param force
	   */
	  toggleAll(elements, className, force = undefined) {
	    return elements.map(element => this.toggle(element, className, force));
	  },
	  /**
	   * Add class to multiple elements
	   * @param          elements
	   * @param {...any} classNames
	   */
	  addAll(elements, ...classNames) {
	    elements.forEach(element => this.add(element, ...classNames));
	  },
	  /**
	   * Remove class from multiple elements
	   * @param          elements
	   * @param {...any} classNames
	   */
	  removeAll(elements, ...classNames) {
	    elements.forEach(element => this.remove(element, ...classNames));
	  },
	  /**
	   * Replace class on element
	   * @param element
	   * @param oldClass
	   * @param newClass
	   */
	  replace(element, oldClass, newClass) {
	    if (!element) {
	      return;
	    }
	    this.remove(element, oldClass);
	    this.add(element, newClass);
	  }
	};

	/**
	 * Touch/Device Detection Utilities
	 */
	const ZotefoamsDeviceUtils = {
	  /**
	   * Detect touch device
	   */
	  isTouchDevice() {
	    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
	  },
	  /**
	   * Get interaction event based on device
	   */
	  getInteractionEvent() {
	    return this.isTouchDevice() ? 'click' : 'hover';
	  },
	  /**
	   * Apply touch-specific classes
	   */
	  initTouchSupport() {
	    if (this.isTouchDevice()) {
	      ZotefoamsClassUtils.add(document.body, 'touch-device');
	    } else {
	      ZotefoamsClassUtils.add(document.body, 'no-touch-device');
	    }
	  }
	};

	/**
	 * Accessibility Utilities - Keyboard navigation and ARIA support
	 */
	const ZotefoamsAccessibilityUtils = {
	  /**
	   * Get all focusable elements within a container
	   * @param {HTMLElement} container - Container element to search within
	   * @return {Array<HTMLElement>} Array of focusable elements
	   */
	  getFocusableElements(container) {
	    if (!container) {
	      return [];
	    }
	    return Array.from(container.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'));
	  },
	  /**
	   * Set aria-expanded attribute
	   * @param {HTMLElement} element  - Element to update
	   * @param {boolean}     expanded - Expanded state
	   */
	  setAriaExpanded(element, expanded) {
	    if (element) {
	      element.setAttribute('aria-expanded', expanded.toString());
	    }
	  }};

	/**
	 * Navigation Keyboard Handlers - Accessibility support for keyboard navigation
	 * @version 1.3 - Removed updateMegaContainer (container deleted)
	 */


	// Re-export utilities for convenience
	const {
	  getFocusableElements,
	  setAriaExpanded
	} = ZotefoamsAccessibilityUtils;

	/**
	 * Handle keyboard navigation within mega menus (Tab, Escape)
	 * @param {KeyboardEvent} e           - Keyboard event
	 * @param {HTMLElement}   megaMenu    - Mega menu element
	 * @param {HTMLElement}   triggerLink - Link that triggers the menu
	 */
	const handleMegaMenuKeyboard = (e, megaMenu, triggerLink) => {
	  if (e.key === 'Escape') {
	    e.preventDefault();
	    megaMenu.setAttribute('aria-hidden', 'true');
	    setAriaExpanded(triggerLink, false);
	    triggerLink.focus();
	    return;
	  }
	  if (e.key === 'Tab') {
	    const focusable = getFocusableElements(megaMenu);
	    const currentIndex = focusable.indexOf(document.activeElement);
	    const isFirst = currentIndex === 0;
	    const isLast = currentIndex === focusable.length - 1;

	    // Tab out of menu when reaching boundaries
	    if (e.shiftKey && isFirst || !e.shiftKey && isLast) {
	      e.preventDefault();
	      megaMenu.setAttribute('aria-hidden', 'true');
	      setAriaExpanded(triggerLink, false);

	      // Focus next/previous top-level link
	      const topLinks = Array.from(document.querySelectorAll("[data-js-nav='menu'] > li > a"));
	      const index = topLinks.indexOf(triggerLink);
	      let targetLink;
	      if (e.shiftKey) {
	        targetLink = index > 0 ? topLinks[index - 1] : triggerLink;
	      } else {
	        targetLink = index < topLinks.length - 1 ? topLinks[index + 1] : triggerLink;
	      }
	      targetLink.focus();
	    }
	  }
	};

	/**
	 * Handle keyboard activation for menu items (Enter, Space, ArrowDown)
	 * @param {KeyboardEvent} e        - Keyboard event
	 * @param {HTMLElement}   link     - Menu item link
	 * @param {HTMLElement}   megaMenu - Mega menu element
	 * @param {Function}      closeAll - Callback to close all menus
	 */
	const handleMenuItemKeyboard = (e, link, megaMenu, closeAll) => {
	  if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
	    e.preventDefault();
	    const isOpen = megaMenu.getAttribute('aria-hidden') === 'false';
	    if (isOpen) {
	      // Close if already open
	      if (e.key === 'ArrowDown') {
	        // Move focus into menu
	        const firstFocusable = getFocusableElements(megaMenu)[0];
	        if (firstFocusable) {
	          firstFocusable.focus();
	        }
	        return;
	      }
	      megaMenu.setAttribute('aria-hidden', 'true');
	      setAriaExpanded(link, false);
	    } else {
	      // Open menu
	      closeAll();
	      megaMenu.setAttribute('aria-hidden', 'false');
	      setAriaExpanded(link, true);

	      // Focus heading for screen readers
	      const heading = megaMenu.querySelector('.mega-menu-intro > h2');
	      if (heading) {
	        heading.setAttribute('tabindex', '-1');
	        setTimeout(() => heading.focus(), 200);
	      }
	    }
	  } else if (e.key === 'Escape') {
	    closeAll();
	    link.focus();
	  }
	};

	/**
	 * Navigation.js - Simplified navigation with accessibility support
	 * @version 0.13 - Mobile nav closes on Tab from last item
	 */

	(() => {
	  try {
	    // CRITICAL: Initialize touch detection FIRST, before any navigation logic
	    // This must run before initNavigation() checks for touch-device class
	    if (document.body) {
	      ZotefoamsDeviceUtils.initTouchSupport();
	    }
	    function initNavigation() {
	      const siteNav = document.querySelector("[data-js-nav='main']");
	      if (!siteNav || siteNav.hasAttribute('data-critical-nav-initialized')) {
	        return false;
	      }
	      const button = document.querySelector("[data-js-nav='toggle']");
	      const menu = document.querySelector("[data-js-nav='menu']");
	      const utilityMenu = document.querySelector("[data-js-nav='utility']");
	      if (!button || !menu) {
	        if (!menu && button) {
	          button.style.display = 'none';
	        }
	        return false;
	      }

	      // Check if touch-device class exists (added by dom-utilities.js)
	      const isTouchDevice = document.body.classList.contains('touch-device');
	      const megaNavMode = isTouchDevice ? 'click' : 'hover';
	      const HOVER_DELAY_MS = 200;

	      // Prevent accidental hover activation on page load (when mouse is already over nav)

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
	        document.querySelectorAll(".mega-menu[aria-hidden='false']").forEach(openMenu => {
	          openMenu.setAttribute('aria-hidden', 'true');
	          const menuId = openMenu.getAttribute('id');
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
	        document.querySelectorAll("[data-js-nav='utility'] a[aria-expanded='true']").forEach(link => {
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
	      button.addEventListener('click', () => {
	        const isExpanded = siteNav.classList.toggle('toggled');
	        setAriaExpanded(button, isExpanded);
	        document.body.classList.toggle('no-scroll', isExpanded);
	      });

	      // Mobile nav keyboard handling - close on Tab from last item
	      siteNav.addEventListener('keydown', e => {
	        if (e.key === 'Tab' && !e.shiftKey) {
	          // Only when nav is open
	          if (siteNav.classList.contains('toggled')) {
	            const focusable = getFocusableElements(siteNav);
	            const lastFocusable = focusable[focusable.length - 1];

	            // If we're on the last focusable element
	            if (document.activeElement === lastFocusable) {
	              e.preventDefault();

	              // Close nav
	              siteNav.classList.remove('toggled');
	              setAriaExpanded(button, false);
	              document.body.classList.remove('no-scroll');

	              // Focus hamburger button after DOM updates
	              setTimeout(() => button.focus(), 0);
	            }
	          }
	        }
	      });

	      // =========================================================================
	      // DROPDOWN TOGGLE BUTTONS (Mobile sub-menus)
	      // =========================================================================
	      const setupDropdownToggles = container => {
	        container.querySelectorAll('.dropdown-toggle').forEach((toggle, index) => {
	          const submenu = toggle.parentNode.querySelector(':scope > ul');
	          if (submenu) {
	            const submenuId = `mobile-submenu-${index}`;
	            submenu.setAttribute('id', submenuId);
	            toggle.setAttribute('aria-controls', submenuId);

	            // Check if parent has .default-expanded class
	            const parentLi = toggle.closest('li');
	            const isDefaultExpanded = parentLi && parentLi.classList.contains('default-expanded');

	            // Set initial aria-expanded state
	            toggle.setAttribute('aria-expanded', isDefaultExpanded ? 'true' : 'false');
	            toggle.addEventListener('click', e => {
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
	      const setupMegaMenu = link => {
	        const controlId = link.getAttribute('aria-controls');
	        const megaMenu = document.getElementById(controlId);
	        if (!megaMenu || link.hasAttribute('data-mega-nav-initialized')) {
	          return;
	        }
	        link.setAttribute('data-mega-nav-initialized', 'true');
	        megaMenu.setAttribute('aria-hidden', 'true');
	        const menuItem = link.parentNode;
	        if (megaNavMode === 'hover') {
	          // DESKTOP: Hover mode
	          let localHideTimer = null;
	          const showMenu = () => {
	            // Cancel this menu's hide timer
	            if (localHideTimer) {
	              clearTimeout(localHideTimer);
	              localHideTimer = null;
	            }

	            // Close any other open mega menus
	            closeMegaMenus();
	            closeUtilityMenus();
	            megaMenu.setAttribute('aria-hidden', 'false');
	            setAriaExpanded(link, true);
	          };
	          const hideMenu = () => {
	            // Delay hiding just this menu
	            localHideTimer = setTimeout(() => {
	              megaMenu.setAttribute('aria-hidden', 'true');
	              setAriaExpanded(link, false);
	              localHideTimer = null;
	            }, HOVER_DELAY_MS);
	          };
	          const cancelHide = () => {
	            if (localHideTimer) {
	              clearTimeout(localHideTimer);
	              localHideTimer = null;
	            }
	          };

	          // Event wiring
	          menuItem.addEventListener('mouseenter', showMenu);
	          menuItem.addEventListener('mouseleave', hideMenu);
	          megaMenu.addEventListener('mouseenter', cancelHide);
	          megaMenu.addEventListener('mouseleave', hideMenu);
	        } else {
	          // TOUCH: Click mode - toggle mega menu open/closed (desktop only)
	          link.addEventListener('click', e => {
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
	        link.addEventListener('keydown', e => {
	          handleMenuItemKeyboard(e, link, megaMenu, closeAll);
	        });
	        megaMenu.addEventListener('keydown', e => {
	          handleMegaMenuKeyboard(e, megaMenu, link);
	        });
	      };

	      // =========================================================================
	      // UTILITY MENU
	      // =========================================================================
	      const setupUtilityMenu = () => {
	        if (!utilityMenu) {
	          return;
	        }
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
	            if (megaNavMode === 'hover') {
	              const showSubmenu = () => {
	                closeUtilityMenus();
	                setAriaExpanded(link, true);
	                submenu.setAttribute('aria-hidden', 'false');
	              };
	              const hideSubmenu = () => {
	                setAriaExpanded(link, false);
	                submenu.setAttribute('aria-hidden', 'true');
	              };
	              menuItem.addEventListener('mouseenter', showSubmenu);
	              menuItem.addEventListener('mouseleave', hideSubmenu);
	            } else {
	              link.addEventListener('click', e => {
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
	            link.addEventListener('keydown', e => {
	              handleMenuItemKeyboard(e, link, submenu, closeUtilityMenus);
	            });
	            submenu.addEventListener('keydown', e => {
	              handleMegaMenuKeyboard(e, submenu, link);
	            });
	          }
	        });
	      };

	      // =========================================================================
	      // INITIALIZE ALL MENUS
	      // =========================================================================

	      // Setup mega menus (only within main navigation)
	      menu.querySelectorAll('[aria-controls]').forEach(setupMegaMenu);

	      // Setup dropdown toggles for mobile navigation
	      setupDropdownToggles(siteNav);

	      // Setup utility menu (hover for desktop, click for touch)
	      setupUtilityMenu();

	      // Make menu labels clickable
	      // if (isTouchDevice) {
	      document.querySelectorAll('.menu-label').forEach(label => {
	        const dropdownToggle = label.nextElementSibling;
	        if (dropdownToggle && dropdownToggle.classList.contains('dropdown-toggle')) {
	          label.addEventListener('click', e => {
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

	      document.addEventListener('click', e => {
	        const clickedInsideNav = siteNav.contains(e.target);
	        const clickedInsideUtility = utilityMenu && utilityMenu.contains(e.target);
	        if (!clickedInsideNav && !clickedInsideUtility) {
	          closeAll();
	          siteNav.classList.remove('toggled');
	          setAriaExpanded(button, false);
	          document.body.classList.remove('no-scroll');
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
	    // eslint-disable-next-line no-console
	    console.error('[Navigation] Error:', error);
	  }
	})();

})();
//# sourceMappingURL=critical.js.map
