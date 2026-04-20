import { ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initFinancialDocumentsPickers() {
	document.querySelectorAll( '[data-js="financial-docs-picker"]' ).forEach( ( picker ) => {
		const yearSelect = picker.querySelector( '.yearSelect' );
		const documentLists = picker.querySelectorAll( '.document-year' );

		if ( ! yearSelect ) { return; }

		function updateDocumentList( year ) {
			documentLists.forEach( ( list ) => {
				list.style.display = list.getAttribute( 'data-year' ) === year ? 'block' : 'none';
			} );
		}

		yearSelect.addEventListener( 'change', function() {
			updateDocumentList( this.value );
		} );

		updateDocumentList( yearSelect.value );
	} );
}

ZotefoamsReadyUtils.ready( initFinancialDocumentsPickers );
