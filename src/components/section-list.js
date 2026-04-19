/**
 * Section List Component
 * Gallery section filtering functionality
 */
import { ZotefoamsReadyUtils, ZotefoamsAccessibilityUtils } from '../utils/dom-utilities.js';

const FILTER_FADE_DURATION_MS = 200;

function initSectionList() {
	const sectionListElements = document.querySelectorAll( '[data-component="section-list"]' );

	if ( sectionListElements.length > 0 ) {
		sectionListElements.forEach( function( article ) {
			const filterButton = article.querySelector( '[data-js="filter-toggle"]' );
			const filterOptions = article.querySelector( '[data-js="filter-options"]' );
			const checkboxes = [ ...article.querySelectorAll( '[data-js="filter-checkbox"]' ) ];
			const showAllButton = article.querySelector( '[data-js="section-list-show-all"]' );
			const sectionItems = [ ...article.querySelectorAll( '.section-list__item' ) ];
			// Close dropdown when user clicks outside it.
			const dropdown = article.querySelector( '[data-js="filter-dropdown"]' );
			const onOutsideClick = ( e ) => {
				if ( dropdown && ! dropdown.contains( e.target ) ) {
					toggleDropdown( false );
				}
			};

			const toggleDropdown = ( show ) => {
				filterOptions.classList.toggle( 'hidden', ! show );
				filterButton.classList.toggle( 'open', show );
				ZotefoamsAccessibilityUtils.setAriaExpanded( filterButton, show );
				ZotefoamsAccessibilityUtils.setAriaHidden( filterOptions, ! show );
				if ( show ) {
					document.addEventListener( 'click', onOutsideClick );
				} else {
					document.removeEventListener( 'click', onOutsideClick );
				}
			};

			const updateShowAllVisibility = () => {
				const selectedCount = checkboxes.filter( ( cb ) => cb.checked ).length;
				if ( showAllButton ) {
					showAllButton.classList.toggle( 'hidden', selectedCount === 0 || selectedCount === checkboxes.length );
				}
			};

			const filterSections = () => {
				// Assume that sectionItems share the same parent container.
				const sectionContainer = sectionItems.length > 0 ? sectionItems[ 0 ].parentNode : null;
				if ( ! sectionContainer ) {
					return;
				} // Exit if no container found.

				// Fade out the container.
				sectionContainer.classList.add( 'is-filtering' );

				setTimeout( () => {
					// Get the selected labels from the checkboxes.
					const selectedLabels = checkboxes.filter( ( cb ) => cb.checked ).map( ( cb ) => cb.value );

					// Update each section item's visibility based on the selected labels.
					sectionItems.forEach( ( item ) => {
						const isVisible = selectedLabels.length === 0 || selectedLabels.includes( item.dataset.galleryLabel );
						item.classList.toggle( 'filtered', ! isVisible );
					} );
					updateShowAllVisibility();

					// Fade the container back in.
					sectionContainer.classList.remove( 'is-filtering' );
				}, FILTER_FADE_DURATION_MS );
			};

			const resetFilters = () => {
				sectionItems.forEach( ( item ) => item.classList.remove( 'filtered' ) );
				checkboxes.forEach( ( cb ) => ( cb.checked = false ) );
				toggleDropdown( false );
				updateShowAllVisibility();
			};

			if ( filterButton ) {
				filterButton.addEventListener( 'click', ( e ) => {
					e.stopPropagation();
					toggleDropdown( filterOptions.classList.contains( 'hidden' ) );
				} );
			}

			checkboxes.forEach( ( checkbox ) => checkbox.addEventListener( 'change', filterSections ) );
			if ( showAllButton ) {
				showAllButton.addEventListener( 'click', resetFilters );
			}

			updateShowAllVisibility();
		} );

	}
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initSectionList );

export { initSectionList };
