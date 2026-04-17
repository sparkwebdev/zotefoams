/**
 * Tabbed Split Component
 * Handles tab switching functionality with content panels
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initTabbedSplit() {
	document.querySelectorAll( '.tabs-container' ).forEach( ( tabsContainer ) => {
		const contentContainer = tabsContainer.nextElementSibling;
		if ( ! contentContainer?.classList.contains( 'content-container' ) ) {return;}

		const tabs = tabsContainer.querySelectorAll( '.tab' );
		const tabContents = contentContainer.querySelectorAll( '.tab-content' );

		tabs.forEach( ( tab ) => {
			tab.addEventListener( 'click', function() {
				// Scope active state to this instance only
				tabs.forEach( ( t ) => t.classList.remove( 'active' ) );
				tabContents.forEach( ( tc ) => tc.classList.remove( 'active' ) );

				this.classList.add( 'active' );
				const target = contentContainer.querySelector( `#${ this.dataset.tab }` );
				if ( target ) {target.classList.add( 'active' );}
			} );
		} );
	} );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initTabbedSplit );

export { initTabbedSplit };
