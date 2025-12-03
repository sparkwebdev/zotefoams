(function () {
	'use strict';

	const ZotefoamsClassUtils = {
	  toggle(element, className, force = undefined) {
	    if (!element) {
	      return false;
	    }
	    return element.classList.toggle(className, force);
	  },
	  add(element, ...classNames) {
	    if (!element) {
	      return;
	    }
	    element.classList.add(...classNames);
	  },
	  remove(element, ...classNames) {
	    if (!element) {
	      return;
	    }
	    element.classList.remove(...classNames);
	  },
	  toggleAll(elements, className, force = undefined) {
	    return elements.map(element => this.toggle(element, className, force));
	  },
	  addAll(elements, ...classNames) {
	    elements.forEach(element => this.add(element, ...classNames));
	  },
	  removeAll(elements, ...classNames) {
	    elements.forEach(element => this.remove(element, ...classNames));
	  },
	  replace(element, oldClass, newClass) {
	    if (!element) {
	      return;
	    }
	    this.remove(element, oldClass);
	    this.add(element, newClass);
	  }
	};
	const ZotefoamsDeviceUtils = {
	  isTouchDevice() {
	    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
	  },
	  getInteractionEvent() {
	    return this.isTouchDevice() ? 'click' : 'hover';
	  },
	  initTouchSupport() {
	    if (this.isTouchDevice()) {
	      ZotefoamsClassUtils.add(document.body, 'touch-device');
	    } else {
	      ZotefoamsClassUtils.add(document.body, 'no-touch-device');
	    }
	  }
	};
	const ZotefoamsAccessibilityUtils = {
	  getFocusableElements(container) {
	    if (!container) {
	      return [];
	    }
	    return Array.from(container.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'));
	  },
	  setAriaExpanded(element, expanded) {
	    if (element) {
	      element.setAttribute('aria-expanded', expanded.toString());
	    }
	  }};

	const {
	  getFocusableElements,
	  setAriaExpanded
	} = ZotefoamsAccessibilityUtils;
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
	    if (e.shiftKey && isFirst || !e.shiftKey && isLast) {
	      e.preventDefault();
	      megaMenu.setAttribute('aria-hidden', 'true');
	      setAriaExpanded(triggerLink, false);
	      const topLinks = Array.from(document.querySelectorAll("[data-js-nav='menu'] > li > a"));
	      const index = topLinks.indexOf(triggerLink);
	      const targetLink = e.shiftKey ? index > 0 ? topLinks[index - 1] : triggerLink : index < topLinks.length - 1 ? topLinks[index + 1] : triggerLink;
	      targetLink.focus();
	    }
	  }
	};
	const handleMenuItemKeyboard = (e, link, megaMenu, closeAll) => {
	  if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
	    e.preventDefault();
	    const isOpen = megaMenu.getAttribute('aria-hidden') === 'false';
	    if (isOpen) {
	      if (e.key === 'ArrowDown') {
	        const firstFocusable = getFocusableElements(megaMenu)[0];
	        if (firstFocusable) {
	          firstFocusable.focus();
	        }
	        return;
	      }
	      megaMenu.setAttribute('aria-hidden', 'true');
	      setAriaExpanded(link, false);
	    } else {
	      closeAll();
	      megaMenu.setAttribute('aria-hidden', 'false');
	      setAriaExpanded(link, true);
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

	(() => {
	  try {
	    if (document.body) {
	      ZotefoamsDeviceUtils.initTouchSupport();
	    }
	    console.log('[Navigation v1.0.1] Script loaded');
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
	      const isTouchDevice = document.body.classList.contains('touch-device');
	      const megaNavMode = isTouchDevice ? 'click' : 'hover';
	      const HOVER_DELAY_MS = 200;
	      let allowHover = false;
	      window.addEventListener('mousemove', () => {
	        allowHover = true;
	      }, {
	        once: true
	      });
	      let hideTimer = null;
	      const closeMegaMenus = () => {
	        if (hideTimer) {
	          clearTimeout(hideTimer);
	          hideTimer = null;
	        }
	        document.querySelectorAll(".mega-menu[aria-hidden='false']").forEach(menu => {
	          menu.setAttribute('aria-hidden', 'true');
	          const menuId = menu.getAttribute('id');
	          const triggerLink = document.querySelector(`[aria-controls="${menuId}"]`);
	          if (triggerLink) {
	            setAriaExpanded(triggerLink, false);
	          }
	        });
	      };
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
	      const closeAll = () => {
	        if (hideTimer) {
	          clearTimeout(hideTimer);
	          hideTimer = null;
	        }
	        closeMegaMenus();
	        closeUtilityMenus();
	      };
	      button.addEventListener('click', () => {
	        const isExpanded = siteNav.classList.toggle('toggled');
	        setAriaExpanded(button, isExpanded);
	        document.body.classList.toggle('no-scroll', isExpanded);
	      });
	      siteNav.addEventListener('keydown', e => {
	        if (e.key === 'Tab' && !e.shiftKey) {
	          if (siteNav.classList.contains('toggled')) {
	            const focusable = getFocusableElements(siteNav);
	            const lastFocusable = focusable[focusable.length - 1];
	            if (document.activeElement === lastFocusable) {
	              e.preventDefault();
	              siteNav.classList.remove('toggled');
	              setAriaExpanded(button, false);
	              document.body.classList.remove('no-scroll');
	              setTimeout(() => button.focus(), 0);
	            }
	          }
	        }
	      });
	      const setupDropdownToggles = container => {
	        container.querySelectorAll('.dropdown-toggle').forEach((toggle, index) => {
	          const submenu = toggle.parentNode.querySelector(':scope > ul');
	          if (submenu) {
	            const submenuId = `mobile-submenu-${index}`;
	            submenu.setAttribute('id', submenuId);
	            toggle.setAttribute('aria-controls', submenuId);
	            const parentLi = toggle.closest('li');
	            const isDefaultExpanded = parentLi && parentLi.classList.contains('default-expanded');
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
	          let localHideTimer = null;
	          const showMenu = () => {
	            if (localHideTimer) {
	              clearTimeout(localHideTimer);
	              localHideTimer = null;
	            }
	            closeMegaMenus();
	            closeUtilityMenus();
	            megaMenu.setAttribute('aria-hidden', 'false');
	            setAriaExpanded(link, true);
	          };
	          const hideMenu = () => {
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
	          menuItem.addEventListener('mouseenter', showMenu);
	          menuItem.addEventListener('mouseleave', hideMenu);
	          megaMenu.addEventListener('mouseenter', cancelHide);
	          megaMenu.addEventListener('mouseleave', hideMenu);
	        } else {
	          link.addEventListener('click', e => {
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
	          });
	        }
	        link.addEventListener('keydown', e => {
	          handleMenuItemKeyboard(e, link, megaMenu, closeAll);
	        });
	        megaMenu.addEventListener('keydown', e => {
	          handleMegaMenuKeyboard(e, megaMenu, link);
	        });
	      };
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
	                  setAriaExpanded(link, false);
	                  submenu.setAttribute('aria-hidden', 'true');
	                } else {
	                  closeUtilityMenus();
	                  setAriaExpanded(link, true);
	                  submenu.setAttribute('aria-hidden', 'false');
	                }
	              });
	            }
	            link.addEventListener('keydown', e => {
	              handleMenuItemKeyboard(e, link, submenu, closeUtilityMenus);
	            });
	            submenu.addEventListener('keydown', e => {
	              handleMegaMenuKeyboard(e, submenu, link);
	            });
	          }
	        });
	      };
	      menu.querySelectorAll('[aria-controls]').forEach(setupMegaMenu);
	      setupDropdownToggles(siteNav);
	      setupUtilityMenu();
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
	      siteNav.setAttribute('data-critical-nav-initialized', 'true');
	      return true;
	    }
	    const hasNav = document.querySelector("[data-js-nav='main']");
	    if (hasNav) {
	      initNavigation();
	    } else if (document.readyState === 'loading') {
	      document.addEventListener('DOMContentLoaded', initNavigation);
	    } else {
	      initNavigation();
	    }
	  } catch (error) {
	    console.error('[Navigation] Error:', error);
	  }
	})();

})();
//# sourceMappingURL=critical.js.map
