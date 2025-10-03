/**
 * Data Points Component
 * Animated counter values with intersection observer
 */
import { ZotefoamsDOMUtils, ZotefoamsClassUtils, ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function animateValue(obj, start, end, duration, prefix, suffix, decimals) {
	let startTimestamp = null;
	const step = (timestamp) => {
		if (!startTimestamp) {
			startTimestamp = timestamp;
		}
		const progress = Math.min((timestamp - startTimestamp) / duration, 1);
		const value = (progress * (end - start) + start);

		// Format value with the correct decimals and remove trailing zeros
		let formattedValue = value.toFixed(decimals);
		if (decimals > 0 && !suffix.includes('%')) {
			formattedValue = formattedValue.replace(/\.?0+$/, '');
		}

		// Apply the formatted value
		obj.innerHTML = (prefix || '') + formattedValue + (suffix || '');

		if (progress < 1) {
			window.requestAnimationFrame(step);
		}
	};
	window.requestAnimationFrame(step);
}

function initDataPoints() {
	const observer = new IntersectionObserver((entries) => {
		entries.forEach((entry) => {
			if (entry.isIntersecting) {
				const values = entry.target.querySelectorAll('.value');

				values.forEach((valueElement) => {
					// Prevent re-animation
					if (valueElement.dataset.animated) {
						return;
					}

					const prefix = valueElement.dataset.prefix || '';
					const suffix = valueElement.dataset.suffix || '';
					const duration = parseInt(valueElement.dataset.duration) || 1100;
					const decimals = parseInt(valueElement.dataset.decimals) || 0;
					const to = parseFloat(valueElement.dataset.to) || 0;

					// Just animate the number, no additional CSS animation classes needed
					animateValue(valueElement, 0, to, duration, prefix, suffix, decimals);

					// Mark as animated
					valueElement.dataset.animated = 'true';
				});
			} else {
				// Allow re-animation if needed by removing flag
				const values = entry.target.querySelectorAll('.value');
				values.forEach((el) => {
					// Only reset animation flag when fully out of view for smoother experience
					if (entry.intersectionRatio <= 0) {
						el.removeAttribute('data-animated');
					}
				});
			}
		});
	}, { threshold: [0, 0.5] }); // Track both entering and fully visible states

	// Fix: Use the correct class name from your HTML
	const target = ZotefoamsDOMUtils.select('.data-points-items');
	if (target) {
		observer.observe(target);
	}
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready(initDataPoints);

export { initDataPoints, animateValue };