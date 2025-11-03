// =============================================================================
// Zotefoams DOM Utilities - Core DOM manipulation utilities (ES Module)
// =============================================================================

/**
 * DOM Selection Utilities - Centralized element selection patterns
 */
export const ZotefoamsDOMUtils = {
	/**
	 * Safe querySelector with optional fallback
	 * @param selector
	 * @param fallback
	 */
	select( selector, fallback = null ) {
		const element = document.querySelector( selector );
		return element || fallback;
	},

	/**
	 * Safe querySelectorAll with guaranteed array return
	 * @param selector
	 */
	selectAll( selector ) {
		return Array.from( document.querySelectorAll( selector ) );
	},

	/**
	 * Select elements with data attribute
	 * @param attribute
	 * @param value
	 */
	selectByData( attribute, value = null ) {
		const selector = value ? `[data-${ attribute }="${ value }"]` : `[data-${ attribute }]`;
		return this.selectAll( selector );
	},

	/**
	 * Safe closest selector with fallback
	 * @param element
	 * @param selector
	 * @param fallback
	 */
	closest( element, selector, fallback = null ) {
		return element?.closest( selector ) || fallback;
	},

	/**
	 * Check if element exists and is visible
	 * @param element
	 */
	isVisible( element ) {
		if ( ! element ) {
			return false;
		}
		const rect = element.getBoundingClientRect();
		return rect.width > 0 && rect.height > 0;
	},
};

/**
 * Event Handling Utilities - Centralized event management patterns
 */
export const ZotefoamsEventUtils = {
	/**
	 * Add event listener with cleanup tracking
	 * @param element
	 * @param event
	 * @param handler
	 * @param options
	 */
	on( element, event, handler, options = {} ) {
		if ( ! element ) {
			return null;
		}
		element.addEventListener( event, handler, options );
		return { element, event, handler };
	},

	/**
	 * Add event listener to multiple elements
	 * @param elements
	 * @param event
	 * @param handler
	 * @param options
	 */
	onAll( elements, event, handler, options = {} ) {
		return elements.map( ( element ) => this.on( element, event, handler, options ) ).filter( Boolean );
	},

	/**
	 * Delegated event handling for dynamic content
	 * @param container
	 * @param selector
	 * @param event
	 * @param handler
	 */
	delegate( container, selector, event, handler ) {
		return this.on( container, event, ( e ) => {
			const target = e.target.closest( selector );
			if ( target && container.contains( target ) ) {
				handler.call( target, e );
			}
		} );
	},

	/**
	 * One-time event listener
	 * @param element
	 * @param event
	 * @param handler
	 */
	once( element, event, handler ) {
		return this.on( element, event, handler, { once: true } );
	},

	/**
	 * Debounced event handler
	 * @param func
	 * @param wait
	 */
	debounce( func, wait ) {
		let timeout;
		return function executedFunction( ...args ) {
			const later = () => {
				clearTimeout( timeout );
				func( ...args );
			};
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
		};
	},
};

/**
 * CSS Class Utilities - Centralized class manipulation patterns
 */
export const ZotefoamsClassUtils = {
	/**
	 * Toggle class on element safely
	 * @param element
	 * @param className
	 * @param force
	 */
	toggle( element, className, force = undefined ) {
		if ( ! element ) {
			return false;
		}
		return element.classList.toggle( className, force );
	},

	/**
	 * Add class to element safely
	 * @param          element
	 * @param {...any} classNames
	 */
	add( element, ...classNames ) {
		if ( ! element ) {
			return;
		}
		element.classList.add( ...classNames );
	},

	/**
	 * Remove class from element safely
	 * @param          element
	 * @param {...any} classNames
	 */
	remove( element, ...classNames ) {
		if ( ! element ) {
			return;
		}
		element.classList.remove( ...classNames );
	},

	/**
	 * Toggle class on multiple elements
	 * @param elements
	 * @param className
	 * @param force
	 */
	toggleAll( elements, className, force = undefined ) {
		return elements.map( ( element ) => this.toggle( element, className, force ) );
	},

	/**
	 * Add class to multiple elements
	 * @param          elements
	 * @param {...any} classNames
	 */
	addAll( elements, ...classNames ) {
		elements.forEach( ( element ) => this.add( element, ...classNames ) );
	},

	/**
	 * Remove class from multiple elements
	 * @param          elements
	 * @param {...any} classNames
	 */
	removeAll( elements, ...classNames ) {
		elements.forEach( ( element ) => this.remove( element, ...classNames ) );
	},

	/**
	 * Replace class on element
	 * @param element
	 * @param oldClass
	 * @param newClass
	 */
	replace( element, oldClass, newClass ) {
		if ( ! element ) {
			return;
		}
		this.remove( element, oldClass );
		this.add( element, newClass );
	},
};

/**
 * Animation Utilities - Centralized animation patterns
 */
export const ZotefoamsAnimationUtils = {
	/**
	 * Fade in element with class toggle
	 * @param element
	 * @param className
	 */
	fadeIn( element, className = 'fade-in' ) {
		if ( ! element ) {
			return;
		}
		ZotefoamsClassUtils.add( element, className );
	},

	/**
	 * Fade out element with class toggle
	 * @param element
	 * @param className
	 */
	fadeOut( element, className = 'fade-in' ) {
		if ( ! element ) {
			return;
		}
		ZotefoamsClassUtils.remove( element, className );
	},

	/**
	 * Show/hide element with smooth transition
	 * @param element
	 * @param show
	 */
	toggleVisibility( element, show ) {
		if ( ! element ) {
			return;
		}

		if ( show ) {
			element.style.display = 'block';
			element.style.opacity = '1';
			element.style.maxHeight = '1000px';
		} else {
			element.style.display = 'none';
			element.style.opacity = '0';
			element.style.maxHeight = '0';
		}
	},

	/**
	 * Smooth scroll to element
	 * @param element
	 * @param offset
	 * @param behavior
	 */
	scrollTo( element, offset = -80, behavior = 'smooth' ) {
		if ( ! element ) {
			return;
		}
		const targetPosition = element.getBoundingClientRect().top + window.pageYOffset + offset;
		window.scrollTo( {
			top: targetPosition,
			behavior,
		} );
	},
};

/**
 * Touch/Device Detection Utilities
 */
export const ZotefoamsDeviceUtils = {
	/**
	 * Detect touch device
	 */
	isTouchDevice() {
		return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0;
	},

	/**
	 * Get interaction event based on device
	 */
	getInteractionEvent() {
		return this.isTouchDevice() ? 'click' : 'hover';
	},

	/**
	 * Apply touch-specific classes
	 */
	initTouchSupport() {
		if ( this.isTouchDevice() ) {
			ZotefoamsClassUtils.add( document.body, 'touch-device' );
		} else {
			ZotefoamsClassUtils.add( document.body, 'no-touch-device' );
		}
	},
};

/**
 * Form Utilities - Centralized form handling patterns
 */
export const ZotefoamsFormUtils = {
	/**
	 * Get form data as object
	 * @param form
	 */
	getFormData( form ) {
		if ( ! form ) {
			return {};
		}
		const formData = new FormData( form );
		return Object.fromEntries( formData.entries() );
	},

	/**
	 * Validate required fields
	 * @param form
	 */
	validateRequired( form ) {
		if ( ! form ) {
			return false;
		}
		const requiredFields = ZotefoamsDOMUtils.selectAll( 'input[required], textarea[required], select[required]' );
		return requiredFields.every( ( field ) => field.value.trim() !== '' );
	},

	/**
	 * Focus first invalid field
	 * @param form
	 */
	focusFirstInvalid( form ) {
		if ( ! form ) {
			return;
		}
		const invalid = form.querySelector( ':invalid' );
		if ( invalid ) {
			invalid.focus();
		}
	},
};

/**
 * URL/Navigation Utilities
 */
export const ZotefoamsURLUtils = {
	/**
	 * Get URL parameter
	 * @param name
	 */
	getParam( name ) {
		const params = new URLSearchParams( window.location.search );
		return params.get( name );
	},

	/**
	 * Set URL parameter without reload
	 * @param name
	 * @param value
	 */
	setParam( name, value ) {
		const params = new URLSearchParams( window.location.search );
		params.set( name, value );
		const newURL = window.location.pathname + '?' + params.toString();
		window.history.replaceState( {}, '', newURL );
	},

	/**
	 * Get hash without #
	 */
	getHash() {
		return window.location.hash.substring( 1 );
	},

	/**
	 * Set hash without reload
	 * @param hash
	 */
	setHash( hash ) {
		window.history.replaceState( null, '', '#' + hash );
	},
};

/**
 * Accessibility Utilities - Keyboard navigation and ARIA support
 */
export const ZotefoamsAccessibilityUtils = {
	/**
	 * Get all focusable elements within a container
	 * @param {HTMLElement} container - Container element to search within
	 * @return {Array<HTMLElement>} Array of focusable elements
	 */
	getFocusableElements( container ) {
		if ( ! container ) {
			return [];
		}
		return Array.from(
			container.querySelectorAll(
				'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
			)
		);
	},

	/**
	 * Set aria-expanded attribute
	 * @param {HTMLElement} element  - Element to update
	 * @param {boolean}     expanded - Expanded state
	 */
	setAriaExpanded( element, expanded ) {
		if ( element ) {
			element.setAttribute( 'aria-expanded', expanded.toString() );
		}
	},

	/**
	 * Set multiple ARIA attributes at once
	 * @param {HTMLElement} element    - Element to update
	 * @param {Object}      attributes - Object with ARIA attribute names and values
	 */
	setAriaAttributes( element, attributes ) {
		if ( ! element ) {
			return;
		}
		Object.entries( attributes ).forEach( ( [ key, value ] ) => {
			element.setAttribute( key, value.toString() );
		} );
	},

	/**
	 * Toggle aria-hidden state
	 * @param {HTMLElement} element - Element to update
	 * @param {boolean}     hidden  - Hidden state
	 */
	setAriaHidden( element, hidden ) {
		if ( element ) {
			element.setAttribute( 'aria-hidden', hidden.toString() );
		}
	},

	/**
	 * Focus element with optional trap
	 * @param {HTMLElement} element     - Element to focus
	 * @param {boolean}     setTabindex - Whether to set tabindex="-1" for non-focusable elements
	 */
	focus( element, setTabindex = false ) {
		if ( ! element ) {
			return;
		}
		if ( setTabindex && ! element.hasAttribute( 'tabindex' ) ) {
			element.setAttribute( 'tabindex', '-1' );
		}
		element.focus();
	},
};

/**
 * Ready State Utilities - Centralized DOMContentLoaded patterns
 */
export const ZotefoamsReadyUtils = {
	/**
	 * Execute function when DOM is ready
	 * @param callback
	 */
	ready( callback ) {
		if ( document.readyState === 'loading' ) {
			document.addEventListener( 'DOMContentLoaded', callback );
		} else {
			callback();
		}
	},

	/**
	 * Execute multiple functions when DOM is ready
	 * @param callbacks
	 */
	readyAll( callbacks ) {
		this.ready( () => {
			callbacks.forEach( ( callback ) => {
				try {
					callback();
				} catch ( error ) {
					console.error( 'Ready callback error:', error );
				}
			} );
		} );
	},
};
