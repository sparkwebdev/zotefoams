/**
 * File List Component
 * Enhanced file filtering with multiple filters, comma-separated values and keyboard support
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initFileList() {
  const fileListElements = document.querySelectorAll('[data-component="file-list"]');

  if (fileListElements.length > 0) {
    fileListElements.forEach(function (container) {
      const filterButton = container.querySelector('#filter-toggle');
      const filterOptions = container.querySelector('#filter-options');
      const checkboxes = Array.from(container.querySelectorAll('.filter-options__checkbox'));
      const showAllButton = container.querySelector('#file-list-show-all');
      const fileItems = Array.from(container.querySelectorAll('.file-list__item'));

      // Toggle the dropdown menu.
      const toggleDropdown = (show) => {
        filterOptions.classList.toggle('hidden', !show);
        filterButton.classList.toggle('open', show);
      };

      // Keyboard support for the filter dropdown.
      filterOptions.addEventListener('keydown', function (e) {
        const KEY_UP = 38,
          KEY_DOWN = 40,
          KEY_ESCAPE = 27,
          KEY_TAB = 9;
        if (e.keyCode === KEY_DOWN) {
          e.preventDefault();
          let currentIndex = checkboxes.findIndex((cb) => cb === document.activeElement);
          if (currentIndex === -1 || currentIndex === checkboxes.length - 1) {
            checkboxes[0].focus();
          } else {
            checkboxes[currentIndex + 1].focus();
          }
        } else if (e.keyCode === KEY_UP) {
          e.preventDefault();
          let currentIndex = checkboxes.findIndex((cb) => cb === document.activeElement);
          if (currentIndex <= 0) {
            checkboxes[checkboxes.length - 1].focus();
          } else {
            checkboxes[currentIndex - 1].focus();
          }
        } else if (e.keyCode === KEY_ESCAPE) {
          e.preventDefault();
          toggleDropdown(false);
          filterButton.focus();
        } else if (e.keyCode === KEY_TAB) {
          // Allow Tab to move focus, then close the dropdown if focus moves outside.
          setTimeout(() => {
            if (!filterOptions.contains(document.activeElement)) {
              toggleDropdown(false);
            }
          }, 0);
        }
      });

      // Gather active filters by filter type.
      const getActiveFilters = () => {
        const activeFilters = {};
        checkboxes.forEach((cb) => {
          const filterType = cb.dataset.filter;
          if (cb.checked) {
            if (!activeFilters[filterType]) activeFilters[filterType] = [];
            activeFilters[filterType].push(cb.value);
          }
        });
        return activeFilters;
      };

      // Filter file items: each item's data attribute may contain multiple values.
      const filterFiles = () => {
        // Get the tbody element inside the container.
        const tbody = container.querySelector('tbody');

        // Fade out the tbody.
        tbody.style.transition = 'opacity 0.5s';
        tbody.style.opacity = 0;

        setTimeout(() => {
          // Update the display of each file item based on the active filters.
          const activeFilters = getActiveFilters();
          fileItems.forEach((item) => {
            let show = true;
            for (const filterType in activeFilters) {
              const dataValue = item.dataset[filterType] || '';
              const itemValues = dataValue
                .split(',')
                .map((v) => v.trim())
                .filter((v) => v !== '');
              const intersection = activeFilters[filterType].filter((val) => itemValues.includes(val));
              if (activeFilters[filterType].length && intersection.length === 0) {
                show = false;
                break;
              }
            }
            if (show) {
              item.classList.remove('filtered');
            } else {
              item.classList.add('filtered');
            }
          });
          updateShowAllVisibility();

          // Fade the tbody back in.
          tbody.style.opacity = 1;
        }, 200); // Wait 0.2 seconds for the fade-out transition.
      };

      // Update the URL query parameters with comma-separated filter values.
      const updateURL = () => {
        const activeFilters = getActiveFilters();
        const params = new URLSearchParams();
        for (const filterType in activeFilters) {
          params.set(filterType, activeFilters[filterType].join(','));
        }
        let queryString = params.toString().replace(/%2C/gi, ',');
        const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
        window.history.replaceState({}, '', newUrl);
      };

      // Initialize filter checkboxes from the URL query parameters.
      const initializeFiltersFromURL = () => {
        const params = new URLSearchParams(window.location.search);
        checkboxes.forEach((cb) => {
          const filterType = cb.dataset.filter;
          const valueParam = params.get(filterType);
          if (valueParam) {
            const values = valueParam
              .split(',')
              .map((v) => v.trim())
              .filter((v) => v !== '');
            if (values.includes(cb.value)) {
              cb.checked = true;
            }
          }
        });
        filterFiles();
      };

      const updateShowAllVisibility = () => {
        const totalSelected = checkboxes.filter((cb) => cb.checked).length;
        if (showAllButton) {
          showAllButton.classList.toggle('hidden', totalSelected === 0);
        }
        // Toggle the "filtered" class on the container (.file-list element)
        container.classList.toggle('filtered', totalSelected > 0);
      };

      const resetFilters = () => {
        checkboxes.forEach((cb) => (cb.checked = false));
        toggleDropdown(false);
        updateURL();
        filterFiles();
      };

      if (filterButton) {
        filterButton.addEventListener('click', (e) => {
          e.stopPropagation();
          const open = filterOptions.classList.contains('hidden');
          toggleDropdown(open);
          if (open && checkboxes.length > 0) {
            // Set focus to the first checkbox when opening the dropdown.
            checkboxes[0].focus();
          }
        });
      }

      checkboxes.forEach((cb) => {
        cb.addEventListener('change', () => {
          updateURL();
          filterFiles();
        });
      });

      showAllButton.addEventListener('click', resetFilters);

      document.addEventListener('click', (e) => {
        if (!container.contains(e.target)) {
          toggleDropdown(false);
        }
      });

      initializeFiltersFromURL();
    });
  }
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready(initFileList);

export { initFileList };