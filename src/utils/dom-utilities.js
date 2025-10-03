// =============================================================================
// Zotefoams DOM Utilities - Core DOM manipulation utilities (ES Module)
// =============================================================================

/**
 * DOM Selection Utilities - Centralized element selection patterns
 */
export const ZotefoamsDOMUtils = {
	/**
	 * Safe querySelector with optional fallback
	 */
	select(selector, fallback = null) {
		const element = document.querySelector(selector);
		return element || fallback;
	},

	/**
	 * Safe querySelectorAll with guaranteed array return
	 */
	selectAll(selector) {
		return Array.from(document.querySelectorAll(selector));
	},

	/**
	 * Select elements with data attribute
	 */
	selectByData(attribute, value = null) {
		const selector = value ? `[data-${attribute}="${value}"]` : `[data-${attribute}]`;
		return this.selectAll(selector);
	},

	/**
	 * Safe closest selector with fallback
	 */
	closest(element, selector, fallback = null) {
		return element?.closest(selector) || fallback;
	},

	/**
	 * Check if element exists and is visible
	 */
	isVisible(element) {
		if (!element) return false;
		const rect = element.getBoundingClientRect();
		return rect.width > 0 && rect.height > 0;
	}
};

/**
 * Event Handling Utilities - Centralized event management patterns
 */
export const ZotefoamsEventUtils = {
	/**
	 * Add event listener with cleanup tracking
	 */
	on(element, event, handler, options = {}) {
		if (!element) return null;
		element.addEventListener(event, handler, options);
		return { element, event, handler };
	},

	/**
	 * Add event listener to multiple elements
	 */
	onAll(elements, event, handler, options = {}) {
		return elements.map(element => this.on(element, event, handler, options)).filter(Boolean);
	},

	/**
	 * Delegated event handling for dynamic content
	 */
	delegate(container, selector, event, handler) {
		return this.on(container, event, (e) => {
			const target = e.target.closest(selector);
			if (target && container.contains(target)) {
				handler.call(target, e);
			}
		});
	},

	/**
	 * One-time event listener
	 */
	once(element, event, handler) {
		return this.on(element, event, handler, { once: true });
	},

	/**
	 * Debounced event handler
	 */
	debounce(func, wait) {
		let timeout;
		return function executedFunction(...args) {
			const later = () => {
				clearTimeout(timeout);
				func(...args);
			};
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
		};
	}
};

/**
 * CSS Class Utilities - Centralized class manipulation patterns
 */
export const ZotefoamsClassUtils = {
	/**
	 * Toggle class on element safely
	 */
	toggle(element, className, force = undefined) {
		if (!element) return false;
		return element.classList.toggle(className, force);
	},

	/**
	 * Add class to element safely
	 */
	add(element, ...classNames) {
		if (!element) return;
		element.classList.add(...classNames);
	},

	/**
	 * Remove class from element safely
	 */
	remove(element, ...classNames) {
		if (!element) return;
		element.classList.remove(...classNames);
	},

	/**
	 * Toggle class on multiple elements
	 */
	toggleAll(elements, className, force = undefined) {
		return elements.map(element => this.toggle(element, className, force));
	},

	/**
	 * Add class to multiple elements
	 */
	addAll(elements, ...classNames) {
		elements.forEach(element => this.add(element, ...classNames));
	},

	/**
	 * Remove class from multiple elements
	 */
	removeAll(elements, ...classNames) {
		elements.forEach(element => this.remove(element, ...classNames));
	},

	/**
	 * Replace class on element
	 */
	replace(element, oldClass, newClass) {
		if (!element) return;
		this.remove(element, oldClass);
		this.add(element, newClass);
	}
};

/**
 * Animation Utilities - Centralized animation patterns
 */
export const ZotefoamsAnimationUtils = {
	/**
	 * Fade in element with class toggle
	 */
	fadeIn(element, className = 'fade-in') {
		if (!element) return;
		ZotefoamsClassUtils.add(element, className);
	},

	/**
	 * Fade out element with class toggle
	 */
	fadeOut(element, className = 'fade-in') {
		if (!element) return;
		ZotefoamsClassUtils.remove(element, className);
	},

	/**
	 * Show/hide element with smooth transition
	 */
	toggleVisibility(element, show) {
		if (!element) return;
		
		if (show) {
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
	 */
	scrollTo(element, offset = -80, behavior = 'smooth') {
		if (!element) return;
		const targetPosition = element.getBoundingClientRect().top + window.pageYOffset + offset;
		window.scrollTo({
			top: targetPosition,
			behavior: behavior
		});
	}
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
		if (this.isTouchDevice()) {
			ZotefoamsClassUtils.add(document.body, 'touch-device');
		}
	}
};

/**
 * Form Utilities - Centralized form handling patterns
 */
export const ZotefoamsFormUtils = {
	/**
	 * Get form data as object
	 */
	getFormData(form) {
		if (!form) return {};
		const formData = new FormData(form);
		return Object.fromEntries(formData.entries());
	},

	/**
	 * Validate required fields
	 */
	validateRequired(form) {
		if (!form) return false;
		const requiredFields = ZotefoamsDOMUtils.selectAll('input[required], textarea[required], select[required]');
		return requiredFields.every(field => field.value.trim() !== '');
	},

	/**
	 * Focus first invalid field
	 */
	focusFirstInvalid(form) {
		if (!form) return;
		const invalid = form.querySelector(':invalid');
		if (invalid) invalid.focus();
	}
};

/**
 * URL/Navigation Utilities
 */
export const ZotefoamsURLUtils = {
	/**
	 * Get URL parameter
	 */
	getParam(name) {
		const params = new URLSearchParams(window.location.search);
		return params.get(name);
	},

	/**
	 * Set URL parameter without reload
	 */
	setParam(name, value) {
		const params = new URLSearchParams(window.location.search);
		params.set(name, value);
		const newURL = window.location.pathname + '?' + params.toString();
		window.history.replaceState({}, '', newURL);
	},

	/**
	 * Get hash without #
	 */
	getHash() {
		return window.location.hash.substring(1);
	},

	/**
	 * Set hash without reload
	 */
	setHash(hash) {
		window.history.replaceState(null, '', '#' + hash);
	}
};

/**
 * Ready State Utilities - Centralized DOMContentLoaded patterns
 */
export const ZotefoamsReadyUtils = {
	/**
	 * Execute function when DOM is ready
	 */
	ready(callback) {
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', callback);
		} else {
			callback();
		}
	},

	/**
	 * Execute multiple functions when DOM is ready
	 */
	readyAll(callbacks) {
		this.ready(() => {
			callbacks.forEach(callback => {
				try {
					callback();
				} catch (error) {
					console.error('Ready callback error:', error);
				}
			});
		});
	}
};

// Initialize touch support on load
ZotefoamsReadyUtils.ready(() => {
	ZotefoamsDeviceUtils.initTouchSupport();
});