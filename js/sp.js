/* Component Init - File List Enhanced with Multiple Filters (Comma-Separated) and Keyboard Support */
document.addEventListener('DOMContentLoaded', function () {
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
				const KEY_UP = 38, KEY_DOWN = 40, KEY_ESCAPE = 27, KEY_TAB = 9;
				if (e.keyCode === KEY_DOWN) {
					e.preventDefault();
					let currentIndex = checkboxes.findIndex(cb => cb === document.activeElement);
					if (currentIndex === -1 || currentIndex === checkboxes.length - 1) {
						checkboxes[0].focus();
					} else {
						checkboxes[currentIndex + 1].focus();
					}
				} else if (e.keyCode === KEY_UP) {
					e.preventDefault();
					let currentIndex = checkboxes.findIndex(cb => cb === document.activeElement);
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
				checkboxes.forEach(cb => {
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
				const activeFilters = getActiveFilters();
				fileItems.forEach(item => {
					let show = true;
					for (const filterType in activeFilters) {
						const dataValue = item.dataset[filterType] || '';
						const itemValues = dataValue.split(',').map(v => v.trim()).filter(v => v !== '');
						const intersection = activeFilters[filterType].filter(val => itemValues.includes(val));
						if (activeFilters[filterType].length && intersection.length === 0) {
							show = false;
							break;
						}
					}
					item.style.display = show ? 'table-row' : 'none';
				});
				updateShowAllVisibility();
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
				checkboxes.forEach(cb => {
					const filterType = cb.dataset.filter;
					const valueParam = params.get(filterType);
					if (valueParam) {
						const values = valueParam.split(',').map(v => v.trim()).filter(v => v !== '');
						if (values.includes(cb.value)) {
							cb.checked = true;
						}
					}
				});
				filterFiles();
			};

			const updateShowAllVisibility = () => {
				const totalSelected = checkboxes.filter(cb => cb.checked).length;
				showAllButton.classList.toggle('hidden', totalSelected === 0);
			};

			const resetFilters = () => {
				checkboxes.forEach(cb => (cb.checked = false));
				toggleDropdown(false);
				updateURL();
				filterFiles();
			};

			filterButton.addEventListener('click', (e) => {
				e.stopPropagation();
				const open = filterOptions.classList.contains('hidden');
				toggleDropdown(open);
				if (open && checkboxes.length > 0) {
					// Set focus to the first checkbox when opening the dropdown.
					checkboxes[0].focus();
				}
			});

			checkboxes.forEach(cb => {
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


	// Add a click event listener to all elements with the data-clickable-url attribute
	document.querySelectorAll( '[data-clickable-url]' ).forEach( function( article ) {
		const url = article.getAttribute( 'data-clickable-url' );
		if ( url ) {
			const matchingChild = article.querySelector( '[href="' + url + '"]' );
			if ( matchingChild ) {
				article.addEventListener( 'click', function() {
					matchingChild.click();
				} );
			}
		}
	} );


	/* Component Init - Section List */
	const sectionListElements = document.querySelectorAll( '[data-component="section-list"]' );

	if ( sectionListElements.length > 0 ) {
		sectionListElements.forEach( function( article ) {
			const filterButton = article.querySelector( '#filter-toggle' );
			const filterOptions = article.querySelector( '#filter-options' );
			const checkboxes = [ ...article.querySelectorAll( '.filter-options__checkbox' ) ];
			const showAllButton = article.querySelector( '#section-list-show-all' );
			const sectionItems = [ ...article.querySelectorAll( '.section-list__item' ) ];
			const toggleDropdown = ( show ) => {
				filterOptions.classList.toggle( 'hidden', ! show );
				filterButton.classList.toggle( 'open', show );
			};

			const updateShowAllVisibility = () => {
				const selectedCount = checkboxes.filter( ( cb ) => cb.checked ).length;
				showAllButton.classList.toggle( 'hidden', selectedCount === 0 || selectedCount === checkboxes.length );
			};

			const filterSections = () => {
				const selectedLabels = checkboxes.filter( ( cb ) => cb.checked ).map( ( cb ) => cb.value );
				sectionItems.forEach( ( item ) => {
					item.style.display = selectedLabels.length === 0 || selectedLabels.includes( item.dataset.galleryLabel )
						? 'block'
						: 'none';
				} );
				updateShowAllVisibility();
			};

			const resetFilters = () => {
				sectionItems.forEach( ( item ) => ( item.style.display = 'block' ) );
				checkboxes.forEach( ( cb ) => ( cb.checked = false ) );
				toggleDropdown( false );
				updateShowAllVisibility();
			};

			filterButton.addEventListener( 'click', ( e ) => {
				e.stopPropagation();
				toggleDropdown( filterOptions.classList.contains( 'hidden' ) );
			} );

			checkboxes.forEach( ( checkbox ) => checkbox.addEventListener( 'change', filterSections ) );
			showAllButton.addEventListener( 'click', resetFilters );

			document.addEventListener( 'click', ( e ) => {
				if ( ! article.contains( e.target ) ) {
					toggleDropdown( false );
				}
			} );

			updateShowAllVisibility();
		} );
	}
} );



// JavaScript to handle the video overlay
document.addEventListener( 'DOMContentLoaded', function() {
	const overlay = document.getElementById( 'video-overlay' );
	const iframe = document.getElementById( 'video-iframe' );
	const closeBtn = document.getElementById( 'close-video' );

	// Open overlay when the link is clicked
	const links = document.querySelectorAll( '.open-video-overlay' );
	links.forEach( function( link ) {
		link.addEventListener( 'click', function( e ) {
			e.preventDefault();
			const videoUrl = this.href;
			const videoId = videoUrl.split( 'v=' )[ 1 ].split( '&' )[ 0 ]; // Get the video ID from the URL
			iframe.src = 'https://www.youtube.com/embed/' + videoId + '?autoplay=1'; // Embed the video
			overlay.style.display = 'flex'; // Show the overlay
		} );
	} );

	// Close the overlay when clicking the close button
	if ( closeBtn ) {
		closeBtn.addEventListener( 'click', function() {
			overlay.style.display = 'none';
			iframe.src = ''; // Stop the video
		} );
	}

	// Close overlay when clicking outside the video (on the overlay background)
	overlay.addEventListener( 'click', function( e ) {
		if ( e.target === overlay ) {
			overlay.style.display = 'none';
			iframe.src = ''; // Stop the video
		}
	} );

	// Close overlay when pressing the Escape key
	document.addEventListener( 'keydown', function( e ) {
		if ( e.key === 'Escape' ) {
			overlay.style.display = 'none';
			iframe.src = ''; // Stop the video
		}
	} );
} );

if ( document.querySelector( '.overlay' ) ) {
	document.querySelector( '.overlay' ).classList.add( 'fade-in' );
}

document.addEventListener( 'DOMContentLoaded', function() {
// Accordion
	const headers = document.querySelectorAll( '.accordion-header' );

	// Add click event listener to each header
	headers.forEach( ( header ) => {
		header.addEventListener( 'click', function() {
			const content = this.nextElementSibling; // The next sibling is the content
			const icon = this.querySelector( '.toggle-icon' ); // Get the plus/minus icon

			// Close all other accordion sections
			headers.forEach( ( otherHeader ) => {
				if ( otherHeader !== this ) {
					const otherContent = otherHeader.nextElementSibling;
					const otherIcon = otherHeader.querySelector( '.toggle-icon' );
					otherContent.style.display = 'none';
					otherContent.style.opacity = '0';
					otherContent.style.maxHeight = '0';
					otherIcon.textContent = '+'; // Reset icon to plus
					otherHeader.classList.remove( 'open' ); // Remove 'open' class
				}
			} );

			// Toggle the display of the clicked content and icon
			if ( content.style.display === 'block' ) {
				content.style.display = 'none';
				content.style.opacity = '0';
				content.style.maxHeight = '0';
				icon.textContent = '+'; // Change the icon to plus
				this.classList.remove( 'open' );
			} else {
				content.style.display = 'block';
				content.style.opacity = '1';
				content.style.maxHeight = '1000px'; // Set a maximum height for the transition
				icon.textContent = '-'; // Change the icon to minus
				this.classList.add( 'open' );
				content.scrollIntoView( {
					behavior: 'smooth',
					block: 'start',
				} );
			}
		} );
	} );
} );
