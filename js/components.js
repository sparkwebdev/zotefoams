// Component - Tabbed Split
document.addEventListener( 'DOMContentLoaded', () => {
	const tabs = document.querySelectorAll( '.tab' );
	const tabContents = document.querySelectorAll( '.tab-content' );

	tabs.forEach( ( tab ) => {
		tab.addEventListener( 'click', () => {
			// Remove active class from all tabs and contents
			tabs.forEach( ( t ) => t.classList.remove( 'active' ) );
			tabContents.forEach( ( c ) => c.classList.remove( 'active' ) );

			// Add active class to clicked tab and corresponding content
			tab.classList.add( 'active' );
			document.getElementById( tab.dataset.tab ).classList.add( 'active' );
		} );
	} );
} );

// Component - Locations Map
function locationClicked( sender ) {
	// Hide all popups first
	document.querySelectorAll( '.locations-map__popup' ).forEach( ( popup ) => {
		popup.style.display = 'none';
	} );

	// Get the popup inside the clicked location
	const popup = sender.querySelector( '.locations-map__popup' );
	if ( popup ) {
		popup.style.display = 'block';
	}
}

// Optional: Close popups when clicking outside
document.addEventListener( 'click', function( event ) {
	const isLocation = event.target.closest( '.locations-map__location' );

	if ( isLocation ) {
		locationClicked( isLocation );
	} else {
		document.querySelectorAll( '.locations-map__popup' ).forEach( ( popup ) => {
			popup.style.display = 'none';
		} );
	}
} );

// Data Points
document.addEventListener( 'DOMContentLoaded', function() {
	function animateValue( obj, start, end, duration, prefix, suffix, decimals ) {
		let startTimestamp = null;
		const step = ( timestamp ) => {
			if ( ! startTimestamp ) {
				startTimestamp = timestamp;
			}
			const progress = Math.min( ( timestamp - startTimestamp ) / duration, 1 );
			const value = ( progress * ( end - start ) + start );

			// Format value with the correct decimals and remove trailing zeros
			let formattedValue = value.toFixed( decimals );
			if ( decimals > 0 && ! suffix.includes( '%' ) ) {
				formattedValue = formattedValue.replace( /\.?0+$/, '' );
			}

			// Apply the formatted value
			obj.innerHTML = ( prefix || '' ) + formattedValue + ( suffix || '' );

			if ( progress < 1 ) {
				window.requestAnimationFrame( step );
			}
		};
		window.requestAnimationFrame( step );
	}

	const observer = new IntersectionObserver( ( entries ) => {
		entries.forEach( ( entry ) => {
			if ( entry.isIntersecting ) {
				const values = entry.target.querySelectorAll( '.value' );

				values.forEach( ( valueElement ) => {
					// Prevent re-animation
					if ( valueElement.dataset.animated ) {
						return;
					}

					const prefix = valueElement.dataset.prefix || '';
					const suffix = valueElement.dataset.suffix || '';
					const duration = parseInt( valueElement.dataset.duration ) || 1100;
					const decimals = parseInt( valueElement.dataset.decimals ) || 0;
					const to = parseFloat( valueElement.dataset.to ) || 0;

					valueElement.classList.add( 'animate__pulse' );
					animateValue( valueElement, 0, to, duration, prefix, suffix, decimals );

					// Mark as animated
					valueElement.dataset.animated = 'true';
				} );
			} else {
				// Allow re-animation if needed by removing flag
				const values = entry.target.querySelectorAll( '.value' );
				values.forEach( ( el ) => {
					// Only reset animation flag when fully out of view for smoother experience
					if ( entry.intersectionRatio <= 0 ) {
						el.removeAttribute( 'data-animated' );
					}
				} );
			}
		} );
	}, { threshold: [ 0, 0.5 ] } ); // Track both entering and fully visible states

	// Fix: Use the correct class name from your HTML
	const target = document.querySelector( '.data-points-items' );
	if ( target ) {
		observer.observe( target );
	}
} );
