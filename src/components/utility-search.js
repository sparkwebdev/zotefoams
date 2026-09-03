/**
 * Utility Search Component
 * Enhanced search functionality in the utility menu with accessibility
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';
import { updateHeaderHeight } from '../utils/site-utilities.js';

const FOCUS_DELAY_MS = 100;
const SCROLL_RESTORE_DELAY_MS = 0;

function initUtilitySearch() {
	const menu = document.querySelector( '#menu-utility' );
	let searchItem = menu?.querySelector( 'a[href="/search"]' );

	if ( ! menu || ! searchItem ) {
		return;
	}

	// Add menu-item-has-children to the parent <li>
	const searchItemParent = searchItem.parentElement;
	searchItemParent?.classList.add( 'menu-item-has-children' );

	const searchButton = document.createElement( 'button' );
	searchButton.type = 'button';
	searchButton.className = searchItem.className;
	searchButton.textContent = searchItem.textContent;
	searchButton.setAttribute( 'aria-expanded', 'false' );
	searchButton.setAttribute( 'aria-controls', 'utility-search-form' );
	searchItem.replaceWith( searchButton );
	searchItem = searchButton;

	// Create and inject the search container
	const searchContainer = document.createElement( 'div' );
	searchContainer.className = 'utility-search';
	searchContainer.id = 'utility-search-form';
	searchContainer.setAttribute( 'hidden', '' );

	searchContainer.innerHTML = `
    <form role="search" aria-label="Site search form" action="/">
      <input type="text" name="s" placeholder="Search..." aria-label="Search input" required />
      <button type="submit" class="btn outline white">Go</button>
      <button type="button" class="btn outline white" aria-label="Close search form">✕</button>
    </form>
  `;

	menu.after( searchContainer );

	const form = searchContainer.querySelector( 'form' );
	const input = form.querySelector( 'input[type="text"]' );
	const closeButton = form.querySelector( 'button[type="button"]' );
	const nextMenuItem = searchItem.closest( 'li' )?.nextElementSibling?.querySelector( 'a' );

	const openSearch = ( focusInput = false ) => {
		searchContainer.removeAttribute( 'hidden' );
		searchContainer.classList.add( 'is-visible' );
		searchItem.setAttribute( 'aria-expanded', 'true' );

		// Focus the input if requested (for keyboard activation)
		if ( focusInput && input ) {
			setTimeout( () => {
				input.focus();
			}, FOCUS_DELAY_MS );
		}
	};

	// Prevent scroll on input
	input?.addEventListener( 'input', ( e ) => {
		e.preventDefault();
		window.scrollTo( window.scrollX, window.scrollY );
	} );

	input?.addEventListener( 'keydown', () => {
		// Store current scroll position
		const currentScrollY = window.scrollY;
		setTimeout( () => {
			window.scrollTo( window.scrollX, currentScrollY );
		}, SCROLL_RESTORE_DELAY_MS );
	} );

	const closeSearch = () => {
		searchContainer.classList.remove( 'is-visible' );
		searchContainer.setAttribute( 'hidden', '' );
		searchItem.setAttribute( 'aria-expanded', 'false' );
	};

	// Recalculate header height once the slide animation finishes
	searchContainer.addEventListener( 'transitionend', ( e ) => {
		if ( e.target === searchContainer ) {
			updateHeaderHeight();
		}
	} );

	const toggleSearch = ( focusInput = false ) => {
		const isHidden = searchContainer.hasAttribute( 'hidden' );
		if ( isHidden ) {
			openSearch( focusInput );
		} else {
			closeSearch();
		}
	};

	// Click to toggle. A native <button> already fires `click` for mouse,
	// touch, and keyboard (Enter/Space) activation, so this one listener
	// replaces the old separate click + keydown handlers. `event.detail`
	// is 0 for a keyboard/AT-triggered click (vs the click count for a
	// real pointer click), which lets us keep auto-focusing the input
	// only when the toggle was activated via keyboard.
	searchItem.addEventListener( 'click', ( e ) => {
		toggleSearch( e.detail === 0 );
	} );

	// Escape closes
	window.addEventListener( 'keydown', ( e ) => {
		if ( e.key === 'Escape' && searchContainer.classList.contains( 'is-visible' ) ) {
			e.preventDefault();
			closeSearch();
			searchItem.focus();
		}
	} );

	// Close button click - clear input and close
	closeButton?.addEventListener( 'click', () => {
		if ( input ) {
			input.value = ''; // Clear the search input
		}
		closeSearch();
		searchItem.focus();
	} );

	// Focus trap exit logic
	form.addEventListener( 'keydown', ( e ) => {
		if ( e.key !== 'Tab' ) {
			return;
		}

		const focusable = Array.from(
			form.querySelectorAll( 'input, button:not([disabled]), [tabindex]:not([tabindex="-1"])' )
		).filter( ( el ) => ! el.hasAttribute( 'hidden' ) );

		const first = focusable[ 0 ];
		const last = focusable[ focusable.length - 1 ];

		if ( e.shiftKey && document.activeElement === first ) {
			// Shift+Tab on first element → close and focus "Search"
			e.preventDefault();
			closeSearch();
			searchItem.focus();
		} else if ( ! e.shiftKey && document.activeElement === last ) {
			// Tab on last element → close, then move focus to the next menu
			// item if there is one. If this is the last item in the menu
			// there's nothing to manually focus, so don't preventDefault —
			// previously that always ran, stranding focus with nowhere to
			// go when nextMenuItem was undefined.
			closeSearch();
			if ( nextMenuItem ) {
				e.preventDefault();
				nextMenuItem.focus();
			}
		}
	} );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initUtilitySearch );

export { initUtilitySearch };
