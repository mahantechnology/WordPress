<?php
/**
 * Front-end weight trimming, all behind theme options.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Performance {

	/**
	 * Hooks the optional clean-ups.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'maybe_disable_emoji' ) );
		add_action( 'init', array( $this, 'maybe_disable_embeds' ) );
		add_filter( 'wp_lazy_loading_enabled', array( $this, 'lazy_loading' ), 10, 2 );
		add_action( 'wp_enqueue_scripts', array( $this, 'trim_block_library' ), 100 );
		add_filter( 'wp_resource_hints', array( $this, 'resource_hints' ), 10, 2 );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'rsd_link' );
	}

	/**
	 * Removes the emoji detection script and styles.
	 */
	public function maybe_disable_emoji() {
		if ( ! mahan_option( 'disable_emoji' ) ) {
			return;
		}

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		add_filter(
			'tiny_mce_plugins',
			static function ( $plugins ) {
				return is_array( $plugins ) ? array_diff( $plugins, array( 'wpemoji' ) ) : array();
			}
		);
	}

	/**
	 * Removes the oEmbed discovery links and the wp-embed script.
	 */
	public function maybe_disable_embeds() {
		if ( ! mahan_option( 'disable_embeds' ) ) {
			return;
		}

		remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
		remove_action( 'wp_head', 'wp_oembed_add_host_js' );

		add_action(
			'wp_footer',
			static function () {
				wp_dequeue_script( 'wp-embed' );
			}
		);
	}

	/**
	 * Honours the lazy-loading option.
	 *
	 * @param bool   $default Whether lazy loading is on.
	 * @param string $tag     Tag being filtered.
	 * @return bool
	 */
	public function lazy_loading( $default, $tag ) {
		return mahan_option( 'lazy_load' ) ? $default : false;
	}

	/**
	 * Drops the block library CSS on pages that hold no blocks.
	 */
	public function trim_block_library() {
		if ( is_admin() || mahan_is_elementor_editor() ) {
			return;
		}

		if ( ! is_singular() ) {
			return;
		}

		$post = get_post();

		if ( ! $post || has_blocks( $post ) ) {
			return;
		}

		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_dequeue_style( 'global-styles' );
	}

	/**
	 * Drops the s.w.org hint, which the theme never uses.
	 *
	 * @param array  $hints    Current hints.
	 * @param string $relation Hint type.
	 * @return array
	 */
	public function resource_hints( $hints, $relation ) {
		if ( 'dns-prefetch' !== $relation ) {
			return $hints;
		}

		return array_filter(
			$hints,
			static function ( $hint ) {
				$url = is_array( $hint ) && isset( $hint['href'] ) ? $hint['href'] : $hint;

				return is_string( $url ) ? false === strpos( $url, 's.w.org' ) : true;
			}
		);
	}
}
