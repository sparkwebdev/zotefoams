/**
 * Tabbed Split Component
 * Handles tab switching functionality with content panels
 */
import { ZotefoamsDOMUtils, ZotefoamsEventUtils, ZotefoamsClassUtils, ZotefoamsReadyUtils } from '../utils/dom-utilities.js';

function initTabbedSplit() {
	const tabs = ZotefoamsDOMUtils.selectAll( '.tab' );
	const tabContents = ZotefoamsDOMUtils.selectAll( '.tab-content' );

	ZotefoamsEventUtils.onAll( tabs, 'click', function() {
		// Remove active class from all tabs and contents
		ZotefoamsClassUtils.removeAll( tabs, 'active' );
		ZotefoamsClassUtils.removeAll( tabContents, 'active' );

		// Add active class to clicked tab and corresponding content
		ZotefoamsClassUtils.add( this, 'active' );
		ZotefoamsClassUtils.add( ZotefoamsDOMUtils.select( `#${ this.dataset.tab }` ), 'active' );
	} );
}

// Initialize when DOM is ready
ZotefoamsReadyUtils.ready( initTabbedSplit );

export { initTabbedSplit };
