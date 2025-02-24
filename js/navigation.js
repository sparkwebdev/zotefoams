/**
 * File navigation.js.
 *
 * Handles toggling the navigation menu for small screens and enables TAB key
 * navigation support for dropdown menus.
 */
( function() {
  const siteNavigation = document.getElementById('site-navigation');
  const utilityMenu = document.querySelector('.utility-menu');

  // Return early if the navigation doesn't exist.
  if (!siteNavigation) {
    return;
  }

  const button = siteNavigation.getElementsByTagName('button')[0];
  const menu = siteNavigation.getElementsByTagName('ul')[0];

  // Early returns for missing elements
  if ('undefined' === typeof button || 'undefined' === typeof menu) {
    if ('undefined' === typeof menu) {
      button.style.display = 'none';
    }
    return;
  }

  if (!menu.classList.contains('nav-menu')) {
    menu.classList.add('nav-menu');
  }

  // Handle hamburger menu toggle
  button.addEventListener('click', function() {
    siteNavigation.classList.toggle('toggled');
    const isExpanded = button.getAttribute('aria-expanded') === 'true';
    button.setAttribute('aria-expanded', (!isExpanded).toString());
  });

  // Handle clicks on menu items with dropdowns
  function setupDropdownHandlers(menuElement) {
    const itemsWithDropdowns = menuElement.querySelectorAll('.menu-item-has-children > a, li:has(.mega-menu) > a');
    
    itemsWithDropdowns.forEach(link => {
      link.addEventListener('click', function(e) {
        // e.preventDefault();
        const menuItem = this.parentNode;
        
        // Close other open dropdowns
        const openItems = menuElement.querySelectorAll('.dropdown-active');
        openItems.forEach(item => {
          if (item !== menuItem) {
            item.classList.remove('dropdown-active');
          }
        });
        
        // Toggle current dropdown
        menuItem.classList.toggle('dropdown-active');
      });
    });
  }

  // Set up handlers for both navigation menus
  setupDropdownHandlers(siteNavigation);
  if (utilityMenu) {
    setupDropdownHandlers(utilityMenu);
  }

  // Close dropdowns when clicking outside
  document.addEventListener('click', function(event) {
    if (!siteNavigation.contains(event.target) && !utilityMenu?.contains(event.target)) {
      document.querySelectorAll('.dropdown-active').forEach(item => {
        item.classList.remove('dropdown-active');
      });
      siteNavigation.classList.remove('toggled');
      button.setAttribute('aria-expanded', 'false');
    }
  });

  // Accessibility support for keyboard navigation
  function handleKeyboard(e) {
    const menuItem = e.target.parentNode;
    
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      if (menuItem.classList.contains('menu-item-has-children') || menuItem.querySelector('.mega-menu')) {
        menuItem.classList.toggle('dropdown-active');
      }
    }

    if (e.key === 'Escape') {
      const openItems = document.querySelectorAll('.dropdown-active');
      openItems.forEach(item => item.classList.remove('dropdown-active'));
    }
  }

  const menuLinks = document.querySelectorAll('.menu-item-has-children > a, li:has(.mega-menu) > a');
  menuLinks.forEach(link => {
    link.addEventListener('keydown', handleKeyboard);
  });
})();