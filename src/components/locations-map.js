/**
 * Locations Map Component
 * Handles interactive popups for location markers with touch/desktop support
 */
import { ZotefoamsDOMUtils, ZotefoamsEventUtils, ZotefoamsDeviceUtils, ZotefoamsAnimationUtils, ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

// Grace period so moving the pointer from marker to popup doesn't close it mid-move.
const HOVER_CLOSE_DELAY_MS = 150;

let hideTimeout = null;

function closePopup( trigger ) {
	trigger.setAttribute( 'aria-expanded', 'false' );
	const popup = trigger.querySelector( '.locations-map__popup' );
	if ( popup ) {
		popup.style.display = 'none';
		ZotefoamsAnimationUtils.fadeOut( popup );
	}
}

function hideAllPopups() {
	ZotefoamsDOMUtils.selectAll( '.locations-map__location[aria-expanded]' ).forEach( closePopup );
}

function showPopup( sender ) {
	clearTimeout( hideTimeout );

	ZotefoamsDOMUtils.selectAll( '.locations-map__location[aria-expanded="true"]' ).forEach( ( other ) => {
		if ( other !== sender ) {
			closePopup( other );
		}
	} );

	const popup = sender.querySelector( '.locations-map__popup' );
	if ( ! popup ) {
		return;
	}

	sender.setAttribute( 'aria-expanded', 'true' );

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

function scheduleHide() {
	clearTimeout( hideTimeout );
	hideTimeout = setTimeout( hideAllPopups, HOVER_CLOSE_DELAY_MS );
}

function cancelScheduledHide() {
	clearTimeout( hideTimeout );
}

function initLocationsMap() {
	const locations = ZotefoamsDOMUtils.selectAll( '.locations-map__location[aria-expanded]' );

	locations.forEach( ( location ) => {
		const popup = location.querySelector( '.locations-map__popup' );

		if ( ! popup ) {
			return;
		}

		// 🖱 Desktop: Hover interaction
		if ( ! ZotefoamsDeviceUtils.isTouchDevice() ) {
			ZotefoamsEventUtils.on( location, 'mouseenter', () => showPopup( location ) );
			ZotefoamsEventUtils.on( location, 'mouseleave', scheduleHide );
			ZotefoamsEventUtils.on( popup, 'mouseenter', cancelScheduledHide );
			ZotefoamsEventUtils.on( popup, 'mouseleave', scheduleHide );
		}
		// 👆 Mobile: Tap interaction
		else {
			ZotefoamsEventUtils.on( location, 'click', ( e ) => {
				e.stopPropagation();
				if ( location.getAttribute( 'aria-expanded' ) === 'true' ) {
					closePopup( location );
				} else {
					showPopup( location );
				}
			} );
		}

		ZotefoamsEventUtils.on( location, 'focus', () => showPopup( location ) );
		ZotefoamsEventUtils.on( location, 'blur', ( e ) => {
			if ( popup.contains( e.relatedTarget ) ) {
				return;
			}
			closePopup( location );
		} );

		ZotefoamsEventUtils.on( location, 'keydown', ( e ) => {
			if ( e.key === 'Escape' && location.getAttribute( 'aria-expanded' ) === 'true' ) {
				closePopup( location );
				location.focus();
			}
		} );

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
