
document.addEventListener( 'DOMContentLoaded', function() {
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

	/* Component Init - File List */
	const fileListElements = document.querySelectorAll( '[data-component="file-list"]' );

	if ( fileListElements.length > 0 ) {
		fileListElements.forEach( function( article ) {
			const filterButton = article.querySelector( '#filter-toggle' );
			const filterOptions = article.querySelector( '#filter-options' );
			const checkboxes = [ ...article.querySelectorAll( '.filter-options__checkbox' ) ];
			const showAllButton = article.querySelector( '#file-list-show-all' );
			const fileItems = [ ...article.querySelectorAll( '.file-list__item' ) ];

			const toggleDropdown = ( show ) => {
				filterOptions.classList.toggle( 'hidden', ! show );
				filterButton.classList.toggle( 'open', show );
			};

			const updateShowAllVisibility = () => {
				const selectedCount = checkboxes.filter( ( cb ) => cb.checked ).length;
				showAllButton.classList.toggle( 'hidden', selectedCount === 0 || selectedCount === checkboxes.length );
			};

			const filterFiles = () => {
				const selectedLabels = checkboxes.filter( ( cb ) => cb.checked ).map( ( cb ) => cb.value );
				fileItems.forEach( ( item ) => {
					item.style.display = selectedLabels.length === 0 || selectedLabels.includes( item.dataset.galleryLabel )
						? 'table-row'
						: 'none';
				} );
				updateShowAllVisibility();
			};

			const resetFilters = () => {
				fileItems.forEach( ( item ) => ( item.style.display = 'table-row' ) );
				checkboxes.forEach( ( cb ) => ( cb.checked = false ) );
				toggleDropdown( false );
				updateShowAllVisibility();
			};

			filterButton.addEventListener( 'click', ( e ) => {
				e.stopPropagation();
				toggleDropdown( filterOptions.classList.contains( 'hidden' ) );
			} );

			checkboxes.forEach( ( checkbox ) => checkbox.addEventListener( 'change', filterFiles ) );
			showAllButton.addEventListener( 'click', resetFilters );

			document.addEventListener( 'click', ( e ) => {
				if ( ! article.contains( e.target ) ) {
					toggleDropdown( false );
				}
			} );

			updateShowAllVisibility();
		} );
	}
	/* Component Init - File List */
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
