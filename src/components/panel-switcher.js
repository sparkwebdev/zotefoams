/**
 * Panel Switcher Component
 * Handles mobile accordion behavior and desktop accessibility enhancements
 *
 * Progressive Enhancement:
 * Desktop: Enhanced radio button accessibility and ARIA state management
 * Mobile: h2 headings converted to buttons with accordion behavior
 */
import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initPanelSwitcher() {
  const wrappers = document.querySelectorAll('[data-panel-switcher]');

  if (!wrappers.length) return;

  wrappers.forEach((wrapper, wrapperIndex) => {
    // Add js-enabled class for progressive enhancement
    wrapper.classList.add('js-enabled');

    // Desktop: Enhance radio button accessibility
    initDesktopTabsAccessibility(wrapper);

    // Mobile: Convert h2 to buttons for accordion behavior
    initMobileAccordion(wrapper, wrapperIndex);
  });
}

function initDesktopTabsAccessibility(wrapper) {
  const radioButtons = wrapper.querySelectorAll('.panel-switcher__radio');

  if (!radioButtons.length) return;

  radioButtons.forEach((radio, index) => {
    // Add change listener to update ARIA states
    radio.addEventListener('change', function() {
      if (this.checked) {
        // Update all radio buttons' aria-selected state
        radioButtons.forEach((r, i) => {
          r.setAttribute('aria-selected', i === index ? 'true' : 'false');
        });
      }
    });

    // Add keyboard navigation (arrow keys)
    radio.addEventListener('keydown', function(e) {
      let newIndex;

      switch(e.key) {
        case 'ArrowRight':
        case 'ArrowDown':
          e.preventDefault();
          newIndex = (index + 1) % radioButtons.length;
          break;
        case 'ArrowLeft':
        case 'ArrowUp':
          e.preventDefault();
          newIndex = (index - 1 + radioButtons.length) % radioButtons.length;
          break;
        case 'Home':
          e.preventDefault();
          newIndex = 0;
          break;
        case 'End':
          e.preventDefault();
          newIndex = radioButtons.length - 1;
          break;
        default:
          return;
      }

      // Focus and select the new tab
      radioButtons[newIndex].focus();
      radioButtons[newIndex].checked = true;
      radioButtons[newIndex].dispatchEvent(new Event('change'));
    });
  });
}

function initMobileAccordion(wrapper, wrapperIndex) {
  const headers = wrapper.querySelectorAll('[data-accordion-header]');

  if (!headers.length) return;

  headers.forEach((header, index) => {
    const panel = header.closest('.panel-switcher__panel');
    const content = panel.querySelector('.panel-switcher__content');
    const contentId = content?.id; // Use existing ID from PHP

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
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready(initPanelSwitcher);

export { initPanelSwitcher };
