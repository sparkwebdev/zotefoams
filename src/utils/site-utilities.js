/**
 * Site Utilities
 * Miscellaneous site-wide utility functions
 */
import { ZotefoamsReadyUtils } from './dom-utilities.js';

// Clickable URL elements
function initClickableUrls() {
	// Add a click event listener to all elements with the data-clickable-url attribute
	document.querySelectorAll( '[data-clickable-url]' ).forEach( function( article ) {
		const url = article.getAttribute( 'data-clickable-url' );
		if ( url ) {
			const childLink = article.querySelector( 'a[href]' );
			const opensNewTab = childLink && childLink.target === '_blank';

			article.addEventListener( 'click', function( e ) {
				// Let native link clicks pass through unmodified
				if ( e.target.closest( 'a' ) ) {
					return;
				}
				// Honour modifier keys, or respect target="_blank" on the child link
				if ( e.metaKey || e.ctrlKey || e.shiftKey || opensNewTab ) {
					window.open( url, '_blank', 'noopener' );
				} else {
					window.location.href = url;
				}
			} );
		}
	} );
}

// Overlay fade-in effect
function initOverlayFadeIn() {
	if ( document.querySelector( '.overlay' ) ) {
		document.querySelector( '.overlay' ).classList.add( 'fade-in' );
	}
}

// Dynamic iframe height adjustment
function initIframeHeightAdjustment() {
	const allowedOrigins = [
		window.location.origin,
		'https://polaris.brighterir.com',
		'https://sirius.brighterir.com',
	];

	window.addEventListener( 'message', function( event ) {
		if ( ! allowedOrigins.includes( event.origin ) ) { return; }
		if ( typeof event.data !== 'number' ) { return; }
		const frames = document.getElementsByTagName( 'iframe' );
		for ( let i = 0; i < frames.length; i++ ) {
			if ( frames[ i ].contentWindow === event.source ) {
				frames[ i ].style.height = event.data + 'px';
				break;
			}
		}
	} );
}

// Header height CSS variable updater
const updateHeaderHeight = () => {
	const header = document.querySelector( '[data-el-site-header]' );
	if ( ! header ) {
		return;
	}

	const headerHeight = header.offsetHeight;
	document.documentElement.style.setProperty( '--header-height', `${ headerHeight }px` );
};

function initHeaderHeightUpdater() {
	// Throttle resize events using requestAnimationFrame
	let resizeTimeout = false;

	const onResize = () => {
		if ( ! resizeTimeout ) {
			resizeTimeout = true;
			window.requestAnimationFrame( () => {
				updateHeaderHeight();
				resizeTimeout = false;
			} );
		}
	};

	window.addEventListener( 'DOMContentLoaded', () => {
		updateHeaderHeight(); // Initial run
		document.body.classList.add( 'has-sticky-header' );
		window.addEventListener( 'resize', onResize ); // Hook into resize once
	} );
}

// Initialize all site utilities when DOM is ready
ZotefoamsReadyUtils.ready( () => {
	initClickableUrls();
	initOverlayFadeIn();
	initIframeHeightAdjustment();
	initHeaderHeightUpdater();
} );

export {
	initClickableUrls,
	initOverlayFadeIn,
	initIframeHeightAdjustment,
	initHeaderHeightUpdater,
	updateHeaderHeight,
};
