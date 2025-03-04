/**
 * navigation.js - Handles navigation menu functionality with accessibility support
 */
( () => {
	const siteNav = document.getElementById( 'site-navigation' );
	if ( ! siteNav ) {
		return;
	}

	const button = siteNav.querySelector( 'button' );
	const menu = siteNav.querySelector( 'ul' );
	const utilityMenu = document.querySelector( '.utility-menu' );
	const megaNavMode = 'hover'; // 'hover' or 'click'
	const hoverDelay = 200; // ms

	// Early returns for missing elements
	if ( ! button || ! menu ) {
		if ( ! menu && button ) {
			button.style.display = 'none';
		}
		return;
	}

	menu.classList.add( 'nav-menu' );

	// Get focusable elements in a container
	const getFocusable = ( container ) => Array.from(
		container.querySelectorAll( 'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])' )
	);

	// Close all open dropdown and mega menus
	const closeAll = () => {
		document.querySelectorAll( '.dropdown-active, .mega-menu.active' ).forEach( ( el ) =>
			el.classList.remove( 'dropdown-active', 'active' )
		);
		const container = document.querySelector( '.mega-menu-container' );
		if ( container ) {
			container.classList.remove( 'active' );
		}
		document.querySelectorAll( '[aria-controls]' ).forEach( ( link ) =>
			link.setAttribute( 'aria-expanded', 'false' )
		);
	};

	// Handle keyboard navigation within mega menu
	const handleMegaKeyNav = ( e ) => {
		const megaMenu = e.currentTarget;
		if ( e.key === 'Escape' ) {
			e.preventDefault();
			megaMenu.classList.remove( 'active' );
			const container = document.querySelector( '.mega-menu-container' );
			if ( container && ! container.querySelector( '.mega-menu.active' ) ) {
				container.classList.remove( 'active' );
			}
			if ( megaMenu._topLink ) {
				megaMenu._topLink.focus();
			}
			megaMenu.removeEventListener( 'keydown', handleMegaKeyNav );
		} else if ( e.key === 'Tab' ) {
			const focusable = getFocusable( megaMenu );
			const currentIndex = focusable.indexOf( document.activeElement );
			if ( ( e.shiftKey && currentIndex === 0 ) || ( ! e.shiftKey && currentIndex === focusable.length - 1 ) ) {
				e.preventDefault();
				megaMenu.classList.remove( 'active' );
				const topLinks = Array.from( document.querySelectorAll( '#menu-primary > li > a' ) );
				const index = topLinks.indexOf( megaMenu._topLink );
				const targetLink = e.shiftKey
					? ( index > 0 ? topLinks[ index - 1 ] : megaMenu._topLink )
					: ( index < topLinks.length - 1 ? topLinks[ index + 1 ] : megaMenu._topLink );
				targetLink.focus();
				megaMenu.removeEventListener( 'keydown', handleMegaKeyNav );
			}
		}
	};

	// Set up dropdown/mega menu handlers
	const setupDropdowns = ( menuElement ) => {
		menuElement.querySelectorAll( '.menu-item-has-children > a, [aria-controls]' ).forEach( ( link ) => {
			const controlId = link.getAttribute( 'aria-controls' );

			if ( controlId ) { // Mega menu setup
				const megaMenu = document.getElementById( controlId );
				if ( ! megaMenu ) {
					return;
				}

				if ( megaNavMode === 'hover' ) {
					let hideTimer = null;
					// Top-level item hover behavior
					const menuItem = link.parentNode;
					const clearTimer = () => {
						if ( hideTimer ) {
							clearTimeout( hideTimer );
							hideTimer = null;
						}
					};

					const showMenu = () => {
						clearTimer();
						closeAll();
						megaMenu.classList.add( 'active' );
						const container = document.querySelector( '.mega-menu-container' );
						if ( container ) {
							container.classList.add( 'active' );
						}
						link.setAttribute( 'aria-expanded', 'true' );
						megaMenu._topLink = link;
						megaMenu.addEventListener( 'keydown', handleMegaKeyNav );
					};

					const hideMenu = () => {
						hideTimer = setTimeout( () => {
							megaMenu.classList.remove( 'active' );
							const container = document.querySelector( '.mega-menu-container' );
							if ( container && ! container.querySelector( '.mega-menu.active' ) ) {
								container.classList.remove( 'active' );
							}
							link.setAttribute( 'aria-expanded', 'false' );
							megaMenu.removeEventListener( 'keydown', handleMegaKeyNav );
						}, hoverDelay );
					};

					// Mouse events
					menuItem.addEventListener( 'mouseenter', showMenu );
					menuItem.addEventListener( 'mouseleave', hideMenu );
					megaMenu.addEventListener( 'mouseenter', clearTimer );
					megaMenu.addEventListener( 'mouseleave', hideMenu );

					// Keyboard
					link.addEventListener( 'keydown', ( e ) => {
						if ( ( e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown' ) ) {
							e.preventDefault();
							const isActive = megaMenu.classList.contains( 'active' );

							if ( isActive ) {
								if ( e.key === 'ArrowDown' ) {
									// If menu is already open and Down is pressed, move focus to first item
									const firstFocusable = getFocusable( megaMenu )[ 0 ];
									if ( firstFocusable ) {
										firstFocusable.focus();
									}
									return;
								}
								megaMenu.classList.remove( 'active' );
								const container = document.querySelector( '.mega-menu-container' );
								if ( container && ! container.querySelector( '.mega-menu.active' ) ) {
									container.classList.remove( 'active' );
								}
								link.setAttribute( 'aria-expanded', 'false' );
								megaMenu.removeEventListener( 'keydown', handleMegaKeyNav );
							} else {
								closeAll();
								megaMenu.classList.add( 'active' );
								const container = document.querySelector( '.mega-menu-container' );
								if ( container ) {
									container.classList.add( 'active' );
								}
								link.setAttribute( 'aria-expanded', 'true' );
								megaMenu._topLink = link;

								// Focus first heading
								const heading = megaMenu.querySelector( '.mega-menu-intro > h2' );
								if ( heading ) {
									heading.setAttribute( 'tabindex', '-1' );
									setTimeout( () => heading.focus(), 200 );
								}
								megaMenu.addEventListener( 'keydown', handleMegaKeyNav );
							}
						} else if ( e.key === 'Escape' ) {
							closeAll();
							link.focus();
						}
					} );
				} else { // Click mega menu
					link.addEventListener( 'click', ( e ) => {
						e.preventDefault();
						const isActive = megaMenu.classList.contains( 'active' );
						closeAll();

						if ( ! isActive ) {
							megaMenu.classList.add( 'active' );
							const container = document.querySelector( '.mega-menu-container' );
							if ( container ) {
								container.classList.add( 'active' );
							}
							link.setAttribute( 'aria-expanded', 'true' );
							megaMenu._topLink = link;
							megaMenu.addEventListener( 'keydown', handleMegaKeyNav );
						}
					} );

					link.addEventListener( 'keydown', ( e ) => {
						if ( e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown' ) {
							e.preventDefault();
							const isActive = megaMenu.classList.contains( 'active' );

							if ( isActive && e.key === 'ArrowDown' ) {
								// If menu is already open and Down is pressed, move focus to first item
								const firstFocusable = getFocusable( megaMenu )[ 0 ];
								if ( firstFocusable ) {
									firstFocusable.focus();
								}
							} else {
								link.click();
							}
						} else if ( e.key === 'Escape' ) {
							closeAll();
							link.focus();
						}
					} );
				}
			} else { // Regular dropdown
				link.addEventListener( 'click', () => {
					const menuItem = link.parentNode;
					menuElement.querySelectorAll( '.dropdown-active' ).forEach( ( item ) => {
						if ( item !== menuItem ) {
							item.classList.remove( 'dropdown-active' );
						}
					} );
					menuItem.classList.toggle( 'dropdown-active' );
				} );

				link.addEventListener( 'keydown', ( e ) => {
					const menuItem = link.parentNode;
					if ( e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown' ) {
						e.preventDefault();
						menuItem.classList.toggle( 'dropdown-active' );

						if ( e.key === 'ArrowDown' && menuItem.classList.contains( 'dropdown-active' ) ) {
							// Move focus to first item in dropdown
							const submenu = menuItem.querySelector( 'ul' );
							if ( submenu ) {
								const firstLink = submenu.querySelector( 'a' );
								if ( firstLink ) {
									firstLink.focus();
								}
							}
						}
					} else if ( e.key === 'Escape' ) {
						closeAll();
						link.focus();
					}
				} );
			}
		} );
	};

	// Set up event listeners
	button.addEventListener( 'click', () => {
		siteNav.classList.toggle( 'toggled' );
		const isExpanded = button.getAttribute( 'aria-expanded' ) === 'true';
		button.setAttribute( 'aria-expanded', ( ! isExpanded ).toString() );
	} );

	setupDropdowns( siteNav );
	if ( utilityMenu ) {
		setupDropdowns( utilityMenu );
	}

	// Close when clicking outside
	document.addEventListener( 'click', ( e ) => {
		if ( ! siteNav.contains( e.target ) && ! ( utilityMenu && utilityMenu.contains( e.target ) ) ) {
			closeAll();
			siteNav.classList.remove( 'toggled' );
			button.setAttribute( 'aria-expanded', 'false' );
		}
	} );

	// Handle keyboard for standard links
	document.querySelectorAll( '.menu-item-has-children > a' ).forEach( ( link ) => {
		if ( ! link.getAttribute( 'aria-controls' ) ) {
			link.addEventListener( 'keydown', ( e ) => {
				const menuItem = link.parentNode;
				if ( ( e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown' ) &&
            ( menuItem.classList.contains( 'menu-item-has-children' ) || menuItem.querySelector( 'ul' ) ) ) {
					e.preventDefault();
					menuItem.classList.toggle( 'dropdown-active' );

					if ( e.key === 'ArrowDown' && menuItem.classList.contains( 'dropdown-active' ) ) {
						// Move focus to first item in dropdown
						const submenu = menuItem.querySelector( 'ul' );
						if ( submenu ) {
							const firstLink = submenu.querySelector( 'a' );
							if ( firstLink ) {
								firstLink.focus();
							}
						}
					}
				} else if ( e.key === 'Escape' ) {
					closeAll();
					link.focus();
				}
			} );
		}
	} );
} )();
