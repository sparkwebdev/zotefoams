/**
 * Section List Component
 * Gallery section filtering functionality
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initSectionList() {
  const sectionListElements = document.querySelectorAll('[data-component="section-list"]');

  if (sectionListElements.length > 0) {
    sectionListElements.forEach(function (article) {
      const filterButton = article.querySelector('#filter-toggle');
      const filterOptions = article.querySelector('#filter-options');
      const checkboxes = [...article.querySelectorAll('.filter-options__checkbox')];
      const showAllButton = article.querySelector('#section-list-show-all');
      const sectionItems = [...article.querySelectorAll('.section-list__item')];
      const toggleDropdown = (show) => {
        filterOptions.classList.toggle('hidden', !show);
        filterButton.classList.toggle('open', show);
      };

      const updateShowAllVisibility = () => {
        const selectedCount = checkboxes.filter((cb) => cb.checked).length;
        if (showAllButton) {
          showAllButton.classList.toggle('hidden', selectedCount === 0 || selectedCount === checkboxes.length);
        }
      };

      const filterSections = () => {
        // Assume that sectionItems share the same parent container.
        const sectionContainer = sectionItems.length > 0 ? sectionItems[0].parentNode : null;
        if (!sectionContainer) return; // Exit if no container found.

        // Fade out the container.
        sectionContainer.style.transition = 'opacity 0.5s';
        sectionContainer.style.opacity = 0;

        setTimeout(() => {
          // Get the selected labels from the checkboxes.
          const selectedLabels = checkboxes.filter((cb) => cb.checked).map((cb) => cb.value);

          // Update each section item's display based on the selected labels.
          sectionItems.forEach((item) => {
            item.style.display = selectedLabels.length === 0 || selectedLabels.includes(item.dataset.galleryLabel) ? 'block' : 'none';
          });
          updateShowAllVisibility();

          // Fade the container back in.
          sectionContainer.style.opacity = 1;
        }, 200); // Wait 0.5 seconds to match the fade-out transition.
      };

      const resetFilters = () => {
        sectionItems.forEach((item) => (item.style.display = 'block'));
        checkboxes.forEach((cb) => (cb.checked = false));
        toggleDropdown(false);
        updateShowAllVisibility();
      };

      if (filterButton) {
        filterButton.addEventListener('click', (e) => {
          e.stopPropagation();
          toggleDropdown(filterOptions.classList.contains('hidden'));
        });
      }

      checkboxes.forEach((checkbox) => checkbox.addEventListener('change', filterSections));
      if (showAllButton) {
        showAllButton.addEventListener('click', resetFilters);
      }

      const dropdown = article.querySelector('.file-list__dropdown');
      document.addEventListener('click', (e) => {
        if (!dropdown.contains(e.target)) {
          toggleDropdown(false);
        }
      });

      updateShowAllVisibility();
    });
  }
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready(initSectionList);

export { initSectionList };