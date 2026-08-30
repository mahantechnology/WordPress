<?php
/**
 * Loads every part of the theme and keeps a single shared instance around.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

final class Mahan_Theme {

	/**
	 * Shared instance.
	 *
	 * @var Mahan_Theme|null
	 */
	private static $instance = null;

	/**
	 * Instantiated component objects, keyed by class name.
	 *
	 * @var array<string,object>
	 */
	private $components = array();

	/**
	 * Returns the shared instance, creating it on first call.
	 *
	 * @return Mahan_Theme
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Pulls in the theme files and boots the components.
	 */
	private function __construct() {
		$this->load_files();
		$this->boot_components();
	}

	/**
	 * Requires the plain function libraries and the component classes.
	 */
	private function load_files() {
		$files = array(
			'helpers.php',
			'template-tags.php',
			'class-mahan-options.php',
			'class-mahan-setup.php',
			'class-mahan-assets.php',
			'class-mahan-nav-walker.php',
			'class-mahan-mega-menu.php',
			'class-mahan-breadcrumb.php',
			'class-mahan-post-types.php',
			'class-mahan-metabox.php',
			'class-mahan-widgets.php',
			'class-mahan-customizer.php',
			'class-mahan-ajax.php',
			'class-mahan-header.php',
			'class-mahan-footer.php',
			'class-mahan-blog.php',
			'class-mahan-seo.php',
			'class-mahan-performance.php',
			'class-mahan-plugin-notice.php',
			'demo/class-mahan-elementor-builder.php',
			'demo/class-mahan-demo-library.php',
			'demo/class-mahan-demo-importer.php',
			'demo/class-mahan-setup-wizard.php',
		);

		foreach ( $files as $file ) {
			require_once MAHAN_INC . $file;
		}

		if ( mahan_has_woocommerce() ) {
			require_once MAHAN_INC . 'class-mahan-woocommerce.php';
		}

		if ( mahan_has_elementor() ) {
			require_once MAHAN_INC . 'class-mahan-elementor.php';
		}
	}

	/**
	 * Creates one object per component and remembers it so templates can reach it.
	 */
	private function boot_components() {
		$classes = array(
			'Mahan_Setup',
			'Mahan_Assets',
			'Mahan_Post_Types',
			'Mahan_Metabox',
			'Mahan_Widgets',
			'Mahan_Customizer',
			'Mahan_Ajax',
			'Mahan_Header',
			'Mahan_Footer',
			'Mahan_Blog',
			'Mahan_Seo',
			'Mahan_Performance',
			'Mahan_Plugin_Notice',
			'Mahan_Mega_Menu',
			'Mahan_Demo_Importer',
			'Mahan_Setup_Wizard',
		);

		if ( mahan_has_woocommerce() ) {
			$classes[] = 'Mahan_WooCommerce';
		}

		if ( mahan_has_elementor() ) {
			$classes[] = 'Mahan_Elementor';
		}

		foreach ( $classes as $class ) {
			if ( class_exists( $class ) ) {
				$this->components[ $class ] = new $class();
			}
		}
	}

	/**
	 * Returns a booted component, or null when it was never loaded.
	 *
	 * @param string $class Component class name.
	 * @return object|null
	 */
	public function get( $class ) {
		return isset( $this->components[ $class ] ) ? $this->components[ $class ] : null;
	}
}
