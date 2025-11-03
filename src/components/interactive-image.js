/**
 * Interactive Image Component
 * Handles interactive popups for image points with touch/desktop support
 * Supports both circle and numbered markers
 */
import { ZotefoamsDOMUtils, ZotefoamsEventUtils, ZotefoamsDeviceUtils, ZotefoamsAnimationUtils, ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function showPopup( sender ) {
	// Hide all other popups
	const allPopups = ZotefoamsDOMUtils.selectAll( '.interactive-image__popup' );
	allPopups.forEach( ( popup ) => {
		popup.style.display = 'none';
		ZotefoamsAnimationUtils.fadeOut( popup );
	} );

	const popup = sender.querySelector( '.interactive-image__popup' );
	if ( popup ) {
		// Get container and sender dimensions first
		const container = sender.closest( '.interactive-image__container' );
		const containerRect = container.getBoundingClientRect();
		const senderRect = sender.getBoundingClientRect();

		// Calculate sender position relative to container
		const senderCenterX = senderRect.left + ( senderRect.width / 2 ) - containerRect.left;
		const containerWidth = containerRect.width;

		// Set initial positioning classes
		popup.className = 'interactive-image__popup';

		// Determine positioning strategy before showing popup
		const isMobile = window.innerWidth <= 768;
		const popupWidth = isMobile ? 200 : 220; // Responsive width from CSS
		const edgeBuffer = 20;

		let positionClass = '';
		if ( senderCenterX < popupWidth / 2 + edgeBuffer ) {
			// Too close to left edge
			positionClass = 'interactive-image__popup--left-aligned';
		} else if ( senderCenterX > containerWidth - popupWidth / 2 - edgeBuffer ) {
			// Too close to right edge
			positionClass = 'interactive-image__popup--right-aligned';
		} else {
			// Center aligned (default)
			positionClass = 'interactive-image__popup--center-aligned';
		}

		// Add positioning class
		popup.classList.add( positionClass );

		// Show popup with fade animation
		popup.style.display = 'block';
		ZotefoamsAnimationUtils.fadeIn( popup );
	}
}

function hideAllPopups() {
	const allPopups = ZotefoamsDOMUtils.selectAll( '.interactive-image__popup' );
	allPopups.forEach( ( popup ) => {
		popup.style.display = 'none';
		ZotefoamsAnimationUtils.fadeOut( popup );
	} );
}

function initInteractiveImage() {
	const points = ZotefoamsDOMUtils.selectAll( '.interactive-image__point' );

	points.forEach( ( point ) => {
		const popup = point.querySelector( '.interactive-image__popup' );

		if ( ! popup ) {
			return;
		}

		// 🖱 Desktop: Hover interaction
		if ( ! ZotefoamsDeviceUtils.isTouchDevice() ) {
			ZotefoamsEventUtils.on( point, 'mouseenter', () => showPopup( point ) );
			ZotefoamsEventUtils.on( point, 'mouseleave', hideAllPopups );
		}
		// 👆 Mobile: Tap interaction
		else {
			ZotefoamsEventUtils.on( point, 'click', ( e ) => {
				e.stopPropagation();
				showPopup( point );
			} );
		}

		// Prevent popup click bubbling so it doesn't auto-close
		ZotefoamsEventUtils.on( popup, 'click', ( e ) => e.stopPropagation() );
	} );

	// 📲 Close popup when tapping outside (mobile)
	ZotefoamsEventUtils.on( document, 'click', ( e ) => {
		if ( ! e.target.closest( '.interactive-image__point' ) ) {
			hideAllPopups();
		}
	} );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initInteractiveImage );

export { initInteractiveImage, showPopup, hideAllPopups };
