
document.addEventListener('DOMContentLoaded', function() {
  // Add a click event listener to all elements with the data-clickable-url attribute
  document.querySelectorAll('[data-clickable-url]').forEach(function(article) {
      var url = article.getAttribute('data-clickable-url');
      if (url) {
          article.addEventListener('click', function() {
              window.location.href = url;
          });
      }
  });

  /* Component Init - File List */
  const fileListElements = document.querySelectorAll('[data-component="file-list"]');

    if (fileListElements.length > 0) {

        fileListElements.forEach(function(article) {

            const filterButton = document.getElementById('filter-toggle');
            const filterOptions = document.getElementById('filter-options');
            const checkboxes = [...article.querySelectorAll('.filter-options__checkbox')];
            const showAllButton = document.getElementById('file-list-show-all');
            const fileItems = [...article.querySelectorAll('.file-list__item')];
        
            const toggleDropdown = (show) => {
                filterOptions.classList.toggle('hidden', !show);
                filterButton.classList.toggle('open', show);
            };
        
            const updateShowAllVisibility = () => {
                const selectedCount = checkboxes.filter(cb => cb.checked).length;
                showAllButton.classList.toggle('hidden', selectedCount === 0 || selectedCount === checkboxes.length);
            };
        
            const filterFiles = () => {
                const selectedLabels = checkboxes.filter(cb => cb.checked).map(cb => cb.value);
                fileItems.forEach(item => {
                    item.style.display = selectedLabels.length === 0 || selectedLabels.includes(item.dataset.galleryLabel)
                        ? 'table-row'
                        : 'none';
                });
                updateShowAllVisibility();
            };
        
            const resetFilters = () => {
                fileItems.forEach(item => (item.style.display = 'table-row'));
                checkboxes.forEach(cb => (cb.checked = false));
                toggleDropdown(false);
                updateShowAllVisibility();
            };
        
            filterButton.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleDropdown(filterOptions.classList.contains('hidden'));
            });
        
            checkboxes.forEach(checkbox => checkbox.addEventListener('change', filterFiles));
            showAllButton.addEventListener('click', resetFilters);
        
            document.addEventListener('click', (e) => {
                if (!article.contains(e.target)) {
                    toggleDropdown(false);
                }
            });
            
        
            updateShowAllVisibility();
        });
    }
    /* Component Init - File List */
    const sectionListElements = document.querySelectorAll('[data-component="section-list"]');
  
    if (sectionListElements.length > 0) {

        sectionListElements.forEach(function(article) {

            const filterButton = document.getElementById('filter-toggle');
            const filterOptions = document.getElementById('filter-options');
            const checkboxes = [...article.querySelectorAll('.filter-options__checkbox')];
            const showAllButton = document.getElementById('section-list-show-all');
            const sectionItems = [...article.querySelectorAll('.section-list__item')];
            const toggleDropdown = (show) => {
                filterOptions.classList.toggle('hidden', !show);
                filterButton.classList.toggle('open', show);
            };
        
            const updateShowAllVisibility = () => {
                const selectedCount = checkboxes.filter(cb => cb.checked).length;
                showAllButton.classList.toggle('hidden', selectedCount === 0 || selectedCount === checkboxes.length);
            };
        
            const filterSections = () => {
                const selectedLabels = checkboxes.filter(cb => cb.checked).map(cb => cb.value);
                sectionItems.forEach(item => {
                    item.style.display = selectedLabels.length === 0 || selectedLabels.includes(item.dataset.galleryLabel)
                        ? 'block'
                        : 'none';
                });
                updateShowAllVisibility();
            };
        
            const resetFilters = () => {
                sectionItems.forEach(item => (item.style.display = 'block'));
                checkboxes.forEach(cb => (cb.checked = false));
                toggleDropdown(false);
                updateShowAllVisibility();
            };
        
            filterButton.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleDropdown(filterOptions.classList.contains('hidden'));
            });
        
            checkboxes.forEach(checkbox => checkbox.addEventListener('change', filterSections));
            showAllButton.addEventListener('click', resetFilters);
        
            document.addEventListener('click', (e) => {
                if (!article.contains(e.target)) {
                    toggleDropdown(false);
                }
            });
            
        
            updateShowAllVisibility();
        });
    }
});

