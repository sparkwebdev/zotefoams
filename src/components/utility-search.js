/**
 * Utility Search Component
 * Enhanced search functionality in the utility menu with accessibility
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initUtilitySearch() {
  const menu = document.querySelector('#menu-utility');
  const searchItem = menu?.querySelector('a[href="/search"]');

  if (!menu || !searchItem) return;

   // Add menu-item-has-children to the parent <li>
  const searchItemParent = searchItem.parentElement;
  searchItemParent?.classList.add('menu-item-has-children');

  // Enhance searchItem for accessibility
  searchItem.setAttribute('role', 'button');
  searchItem.setAttribute('aria-expanded', 'false');
  searchItem.setAttribute('aria-controls', 'utility-search-form');

  // Create and inject the search container
  const searchContainer = document.createElement('div');
  searchContainer.className = 'utility-search';
  searchContainer.id = 'utility-search-form';
  searchContainer.setAttribute('hidden', '');

  searchContainer.innerHTML = `
    <form role="search" aria-label="Site search form" action="/">
      <input type="text" name="s" placeholder="Search..." aria-label="Search input" required />
      <button type="submit" class="btn outline white">Go</button>
      <button type="button" class="btn outline white" aria-label="Close search form">✕</button>
    </form>
  `;

  menu.after(searchContainer);

  const form = searchContainer.querySelector('form');
  const input = form.querySelector('input[type="text"]');
  const closeButton = form.querySelector('button[type="button"]');
  const nextMenuItem = searchItem.closest('li')?.nextElementSibling?.querySelector('a');

  const openSearch = (focusInput = false) => {
    searchContainer.removeAttribute('hidden');
    searchContainer.classList.add('is-visible');
    searchItem.setAttribute('aria-expanded', 'true');
    
    // Focus the input if requested (for keyboard activation)
    if (focusInput && input) {
      setTimeout(() => {
        input.focus();
      }, 100);
    }
  };

  // Prevent scroll on input
  input?.addEventListener("input", (e) => {
    e.preventDefault();
    window.scrollTo(window.scrollX, window.scrollY);
  });

  input?.addEventListener("keydown", (e) => {
    // Store current scroll position
    const currentScrollY = window.scrollY;
    setTimeout(() => {
      window.scrollTo(window.scrollX, currentScrollY);
    }, 0);
  });

  const closeSearch = () => {
    searchContainer.classList.remove('is-visible');
    searchContainer.setAttribute('hidden', '');
    searchItem.setAttribute('aria-expanded', 'false');
  };

  const toggleSearch = () => {
    const isHidden = searchContainer.hasAttribute('hidden');
    if (isHidden) {
      openSearch();
    } else {
      closeSearch();
    }
  };

  // Click to toggle
  searchItem.addEventListener('click', (e) => {
    e.preventDefault();
    toggleSearch();
  });

  // Enter/Space key opens with focus on input
  searchItem.addEventListener('keydown', (e) => {
    if (['Enter', ' '].includes(e.key)) {
      e.preventDefault();
      const isHidden = searchContainer.hasAttribute('hidden');
      if (isHidden) {
        openSearch(true); // Focus input when opening via keyboard
      } else {
        closeSearch();
      }
    }
  });

  // Escape closes
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && searchContainer.classList.contains('is-visible')) {
      e.preventDefault();
      closeSearch();
      searchItem.focus();
    }
  });

  // Close button click - clear input and close
  closeButton?.addEventListener('click', () => {
    if (input) {
      input.value = ''; // Clear the search input
    }
    closeSearch();
    searchItem.focus();
  });

  // Focus trap exit logic
  form.addEventListener('keydown', (e) => {
    if (e.key !== 'Tab') return;

    const focusable = Array.from(
      form.querySelectorAll('input, button:not([disabled]), [tabindex]:not([tabindex="-1"])')
    ).filter(el => !el.hasAttribute('hidden'));

    const first = focusable[0];
    const last = focusable[focusable.length - 1];

    if (e.shiftKey && document.activeElement === first) {
      // Shift+Tab on first element → close and focus "Search"
      e.preventDefault();
      closeSearch();
      searchItem.focus();
    } else if (!e.shiftKey && document.activeElement === last) {
      // Tab on last element → close and focus next menu item
      e.preventDefault();
      closeSearch();
      nextMenuItem?.focus();
    }
  });
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready(initUtilitySearch);

export { initUtilitySearch };