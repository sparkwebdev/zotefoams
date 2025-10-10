(function () {
    'use strict';

    (function () {
      try {
        console.log('[Navigation v0.01] Script loaded - starting initialization');
        console.log('[Navigation] Browser:', navigator.userAgent);
        function initNavigation() {
          console.log('[Navigation] initNavigation() called');
          var hasTouch = "ontouchstart" in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
          var hasPointerFine = window.matchMedia && window.matchMedia("(pointer: fine)").matches;
          var isLikelyDesktop = hasPointerFine && screen.width >= 1024;
          var isTouchDevice = hasTouch && !isLikelyDesktop;
          console.log('[Navigation] Device detection:', {
            hasTouch: hasTouch,
            hasPointerFine: hasPointerFine,
            isLikelyDesktop: isLikelyDesktop,
            finalDecision: isTouchDevice ? 'touch' : 'desktop',
            screenWidth: screen.width,
            maxTouchPoints: navigator.maxTouchPoints
          });
          if (isTouchDevice && document.body) {
            document.body.classList.add("touch-device");
          }
          var siteNav = document.getElementById("site-navigation");
          if (!siteNav) {
            console.warn('[Navigation] #site-navigation not found - aborting');
            return false;
          }
          if (siteNav.hasAttribute('data-critical-nav-initialized')) {
            console.log('[Navigation] Already initialized - skipping');
            return true;
          }
          console.log('[Navigation] #site-navigation found');
          var button = siteNav.querySelector("button");
          var menu = siteNav.querySelector("ul");
          var utilityMenu = document.querySelector(".utility-menu");
          var megaNavMode = isTouchDevice ? "click" : "hover";
          var hoverDelay = 200;
          console.log('[Navigation] Mode:', megaNavMode, '| Button:', !!button, '| Menu:', !!menu);
          if (!button || !menu) {
            console.warn('[Navigation] Missing required elements - button or menu not found');
            if (!menu && button) {
              button.style.display = "none";
            }
            return false;
          }
          menu.classList.add("nav-menu");
          console.log('[Navigation] Added nav-menu class');
          var hideTimers = new Map();
          var getFocusable = function getFocusable(container) {
            return Array.from(container.querySelectorAll('a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'));
          };
          var closeMegaMenus = function closeMegaMenus() {
            document.querySelectorAll(".mega-menu.active").forEach(function (el) {
              return el.classList.remove("active");
            });
            hideTimers.forEach(function (timer, key) {
              if (key.startsWith('mega-')) {
                clearTimeout(timer);
                hideTimers.delete(key);
              }
            });
            setTimeout(function () {
              var container = document.querySelector(".mega-menu-container");
              if (container && !container.querySelector(".mega-menu.active")) {
                container.classList.remove("active");
              }
            }, 10);
            document.querySelectorAll("[aria-controls]").forEach(function (link) {
              var controlId = link.getAttribute("aria-controls");
              var controlledEl = controlId && document.getElementById(controlId);
              if (controlledEl && controlledEl.classList.contains("mega-menu")) {
                link.setAttribute("aria-expanded", "false");
              }
            });
          };
          var closeUtilityMenus = function closeUtilityMenus() {
            document.querySelectorAll(".utility-menu .dropdown-active").forEach(function (el) {
              return el.classList.remove("dropdown-active");
            });
          };
          var closeAll = function closeAll() {
            hideTimers.forEach(function (timer) {
              return clearTimeout(timer);
            });
            hideTimers.clear();
            document.querySelectorAll(".dropdown-active, .mega-menu.active").forEach(function (el) {
              return el.classList.remove("dropdown-active", "active");
            });
            setTimeout(function () {
              var container = document.querySelector(".mega-menu-container");
              if (container) {
                container.classList.remove("active");
              }
            }, 10);
            document.querySelectorAll("[aria-controls]").forEach(function (link) {
              return link.setAttribute("aria-expanded", "false");
            });
          };
          var _handleMegaKeyNav = function handleMegaKeyNav(e) {
            var megaMenu = e.currentTarget;
            if (e.key === "Escape") {
              e.preventDefault();
              megaMenu.classList.remove("active");
              setTimeout(function () {
                var container = document.querySelector(".mega-menu-container");
                if (container && !container.querySelector(".mega-menu.active")) {
                  container.classList.remove("active");
                }
              }, 10);
              if (megaMenu._topLink) {
                megaMenu._topLink.focus();
              }
              megaMenu.removeEventListener("keydown", _handleMegaKeyNav);
            } else if (e.key === "Tab") {
              var focusable = getFocusable(megaMenu);
              var currentIndex = focusable.indexOf(document.activeElement);
              if (e.shiftKey && currentIndex === 0 || !e.shiftKey && currentIndex === focusable.length - 1) {
                e.preventDefault();
                megaMenu.classList.remove("active");
                var topLinks = Array.from(document.querySelectorAll("#menu-primary > li > a"));
                var index = topLinks.indexOf(megaMenu._topLink);
                var targetLink = e.shiftKey ? index > 0 ? topLinks[index - 1] : megaMenu._topLink : index < topLinks.length - 1 ? topLinks[index + 1] : megaMenu._topLink;
                targetLink.focus();
                megaMenu.removeEventListener("keydown", _handleMegaKeyNav);
              }
            }
          };
          var setupDropdowns = function setupDropdowns(menuElement) {
            menuElement.querySelectorAll(".dropdown-toggle").forEach(function (toggle) {
              toggle.addEventListener("click", function (e) {
                e.stopPropagation();
                e.preventDefault();
                var menuItem = toggle.parentNode;
                menuItem.classList.toggle("dropdown-active");
              });
            });
            menuElement.querySelectorAll(".menu-item-has-children > a, [aria-controls]").forEach(function (link) {
              var controlId = link.getAttribute("aria-controls");
              if (controlId) {
                var megaMenu = document.getElementById(controlId);
                if (!megaMenu) {
                  console.error('[Navigation] Mega menu not found:', controlId);
                  return;
                }
                console.log('[Navigation] Processing mega menu:', {
                  controlId: controlId,
                  mode: megaNavMode,
                  exists: !!megaMenu,
                  linkHref: link.href,
                  linkText: link.textContent.trim()
                });
                if (link.hasAttribute('data-mega-nav-initialized')) {
                  console.log('[Navigation] Menu link already initialized:', controlId);
                  return;
                }
                link.setAttribute('data-mega-nav-initialized', 'true');
                if (megaNavMode === "hover") {
                  console.log('[Navigation] Setting up hover mode for mega menu:', controlId);
                  var menuItem = link.parentNode;
                  var timerId = "mega-".concat(controlId);
                  var clearTimer = function clearTimer() {
                    if (hideTimers.has(timerId)) {
                      clearTimeout(hideTimers.get(timerId));
                      hideTimers.delete(timerId);
                    }
                  };
                  var showMenu = function showMenu() {
                    console.log('[Navigation] Showing mega menu:', controlId);
                    clearTimer();
                    document.querySelectorAll(".mega-menu.active").forEach(function (el) {
                      if (el !== megaMenu) {
                        el.classList.remove("active");
                        var otherLink = document.querySelector("[aria-controls=\"".concat(el.id, "\"]"));
                        if (otherLink) {
                          otherLink.setAttribute("aria-expanded", "false");
                        }
                      }
                    });
                    closeUtilityMenus();
                    megaMenu.classList.add("active");
                    var container = document.querySelector(".mega-menu-container");
                    if (container) {
                      container.classList.add("active");
                    }
                    link.setAttribute("aria-expanded", "true");
                    megaMenu._topLink = link;
                    megaMenu.addEventListener("keydown", _handleMegaKeyNav);
                  };
                  var hideMenu = function hideMenu() {
                    var timer = setTimeout(function () {
                      console.log('[Navigation] Hiding mega menu:', controlId);
                      megaMenu.classList.remove("active");
                      setTimeout(function () {
                        var container = document.querySelector(".mega-menu-container");
                        if (container && !container.querySelector(".mega-menu.active")) {
                          container.classList.remove("active");
                        }
                      }, 10);
                      link.setAttribute("aria-expanded", "false");
                      megaMenu.removeEventListener("keydown", _handleMegaKeyNav);
                      hideTimers.delete(timerId);
                    }, hoverDelay);
                    hideTimers.set(timerId, timer);
                  };
                  menuItem.addEventListener("mouseenter", showMenu);
                  menuItem.addEventListener("mouseleave", hideMenu);
                  megaMenu.addEventListener("mouseenter", clearTimer);
                  megaMenu.addEventListener("mouseleave", hideMenu);
                  link.addEventListener("click", function (e) {});
                  link.addEventListener("keydown", function (e) {
                    if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
                      e.preventDefault();
                      var isActive = megaMenu.classList.contains("active");
                      if (isActive) {
                        if (e.key === "ArrowDown") {
                          var firstFocusable = getFocusable(megaMenu)[0];
                          if (firstFocusable) {
                            firstFocusable.focus();
                          }
                          return;
                        }
                        megaMenu.classList.remove("active");
                        setTimeout(function () {
                          var container = document.querySelector(".mega-menu-container");
                          if (container && !container.querySelector(".mega-menu.active")) {
                            container.classList.remove("active");
                          }
                        }, 10);
                        link.setAttribute("aria-expanded", "false");
                        megaMenu.removeEventListener("keydown", _handleMegaKeyNav);
                      } else {
                        closeMegaMenus();
                        closeUtilityMenus();
                        megaMenu.classList.add("active");
                        var container = document.querySelector(".mega-menu-container");
                        if (container) {
                          container.classList.add("active");
                        }
                        link.setAttribute("aria-expanded", "true");
                        megaMenu._topLink = link;
                        var heading = megaMenu.querySelector(".mega-menu-intro > h2");
                        if (heading) {
                          heading.setAttribute("tabindex", "-1");
                          setTimeout(function () {
                            return heading.focus();
                          }, 200);
                        }
                        megaMenu.addEventListener("keydown", _handleMegaKeyNav);
                      }
                    } else if (e.key === "Escape") {
                      closeAll();
                      link.focus();
                    }
                  });
                } else {
                  link.addEventListener("keydown", function (e) {
                    if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {} else if (e.key === "Escape") {
                      closeAll();
                      link.focus();
                    }
                  });
                }
              } else {
                var isUtilityMenu = link.closest('.utility-menu');
                var hasDropdownToggle = link.parentNode.querySelector('.dropdown-toggle');
                if (isUtilityMenu && !hasDropdownToggle) {
                  if (isTouchDevice) {
                    link.addEventListener("click", function (e) {
                      var submenu = link.parentNode.querySelector('ul');
                      if (submenu) {
                        e.preventDefault();
                        var _menuItem = link.parentNode;
                        _menuItem.classList.toggle("dropdown-active");
                      }
                    });
                  }
                }
                link.addEventListener("keydown", function (e) {
                  if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
                    e.preventDefault();
                    var _menuItem2 = link.parentNode;
                    _menuItem2.classList.toggle("dropdown-active");
                    if (e.key === "ArrowDown" && _menuItem2.classList.contains("dropdown-active")) {
                      var submenu = _menuItem2.querySelector("ul");
                      if (submenu) {
                        var firstLink = submenu.querySelector("a");
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
          button.addEventListener("click", function () {
            siteNav.classList.toggle("toggled");
            var isExpanded = button.getAttribute("aria-expanded") === "true";
            button.setAttribute("aria-expanded", (!isExpanded).toString());
            if (siteNav.classList.contains("toggled")) {
              document.body.classList.add("no-scroll");
            } else {
              document.body.classList.remove("no-scroll");
            }
          });
          console.log('[Navigation] Setting up dropdowns for main nav');
          setupDropdowns(siteNav);
          if (utilityMenu) {
            console.log('[Navigation] Setting up dropdowns for utility menu');
            setupDropdowns(utilityMenu);
          }
          if (isTouchDevice) {
            document.querySelectorAll('.menu-label').forEach(function (label) {
              var menuItem = label.parentNode;
              var dropdownToggle = menuItem.querySelector('.dropdown-toggle');
              if (dropdownToggle) {
                label.style.cursor = 'pointer';
                label.addEventListener('click', function (e) {
                  e.preventDefault();
                  e.stopPropagation();
                  dropdownToggle.click();
                });
              }
            });
          }
          document.addEventListener("click", function (e) {
            if (!siteNav.contains(e.target) && !(utilityMenu && utilityMenu.contains(e.target))) {
              closeAll();
              siteNav.classList.remove("toggled");
              button.setAttribute("aria-expanded", "false");
              document.body.classList.remove("no-scroll");
            }
          });
          document.querySelectorAll(".menu-item-has-children > a").forEach(function (link) {
            if (!link.getAttribute("aria-controls")) {
              var hasDropdownToggle = link.parentNode.querySelector('.dropdown-toggle');
              if (!hasDropdownToggle) {
                link.addEventListener("keydown", function (e) {
                  var menuItem = link.parentNode;
                  if ((e.key === "Enter" || e.key === " " || e.key === "ArrowDown") && (menuItem.classList.contains("menu-item-has-children") || menuItem.querySelector("ul"))) {
                    e.preventDefault();
                    menuItem.classList.toggle("dropdown-active");
                    if (e.key === "ArrowDown" && menuItem.classList.contains("dropdown-active")) {
                      var submenu = menuItem.querySelector("ul");
                      if (submenu) {
                        var firstLink = submenu.querySelector("a");
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
          console.log('[Navigation] ✓ Initialization complete');
          return true;
        }
        if (!initNavigation()) {
          console.log('[Navigation] Initial attempt failed, starting polling...');
          var attempts = 0;
          var maxAttempts = 50;
          var pollForNav = setInterval(function () {
            attempts++;
            console.log('[Navigation] Poll attempt', attempts, 'of', maxAttempts);
            if (initNavigation() || attempts >= maxAttempts) {
              clearInterval(pollForNav);
              if (attempts >= maxAttempts) {
                console.error('[Navigation] ✗ Failed to initialize after', maxAttempts, 'attempts');
              }
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
