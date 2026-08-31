/**
 * Navigation Keyboard Handlers - Accessibility support for keyboard navigation
 * @version 1.3 - Removed updateMegaContainer (container deleted)
 */

import { ZotefoamsAccessibilityUtils } from '../utils/dom-utilities.js';

// Re-export utilities for convenience
export const { getFocusableElements, setAriaExpanded } = ZotefoamsAccessibilityUtils;

/**
 * Handle keyboard navigation within mega menus (Tab, Escape)
 * @param {KeyboardEvent} e           - Keyboard event
 * @param {HTMLElement}   megaMenu    - Mega menu element
 * @param {HTMLElement}   triggerLink - Link that triggers the menu
 */
export const handleMegaMenuKeyboard = ( e, megaMenu, triggerLink ) => {
	if ( e.key === 'Escape' ) {
		e.preventDefault();
		megaMenu.setAttribute( 'aria-hidden', 'true' );
		setAriaExpanded( triggerLink, false );
		triggerLink.focus();
		return;
	}

	if ( e.key === 'Tab' ) {
		const focusable = getFocusableElements( megaMenu );
		const currentIndex = focusable.indexOf( document.activeElement );
		// -1 when focus is on the tabindex="-1" intro label — treat as "first" too.
		const isFirst = currentIndex <= 0;
		const isLast = currentIndex === focusable.length - 1;

		// Tab out of menu when reaching boundaries
		if ( ( e.shiftKey && isFirst ) || ( ! e.shiftKey && isLast ) ) {
			e.preventDefault();
			megaMenu.setAttribute( 'aria-hidden', 'true' );
			setAriaExpanded( triggerLink, false );

			// Focus next/previous top-level link
			const topLinks = Array.from( document.querySelectorAll( "[data-js-nav='menu'] > li > a" ) );
			const index = topLinks.indexOf( triggerLink );
			let targetLink;
			if ( e.shiftKey ) {
				targetLink = index > 0 ? topLinks[ index - 1 ] : triggerLink;
			} else {
				targetLink = index < topLinks.length - 1 ? topLinks[ index + 1 ] : triggerLink;
			}
			targetLink.focus();
		}
	}
};

/**
 * Handle keyboard activation for menu items (Enter, Space, ArrowDown)
 * @param {KeyboardEvent} e        - Keyboard event
 * @param {HTMLElement}   link     - Menu item link
 * @param {HTMLElement}   megaMenu - Mega menu element
 * @param {Function}      closeAll - Callback to close all menus
 */
export const handleMenuItemKeyboard = ( e, link, megaMenu, closeAll ) => {
	// Enter follows the link href, Space / ArrowDown open the mega menu.
	if ( e.key === ' ' || e.key === 'ArrowDown' ) {
		e.preventDefault();

		const isOpen = megaMenu.getAttribute( 'aria-hidden' ) === 'false';

		if ( isOpen ) {
			if ( e.key === 'ArrowDown' ) {
				// Move focus into menu
				const firstFocusable = getFocusableElements( megaMenu )[ 0 ];
				if ( firstFocusable ) {
					firstFocusable.focus();
				}
				return;
			}
			megaMenu.setAttribute( 'aria-hidden', 'true' );
			setAriaExpanded( link, false );
		} else {
			// Open menu
			closeAll();
			megaMenu.setAttribute( 'aria-hidden', 'false' );
			setAriaExpanded( link, true );

			// Deliberately a <div>, not <h2> — SEO wants no headings in the mega-nav.
			const introLabel = megaMenu.querySelector( '[data-js="mega-menu-title"]' );
			if ( introLabel ) {
				introLabel.setAttribute( 'tabindex', '-1' );
				setTimeout( () => introLabel.focus(), 200 );
			}
		}
	} else if ( e.key === 'Escape' ) {
		closeAll();
		link.focus();
	}
};
