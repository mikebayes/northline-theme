/**
 * Northline - primary navigation.
 *
 * The only JavaScript the theme ships: opens and closes the mobile menu and
 * keeps the toggle's ARIA state honest. Sub-menus are handled in CSS with
 * :hover and :focus-within, so keyboard users need no script at all.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '[data-nav-toggle]' );
	var panel = document.querySelector( '[data-nav-panel]' );

	if ( ! toggle || ! panel ) {
		return;
	}

	var DESKTOP = window.matchMedia( '(min-width: 62em)' );

	function setExpanded( isOpen ) {
		toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		panel.classList.toggle( 'is-open', isOpen );
	}

	function isExpanded() {
		return 'true' === toggle.getAttribute( 'aria-expanded' );
	}

	toggle.addEventListener( 'click', function () {
		setExpanded( ! isExpanded() );
	} );

	// Escape closes the menu and returns focus to the toggle.
	document.addEventListener( 'keydown', function ( event ) {
		if ( 'Escape' === event.key && isExpanded() ) {
			setExpanded( false );
			toggle.focus();
		}
	} );

	// A click outside the header closes the menu.
	document.addEventListener( 'click', function ( event ) {
		if ( ! isExpanded() ) {
			return;
		}

		if ( ! panel.contains( event.target ) && ! toggle.contains( event.target ) ) {
			setExpanded( false );
		}
	} );

	// Reset state when the layout crosses into the desktop breakpoint.
	DESKTOP.addEventListener( 'change', function ( event ) {
		if ( event.matches && isExpanded() ) {
			setExpanded( false );
		}
	} );
}() );
