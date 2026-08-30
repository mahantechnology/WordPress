/**
 * Live preview for the customizer settings that map to a CSS custom property.
 */
( function ( api ) {
	'use strict';

	function bind( setting, apply ) {
		api( 'mahan_settings[' + setting + ']', function ( value ) {
			value.bind( apply );
		} );
	}

	function setVariable( name ) {
		return function ( value ) {
			document.documentElement.style.setProperty( name, value );
		};
	}

	function hexToRgb( hex ) {
		var clean = String( hex ).replace( '#', '' );

		if ( 3 === clean.length ) {
			clean = clean[ 0 ] + clean[ 0 ] + clean[ 1 ] + clean[ 1 ] + clean[ 2 ] + clean[ 2 ];
		}

		if ( 6 !== clean.length ) {
			return '0, 0, 0';
		}

		return [
			parseInt( clean.substring( 0, 2 ), 16 ),
			parseInt( clean.substring( 2, 4 ), 16 ),
			parseInt( clean.substring( 4, 6 ), 16 )
		].join( ', ' );
	}

	api( 'blogname', function ( value ) {
		value.bind( function ( text ) {
			var target = document.querySelector( '.mahan-logo__text' );

			if ( target ) {
				target.textContent = text;
			}
		} );
	} );

	bind( 'color_primary', function ( color ) {
		setVariable( '--mahan-primary' )( color );
		setVariable( '--mahan-primary-rgb' )( hexToRgb( color ) );
	} );

	bind( 'color_secondary', function ( color ) {
		setVariable( '--mahan-secondary' )( color );
		setVariable( '--mahan-secondary-rgb' )( hexToRgb( color ) );
		document.documentElement.style.setProperty(
			'--mahan-gradient',
			'linear-gradient(135deg, var(--mahan-primary) 0%, ' + color + ' 100%)'
		);
	} );

	bind( 'color_accent', setVariable( '--mahan-accent' ) );
	bind( 'color_text', setVariable( '--mahan-text' ) );
	bind( 'color_muted', setVariable( '--mahan-muted' ) );
	bind( 'color_surface', setVariable( '--mahan-surface' ) );
	bind( 'color_background', setVariable( '--mahan-bg' ) );
	bind( 'color_border', setVariable( '--mahan-border' ) );

	bind( 'font_size_base', function ( size ) {
		setVariable( '--mahan-fs-base' )( size + 'px' );
	} );

	bind( 'line_height', setVariable( '--mahan-lh' ) );

	bind( 'container_width', function ( width ) {
		setVariable( '--mahan-container' )( width + 'px' );
	} );

	bind( 'radius', function ( radius ) {
		setVariable( '--mahan-radius' )( radius + 'px' );
		setVariable( '--mahan-radius-sm' )( Math.max( 4, Math.round( radius * 0.5 ) ) + 'px' );
		setVariable( '--mahan-radius-lg' )( Math.round( radius * 1.5 ) + 'px' );
	} );

	bind( 'section_spacing', function ( spacing ) {
		setVariable( '--mahan-section-gap' )( spacing + 'px' );
	} );

	bind( 'topbar_text', function ( text ) {
		var target = document.querySelector( '.mahan-topbar__text span' );

		if ( target ) {
			target.textContent = text;
		}
	} );
}( window.wp.customize ) );
