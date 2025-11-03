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

				// Scroll the .accordion to the top of the page
				const accordion = this.closest( '.accordion' ); // Get the .accordion container
				accordion.scrollIntoView( {
					behavior: 'smooth',
					block: 'start', // Scroll so the .accordion is at the top of the page
				} );
			}
		} );
	} );

	const hash = window.location.hash;
	if ( hash ) {
		const targetItem = document.querySelector( hash );
		if ( targetItem && targetItem.classList.contains( 'accordion-item' ) ) {
			const header = targetItem.querySelector( '.accordion-header' );
			if ( header ) {
				header.click(); // Simulate the user clicking the header
				targetItem.scrollIntoView( { behavior: 'smooth', block: 'start' } );

				// === Remove hash from URL without refreshing the page
				history.replaceState( null, document.title, window.location.pathname + window.location.search );
			}
		}
	}
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initAccordion );

export { initAccordion };
