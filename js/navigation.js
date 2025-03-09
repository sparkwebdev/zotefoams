/**
 * navigation.js - Handles navigation menu functionality with accessibility support
 */

// Detect touch devices, add a class to the body, and set megaNavMode accordingly
const isTouchDevice =
  "ontouchstart" in window ||
  navigator.maxTouchPoints > 0 ||
  navigator.msMaxTouchPoints > 0;
if (isTouchDevice) {
  document.body.classList.add("touch-device");
}

(() => {
  const siteNav = document.getElementById("site-navigation");
  if (!siteNav) {
    return;
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
    return;
  }

  menu.classList.add("nav-menu");

  // Helper functions for focusable elements
  const getFocusable = (container) =>
    Array.from(
      container.querySelectorAll(
        'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
      )
    );

  // New helper functions to close only mega menus or utility menus
  const closeMegaMenus = () => {
    document
      .querySelectorAll(".mega-menu.active")
      .forEach((el) => el.classList.remove("active"));
    const container = document.querySelector(".mega-menu-container");
    if (container && !container.querySelector(".mega-menu.active")) {
      container.classList.remove("active");
    }
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

  // Existing closeAll() now remains available for global closing if needed
  const closeAll = () => {
    document
      .querySelectorAll(".dropdown-active, .mega-menu.active")
      .forEach((el) => el.classList.remove("dropdown-active", "active"));
    const container = document.querySelector(".mega-menu-container");
    if (container) {
      container.classList.remove("active");
    }
    document
      .querySelectorAll("[aria-controls]")
      .forEach((link) => link.setAttribute("aria-expanded", "false"));
  };

  // Handle keyboard navigation within mega menu
  const handleMegaKeyNav = (e) => {
    const megaMenu = e.currentTarget;
    if (e.key === "Escape") {
      e.preventDefault();
      megaMenu.classList.remove("active");
      const container = document.querySelector(".mega-menu-container");
      if (container && !container.querySelector(".mega-menu.active")) {
        container.classList.remove("active");
      }
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

		menuElement.querySelectorAll('.dropdown-toggle').forEach((toggle) => {
			toggle.addEventListener('click', (e) => {
				// Prevent the click from affecting the main link
				e.stopPropagation();
				e.preventDefault();
				
				// Toggle the dropdown on the parent menu item
				const menuItem = toggle.parentNode;
				// Optionally, close other open utility or mega menus here
				menuItem.classList.toggle('dropdown-active');
			});
		});
    menuElement
      .querySelectorAll(".menu-item-has-children > a, [aria-controls]")
      .forEach((link) => {
        const controlId = link.getAttribute("aria-controls");

        if (controlId) {
          // Mega menu setup
          const megaMenu = document.getElementById(controlId);
          if (!megaMenu) {
            return;
          }

          if (megaNavMode === "hover") {
            let hideTimer = null;
            const menuItem = link.parentNode;
            const clearTimer = () => {
              if (hideTimer) {
                clearTimeout(hideTimer);
                hideTimer = null;
              }
            };

            const showMenu = () => {
              clearTimer();
              // Close any open utility menus before showing mega menu
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
              hideTimer = setTimeout(() => {
                megaMenu.classList.remove("active");
                const container = document.querySelector(
                  ".mega-menu-container"
                );
                if (
                  container &&
                  !container.querySelector(".mega-menu.active")
                ) {
                  container.classList.remove("active");
                }
                link.setAttribute("aria-expanded", "false");
                megaMenu.removeEventListener("keydown", handleMegaKeyNav);
              }, hoverDelay);
            };

            menuItem.addEventListener("mouseenter", showMenu);
            menuItem.addEventListener("mouseleave", hideMenu);
            megaMenu.addEventListener("mouseenter", clearTimer);
            megaMenu.addEventListener("mouseleave", hideMenu);

            // Keyboard handling for mega menu
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
                  const container = document.querySelector(
                    ".mega-menu-container"
                  );
                  if (
                    container &&
                    !container.querySelector(".mega-menu.active")
                  ) {
                    container.classList.remove("active");
                  }
                  link.setAttribute("aria-expanded", "false");
                  megaMenu.removeEventListener("keydown", handleMegaKeyNav);
                } else {
                  // Close any open utility menus when opening a mega menu
                  closeUtilityMenus();
                  megaMenu.classList.add("active");
                  const container = document.querySelector(
                    ".mega-menu-container"
                  );
                  if (container) {
                    container.classList.add("active");
                  }
                  link.setAttribute("aria-expanded", "true");
                  megaMenu._topLink = link;
                  const heading = megaMenu.querySelector(
                    ".mega-menu-intro > h2"
                  );
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
            // Click mega menu for touch devices
            link.addEventListener("click", (e) => {
              e.preventDefault();
              const isActive = megaMenu.classList.contains("active");
              // Close any open utility menus when opening a mega menu
              closeUtilityMenus();

              if (!isActive) {
                megaMenu.classList.add("active");
                const container = document.querySelector(
                  ".mega-menu-container"
                );
                if (container) {
                  container.classList.add("active");
                }
                link.setAttribute("aria-expanded", "true");
                megaMenu._topLink = link;
                megaMenu.addEventListener("keydown", handleMegaKeyNav);
              }
            });

            link.addEventListener("keydown", (e) => {
              if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
                e.preventDefault();
                const isActive = megaMenu.classList.contains("active");

                if (isActive && e.key === "ArrowDown") {
                  const firstFocusable = getFocusable(megaMenu)[0];
                  if (firstFocusable) {
                    firstFocusable.focus();
                  }
                } else {
                  link.click();
                }
              } else if (e.key === "Escape") {
                closeAll();
                link.focus();
              }
            });
          }
        } else {
          // Regular dropdown – typically used for utility menus
          link.addEventListener("click", (e) => {
            // For utility menu (or touch devices), prevent default navigation
            if (
              menuElement.classList.contains("utility-menu") ||
              document.body.classList.contains("touch-device")
            ) {
              e.preventDefault();
            }
            const menuItem = link.parentNode;
            // If we're opening this dropdown, close any open mega menus
            if (!menuItem.classList.contains("dropdown-active")) {
              closeMegaMenus();
            }
            menuElement.querySelectorAll(".dropdown-active").forEach((item) => {
              if (item !== menuItem) {
                item.classList.remove("dropdown-active");
              }
            });
            menuItem.classList.toggle("dropdown-active");
          });

          link.addEventListener("keydown", (e) => {
            const menuItem = link.parentNode;
            if (e.key === "Enter" || e.key === " " || e.key === "ArrowDown") {
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
      });
  };

  // Set up event listeners for the main navigation and utility menu
  button.addEventListener("click", () => {
    siteNav.classList.toggle("toggled");
    const isExpanded = button.getAttribute("aria-expanded") === "true";
    button.setAttribute("aria-expanded", (!isExpanded).toString());
  });

  setupDropdowns(siteNav);
  if (utilityMenu) {
    setupDropdowns(utilityMenu);
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
    }
  });

  // Handle keyboard for standard links without aria-controls
  document.querySelectorAll(".menu-item-has-children > a").forEach((link) => {
    if (!link.getAttribute("aria-controls")) {
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
  });

})();
