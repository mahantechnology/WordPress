<?php
/**
 * Elementor integration: category, controls, widget registration and the
 * theme-location support that lets Elementor Pro build the header and footer.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Elementor {

	/**
	 * The widget files, relative to inc/elementor/widgets/ and without .php.
	 *
	 * @var string[]
	 */
	private $widgets = array(
		// Layout and headings.
		'section-heading',
		'hero-banner',
		'hero-slider',
		'feature-grid',
		'icon-box',
		'image-box',
		'cta-banner',
		'divider',
		'button-group',
		'stats-counter',
		'progress-bars',
		'pricing-table',
		'faq-accordion',
		'tabs',
		'timeline',
		'team-grid',
		'testimonial-carousel',
		'logo-carousel',
		'gallery-grid',
		'video-popup',
		'countdown',
		'typewriter',
		'marquee',
		'before-after',
		'contact-info',
		'newsletter-form',
		'social-icons',
		'map-embed',
		'lottie-player',
		'card-flip',
		'process-steps',
		'compare-table',
		// Content.
		'post-grid',
		'post-carousel',
		'post-list',
		'post-tabs',
		'category-boxes',
		'portfolio-grid',
		'service-grid',
		'breadcrumb',
		'search-box',
	);

	/**
	 * WooCommerce-only widget files.
	 *
	 * @var string[]
	 */
	private $woo_widgets = array(
		'product-grid',
		'product-carousel',
		'product-categories',
		'product-tabs',
		'product-deal',
		'product-banner',
	);

	/**
	 * Hooks the Elementor integration.
	 */
	public function __construct() {
		add_action( 'elementor/elements/categories_registered', array( $this, 'register_category' ) );
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'frontend_styles' ) );
		add_action( 'elementor/theme/register_locations', array( $this, 'register_locations' ) );
		add_action( 'elementor/kit/register_tabs', array( $this, 'sync_kit' ), 20 );
		add_filter( 'elementor/utils/get_the_archive_title', array( $this, 'archive_title' ) );
	}

	/**
	 * Adds the "Mahan" panel category.
	 *
	 * @param \Elementor\Elements_Manager $manager Elements manager.
	 */
	public function register_category( $manager ) {
		$manager->add_category(
			'mahan',
			array(
				'title' => __( 'المان‌های ماهان', 'mahan' ),
				'icon'  => 'eicon-star',
			),
			1
		);

		if ( mahan_has_woocommerce() ) {
			$manager->add_category(
				'mahan-woo',
				array(
					'title' => __( 'فروشگاه ماهان', 'mahan' ),
					'icon'  => 'eicon-woocommerce',
				),
				2
			);
		}
	}

	/**
	 * Loads and registers every widget class.
	 *
	 * @param \Elementor\Widgets_Manager $manager Widgets manager.
	 */
	public function register_widgets( $manager ) {
		require_once MAHAN_INC . 'elementor/class-mahan-widget-base.php';
		require_once MAHAN_INC . 'elementor/trait-mahan-query.php';

		$files = $this->widgets;

		if ( mahan_has_woocommerce() ) {
			$files = array_merge( $files, $this->woo_widgets );
		}

		foreach ( $files as $file ) {
			$path = MAHAN_INC . 'elementor/widgets/' . $file . '.php';

			if ( ! file_exists( $path ) ) {
				continue;
			}

			require_once $path;

			$class = 'Mahan_Widget_' . str_replace( '-', '_', $file );

			if ( class_exists( $class ) ) {
				$manager->register( new $class() );
			}
		}
	}

	/**
	 * Editor-only styles for the panel icons.
	 */
	public function editor_styles() {
		wp_enqueue_style( 'mahan-elementor-editor', MAHAN_URI . 'assets/css/elementor-editor.css', array(), MAHAN_VERSION );
	}

	/**
	 * Front-end styles for the Mahan elements.
	 */
	public function frontend_styles() {
		wp_enqueue_style( 'mahan-elementor', MAHAN_URI . 'assets/css/elementor.css', array(), MAHAN_VERSION );
	}

	/**
	 * Declares the header/footer/single/archive locations for Elementor Pro.
	 *
	 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $manager Locations manager.
	 */
	public function register_locations( $manager ) {
		$manager->register_all_core_location();
	}

	/**
	 * Pushes the theme palette into the Elementor kit's global colours so both
	 * systems agree on what "primary" means.
	 */
	public function sync_kit() {
		if ( ! mahan_has_elementor() ) {
			return;
		}

		$kit_id = (int) get_option( 'elementor_active_kit' );

		if ( ! $kit_id || get_post_meta( $kit_id, '_mahan_palette_synced', true ) === mahan_option( 'palette' ) ) {
			return;
		}

		$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
		$settings = is_array( $settings ) ? $settings : array();

		$settings['system_colors'] = array(
			array(
				'_id'   => 'primary',
				'title' => __( 'اصلی', 'mahan' ),
				'color' => mahan_option( 'color_primary' ),
			),
			array(
				'_id'   => 'secondary',
				'title' => __( 'دوم', 'mahan' ),
				'color' => mahan_option( 'color_secondary' ),
			),
			array(
				'_id'   => 'text',
				'title' => __( 'متن', 'mahan' ),
				'color' => mahan_option( 'color_text' ),
			),
			array(
				'_id'   => 'accent',
				'title' => __( 'تأکید', 'mahan' ),
				'color' => mahan_option( 'color_accent' ),
			),
		);

		update_post_meta( $kit_id, '_elementor_page_settings', $settings );
		update_post_meta( $kit_id, '_mahan_palette_synced', mahan_option( 'palette' ) );
	}

	/**
	 * Uses the theme's clean archive titles inside Elementor.
	 *
	 * @param string $title Archive title.
	 * @return string
	 */
	public function archive_title( $title ) {
		return $title;
	}
}
