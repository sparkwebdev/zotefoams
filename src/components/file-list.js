/**
 * File List Component
 * Enhanced file filtering with multiple filters, comma-separated values and keyboard support
 */
import { ZotefoamsReadyUtils, ZotefoamsAccessibilityUtils } from '../utils/dom-utilities.js';

const FILTER_FADE_DURATION_MS = 200;
const KEYBOARD_NAV_DELAY_MS = 0;

function initFileList() {
	const fileListElements = document.querySelectorAll( '[data-component="file-list"]' );

	if ( fileListElements.length > 0 ) {
		fileListElements.forEach( function( container ) {
			const filterButton = container.querySelector( '[data-js="filter-toggle"]' );
			const filterOptions = container.querySelector( '[data-js="filter-options"]' );
			const checkboxes = Array.from( container.querySelectorAll( '[data-js="filter-checkbox"]' ) );
			const showAllButton = container.querySelector( '[data-js="file-list-show-all"]' );
			const fileItems = Array.from( container.querySelectorAll( '.file-list__item' ) );

			// Gather active filters by filter type.
			const getActiveFilters = () => {
				const activeFilters = {};
				checkboxes.forEach( ( cb ) => {
					const filterType = cb.dataset.filter;
					if ( cb.checked ) {
						if ( ! activeFilters[ filterType ] ) {
							activeFilters[ filterType ] = [];
						}
						activeFilters[ filterType ].push( cb.value );
					}
				} );
				return activeFilters;
			};

			const updateShowAllVisibility = () => {
				const totalSelected = checkboxes.filter( ( cb ) => cb.checked ).length;
				if ( showAllButton ) {
					showAllButton.classList.toggle( 'hidden', totalSelected === 0 );
				}
				// Toggle the "filtered" class on the container (.file-list element)
				container.classList.toggle( 'filtered', totalSelected > 0 );
			};

			// Filter file items: each item's data attribute may contain multiple values.
			const filterFiles = () => {
				// Get the tbody element inside the container.
				const tbody = container.querySelector( 'tbody' );

				// Fade out the tbody.
				tbody.classList.add( 'is-filtering' );

				setTimeout( () => {
					// Update the display of each file item based on the active filters.
					const activeFilters = getActiveFilters();
					fileItems.forEach( ( item ) => {
						let show = true;
						for ( const filterType in activeFilters ) {
							const dataValue = item.dataset[ filterType ] || '';
							const itemValues = dataValue
								.split( ',' )
								.map( ( v ) => v.trim() )
								.filter( ( v ) => v !== '' );
							const intersection = activeFilters[ filterType ].filter( ( val ) => itemValues.includes( val ) );
							if ( activeFilters[ filterType ].length && intersection.length === 0 ) {
								show = false;
								break;
							}
						}
						item.classList.toggle( 'filtered', ! show );
					} );
					updateShowAllVisibility();

					// Fade the tbody back in.
					tbody.classList.remove( 'is-filtering' );
				}, FILTER_FADE_DURATION_MS );
			};

			// Update the URL query parameters with comma-separated filter values.
			const updateURL = () => {
				const activeFilters = getActiveFilters();
				const params = new URLSearchParams();
				for ( const filterType in activeFilters ) {
					params.set( filterType, activeFilters[ filterType ].join( ',' ) );
				}
				const queryString = params.toString().replace( /%2C/gi, ',' );
				const newUrl = window.location.pathname + ( queryString ? '?' + queryString : '' );
				window.history.replaceState( {}, '', newUrl );
			};

			// Initialize filter checkboxes from the URL query parameters.
			const initializeFiltersFromURL = () => {
				const params = new URLSearchParams( window.location.search );
				checkboxes.forEach( ( cb ) => {
					const filterType = cb.dataset.filter;
					const valueParam = params.get( filterType );
					if ( valueParam ) {
						const values = valueParam
							.split( ',' )
							.map( ( v ) => v.trim() )
							.filter( ( v ) => v !== '' );
						if ( values.includes( cb.value ) ) {
							cb.checked = true;
						}
					}
				} );
				filterFiles();
			};

			// Filter dropdown — only set up if present in the DOM.
			if ( filterButton && filterOptions ) {
				// Close dropdown when user clicks outside it.
				const onOutsideClick = ( e ) => {
					if ( ! filterButton.contains( e.target ) && ! filterOptions.contains( e.target ) ) {
						toggleDropdown( false );
					}
				};

				// Toggle the dropdown menu.
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

				const resetFilters = () => {
					checkboxes.forEach( ( cb ) => ( cb.checked = false ) );
					toggleDropdown( false );
					updateURL();
					filterFiles();
				};

				// Keyboard support for the filter dropdown.
				filterOptions.addEventListener( 'keydown', function( e ) {
					const KEY_UP = 38,
						KEY_DOWN = 40,
						KEY_ESCAPE = 27,
						KEY_TAB = 9;
					if ( e.keyCode === KEY_DOWN ) {
						e.preventDefault();
						const currentIndex = checkboxes.findIndex( ( cb ) => cb === document.activeElement );
						if ( currentIndex === -1 || currentIndex === checkboxes.length - 1 ) {
							checkboxes[ 0 ].focus();
						} else {
							checkboxes[ currentIndex + 1 ].focus();
						}
					} else if ( e.keyCode === KEY_UP ) {
						e.preventDefault();
						const currentIndex = checkboxes.findIndex( ( cb ) => cb === document.activeElement );
						if ( currentIndex <= 0 ) {
							checkboxes[ checkboxes.length - 1 ].focus();
						} else {
							checkboxes[ currentIndex - 1 ].focus();
						}
					} else if ( e.keyCode === KEY_ESCAPE ) {
						e.preventDefault();
						toggleDropdown( false );
						filterButton.focus();
					} else if ( e.keyCode === KEY_TAB ) {
						// Allow Tab to move focus, then close the dropdown if focus moves outside.
						setTimeout( () => {
							if ( ! filterOptions.contains( document.activeElement ) ) {
								toggleDropdown( false );
							}
						}, KEYBOARD_NAV_DELAY_MS );
					}
				} );

				filterButton.addEventListener( 'click', ( e ) => {
					e.stopPropagation();
					const open = filterOptions.classList.contains( 'hidden' );
					toggleDropdown( open );
					if ( open && checkboxes.length > 0 ) {
						// Set focus to the first checkbox when opening the dropdown.
						checkboxes[ 0 ].focus();
					}
				} );

				checkboxes.forEach( ( cb ) => {
					cb.addEventListener( 'change', () => {
						updateURL();
						filterFiles();
					} );
				} );

				if ( showAllButton ) {
					showAllButton.addEventListener( 'click', resetFilters );
				}

				initializeFiltersFromURL();
			}
		} );
	}
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initFileList );

export { initFileList };
