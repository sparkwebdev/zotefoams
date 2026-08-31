/**
 * Tabbed Split Component
 * Handles tab switching functionality with content panels
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initTabbedSplit() {
	document.querySelectorAll( '[data-js="tabs-container"]' ).forEach( ( tabsContainer ) => {
		const contentContainer = tabsContainer.nextElementSibling;
		if ( ! contentContainer?.matches( '[data-js="content-container"]' ) ) { return; }

		const tabs = [ ...tabsContainer.querySelectorAll( '[data-js="tab"]' ) ];
		const tabContents = contentContainer.querySelectorAll( '[data-js="tab-content"]' );

		const activateTab = ( tab ) => {
			const targetId = tab.dataset.tab;

			tabs.forEach( ( t ) => {
				const isActive = t === tab;
				t.classList.toggle( 'active', isActive );
				t.setAttribute( 'aria-selected', isActive ? 'true' : 'false' );
				t.setAttribute( 'tabindex', isActive ? '0' : '-1' );
			} );

			tabContents.forEach( ( tc ) => {
				const isActive = tc.id === targetId;
				tc.classList.toggle( 'active', isActive );
				tc.setAttribute( 'aria-hidden', isActive ? 'false' : 'true' );
			} );
		};

		tabs.forEach( ( tab, index ) => {
			tab.addEventListener( 'click', () => activateTab( tab ) );

			tab.addEventListener( 'keydown', ( e ) => {
				let newIndex;

				switch ( e.key ) {
					case 'ArrowRight':
						e.preventDefault();
						newIndex = ( index + 1 ) % tabs.length;
						break;
					case 'ArrowLeft':
						e.preventDefault();
						newIndex = ( index - 1 + tabs.length ) % tabs.length;
						break;
					case 'Home':
						e.preventDefault();
						newIndex = 0;
						break;
					case 'End':
						e.preventDefault();
						newIndex = tabs.length - 1;
						break;
					default:
						return;
				}

				tabs[ newIndex ].focus();
				activateTab( tabs[ newIndex ] );
			} );
		} );
	} );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initTabbedSplit );

export { initTabbedSplit };
