import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initFinancialDocumentsPickers() {
	document.querySelectorAll( '[data-js="financial-docs-picker"]' ).forEach( ( picker ) => {
		const yearSelect = picker.querySelector( '.yearSelect' );
		const documentLists = picker.querySelectorAll( '.document-year' );
		const status = picker.querySelector( '[data-js="financial-docs-status"]' );

		if ( ! yearSelect ) { return; }

		function updateDocumentList( year ) {
			let count = 0;
			documentLists.forEach( ( list ) => {
				const isActive = list.getAttribute( 'data-year' ) === year;
				list.style.display = isActive ? 'block' : 'none';
				if ( isActive ) {
					// li > div matches real document rows only, not the
					// "No documents available for this year" placeholder <li>.
					count = list.querySelectorAll( 'li > div' ).length;
				}
			} );
			if ( status ) {
				status.textContent = `Showing ${ count } document${ count === 1 ? '' : 's' } for ${ year }.`;
			}
		}

		yearSelect.addEventListener( 'change', function() {
			updateDocumentList( this.value );
		} );

		updateDocumentList( yearSelect.value );
	} );
}

ZotefoamsReadyUtils.ready( initFinancialDocumentsPickers );
