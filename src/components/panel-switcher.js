/**
 * Panel Switcher Component
 * Handles mobile accordion behavior for panel switcher components
 * Desktop uses CSS-only radio button switching
 *
 * Progressive Enhancement:
 * - Without JS: h2 headings with all content visible
 * - With JS: h2 converted to buttons with accordion behavior
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initPanelSwitcher() {
  const wrappers = document.querySelectorAll('[data-panel-switcher]');

  if (!wrappers.length) return;

  wrappers.forEach((wrapper, wrapperIndex) => {
    // Add js-enabled class for progressive enhancement
    wrapper.classList.add('js-enabled');

    const headers = wrapper.querySelectorAll('[data-accordion-header]');

    if (!headers.length) return;

    headers.forEach((header, index) => {
      const panel = header.closest('.panel-switcher__panel');
      const panelIndex = panel.getAttribute('data-panel');
      const contentId = `panel-content-${wrapperIndex}-${panelIndex}`;
      const content = panel.querySelector('.panel-switcher__content');

      // Set ARIA reference ID on content
      content?.setAttribute('id', contentId);

      // Create button to replace h2
      const button = document.createElement('button');
      button.className = 'panel-switcher__accordion-toggle';
      button.setAttribute('aria-expanded', index === 0 ? 'true' : 'false');
      button.setAttribute('aria-controls', contentId);
      button.innerHTML = header.innerHTML;

      // Replace h2 with button
      header.parentNode.replaceChild(button, header);

      // Add click handler
      button.addEventListener('click', function (e) {
        e.preventDefault();

        const isExpanded = this.getAttribute('aria-expanded') === 'true';

        // Close all other panels (accordion behavior)
        wrapper.querySelectorAll('.panel-switcher__accordion-toggle').forEach((toggle) => {
          if (toggle !== this) {
            toggle.setAttribute('aria-expanded', 'false');
          }
        });

        // Toggle current panel
        this.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
      });
    });
  });
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready(initPanelSwitcher);

export { initPanelSwitcher };
