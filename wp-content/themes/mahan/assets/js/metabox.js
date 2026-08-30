/**
 * Page-settings metabox: hides the fields that do not apply to the chosen layout.
 */
( function ( $ ) {
	'use strict';

	$( function () {
		var layout = $( '#mahan-field-mahan_layout' );
		var sidebar = $( '#mahan-field-mahan_sidebar' ).closest( '.mahan-metabox__field' );
		var chrome = $( '#mahan-field-mahan_hide_header, #mahan-field-mahan_hide_footer' ).closest( '.mahan-metabox__field' );

		if ( ! layout.length ) {
			return;
		}

		function update() {
			var value = layout.val();

			// A blank canvas has no header, footer or sidebar to configure.
			sidebar.toggle( 'blank' !== value && 'full' !== value );
			chrome.toggle( 'blank' !== value );
		}

		layout.on( 'change', update );
		update();
	} );
}( window.jQuery ) );
