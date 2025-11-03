/**
 * Locations Map Component
 * Handles interactive popups for location markers with touch/desktop support
 */
import { ZotefoamsDOMUtils, ZotefoamsEventUtils, ZotefoamsDeviceUtils, ZotefoamsAnimationUtils, ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function showPopup( sender ) {
	// Hide all other popups
	const allPopups = ZotefoamsDOMUtils.selectAll( '.locations-map__popup' );
	allPopups.forEach( ( popup ) => {
		popup.style.display = 'none';
		ZotefoamsAnimationUtils.fadeOut( popup );
	} );

	const popup = sender.querySelector( '.locations-map__popup' );
	if ( popup ) {
		if ( CSS.supports( 'position', 'anchor' ) ) {
			popup.style.position = 'anchor';
			sender.style.anchorName = '--popup-anchor';
		} else {
			popup.style.display = 'block';
			popup.style.position = 'absolute';
			popup.style.top = '100%';
			popup.style.left = '50%';
			popup.style.transform = 'translateX(-50%)';
		}
		ZotefoamsAnimationUtils.fadeIn( popup );
	}
}

function hideAllPopups() {
	const allPopups = ZotefoamsDOMUtils.selectAll( '.locations-map__popup' );
	allPopups.forEach( ( popup ) => {
		popup.style.display = 'none';
		ZotefoamsAnimationUtils.fadeOut( popup );
	} );
}

function initLocationsMap() {
	const locations = ZotefoamsDOMUtils.selectAll( '.locations-map__location' );

	locations.forEach( ( location ) => {
		const popup = location.querySelector( '.locations-map__popup' );

		if ( ! popup ) {
			return;
		}

		// 🖱 Desktop: Hover interaction
		if ( ! ZotefoamsDeviceUtils.isTouchDevice() ) {
			ZotefoamsEventUtils.on( location, 'mouseenter', () => showPopup( location ) );
			ZotefoamsEventUtils.on( location, 'mouseleave', hideAllPopups );
		}
		// 👆 Mobile: Tap interaction
		else {
			ZotefoamsEventUtils.on( location, 'click', ( e ) => {
				e.stopPropagation();
				showPopup( location );
			} );
		}

		// Prevent popup click bubbling so it doesn't auto-close
		ZotefoamsEventUtils.on( popup, 'click', ( e ) => e.stopPropagation() );
	} );

	// 📲 Close popup when tapping outside (mobile)
	ZotefoamsEventUtils.on( document, 'click', ( e ) => {
		if ( ! e.target.closest( '.locations-map__location' ) ) {
			hideAllPopups();
		}
	} );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initLocationsMap );

export { initLocationsMap, showPopup, hideAllPopups };
