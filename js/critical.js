(function () {
    'use strict';

    (() => {
      try {
        console.log('[Navigation v0.04] Script loaded');
        function initNavigation() {
          const hasTouch = "ontouchstart" in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
          const hasPointerFine = window.matchMedia && window.matchMedia("(pointer: fine)").matches;
          const isLikelyDesktop = hasPointerFine && screen.width >= 1024;
          const isTouchDevice = hasTouch && !isLikelyDesktop;
          if (isTouchDevice && document.body) {
            document.body.classList.add("touch-device");
          }
          const siteNav = document.getElementById("site-navigation");
          if (!siteNav) {
            return false;
          }
          if (siteNav.hasAttribute('data-critical-nav-initialized')) {
            return true;
          }
          const button = siteNav.querySelector("button");
          const menu = siteNav.querySelector("ul");
          const utilityMenu = document.querySelector(".utility-menu");
          const megaNavMode = isTouchDevice ? "click" : "hover";
          const hoverDelay = 200;
          if (!button || !menu) {
            if (!menu && button) {
              button.style.display = "none";
            }
            return false;
          }
          menu.classList.add("nav-menu");
          const hideTimers = new Map();
          const getFocusable = container => Array.from(container.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'));
          const closeMegaMenus = () => {
            document.querySelectorAll(".mega-menu.active").forEach(el => el.classList.remove("active"));
            hideTimers.forEach((timer, key) => {
              if (key.startsWith('mega-')) {
                clearTimeout(timer);
                hideTimers.delete(key);
              }
            });
            setTimeout(() => {
              const container = document.querySelector(".mega-menu-container");
              if (container && !container.querySelector(".mega-menu.active")) {
                container.classList.remove("active");
              }
            }, 10);
            document.querySelectorAll("[aria-controls]").forEach(link => {
              const controlId = link.getAttribute("aria-controls");
              const controlledEl = controlId && document.getElementById(controlId);
              if (controlledEl && controlledEl.classList.contains("mega-menu")) {
                link.setAttribute("aria-expanded", "false");
              }
            });
          };
          const closeUtilityMenus = () => {
            document.querySelectorAll(".utility-menu .dropdown-active").forEach(el => el.classList.remove("dropdown-active"));
          };
          const closeAll = () => {
            hideTimers.forEach(timer => clearTimeout(timer));
            hideTimers.clear();
            document.querySelectorAll(".dropdown-active, .mega-menu.active").forEach(el => el.classList.remove("dropdown-active", "active"));
            setTimeout(() => {
              const container = document.querySelector(".mega-menu-container");
              if (container) {
                container.classList.remove("active");
              }
            }, 10);
            document.querySelectorAll("[aria-controls]").forEach(link => link.setAttribute("aria-expanded", "false"));
          };
          const handleMegaKeyNav = e => {
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
              if (e.shiftKey && currentIndex === 0 || !e.shiftKey && currentIndex === focusable.length - 1) {
                e.preventDefault();
                megaMenu.classList.remove("active");
                const topLinks = Array.from(document.querySelectorAll("#menu-primary > li > a"));
                const index = topLinks.indexOf(megaMenu._topLink);
                const targetLink = e.shiftKey ? index > 0 ? topLinks[index - 1] : megaMenu._topLink : index < topLinks.length - 1 ? topLinks[index + 1] : megaMenu._topLink;
                targetLink.focus();
                megaMenu.removeEventListener("keydown", handleMegaKeyNav);
              }
            }
          };
          const setupDropdowns = menuElement => {
            menuElement.querySelectorAll(".dropdown-toggle").forEach(toggle => {
              toggle.addEventListener("click", e => {
                e.stopPropagation();
                e.preventDefault();
                const menuItem = toggle.parentNode;
                menuItem.classList.toggle("dropdown-active");
              });
            });
            menuElement.querySelectorAll(".menu-item-has-children > a, [aria-controls]").forEach(link => {
              const controlId = link.getAttribute("aria-controls");
              if (controlId) {
                const megaMenu = document.getElementById(controlId);
                if (!megaMenu) {
                  return;
                }
                if (link.hasAttribute('data-mega-nav-initialized')) {
                  return;
                }
                link.setAttribute('data-mega-nav-initialized', 'true');
                if (megaNavMode === "hover") {
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
                    document.querySelectorAll(".mega-menu.active").forEach(el => {
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
                  link.addEventListener("click", e => {});
                  link.addEventListener("keydown", e => {
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
                  link.addEventListener("keydown", e => {
                    if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {} else if (e.key === "Escape") {
                      closeAll();
                      link.focus();
                    }
                  });
                }
              } else {
                const isUtilityMenu = link.closest('.utility-menu');
                const hasDropdownToggle = link.parentNode.querySelector('.dropdown-toggle');
                if (isUtilityMenu && !hasDropdownToggle) {
                  if (isTouchDevice) {
                    link.addEventListener("click", e => {
                      const submenu = link.parentNode.querySelector('ul');
                      if (submenu) {
                        e.preventDefault();
                        const menuItem = link.parentNode;
                        menuItem.classList.toggle("dropdown-active");
                      }
                    });
                  }
                }
                link.addEventListener("keydown", e => {
                  if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
                    e.preventDefault();
                    const menuItem = link.parentNode;
                    menuItem.classList.toggle("dropdown-active");
                    if (e.key === "ArrowDown" && menuItem.classList.contains("dropdown-active")) {
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
          button.addEventListener("click", () => {
            siteNav.classList.toggle("toggled");
            const isExpanded = button.getAttribute("aria-expanded") === "true";
            button.setAttribute("aria-expanded", (!isExpanded).toString());
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
          if (isTouchDevice) {
            document.querySelectorAll('.menu-label').forEach(label => {
              const menuItem = label.parentNode;
              const dropdownToggle = menuItem.querySelector('.dropdown-toggle');
              if (dropdownToggle) {
                label.style.cursor = 'pointer';
                label.addEventListener('click', e => {
                  e.preventDefault();
                  e.stopPropagation();
                  dropdownToggle.click();
                });
              }
            });
          }
          document.addEventListener("click", e => {
            if (!siteNav.contains(e.target) && !(utilityMenu && utilityMenu.contains(e.target))) {
              closeAll();
              siteNav.classList.remove("toggled");
              button.setAttribute("aria-expanded", "false");
              document.body.classList.remove("no-scroll");
            }
          });
          document.querySelectorAll(".menu-item-has-children > a").forEach(link => {
            if (!link.getAttribute("aria-controls")) {
              const hasDropdownToggle = link.parentNode.querySelector('.dropdown-toggle');
              if (!hasDropdownToggle) {
                link.addEventListener("keydown", e => {
                  const menuItem = link.parentNode;
                  if ((e.key === "Enter" || e.key === " " || e.key === "ArrowDown") && (menuItem.classList.contains("menu-item-has-children") || menuItem.querySelector("ul"))) {
                    e.preventDefault();
                    menuItem.classList.toggle("dropdown-active");
                    if (e.key === "ArrowDown" && menuItem.classList.contains("dropdown-active")) {
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
          siteNav.setAttribute('data-critical-nav-initialized', 'true');
          return true;
        }
        if (!initNavigation()) {
          let attempts = 0;
          const maxAttempts = 50;
          const pollForNav = setInterval(function () {
            attempts++;
            if (initNavigation() || attempts >= maxAttempts) {
              clearInterval(pollForNav);
            }
          }, 100);
        }
      } catch (error) {
        console.error('[Navigation] ✗ FATAL ERROR:', error);
        console.error('[Navigation] Error details:', error.message, error.stack);
      }
    })();

})();
//# sourceMappingURL=critical.js.map
