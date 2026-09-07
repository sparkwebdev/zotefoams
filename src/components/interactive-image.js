/**
 * Interactive Image Component
 * Handles interactive popups for image points with touch/desktop support
 * Supports both circle and numbered markers
 */
import { ZotefoamsDOMUtils, ZotefoamsEventUtils, ZotefoamsDeviceUtils, ZotefoamsAnimationUtils, ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

const HOVER_CLOSE_DELAY_MS = 150;

let hideTimeout = null;

function positionPopup( sender, popup ) {
	const container = sender.closest( '.interactive-image__container' );
	const containerRect = container.getBoundingClientRect();
	const senderRect = sender.getBoundingClientRect();

	const senderCenterX = senderRect.left + ( senderRect.width / 2 ) - containerRect.left;
	const containerWidth = containerRect.width;

	const isMobile = window.innerWidth <= 768;
	const popupWidth = isMobile ? 200 : 220;
	const edgeBuffer = 20;

	popup.className = 'interactive-image__popup';

	if ( senderCenterX < popupWidth / 2 + edgeBuffer ) {
		popup.classList.add( 'interactive-image__popup--left-aligned' );
	} else if ( senderCenterX > containerWidth - popupWidth / 2 - edgeBuffer ) {
		popup.classList.add( 'interactive-image__popup--right-aligned' );
	} else {
		popup.classList.add( 'interactive-image__popup--center-aligned' );
	}
}

function closePopup( trigger ) {
	trigger.setAttribute( 'aria-expanded', 'false' );
	const popup = trigger.querySelector( '.interactive-image__popup' );
	if ( popup ) {
		popup.style.display = 'none';
		ZotefoamsAnimationUtils.fadeOut( popup );
	}
}

function hideAllPopups() {
	ZotefoamsDOMUtils.selectAll( '.interactive-image__point[aria-expanded]' ).forEach( closePopup );
}

function showPopup( sender ) {
	clearTimeout( hideTimeout );

	ZotefoamsDOMUtils.selectAll( '.interactive-image__point[aria-expanded="true"]' ).forEach( ( other ) => {
		if ( other !== sender ) {
			closePopup( other );
		}
	} );

	const popup = sender.querySelector( '.interactive-image__popup' );
	if ( ! popup ) {
		return;
	}

	sender.setAttribute( 'aria-expanded', 'true' );
	positionPopup( sender, popup );
	popup.style.display = 'block';
	ZotefoamsAnimationUtils.fadeIn( popup );
}

function scheduleHide() {
	clearTimeout( hideTimeout );
	hideTimeout = setTimeout( hideAllPopups, HOVER_CLOSE_DELAY_MS );
}

function cancelScheduledHide() {
	clearTimeout( hideTimeout );
}

function initInteractiveImage() {
	const points = ZotefoamsDOMUtils.selectAll( '.interactive-image__point[aria-expanded]' );

	points.forEach( ( point ) => {
		const popup = point.querySelector( '.interactive-image__popup' );

		if ( ! popup ) {
			return;
		}

		// 🖱 Desktop: Hover interaction
		if ( ! ZotefoamsDeviceUtils.isTouchDevice() ) {
			ZotefoamsEventUtils.on( point, 'mouseenter', () => showPopup( point ) );
			ZotefoamsEventUtils.on( point, 'mouseleave', scheduleHide );
			ZotefoamsEventUtils.on( popup, 'mouseenter', cancelScheduledHide );
			ZotefoamsEventUtils.on( popup, 'mouseleave', scheduleHide );
		}
		// 👆 Mobile: Tap interaction
		else {
			ZotefoamsEventUtils.on( point, 'click', ( e ) => {
				e.stopPropagation();
				if ( point.getAttribute( 'aria-expanded' ) === 'true' ) {
					closePopup( point );
				} else {
					showPopup( point );
				}
			} );
		}

		ZotefoamsEventUtils.on( point, 'focus', () => showPopup( point ) );
		ZotefoamsEventUtils.on( point, 'blur', ( e ) => {
			if ( popup.contains( e.relatedTarget ) ) {
				return;
			}
			closePopup( point );
		} );

		ZotefoamsEventUtils.on( point, 'keydown', ( e ) => {
			if ( e.key === 'Escape' && point.getAttribute( 'aria-expanded' ) === 'true' ) {
				closePopup( point );
				point.focus();
			}
		} );

		ZotefoamsEventUtils.on( popup, 'click', ( e ) => e.stopPropagation() );
	} );

	// 📲 Close popup when tapping outside (mobile)
	ZotefoamsEventUtils.on( document, 'click', ( e ) => {
		if ( ! e.target.closest( '.interactive-image__point' ) ) {
			hideAllPopups();
		}
	} );
}

ZotefoamsReadyUtils.ready( initInteractiveImage );

export { initInteractiveImage, showPopup, hideAllPopups };
