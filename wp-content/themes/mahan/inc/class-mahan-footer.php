<?php
/**
 * Footer rendering.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Footer {

	/**
	 * Hooks the footer parts.
	 */
	public function __construct() {
		add_action( 'mahan_footer', array( $this, 'newsletter' ), 10 );
		add_action( 'mahan_footer', array( $this, 'main' ), 20 );
		add_action( 'mahan_footer', array( $this, 'bottom' ), 30 );
	}

	/**
	 * Whether the footer should be printed.
	 *
	 * @return bool
	 */
	public static function is_hidden() {
		if ( ! is_singular() ) {
			return false;
		}

		$layout = get_post_meta( get_the_ID(), '_mahan_layout', true );

		return 'blank' === $layout || (bool) get_post_meta( get_the_ID(), '_mahan_hide_footer', true );
	}

	/**
	 * Prints the newsletter strip.
	 */
	public function newsletter() {
		if ( ! mahan_option( 'footer_newsletter' ) ) {
			return;
		}

		get_template_part( 'template-parts/footer/newsletter' );
	}

	/**
	 * Prints the widget columns for the configured layout.
	 */
	public function main() {
		$layout = mahan_sanitize_choice(
			mahan_option( 'footer_layout', 'columns' ),
			array( 'columns', 'compact', 'centered', 'shop' )
		);

		get_template_part( 'template-parts/footer/layout', $layout );
	}

	/**
	 * Prints the copyright row.
	 */
	public function bottom() {
		get_template_part( 'template-parts/footer/bottom' );
	}

	/**
	 * The copyright line with placeholders expanded.
	 *
	 * @return string
	 */
	public static function copyright() {
		$text = (string) mahan_option( 'footer_copyright' );

		$text = strtr(
			$text,
			array(
				'{site}' => get_bloginfo( 'name' ),
				'{year}' => mahan_fa_numbers( wp_date( 'Y' ) ),
			)
		);

		return wp_kses_post( $text );
	}
}
