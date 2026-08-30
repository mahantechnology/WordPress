<?php
/**
 * Theme supports, image sizes, menus and sidebars.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Setup {

	/**
	 * Hooks the setup routines.
	 */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'setup' ) );
		add_action( 'after_setup_theme', array( $this, 'content_width' ), 0 );
		add_action( 'after_setup_theme', array( $this, 'force_rtl' ), 5 );
		add_action( 'init', array( $this, 'register_menus' ) );
		add_action( 'widgets_init', array( $this, 'register_sidebars' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_filter( 'excerpt_length', array( $this, 'excerpt_length' ), 999 );
		add_filter( 'excerpt_more', array( $this, 'excerpt_more' ) );
		add_filter( 'nav_menu_link_attributes', array( $this, 'menu_link_attributes' ), 10, 3 );
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_defaults' ) );
		add_filter( 'language_attributes', array( $this, 'language_attributes' ) );
	}

	/**
	 * Declares what the theme supports.
	 */
	public function setup() {
		load_theme_textdomain( 'mahan', MAHAN_DIR . 'languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_theme_support( 'appearance-tools' );

		add_editor_style( 'assets/css/editor.css' );

		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
		);

		add_theme_support(
			'custom-logo',
			array(
				'height'      => 64,
				'width'       => 260,
				'flex-height' => true,
				'flex-width'  => true,
				'unlink-homepage-logo' => false,
			)
		);

		add_theme_support(
			'custom-background',
			array( 'default-color' => 'f6f7fb' )
		);

		add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio', 'quote', 'link' ) );

		// WooCommerce.
		add_theme_support( 'woocommerce', array(
			'thumbnail_image_width' => 480,
			'single_image_width'    => 900,
			'product_grid'          => array(
				'default_rows'    => 3,
				'min_rows'        => 1,
				'default_columns' => 4,
				'min_columns'     => 2,
				'max_columns'     => 6,
			),
		) );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		$this->register_image_sizes();
		$this->register_editor_palette();
	}

	/**
	 * Registers the image sizes the templates and elements ask for.
	 */
	private function register_image_sizes() {
		add_image_size( 'mahan-card', 640, 420, true );
		add_image_size( 'mahan-card-tall', 640, 800, true );
		add_image_size( 'mahan-wide', 1200, 600, true );
		add_image_size( 'mahan-hero', 1920, 900, true );
		add_image_size( 'mahan-thumb', 180, 130, true );
		add_image_size( 'mahan-square', 600, 600, true );
		add_image_size( 'mahan-portrait', 500, 700, true );
	}

	/**
	 * Feeds the theme palette into the block editor.
	 */
	private function register_editor_palette() {
		$palette = array(
			array(
				'name'  => __( 'رنگ اصلی', 'mahan' ),
				'slug'  => 'mahan-primary',
				'color' => mahan_option( 'color_primary' ),
			),
			array(
				'name'  => __( 'رنگ دوم', 'mahan' ),
				'slug'  => 'mahan-secondary',
				'color' => mahan_option( 'color_secondary' ),
			),
			array(
				'name'  => __( 'رنگ تأکید', 'mahan' ),
				'slug'  => 'mahan-accent',
				'color' => mahan_option( 'color_accent' ),
			),
			array(
				'name'  => __( 'متن', 'mahan' ),
				'slug'  => 'mahan-text',
				'color' => mahan_option( 'color_text' ),
			),
			array(
				'name'  => __( 'پس‌زمینه', 'mahan' ),
				'slug'  => 'mahan-background',
				'color' => mahan_option( 'color_background' ),
			),
			array(
				'name'  => __( 'سفید', 'mahan' ),
				'slug'  => 'mahan-white',
				'color' => '#ffffff',
			),
		);

		add_theme_support( 'editor-color-palette', $palette );

		add_theme_support(
			'editor-font-sizes',
			array(
				array(
					'name' => __( 'کوچک', 'mahan' ),
					'slug' => 'small',
					'size' => 14,
				),
				array(
					'name' => __( 'معمولی', 'mahan' ),
					'slug' => 'normal',
					'size' => 16,
				),
				array(
					'name' => __( 'بزرگ', 'mahan' ),
					'slug' => 'large',
					'size' => 22,
				),
				array(
					'name' => __( 'خیلی بزرگ', 'mahan' ),
					'slug' => 'huge',
					'size' => 32,
				),
			)
		);
	}

	/**
	 * Sets `$content_width` from the container option.
	 */
	public function content_width() {
		global $content_width;

		if ( ! isset( $content_width ) ) {
			$content_width = (int) mahan_option( 'container_width', 1280 );
		}
	}

	/**
	 * Renders the site right-to-left even on a locale WordPress treats as LTR.
	 *
	 * Every string the theme and its starter sites ship is Persian, so mirroring
	 * the layout to LTR would break the design rather than adapt it. Site owners
	 * who translate the theme can turn this off in the customizer.
	 */
	public function force_rtl() {
		if ( ! mahan_option( 'force_rtl' ) || is_rtl() ) {
			return;
		}

		global $wp_locale;

		if ( $wp_locale instanceof WP_Locale ) {
			$wp_locale->text_direction = 'rtl';
		}
	}

	/**
	 * Registers the navigation locations.
	 */
	public function register_menus() {
		register_nav_menus(
			array(
				'primary'     => __( 'منوی اصلی', 'mahan' ),
				'secondary'   => __( 'منوی فرعی (نوار بالا)', 'mahan' ),
				'categories'  => __( 'منوی دسته‌بندی‌ها (فروشگاه)', 'mahan' ),
				'mobile'      => __( 'منوی موبایل', 'mahan' ),
				'footer'      => __( 'منوی فوتر', 'mahan' ),
				'footer_help' => __( 'منوی راهنمای فوتر', 'mahan' ),
				'account'     => __( 'منوی حساب کاربری', 'mahan' ),
			)
		);
	}

	/**
	 * Registers the widget areas.
	 */
	public function register_sidebars() {
		$areas = array(
			'sidebar-main'    => array( __( 'ستون کناری اصلی', 'mahan' ), __( 'در برگه‌ها و نوشته‌ها نمایش داده می‌شود.', 'mahan' ) ),
			'sidebar-blog'    => array( __( 'ستون کناری بلاگ', 'mahan' ), __( 'در آرشیو و تک‌نوشته‌ها نمایش داده می‌شود.', 'mahan' ) ),
			'sidebar-shop'    => array( __( 'ستون کناری فروشگاه', 'mahan' ), __( 'در آرشیو محصولات ووکامرس نمایش داده می‌شود.', 'mahan' ) ),
			'sidebar-product' => array( __( 'ستون کناری تک‌محصول', 'mahan' ), __( 'در صفحهٔ محصول نمایش داده می‌شود.', 'mahan' ) ),
		);

		foreach ( $areas as $id => $area ) {
			register_sidebar(
				array(
					'name'          => $area[0],
					'id'            => $id,
					'description'   => $area[1],
					'before_widget' => '<section id="%1$s" class="widget mahan-widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h3 class="widget-title mahan-widget__title"><span>',
					'after_title'   => '</span></h3>',
				)
			);
		}

		$columns = max( 1, min( 6, (int) mahan_option( 'footer_columns', 4 ) ) );

		for ( $i = 1; $i <= $columns; $i++ ) {
			register_sidebar(
				array(
					'name'          => sprintf(
						/* translators: %s: column number. */
						__( 'ستون %s فوتر', 'mahan' ),
						mahan_fa_numbers( $i )
					),
					'id'            => 'footer-' . $i,
					'description'   => __( 'ابزارک‌های این ستون در فوتر نمایش داده می‌شوند.', 'mahan' ),
					'before_widget' => '<section id="%1$s" class="widget mahan-footer-widget %2$s">',
					'after_widget'  => '</section>',
					'before_title'  => '<h4 class="widget-title mahan-footer-widget__title">',
					'after_title'   => '</h4>',
				)
			);
		}
	}

	/**
	 * Adds the classes templates and CSS rely on.
	 *
	 * @param array $classes Existing classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		$classes[] = 'mahan';
		$classes[] = 'mahan-header-' . sanitize_html_class( mahan_option( 'header_layout', 'classic' ) );
		$classes[] = 'mahan-footer-' . sanitize_html_class( mahan_option( 'footer_layout', 'columns' ) );
		$classes[] = 'mahan-shadow-' . sanitize_html_class( mahan_option( 'shadow_strength', 'soft' ) );

		if ( mahan_option( 'header_sticky' ) ) {
			$classes[] = 'mahan-has-sticky-header';
		}

		if ( mahan_option( 'header_transparent' ) && ( is_front_page() || is_page_template( 'templates/template-transparent.php' ) ) ) {
			$classes[] = 'mahan-transparent-header';
		}

		if ( mahan_option( 'mobile_bottom_bar' ) ) {
			$classes[] = 'mahan-has-bottom-bar';
		}

		if ( 'always' === mahan_option( 'dark_mode' ) ) {
			$classes[] = 'mahan-dark';
		}

		if ( mahan_is_built_with_elementor() ) {
			$classes[] = 'mahan-elementor-page';
		}

		$sidebar = mahan_current_sidebar_position();
		$classes[] = 'mahan-sidebar-' . sanitize_html_class( $sidebar );

		return $classes;
	}

	/**
	 * Uses the configured excerpt length.
	 *
	 * @param int $length Default length.
	 * @return int
	 */
	public function excerpt_length( $length ) {
		return (int) mahan_option( 'blog_excerpt_length', 26 );
	}

	/**
	 * Replaces the excerpt ellipsis with a Persian one.
	 *
	 * @param string $more Default string.
	 * @return string
	 */
	public function excerpt_more( $more ) {
		return ' …';
	}

	/**
	 * Adds a class to every menu link so the CSS has a hook.
	 *
	 * @param array   $atts Link attributes.
	 * @param WP_Post $item Menu item.
	 * @param object  $args Menu args.
	 * @return array
	 */
	public function menu_link_attributes( $atts, $item, $args ) {
		$location = isset( $args->theme_location ) ? $args->theme_location : '';

		if ( ! in_array( $location, array( 'primary', 'mobile', 'categories' ), true ) ) {
			return $atts;
		}

		$classes = isset( $atts['class'] ) ? preg_split( '/\s+/', $atts['class'], -1, PREG_SPLIT_NO_EMPTY ) : array();

		// Mahan_Nav_Walker sets this too, so only add it for menus printed with another walker.
		if ( ! in_array( 'mahan-menu__link', $classes, true ) ) {
			$classes[] = 'mahan-menu__link';
		}

		$atts['class'] = implode( ' ', $classes );

		return $atts;
	}

	/**
	 * Falls back to the theme walker whenever a menu is printed without one.
	 *
	 * @param array $args Menu args.
	 * @return array
	 */
	public function nav_menu_defaults( $args ) {
		if ( empty( $args['walker'] ) && in_array( $args['theme_location'], array( 'primary', 'mobile' ), true ) ) {
			$args['walker'] = new Mahan_Nav_Walker();
		}

		return $args;
	}

	/**
	 * Marks the document as RTL for Persian even when the site language is not fa_IR.
	 *
	 * @param string $output Existing attributes.
	 * @return string
	 */
	public function language_attributes( $output ) {
		if ( false === strpos( $output, 'dir=' ) && is_rtl() ) {
			$output .= ' dir="rtl"';
		}

		return $output;
	}
}
