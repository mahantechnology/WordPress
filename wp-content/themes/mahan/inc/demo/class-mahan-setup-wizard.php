<?php
/**
 * Sends someone arriving from theme activation, or from an old Appearance
 * bookmark, to the starter-site screen in the Mahan panel.
 *
 * The screen itself lives in inc/admin/views/starter-sites.php.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Setup_Wizard {

	/**
	 * The slug the wizard used before the panel existed.
	 */
	const LEGACY_SLUG = 'mahan-setup';

	/**
	 * Option recording that the welcome redirect has already run.
	 */
	const REDIRECT_OPTION = 'mahan_did_welcome_redirect';

	/**
	 * Hooks the redirects.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_legacy_page' ), 100 );
		add_action( 'after_switch_theme', array( $this, 'flag_redirect' ) );
		add_action( 'admin_init', array( $this, 'maybe_redirect' ) );
	}

	/**
	 * Keeps the old Appearance entry alive as a redirect target.
	 */
	public function register_legacy_page() {
		add_theme_page(
			__( 'قالب‌های آمادهٔ ماهان', 'mahan' ),
			__( 'قالب‌های آمادهٔ ماهان', 'mahan' ),
			Mahan_Admin::CAPABILITY,
			self::LEGACY_SLUG,
			array( $this, 'redirect_to_panel' )
		);
	}

	/**
	 * Bounces the legacy screen to the panel.
	 */
	public function redirect_to_panel() {
		wp_safe_redirect( mahan_is_licensed() ? Mahan_Admin::url( 'starter-sites' ) : Mahan_License_Screen::url() );
		exit;
	}

	/**
	 * Marks that the welcome redirect should run once.
	 */
	public function flag_redirect() {
		if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
			update_option( self::REDIRECT_OPTION, 0 );
		}
	}

	/**
	 * Opens the panel the first time after activation.
	 */
	public function maybe_redirect() {
		if ( '0' !== (string) get_option( self::REDIRECT_OPTION, '1' ) ) {
			return;
		}

		if ( ! current_user_can( Mahan_Admin::CAPABILITY ) || wp_doing_ajax() ) {
			return;
		}

		update_option( self::REDIRECT_OPTION, 1 );

		// Activation lands on the licence screen; the panel opens once the key checks out.
		wp_safe_redirect( mahan_is_licensed() ? Mahan_Admin::url() : Mahan_License_Screen::url() );
		exit;
	}
}
