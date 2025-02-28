/**
 * navigation.js
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown and mega menus.
 */
(function () {
  const siteNavigation = document.getElementById("site-navigation");
  const utilityMenu = document.querySelector(".utility-menu");

  // Return early if navigation doesn't exist.
  if (!siteNavigation) {
    return;
  }

  const button = siteNavigation.getElementsByTagName("button")[0];
  const menu = siteNavigation.getElementsByTagName("ul")[0];

  // Early returns for missing elements.
  if (typeof button === "undefined" || typeof menu === "undefined") {
    if (typeof menu === "undefined") {
      button.style.display = "none";
    }
    return;
  }

  if (!menu.classList.contains("nav-menu")) {
    menu.classList.add("nav-menu");
  }

  // Handle hamburger menu toggle.
  button.addEventListener("click", function () {
    siteNavigation.classList.toggle("toggled");
    const isExpanded = button.getAttribute("aria-expanded") === "true";
    button.setAttribute("aria-expanded", (!isExpanded).toString());
  });

  // Option: set interaction mode for mega nav. Can be "click" or "hover".
  const megaNavInteraction = "hover";

  // Helper: Get focusable elements within a container.
  function getFocusableElements(container) {
    return Array.from(
      container.querySelectorAll(
        'a, button, input, select, textarea, [tabindex]:not([tabindex="-1"])'
      )
    );
  }

  // Function to close all open dropdowns and mega menus.
  function closeAllDropdowns() {
    document.querySelectorAll(".dropdown-active").forEach((item) => {
      item.classList.remove("dropdown-active");
    });
    document.querySelectorAll(".mega-menu.active").forEach((mega) => {
      mega.classList.remove("active");
    });
    const container = document.querySelector(".mega-menu-container");
    if (container) {
      container.classList.remove("active");
    }
    // Reset aria-expanded on links controlling mega menus.
    document.querySelectorAll("[aria-controls]").forEach((link) => {
      link.setAttribute("aria-expanded", "false");
    });
  }

  // Key handler for an open mega nav panel.
  function megaNavKeyHandler(e) {
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
      megaMenu.removeEventListener("keydown", megaNavKeyHandler);
    } else if (e.key === "Tab") {
      const focusable = getFocusableElements(megaMenu);
      const currentIndex = focusable.indexOf(document.activeElement);
      if (e.shiftKey && currentIndex === 0) {
        // When shift+tab on the first element, close and focus previous top-level link.
        e.preventDefault();
        megaMenu.classList.remove("active");
        const topLinks = Array.from(
          document.querySelectorAll("#menu-primary > li > a")
        );
        const index = topLinks.indexOf(megaMenu._topLink);
        let prevLink = index > 0 ? topLinks[index - 1] : megaMenu._topLink;
        prevLink.focus();
        megaMenu.removeEventListener("keydown", megaNavKeyHandler);
      } else if (!e.shiftKey && currentIndex === focusable.length - 1) {
        // When tab on the last element, close and focus next top-level link.
        e.preventDefault();
        megaMenu.classList.remove("active");
        const topLinks = Array.from(
          document.querySelectorAll("#menu-primary > li > a")
        );
        const index = topLinks.indexOf(megaMenu._topLink);
        let nextLink =
          index < topLinks.length - 1 ? topLinks[index + 1] : megaMenu._topLink;
        nextLink.focus();
        megaMenu.removeEventListener("keydown", megaNavKeyHandler);
      }
    }
  }

  // Set up dropdown/mega menu handlers.
  function setupDropdownHandlers(menuElement) {
    const itemsWithDropdowns = menuElement.querySelectorAll(
      ".menu-item-has-children > a, [aria-controls]"
    );
    itemsWithDropdowns.forEach((link) => {
      if (link.getAttribute("aria-controls")) {
        if (megaNavInteraction === "hover") {
          const menuItem = link.parentNode;
          let hideTimer = null;
          // On mouseenter on the top-level item, open mega nav.
          menuItem.addEventListener("mouseenter", function () {
            if (hideTimer) {
              clearTimeout(hideTimer);
              hideTimer = null;
            }
            closeAllDropdowns(); // Optionally close others.
            const controlId = link.getAttribute("aria-controls");
            const megaMenu = document.getElementById(controlId);
            if (megaMenu) {
              megaMenu.classList.add("active");
              const container = document.querySelector(".mega-menu-container");
              if (container) {
                container.classList.add("active");
              }
              link.setAttribute("aria-expanded", "true");
              megaMenu._topLink = link; // store reference to the top-level link
              megaMenu.addEventListener("keydown", megaNavKeyHandler);
            }
          });
          // On mouseleave from the top-level item, start a timer to hide the mega nav.
          menuItem.addEventListener("mouseleave", function () {
            hideTimer = setTimeout(function () {
              const controlId = link.getAttribute("aria-controls");
              const megaMenu = document.getElementById(controlId);
              if (megaMenu) {
                megaMenu.classList.remove("active");
                megaMenu.removeEventListener("keydown", megaNavKeyHandler);
              }
              const container = document.querySelector(".mega-menu-container");
              if (container && !container.querySelector(".mega-menu.active")) {
                container.classList.remove("active");
              }
              link.setAttribute("aria-expanded", "false");
            }, 300);
          });
          // **NEW:** Also attach mouse events on the mega nav panel itself.
          const controlId = link.getAttribute("aria-controls");
          const megaMenu = document.getElementById(controlId);
          if (megaMenu) {
            megaMenu.addEventListener("mouseenter", function () {
              if (hideTimer) {
                clearTimeout(hideTimer);
                hideTimer = null;
              }
            });
            megaMenu.addEventListener("mouseleave", function () {
              hideTimer = setTimeout(function () {
                megaMenu.classList.remove("active");
                megaMenu.removeEventListener("keydown", megaNavKeyHandler);
                const container = document.querySelector(".mega-menu-container");
                if (container && !container.querySelector(".mega-menu.active")) {
                  container.classList.remove("active");
                }
                link.setAttribute("aria-expanded", "false");
              }, 300);
            });
          }
          // Keyboard support for hover mode.
          link.addEventListener("keydown", function (e) {
            if (e.key === "Enter" || e.key === " ") {
              e.preventDefault();
              const controlId = link.getAttribute("aria-controls");
              const megaMenu = document.getElementById(controlId);
              const isActive =
                megaMenu && megaMenu.classList.contains("active");
              if (isActive) {
                megaMenu.classList.remove("active");
                megaMenu.removeEventListener("keydown", megaNavKeyHandler);
                const container = document.querySelector(".mega-menu-container");
                if (container && !container.querySelector(".mega-menu.active")) {
                  container.classList.remove("active");
                }
                link.setAttribute("aria-expanded", "false");
                link.focus();
              } else {
                closeAllDropdowns();
                if (megaMenu) {
                  megaMenu.classList.add("active");
                  const container = document.querySelector(".mega-menu-container");
                  if (container) {
                    container.classList.add("active");
                  }
                  link.setAttribute("aria-expanded", "true");
                  megaMenu._topLink = link;
                  // Only on keyboard activation, shift focus to the heading.
                  const heading = megaMenu.querySelector(".mega-menu-intro > h2");
                  if (heading) {
                    heading.setAttribute("tabindex", "-1");
                    setTimeout(() => {
                      heading.focus();
                    }, 500);
                  }
                  megaMenu.addEventListener("keydown", megaNavKeyHandler);
                }
              }
            } else if (e.key === "Escape") {
              closeAllDropdowns();
              link.focus();
            }
          });
        } else {
          // Click interaction for mega nav.
          link.addEventListener("click", function (e) {
            e.preventDefault();
            const controlId = this.getAttribute("aria-controls");
            const megaMenu = document.getElementById(controlId);
            const isActive =
              megaMenu && megaMenu.classList.contains("active");
            closeAllDropdowns();
            if (megaMenu && !isActive) {
              megaMenu.classList.add("active");
              const container = document.querySelector(".mega-menu-container");
              if (container) {
                container.classList.add("active");
              }
              this.setAttribute("aria-expanded", "true");
            } else if (megaMenu) {
              megaMenu.classList.remove("active");
              const container = document.querySelector(".mega-menu-container");
              if (container) {
                container.classList.remove("active");
              }
              this.setAttribute("aria-expanded", "false");
            }
          });
          link.addEventListener("keydown", function (e) {
            if (e.key === "Enter" || e.key === " ") {
              e.preventDefault();
              link.click();
            } else if (e.key === "Escape") {
              closeAllDropdowns();
              link.focus();
            }
          });
        }
      } else {
        // For regular dropdown toggling on mobile.
        link.addEventListener("click", function (e) {
          const menuItem = this.parentNode;
          menuElement.querySelectorAll(".dropdown-active").forEach((item) => {
            if (item !== menuItem) {
              item.classList.remove("dropdown-active");
            }
          });
          menuItem.classList.toggle("dropdown-active");
        });
        link.addEventListener("keydown", function (e) {
          const menuItem = e.target.parentNode;
          if (e.key === "Enter" || e.key === " ") {
            e.preventDefault();
            menuItem.classList.toggle("dropdown-active");
          }
          if (e.key === "Escape") {
            closeAllDropdowns();
            link.focus();
          }
        });
      }
    });
  }

  setupDropdownHandlers(siteNavigation);
  if (utilityMenu) {
    setupDropdownHandlers(utilityMenu);
  }

  // Close dropdowns when clicking outside.
  document.addEventListener("click", function (event) {
    if (
      !siteNavigation.contains(event.target) &&
      !(utilityMenu && utilityMenu.contains(event.target))
    ) {
      closeAllDropdowns();
      siteNavigation.classList.remove("toggled");
      button.setAttribute("aria-expanded", "false");
    }
  });

  // Accessibility: handle keyboard interactions for non-mega links.
  function handleKeyboard(e) {
    const menuItem = e.target.parentNode;
    if (e.key === "Enter" || e.key === " ") {
      e.preventDefault();
      if (
        !e.target.getAttribute("aria-controls") &&
        (menuItem.classList.contains("menu-item-has-children") ||
          menuItem.querySelector("ul"))
      ) {
        menuItem.classList.toggle("dropdown-active");
      }
    }
    if (e.key === "Escape") {
      closeAllDropdowns();
      e.target.focus();
    }
  }

  const menuLinks = document.querySelectorAll(
    ".menu-item-has-children > a, [aria-controls]"
  );
  menuLinks.forEach((link) => {
    if (!link.getAttribute("aria-controls")) {
      link.addEventListener("keydown", handleKeyboard);
    }
  });
})();
