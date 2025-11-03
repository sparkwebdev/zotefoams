/**
 * Video Modal Component
 * YouTube video modal overlay with accessibility features
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initVideoModal() {
	const overlay = document.querySelector( '[data-modal="video"]' );
	const iframe = document.querySelector( '[data-video-iframe]' );
	const closeBtn = document.querySelector( '[data-video-close]' );
	const triggers = document.querySelectorAll( '[data-modal-trigger="video"]' );
	const mainPage = document.getElementById( 'page' );

	let lastFocusedElement = null;

	function getYouTubeId( url ) {
		try {
			const parsedUrl = new URL( url );
			const videoId = new URLSearchParams( parsedUrl.search ).get( 'v' );
			return videoId;
		} catch ( e ) {
			console.error( 'Invalid YouTube URL:', url );
			return null;
		}
	}

	function openOverlay( videoUrl ) {
		const videoId = getYouTubeId( videoUrl );
		if ( ! videoId ) {
			return;
		}

		lastFocusedElement = document.activeElement;

		iframe.src = `https://www.youtube.com/embed/${ videoId }?autoplay=1`;
		overlay.classList.add( 'is-visible' );
		overlay.setAttribute( 'aria-hidden', 'false' );
		mainPage?.setAttribute( 'aria-hidden', 'true' );
		document.body.classList.add( 'modal-open' );
		closeBtn?.focus();
	}

	function closeOverlay() {
		overlay.classList.remove( 'is-visible' );
		overlay.setAttribute( 'aria-hidden', 'true' );
		iframe.src = '';
		mainPage?.removeAttribute( 'aria-hidden' );
		document.body.classList.remove( 'modal-open' );
		if ( lastFocusedElement ) {
			lastFocusedElement.focus();
		}
	}

	// Bind open triggers
	triggers.forEach( ( link ) => {
		link.addEventListener( 'click', function( e ) {
			e.preventDefault();
			const videoUrl = this.dataset.videoUrl;
			openOverlay( videoUrl );
		} );
	} );

	// Close on background click
	overlay?.addEventListener( 'click', function( e ) {
		if ( e.target === overlay ) {
			closeOverlay();
		}
	} );

	// Close on ESC key
	document.addEventListener( 'keydown', function( e ) {
		if ( e.key === 'Escape' && overlay.classList.contains( 'is-visible' ) ) {
			closeOverlay();
		}
	} );

	// Close on close button click
	closeBtn?.addEventListener( 'click', closeOverlay );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initVideoModal );

export { initVideoModal };
