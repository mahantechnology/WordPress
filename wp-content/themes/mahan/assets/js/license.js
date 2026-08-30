/**
 * Licence screen behaviour: key formatting, activation, re-check and removal.
 */
( function () {
	'use strict';

	var config = window.mahanLicense || {};
	var i18n = config.i18n || {};
	var root = document.querySelector( '[data-mahan-license]' );

	if ( ! root ) {
		return;
	}

	var message = root.querySelector( '[data-mahan-license-message]' );
	var PATTERN = /^MT(-[A-Z0-9]{4}){5}$/;

	/**
	 * Shows a line under the form.
	 *
	 * @param {string} text  What to say.
	 * @param {string} state '', 'ok' or 'error'.
	 */
	function say( text, state ) {
		if ( ! message ) {
			return;
		}

		message.textContent = text || '';
		message.className = 'mahan-lic__message' + ( state ? ' is-' + state : '' );
	}

	/**
	 * Turns anything typed or pasted into MT-XXXX-XXXX-XXXX-XXXX-XXXX.
	 *
	 * @param {string} value Raw value.
	 * @return {string} Formatted key.
	 */
	function format( value ) {
		var clean = String( value ).toUpperCase().replace( /[^A-Z0-9]/g, '' );

		if ( 0 === clean.indexOf( 'MT' ) ) {
			clean = clean.slice( 2 );
		}

		clean = clean.slice( 0, 20 );

		var blocks = clean.match( /.{1,4}/g ) || [];

		return blocks.length ? 'MT-' + blocks.join( '-' ) : ( clean ? 'MT-' + clean : '' );
	}

	/**
	 * Posts one action to admin-ajax.
	 *
	 * @param {string} action Action suffix.
	 * @param {Object} extra  Extra body fields.
	 * @return {Promise<Object>} Parsed response.
	 */
	function send( action, extra ) {
		var body = new URLSearchParams();

		body.set( 'action', 'mahan_license_' + action );
		body.set( 'nonce', config.nonce || '' );

		Object.keys( extra || {} ).forEach( function ( key ) {
			body.set( key, extra[ key ] );
		} );

		return fetch( config.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: body.toString(),
		} ).then( function ( response ) {
			return response.json().catch( function () {
				return { success: false, data: { message: i18n.network } };
			} );
		} );
	}

	/**
	 * Locks the screen while a request is in flight.
	 *
	 * @param {boolean} busy Whether a request is running.
	 */
	function setBusy( busy ) {
		root.classList.toggle( 'is-busy', !! busy );

		root.querySelectorAll( 'button' ).forEach( function ( button ) {
			button.disabled = !! busy;
		} );
	}

	/* ----------------------------------------------------------------
	 * Activation form
	 * ------------------------------------------------------------- */

	var form = root.querySelector( '[data-mahan-license-form]' );
	var input = root.querySelector( '[data-mahan-license-input]' );

	if ( input ) {
		input.addEventListener( 'input', function () {
			// Keep the caret at the end: the value is rewritten on every keystroke.
			input.value = format( input.value );
			input.removeAttribute( 'aria-invalid' );
			say( '' );
		} );

		input.addEventListener( 'paste', function ( event ) {
			event.preventDefault();
			input.value = format( ( event.clipboardData || window.clipboardData ).getData( 'text' ) );
		} );

		input.focus();
	}

	if ( form ) {
		form.addEventListener( 'submit', function ( event ) {
			event.preventDefault();

			var key = input ? format( input.value ) : '';

			if ( ! key ) {
				input.setAttribute( 'aria-invalid', 'true' );
				say( i18n.empty, 'error' );
				return;
			}

			if ( ! PATTERN.test( key ) ) {
				input.setAttribute( 'aria-invalid', 'true' );
				say( i18n.malformed, 'error' );
				return;
			}

			setBusy( true );
			say( i18n.checking );

			send( 'activate', { license_key: key } )
				.then( function ( response ) {
					var data = response.data || {};

					if ( response.success ) {
						say( data.message, 'ok' );
						// Reload so the screen comes back in its congratulations state.
						window.setTimeout( function () {
							window.location.reload();
						}, 700 );
						return;
					}

					setBusy( false );
					input.setAttribute( 'aria-invalid', 'true' );
					say( data.message || i18n.network, 'error' );
				} )
				.catch( function () {
					setBusy( false );
					say( i18n.network, 'error' );
				} );
		} );
	}

	/* ----------------------------------------------------------------
	 * Re-check and removal
	 * ------------------------------------------------------------- */

	var refresh = root.querySelector( '[data-mahan-license-refresh]' );

	if ( refresh ) {
		refresh.addEventListener( 'click', function () {
			setBusy( true );
			say( i18n.checking );

			send( 'refresh', {} ).then( function ( response ) {
				var data = response.data || {};

				setBusy( false );
				say( data.message || '', response.success ? 'ok' : 'error' );

				if ( ! response.success && false === data.active ) {
					window.setTimeout( function () {
						window.location.reload();
					}, 1200 );
				}
			} ).catch( function () {
				setBusy( false );
				say( i18n.network, 'error' );
			} );
		} );
	}

	var remove = root.querySelector( '[data-mahan-license-remove]' );

	if ( remove ) {
		remove.addEventListener( 'click', function () {
			if ( ! window.confirm( i18n.confirm ) ) {
				return;
			}

			setBusy( true );
			say( i18n.removing );

			send( 'remove', {} ).then( function () {
				window.location.reload();
			} ).catch( function () {
				setBusy( false );
				say( i18n.network, 'error' );
			} );
		} );
	}

	/* ----------------------------------------------------------------
	 * Confetti on the congratulations state
	 * ------------------------------------------------------------- */

	var stage = root.querySelector( '[data-mahan-confetti]' );

	if ( stage && ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		var colours = [ '#6366f1', '#22d3ee', '#22c55e', '#f59e0b', '#f472b6', '#a855f7' ];
		var pieces = document.createDocumentFragment();

		for ( var i = 0; i < 46; i++ ) {
			var piece = document.createElement( 'i' );

			piece.style.insetInlineStart = ( Math.random() * 100 ).toFixed( 2 ) + '%';
			piece.style.background = colours[ i % colours.length ];
			piece.style.animationDuration = ( 2.4 + Math.random() * 1.8 ).toFixed( 2 ) + 's';
			piece.style.animationDelay = ( Math.random() * 0.9 ).toFixed( 2 ) + 's';
			piece.style.setProperty( '--drift', ( Math.random() * 160 - 80 ).toFixed( 0 ) + 'px' );
			piece.style.setProperty( '--spin', ( Math.random() * 720 + 240 ).toFixed( 0 ) + 'deg' );

			pieces.appendChild( piece );
		}

		stage.appendChild( pieces );
	}
}() );
