/**
 * Accordion Component
 * Collapsible content sections with smooth animations and URL hash support
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initAccordion() {
	// Accordion
	const headers = document.querySelectorAll( '.accordion-header' );

	// Add click event listener to each header
	headers.forEach( ( header ) => {
		header.addEventListener( 'click', function() {
			const content = this.nextElementSibling; // The next sibling is the content
			if ( ! content ) {
				return;
			}
			const icon = this.querySelector( '.toggle-icon' ); // Get the plus/minus icon
			const isOpening = content.style.display !== 'block';

			// Close all other accordion sections
			headers.forEach( ( otherHeader ) => {
				if ( otherHeader !== this ) {
					const otherContent = otherHeader.nextElementSibling;
					const otherIcon = otherHeader.querySelector( '.toggle-icon' );
					if ( otherContent ) {
						otherContent.style.display = 'none';
						otherContent.style.opacity = '0';
						otherContent.style.maxHeight = '0';
					}
					if ( otherIcon ) {
						otherIcon.textContent = '+'; // Reset icon to plus
					}
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

				// Scroll the .accordion container into view
				const accordion = this.closest( '.accordion' );
				accordion.scrollIntoView( {
					behavior: 'smooth',
					block: 'start',
				} );
			}

			// Crossfade image for split-accordion-image component
			const splitAccordionImage = this.closest( '.split-accordion-image' );
			if ( splitAccordionImage ) {
				const imageEl = splitAccordionImage.querySelector( '.split-accordion-image__image' );
				if ( imageEl ) {
					const newUrl = isOpening
						? this.dataset.image || ''
						: imageEl.dataset.defaultImage || '';
					if ( ( imageEl.dataset.currentImage || '' ) === newUrl ) {return;}
					imageEl.dataset.currentImage = newUrl;

					const activeLayer = imageEl.dataset.activeLayer === 'b'
						? imageEl.querySelector( '.split-accordion-image__image-layer--b' )
						: imageEl.querySelector( '.split-accordion-image__image-layer--a' );
					const inactiveLayer = imageEl.dataset.activeLayer === 'b'
						? imageEl.querySelector( '.split-accordion-image__image-layer--a' )
						: imageEl.querySelector( '.split-accordion-image__image-layer--b' );

					inactiveLayer.style.backgroundImage = newUrl ? `url('${ newUrl }')` : '';
					inactiveLayer.style.opacity = '1';
					activeLayer.style.opacity = '0';

					imageEl.dataset.activeLayer = imageEl.dataset.activeLayer === 'b' ? 'a' : 'b';
				}
			}
		} );
	} );

	// Preload split-accordion-image item images
	document.querySelectorAll( '.split-accordion-image .accordion-header[data-image]' ).forEach( ( btn ) => {
		const img = new Image();
		img.src = btn.dataset.image;
	} );

	openAccordionFromHash();
	window.addEventListener( 'hashchange', openAccordionFromHash );
}

function openAccordionFromHash() {
	const hash = window.location.hash;
	if ( ! hash ) {
		return;
	}

	const targetItem = document.querySelector( hash );
	if ( targetItem && targetItem.classList.contains( 'accordion-item' ) ) {
		const header = targetItem.querySelector( '.accordion-header' );
		if ( header ) {
			header.click();
			targetItem.scrollIntoView( { behavior: 'smooth', block: 'start' } );

			// Remove hash from URL without refreshing the page
			history.replaceState( null, document.title, window.location.pathname + window.location.search );
		}
	}
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initAccordion );

export { initAccordion };
