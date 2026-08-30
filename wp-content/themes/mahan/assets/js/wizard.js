/**
 * The Appearance → Mahan Setup screen.
 *
 * Runs the import one step at a time so a slow host does not hit the PHP time
 * limit, and reflects each step's state in the progress dialog.
 */
( function () {
	'use strict';

	var config = window.mahanWizard || {};
	var i18n = config.i18n || {};

	function $( selector, scope ) {
		return ( scope || document ).querySelector( selector );
	}

	function $$( selector, scope ) {
		return Array.prototype.slice.call( ( scope || document ).querySelectorAll( selector ) );
	}

	function request( action, body ) {
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

	var modal = $( '[data-mahan-progress]' );
	var status = modal ? $( '.mahan-wizard__status', modal ) : null;
	var actions = modal ? $( '.mahan-wizard__actions', modal ) : null;

	function setStepState( step, state ) {
		var item = $( '[data-step="' + step + '"]', modal );

		if ( ! item ) {
			return;
		}

		item.classList.remove( 'is-running', 'is-done', 'is-failed' );

		if ( state ) {
			item.classList.add( 'is-' + state );
		}
	}

	function resetSteps() {
		$$( '.mahan-wizard__steps li', modal ).forEach( function ( item ) {
			item.classList.remove( 'is-running', 'is-done', 'is-failed' );
		} );

		status.textContent = '';
		status.classList.remove( 'is-error' );
		actions.hidden = true;
	}

	function runStep( demo, step ) {
		setStepState( step, 'running' );
		status.textContent = i18n.importing || '';

		return request( 'mahan_import_demo', { demo: demo, step: step } ).then( function ( response ) {
			if ( ! response || ! response.success ) {
				setStepState( step, 'failed' );

				var message = ( response && response.data && response.data.message ) || i18n.failed;

				status.textContent = message;
				status.classList.add( 'is-error' );
				actions.hidden = false;

				return null;
			}

			setStepState( step, 'done' );

			return response.data.next;
		} ).catch( function () {
			setStepState( step, 'failed' );
			status.textContent = i18n.failed || '';
			status.classList.add( 'is-error' );
			actions.hidden = false;

			return null;
		} );
	}

	function runImport( demo ) {
		var steps = Object.keys( config.steps || {} );

		if ( ! steps.length ) {
			return;
		}

		resetSteps();
		modal.hidden = false;

		function next( step ) {
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
		}

		next( steps[ 0 ] );
	}

	document.addEventListener( 'click', function ( event ) {
		var install = event.target.closest( '[data-mahan-install]' );

		if ( install ) {
			event.preventDefault();

			if ( ! window.confirm( i18n.confirm || '' ) ) {
				return;
			}

			runImport( install.getAttribute( 'data-mahan-install' ) );
			return;
		}

		if ( event.target.closest( '[data-mahan-close-progress]' ) ) {
			modal.hidden = true;
			window.location.reload();
			return;
		}

		var rollback = event.target.closest( '[data-mahan-rollback]' );

		if ( ! rollback ) {
			return;
		}

		event.preventDefault();

		if ( ! window.confirm( i18n.rollback || '' ) ) {
			return;
		}

		rollback.disabled = true;
		rollback.textContent = i18n.removing || '';

		request( 'mahan_rollback_demo', {} ).then( function () {
			window.location.reload();
		} ).catch( function () {
			rollback.disabled = false;
			rollback.textContent = i18n.failed || '';
		} );
	} );
}() );
