/**
 * The «قالب ماهان» admin panel.
 *
 * Drives the starter-site import, the search and filter controls on the
 * starter-site and element screens, and the small conveniences on the settings
 * form. No jQuery.
 */
( function () {
	'use strict';

	var config = window.mahanPanel || {};
	var i18n = config.i18n || {};

	function $( selector, scope ) {
		return ( scope || document ).querySelector( selector );
	}

	function $$( selector, scope ) {
		return Array.prototype.slice.call( ( scope || document ).querySelectorAll( selector ) );
	}

	function post( action, body ) {
		var payload = new FormData();

		payload.append( 'action', action );
		payload.append( 'nonce', config.nonce );

		Object.keys( body || {} ).forEach( function ( key ) {
			payload.append( key, body[ key ] );
		} );

		return fetch( config.ajaxUrl, {
			method: 'POST',
			credentials: 'same-origin',
			body: payload
		} ).then( function ( response ) {
			return response.json();
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Starter-site import
	 * ---------------------------------------------------------------- */

	function initImport() {
		var modal = $( '[data-mahan-progress]' );

		if ( ! modal ) {
			return;
		}

		var status = $( '.mahan-panel__status', modal );
		var actions = $( '.mahan-panel__modal-actions', modal );

		function markStep( step, state ) {
			var item = $( '[data-step="' + step + '"]', modal );

			if ( ! item ) {
				return;
			}

			item.classList.remove( 'is-running', 'is-done', 'is-failed' );

			if ( state ) {
				item.classList.add( 'is-' + state );
			}
		}

		function fail( step, message ) {
			markStep( step, 'failed' );
			status.textContent = message || i18n.failed || '';
			status.classList.add( 'is-error' );
			actions.hidden = false;
		}

		function runStep( demo, step ) {
			markStep( step, 'running' );
			status.textContent = i18n.importing || '';

			return post( 'mahan_import_demo', { demo: demo, step: step } )
				.then( function ( response ) {
					if ( ! response || ! response.success ) {
						fail( step, response && response.data && response.data.message );

						return null;
					}

					// A step that asks to run again stays in the running state.
					markStep( step, response.data.repeat ? 'running' : 'done' );

					if ( response.data.note ) {
						status.textContent = response.data.note;
					}

					return response.data.next;
				} )
				.catch( function () {
					fail( step );

					return null;
				} );
		}

		function run( demo ) {
			var steps = Object.keys( config.steps || {} );

			if ( ! steps.length ) {
				return;
			}

			$$( '.mahan-panel__steps li', modal ).forEach( function ( item ) {
				item.classList.remove( 'is-running', 'is-done', 'is-failed' );
			} );

			status.textContent = '';
			status.classList.remove( 'is-error' );
			actions.hidden = true;
			modal.hidden = false;

			( function next( step ) {
				if ( ! step ) {
					return;
				}

				runStep( demo, step ).then( function ( following ) {
					if ( following ) {
						next( following );

						return;
					}

					if ( ! status.classList.contains( 'is-error' ) ) {
						status.textContent = i18n.done || '';
						actions.hidden = false;
					}
				} );
			}( steps[ 0 ] ) );
		}

		document.addEventListener( 'click', function ( event ) {
			var install = event.target.closest( '[data-mahan-install]' );

			if ( install ) {
				event.preventDefault();

				if ( window.confirm( i18n.confirm || '' ) ) {
					run( install.getAttribute( 'data-mahan-install' ) );
				}

				return;
			}

			if ( event.target.closest( '[data-mahan-close-progress]' ) ) {
				modal.hidden = true;
				window.location.reload();
			}
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Rollback
	 * ---------------------------------------------------------------- */

	function initRollback() {
		var button = $( '[data-mahan-rollback]' );

		if ( ! button ) {
			return;
		}

		button.addEventListener( 'click', function () {
			if ( ! window.confirm( i18n.rollback || '' ) ) {
				return;
			}

			var label = $( 'span', button );

			button.disabled = true;

			if ( label ) {
				label.textContent = i18n.removing || '';
			}

			post( 'mahan_rollback_demo', {} )
				.then( function () {
					window.location.reload();
				} )
				.catch( function () {
					button.disabled = false;

					if ( label ) {
						label.textContent = i18n.failed || '';
					}
				} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Search and filter, shared by the starter-site and element screens
	 * ---------------------------------------------------------------- */

	function initFilterable( options ) {
		var input = $( options.input );
		var items = $$( options.item );
		var empty = $( options.empty );

		if ( ! items.length ) {
			return;
		}

		var term = '';
		var tag = '*';

		function apply() {
			var visible = 0;

			items.forEach( function ( item ) {
				var name = ( item.getAttribute( 'data-name' ) || item.textContent || '' ).toLowerCase();
				var terms = ( item.getAttribute( 'data-terms' ) || '' ).split( ' ' );
				var matchesTerm = ! term || name.indexOf( term ) !== -1;
				var matchesTag = '*' === tag || terms.indexOf( tag ) !== -1;
				var show = matchesTerm && matchesTag;

				item.classList.toggle( 'is-hidden', ! show );

				if ( show ) {
					visible += 1;
				}
			} );

			// A group whose children are all filtered out should go too.
			if ( options.group ) {
				$$( options.group ).forEach( function ( group ) {
					var shown = $$( options.item, group ).filter( function ( item ) {
						return ! item.classList.contains( 'is-hidden' );
					} );

					group.classList.toggle( 'is-hidden', 0 === shown.length );
				} );
			}

			if ( empty ) {
				empty.hidden = visible > 0;
			}
		}

		if ( input ) {
			input.addEventListener( 'input', function () {
				term = input.value.trim().toLowerCase();
				apply();
			} );
		}

		if ( options.chips ) {
			$$( options.chips ).forEach( function ( chip ) {
				chip.addEventListener( 'click', function () {
					$$( options.chips ).forEach( function ( other ) {
						other.classList.toggle( 'is-active', other === chip );
					} );

					tag = chip.getAttribute( 'data-mahan-demo-filter' );
					apply();
				} );
			} );
		}
	}

	/* ---------------------------------------------------------------- *
	 * Settings form conveniences
	 * ---------------------------------------------------------------- */

	function initSettings() {
		// Show the chosen colour next to each swatch.
		$$( '[data-mahan-color]' ).forEach( function ( input ) {
			var output = input.parentElement.querySelector( 'output' );

			if ( ! output ) {
				return;
			}

			input.addEventListener( 'input', function () {
				output.textContent = input.value;
			} );
		} );

		// Clicking a palette card selects it and previews its three colours.
		var select = $( '#mahan-field-palette' );

		$$( '[data-mahan-palette]' ).forEach( function ( swatch ) {
			swatch.addEventListener( 'click', function () {
				var key = swatch.getAttribute( 'data-mahan-palette' );

				if ( select ) {
					select.value = key;
				}

				$$( '[data-mahan-palette]' ).forEach( function ( other ) {
					other.classList.toggle( 'is-active', other === swatch );
				} );

				var palette = ( config.palettes || {} )[ key ];

				if ( ! palette ) {
					return;
				}

				[
					[ '#mahan-field-color_primary', palette.primary ],
					[ '#mahan-field-color_secondary', palette.secondary ],
					[ '#mahan-field-color_accent', palette.accent ]
				].forEach( function ( pair ) {
					var field = $( pair[ 0 ] );

					if ( ! field ) {
						return;
					}

					field.value = pair[ 1 ];
					field.dispatchEvent( new Event( 'input', { bubbles: true } ) );
				} );
			} );
		} );

		// The import form starts collapsed.
		var toggle = $( '[data-mahan-toggle-import]' );
		var form = $( '[data-mahan-import-form]' );

		if ( toggle && form ) {
			toggle.addEventListener( 'click', function () {
				form.hidden = ! form.hidden;

				if ( ! form.hidden ) {
					form.scrollIntoView( { behavior: 'smooth', block: 'nearest' } );
				}
			} );
		}

		// Destructive links confirm first.
		$$( '[data-mahan-confirm]' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( event ) {
				if ( ! window.confirm( link.getAttribute( 'data-mahan-confirm' ) ) ) {
					event.preventDefault();
				}
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * System report
	 * ---------------------------------------------------------------- */

	function initReport() {
		var button = $( '[data-mahan-copy-report]' );
		var table = $( '[data-mahan-report]' );

		if ( ! button || ! table || ! navigator.clipboard ) {
			return;
		}

		button.addEventListener( 'click', function () {
			var lines = $$( 'tr', table ).map( function ( row ) {
				var label = $( 'th', row );
				var value = $( '.mahan-panel__pill', row );

				return ( label ? label.textContent.trim() : '' ) + ': ' + ( value ? value.textContent.trim() : '' );
			} );

			navigator.clipboard.writeText( lines.join( '\n' ) ).then( function () {
				var label = $( 'span', button );
				var original = label ? label.textContent : '';

				if ( label ) {
					label.textContent = i18n.copied || '';

					window.setTimeout( function () {
						label.textContent = original;
					}, 1800 );
				}
			} );
		} );
	}

	/* ---------------------------------------------------------------- *
	 * Boot
	 * ---------------------------------------------------------------- */

	function boot() {
		initImport();
		initRollback();
		initSettings();
		initReport();

		initFilterable( {
			input: '[data-mahan-demo-search]',
			item: '.mahan-demo',
			chips: '[data-mahan-demo-filter]',
			empty: '[data-mahan-demo-empty]'
		} );

		initFilterable( {
			input: '[data-mahan-element-search]',
			item: '.mahan-panel__element',
			group: '[data-mahan-element-group]',
			empty: '[data-mahan-element-empty]'
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
}() );
