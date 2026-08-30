<?php
/**
 * Theme options: defaults, reads and writes.
 *
 * Options live in a single `mahan_settings` theme mod so a demo import can
 * swap the whole set atomically and so exporting a configuration is one call.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Options {

	/**
	 * Theme mod key holding every setting.
	 */
	const KEY = 'mahan_settings';

	/**
	 * Runtime cache of the merged settings.
	 *
	 * @var array|null
	 */
	private static $cache = null;

	/**
	 * The full default configuration.
	 *
	 * @return array
	 */
	public static function defaults() {
		$defaults = array(
			// Identity.
			'palette'                 => 'royal',
			'color_primary'           => '#4f46e5',
			'color_secondary'         => '#0ea5e9',
			'color_accent'            => '#f59e0b',
			'color_success'           => '#16a34a',
			'color_danger'            => '#e11d48',
			'color_text'              => '#1f2937',
			'color_muted'             => '#6b7280',
			'color_surface'           => '#ffffff',
			'color_background'        => '#f6f7fb',
			'color_border'            => '#e6e8ef',

			// Dark palette.
			'dark_mode'               => 'toggle', // off | toggle | auto | always.
			'dark_color_text'         => '#e5e7eb',
			'dark_color_muted'        => '#9ca3af',
			'dark_color_surface'      => '#151a26',
			'dark_color_background'   => '#0b0f18',
			'dark_color_border'       => '#242b3a',

			// Typography.
			'font_family'             => 'vazirmatn',
			'font_size_base'          => 16,
			'font_scale'              => 1.22,
			'heading_weight'          => 700,
			'body_weight'             => 400,
			'line_height'             => 1.9,
			'persian_digits'          => true,
			'force_rtl'               => true,

			// Layout.
			'container_width'         => 1280,
			'radius'                  => 18,
			'shadow_strength'         => 'soft', // none | soft | strong.
			'section_spacing'         => 88,
			'sticky_sidebar'          => true,
			'page_transition'         => true,
			'back_to_top'             => true,
			'preloader'               => false,

			// Header.
			'header_layout'           => 'classic', // classic | centered | split | minimal | shop.
			'header_sticky'           => true,
			'header_sticky_shrink'    => true,
			'header_transparent'      => false,
			'header_search'           => true,
			'header_account'          => true,
			'header_cart'             => true,
			'header_wishlist'         => true,
			'header_compare'          => false,
			'header_dark'             => false,
			'topbar_enabled'          => true,
			'topbar_text'             => 'ارسال رایگان برای سفارش‌های بالای ۵۰۰ هزار تومان',
			'topbar_phone'            => '۰۲۱-۱۲۳۴۵۶۷۸',
			'mobile_menu_style'       => 'drawer', // drawer | fullscreen.
			'mobile_bottom_bar'       => true,

			// Footer.
			'footer_layout'           => 'columns', // columns | compact | centered | shop.
			'footer_columns'          => 4,
			'footer_dark'             => true,
			'footer_newsletter'       => true,
			'footer_about_title'      => 'درباره ما',
			'footer_about_text'       => 'ما با تکیه بر تجربه و فناوری روز، راهکارهایی می‌سازیم که کسب‌وکار شما را یک گام جلوتر می‌برد.',
			'footer_copyright'        => 'تمامی حقوق این وب‌سایت متعلق به {site} است.',
			'footer_badges'           => true,

			// Blog.
			'blog_layout'             => 'grid', // grid | list | masonry | magazine.
			'blog_columns'            => 3,
			'blog_sidebar'            => 'right', // right | left | none.
			'blog_excerpt_length'     => 26,
			'blog_show_author'        => true,
			'blog_show_date'          => true,
			'blog_show_category'      => true,
			'blog_show_reading_time'  => true,
			'blog_show_views'         => true,
			'blog_pagination'         => 'numbers', // numbers | loadmore | infinite.

			// Single post.
			'single_layout'           => 'sidebar', // sidebar | narrow | wide.
			'single_sidebar'          => 'right',
			'single_share'            => true,
			'single_toc'              => true,
			'single_author_box'       => true,
			'single_related'          => true,
			'single_related_count'    => 3,
			'single_progress_bar'     => true,
			'single_prev_next'        => true,

			// Shop.
			'shop_columns'            => 4,
			'shop_per_page'           => 12,
			'shop_sidebar'            => 'right',
			'shop_card_style'         => 'modern', // modern | classic | minimal | overlay.
			'shop_quick_view'         => true,
			'shop_ajax_add_to_cart'   => true,
			'shop_wishlist'           => true,
			'shop_badge_discount'     => true,
			'shop_hover_gallery'      => true,
			'shop_stock_bar'          => true,
			'single_product_layout'   => 'modern', // modern | classic | wide.
			'shop_sticky_add_to_cart' => true,
			'shop_trust_badges'       => true,

			// Contact / social.
			'social_instagram'        => '',
			'social_telegram'         => '',
			'social_whatsapp'         => '',
			'social_linkedin'         => '',
			'social_twitter'          => '',
			'social_youtube'          => '',
			'social_aparat'           => '',
			'contact_phone'           => '',
			'contact_email'           => '',
			'contact_address'         => '',

			// Performance.
			'lazy_load'               => true,
			'disable_emoji'           => true,
			'disable_embeds'          => false,
			'preload_fonts'           => true,
		);

		/**
		 * Filters the theme's default settings.
		 *
		 * @param array $defaults Default settings.
		 */
		return apply_filters( 'mahan_default_options', $defaults );
	}

	/**
	 * All settings, defaults merged with what has been saved.
	 *
	 * @return array
	 */
	public static function all() {
		if ( null === self::$cache ) {
			$saved = get_theme_mod( self::KEY, array() );
			$saved = is_array( $saved ) ? $saved : array();

			self::$cache = array_merge( self::defaults(), $saved );
		}

		return self::$cache;
	}

	/**
	 * Reads one setting.
	 *
	 * @param string $key     Setting key.
	 * @param mixed  $default Fallback when the key is unknown.
	 * @return mixed
	 */
	public static function get( $key, $default = null ) {
		$all = self::all();

		if ( array_key_exists( $key, $all ) ) {
			/**
			 * Filters a single option value as it is read.
			 *
			 * @param mixed  $value The value.
			 * @param string $key   The option key.
			 */
			return apply_filters( 'mahan_option', $all[ $key ], $key );
		}

		return $default;
	}

	/**
	 * Writes one setting.
	 *
	 * @param string $key   Setting key.
	 * @param mixed  $value New value.
	 */
	public static function set( $key, $value ) {
		$all         = self::all();
		$all[ $key ] = $value;

		self::save( $all );
	}

	/**
	 * Replaces the whole settings array.
	 *
	 * @param array $settings Settings to store.
	 */
	public static function save( array $settings ) {
		$defaults = self::defaults();
		$clean    = array();

		// Only keep keys the theme knows about, so a bad import cannot inject junk.
		foreach ( $settings as $key => $value ) {
			if ( array_key_exists( $key, $defaults ) ) {
				$clean[ $key ] = self::sanitize( $key, $value );
			}
		}

		set_theme_mod( self::KEY, $clean );
		self::$cache = null;
	}

	/**
	 * Merges a partial set of settings into what is stored.
	 *
	 * @param array $settings Settings to merge in.
	 */
	public static function merge( array $settings ) {
		self::save( array_merge( self::all(), $settings ) );
	}

	/**
	 * Restores the defaults.
	 */
	public static function reset() {
		remove_theme_mod( self::KEY );
		self::$cache = null;
	}

	/**
	 * Sanitizes a value using the type of its default.
	 *
	 * @param string $key   Setting key.
	 * @param mixed  $value Raw value.
	 * @return mixed
	 */
	public static function sanitize( $key, $value ) {
		$defaults = self::defaults();
		$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		if ( is_bool( $default ) ) {
			return (bool) $value;
		}

		if ( is_int( $default ) ) {
			return (int) $value;
		}

		if ( is_float( $default ) ) {
			return (float) $value;
		}

		if ( 0 === strpos( $key, 'color_' ) || 0 === strpos( $key, 'dark_color_' ) ) {
			$hex = sanitize_hex_color( $value );

			return $hex ? $hex : $default;
		}

		if ( 0 === strpos( $key, 'social_' ) ) {
			return esc_url_raw( $value );
		}

		if ( is_array( $default ) ) {
			return is_array( $value ) ? $value : $default;
		}

		return wp_kses_post( $value );
	}

	/**
	 * The bundled colour palettes offered in the customizer and the wizard.
	 *
	 * @return array<string,array>
	 */
	public static function palettes() {
		$palettes = array(
			'royal'     => array(
				'label'     => __( 'بنفش سلطنتی', 'mahan' ),
				'primary'   => '#4f46e5',
				'secondary' => '#0ea5e9',
				'accent'    => '#f59e0b',
			),
			'emerald'   => array(
				'label'     => __( 'سبز زمردی', 'mahan' ),
				'primary'   => '#059669',
				'secondary' => '#0d9488',
				'accent'    => '#f59e0b',
			),
			'crimson'   => array(
				'label'     => __( 'قرمز آتشین', 'mahan' ),
				'primary'   => '#e11d48',
				'secondary' => '#f97316',
				'accent'    => '#facc15',
			),
			'ocean'     => array(
				'label'     => __( 'آبی اقیانوسی', 'mahan' ),
				'primary'   => '#0284c7',
				'secondary' => '#0891b2',
				'accent'    => '#22d3ee',
			),
			'midnight'  => array(
				'label'     => __( 'سرمه‌ای شب', 'mahan' ),
				'primary'   => '#1e293b',
				'secondary' => '#475569',
				'accent'    => '#38bdf8',
			),
			'sunset'    => array(
				'label'     => __( 'نارنجی غروب', 'mahan' ),
				'primary'   => '#ea580c',
				'secondary' => '#db2777',
				'accent'    => '#fbbf24',
			),
			'sakura'    => array(
				'label'     => __( 'صورتی شکوفه', 'mahan' ),
				'primary'   => '#db2777',
				'secondary' => '#a855f7',
				'accent'    => '#fb7185',
			),
			'graphite'  => array(
				'label'     => __( 'خاکستری مدرن', 'mahan' ),
				'primary'   => '#374151',
				'secondary' => '#6b7280',
				'accent'    => '#f59e0b',
			),
			'saffron'   => array(
				'label'     => __( 'زعفرانی', 'mahan' ),
				'primary'   => '#b45309',
				'secondary' => '#d97706',
				'accent'    => '#65a30d',
			),
			'turquoise' => array(
				'label'     => __( 'فیروزه‌ای ایرانی', 'mahan' ),
				'primary'   => '#0d9488',
				'secondary' => '#1d4ed8',
				'accent'    => '#f43f5e',
			),
		);

		/**
		 * Filters the bundled colour palettes.
		 *
		 * @param array $palettes Palette definitions.
		 */
		return apply_filters( 'mahan_palettes', $palettes );
	}

	/**
	 * Applies a named palette to the colour settings.
	 *
	 * @param string $name Palette key.
	 * @return bool Whether the palette existed.
	 */
	public static function apply_palette( $name ) {
		$palettes = self::palettes();

		if ( ! isset( $palettes[ $name ] ) ) {
			return false;
		}

		$palette = $palettes[ $name ];

		self::merge(
			array(
				'palette'         => $name,
				'color_primary'   => $palette['primary'],
				'color_secondary' => $palette['secondary'],
				'color_accent'    => $palette['accent'],
			)
		);

		return true;
	}

	/**
	 * The bundled font stacks.
	 *
	 * @return array<string,array>
	 */
	public static function fonts() {
		return array(
			'vazirmatn' => array(
				'label' => __( 'وزیرمتن (پیش‌فرض)', 'mahan' ),
				'stack' => "'Vazirmatn', 'Segoe UI', Tahoma, sans-serif",
				'local' => true,
			),
			'tahoma'    => array(
				'label' => __( 'تاهوما (سیستمی)', 'mahan' ),
				'stack' => "Tahoma, 'Segoe UI', Arial, sans-serif",
				'local' => false,
			),
			'system'    => array(
				'label' => __( 'فونت پیش‌فرض سیستم', 'mahan' ),
				'stack' => "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Tahoma, sans-serif",
				'local' => false,
			),
		);
	}

	/**
	 * The active font stack.
	 *
	 * @return string
	 */
	public static function font_stack() {
		$fonts = self::fonts();
		$key   = self::get( 'font_family', 'vazirmatn' );

		return isset( $fonts[ $key ] ) ? $fonts[ $key ]['stack'] : $fonts['vazirmatn']['stack'];
	}
}
