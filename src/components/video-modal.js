/**
 * Video Modal Component
 * YouTube video modal using native <dialog> element
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initVideoModal() {
	const overlay = document.querySelector( '[data-modal="video"]' );
	const triggers = document.querySelectorAll( '[data-modal-trigger="video"]' );

	if ( ! overlay || ! triggers.length ) {
		return;
	}

	const iframe = document.querySelector( '[data-video-iframe]' );
	const closeBtn = document.querySelector( '[data-video-close]' );

	let lastFocusedElement = null;

	function getYouTubeId( url ) {
		try {
			const parsedUrl = new URL( url );
			return new URLSearchParams( parsedUrl.search ).get( 'v' );
		} catch ( e ) {
			// eslint-disable-next-line no-console
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
		overlay.showModal();
		requestAnimationFrame( () => overlay.classList.add( 'is-visible' ) );
		document.body.classList.add( 'modal-open' );
		closeBtn?.focus();
	}

	function closeOverlay() {
		overlay.classList.remove( 'is-visible' );
		document.body.classList.remove( 'modal-open' );
	}

	// Waits for the fade-out transition rather than a fixed setTimeout — elements
	// outside an open showModal() dialog are inert, so closing/unfocusing early fails silently.
	overlay.addEventListener( 'transitionend', ( e ) => {
		if ( e.target !== overlay || e.propertyName !== 'opacity' || overlay.classList.contains( 'is-visible' ) ) {
			return;
		}
		overlay.close();
		iframe.src = '';
		if ( lastFocusedElement ) {
			lastFocusedElement.focus();
		}
	} );

	triggers.forEach( ( link ) => {
		link.addEventListener( 'click', function( e ) {
			e.preventDefault();
			openOverlay( this.dataset.videoUrl );
		} );
	} );

	// Backdrop click — clicking outside dialog content fires click on the dialog itself
	overlay.addEventListener( 'click', ( e ) => {
		if ( e.target === overlay ) {
			closeOverlay();
		}
	} );

	// Intercept native ESC so our fade-out transition runs before close()
	overlay.addEventListener( 'cancel', ( e ) => {
		e.preventDefault();
		closeOverlay();
	} );

	// Manual focus trap — native <dialog> cycling is unreliable in Safari/some Chrome builds
	overlay.addEventListener( 'keydown', ( e ) => {
		if ( e.key !== 'Tab' || ! overlay.open ) {
			return;
		}
		const focusable = Array.from(
			overlay.querySelectorAll( 'button:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])' )
		);
		if ( ! focusable.length ) {
			e.preventDefault();
			return;
		}
		const first = focusable[ 0 ];
		const last = focusable[ focusable.length - 1 ];
		if ( e.shiftKey && document.activeElement === first ) {
			e.preventDefault();
			last.focus();
		} else if ( ! e.shiftKey && document.activeElement === last ) {
			e.preventDefault();
			first.focus();
		}
	} );

	closeBtn?.addEventListener( 'click', closeOverlay );
}

ZotefoamsReadyUtils.ready( initVideoModal );

export { initVideoModal };
