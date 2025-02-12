
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

	
	
	// Image Banner Swiper
	const swiperImage = new Swiper('.swiper-image', {
		direction: 'horizontal',
		loop: true,
		speed: 400,
		autoplay: {
			delay: 3000,
			disableOnInteraction: false,
		},
		navigation: {
			nextEl: '.swiper-button-next-image',
		},
		on: {
			init: function () {
				updateNextButtonTitle(this, '.swiper-button-next-image p span');
			},
			slideChangeTransitionStart : function (e) {
				updateNextButtonTitle(e, '.swiper-button-next-image p span');
				resetProgressAnimation('.circle-progress-image');
			},
		},
	});

	function updateNextButtonTitle(swiperInstance, selector) {
		const nextSlide = getNextSlide(swiperInstance);
		if (nextSlide) {
			const nextTitle = nextSlide.getAttribute('data-title');
			const nextButtonText = document.querySelector(selector);
			if (nextButtonText && nextTitle) {
				nextButtonText.textContent = nextTitle;
			}
		}
	}

	function getNextSlide(swiperInstance) {
		for	(let i = 0; i < swiperInstance.slides.length; i++) {
			if (swiperInstance.slides[i].classList.contains('swiper-slide-next')) {
				return swiperInstance.slides[i];
			}
		}
		return null;
	}

	function resetProgressAnimation(circleSelector) {
		const progressCircle = document.querySelector(circleSelector);
		if (progressCircle) {
			progressCircle.style.transition = 'none';
			progressCircle.style.strokeDashoffset = 144.513;
			setTimeout(() => {
				progressCircle.style.transition = 'stroke-dashoffset 3s linear';
				progressCircle.style.strokeDashoffset = 0;
			}, 50);
		}
	}
	
	
	
  /* Component Init - File List */
  const fileListElements = document.querySelectorAll('[data-component="file-list"]');

    if (fileListElements.length > 0) {

        fileListElements.forEach(function(article) {

            const filterButton = article.querySelector('#filter-toggle');
            const filterOptions = article.querySelector('#filter-options');
            const checkboxes = [...article.querySelectorAll('.filter-options__checkbox')];
            const showAllButton = article.querySelector('#file-list-show-all');
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
            console.log(article);

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

