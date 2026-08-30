<?php
/**
 * Stylesheet and script registration, plus the dynamic CSS custom properties
 * that carry the theme options into the browser.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Assets {

	/**
	 * Hooks the enqueue routines.
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'block_editor' ) );
		add_action( 'wp_head', array( $this, 'preload_fonts' ), 1 );
		add_action( 'wp_head', array( $this, 'dark_mode_bootstrap' ), 2 );
		add_filter( 'style_loader_tag', array( $this, 'defer_noncritical_css' ), 10, 4 );
	}

	/**
	 * Front-end styles and scripts.
	 */
	public function frontend() {
		wp_enqueue_style( 'mahan-fonts', MAHAN_URI . 'assets/css/fonts.css', array(), MAHAN_VERSION );
		wp_enqueue_style( 'mahan-main', MAHAN_URI . 'assets/css/main.css', array( 'mahan-fonts' ), MAHAN_VERSION );
		wp_enqueue_style( 'mahan-style', get_stylesheet_uri(), array( 'mahan-main' ), MAHAN_VERSION );

		if ( is_rtl() ) {
			wp_enqueue_style( 'mahan-rtl', MAHAN_URI . 'assets/css/rtl.css', array( 'mahan-main' ), MAHAN_VERSION );
		}

		if ( mahan_has_woocommerce() ) {
			wp_enqueue_style( 'mahan-woocommerce', MAHAN_URI . 'assets/css/woocommerce.css', array( 'mahan-main' ), MAHAN_VERSION );
		}

		wp_add_inline_style( 'mahan-main', $this->dynamic_css() );

		wp_enqueue_script( 'mahan-main', MAHAN_URI . 'assets/js/main.js', array(), MAHAN_VERSION, true );

		wp_localize_script(
			'mahan-main',
			'mahanData',
			array(
				'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'mahan_frontend' ),
				'restUrl'       => esc_url_raw( rest_url() ),
				'isRtl'         => is_rtl(),
				'darkMode'      => mahan_option( 'dark_mode' ),
				'persianDigits' => (bool) mahan_option( 'persian_digits' ),
				'hasWoo'        => mahan_has_woocommerce(),
				'ajaxCart'      => (bool) mahan_option( 'shop_ajax_add_to_cart' ),
				'quickView'     => (bool) mahan_option( 'shop_quick_view' ),
				'stickySidebar' => (bool) mahan_option( 'sticky_sidebar' ),
				'i18n'          => array(
					'loading'      => __( 'در حال بارگذاری…', 'mahan' ),
					'loadMore'     => __( 'نمایش بیشتر', 'mahan' ),
					'noMore'       => __( 'محتوای بیشتری وجود ندارد', 'mahan' ),
					'error'        => __( 'خطایی رخ داد. دوباره تلاش کنید.', 'mahan' ),
					'added'        => __( 'به سبد خرید اضافه شد', 'mahan' ),
					'noResults'    => __( 'نتیجه‌ای یافت نشد', 'mahan' ),
					'searchHint'   => __( 'حداقل ۳ حرف بنویسید…', 'mahan' ),
					'copied'       => __( 'کپی شد!', 'mahan' ),
					'days'         => __( 'روز', 'mahan' ),
					'hours'        => __( 'ساعت', 'mahan' ),
					'minutes'      => __( 'دقیقه', 'mahan' ),
					'seconds'      => __( 'ثانیه', 'mahan' ),
					'toc'          => __( 'فهرست مطالب', 'mahan' ),
				),
			)
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}

	/**
	 * Admin styles for the metaboxes and the setup wizard.
	 *
	 * @param string $hook Current admin page.
	 */
	public function admin( $hook ) {
		wp_enqueue_style( 'mahan-admin', MAHAN_URI . 'assets/css/admin.css', array(), MAHAN_VERSION );

		if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
			wp_enqueue_script( 'mahan-metabox', MAHAN_URI . 'assets/js/metabox.js', array( 'jquery' ), MAHAN_VERSION, true );
		}
	}

	/**
	 * Block editor styles so the editor matches the front end.
	 */
	public function block_editor() {
		wp_enqueue_style( 'mahan-editor', MAHAN_URI . 'assets/css/editor.css', array(), MAHAN_VERSION );
		wp_add_inline_style( 'mahan-editor', $this->dynamic_css( '.editor-styles-wrapper' ) );
	}

	/**
	 * Preloads the two font weights that are on the critical path.
	 */
	public function preload_fonts() {
		if ( ! mahan_option( 'preload_fonts' ) ) {
			return;
		}

		$fonts = Mahan_Options::fonts();
		$key   = mahan_option( 'font_family', 'vazirmatn' );

		if ( ! isset( $fonts[ $key ] ) || empty( $fonts[ $key ]['local'] ) ) {
			return;
		}

		foreach ( array( 'Regular', 'Bold' ) as $weight ) {
			printf(
				'<link rel="preload" as="font" type="font/woff2" href="%s" crossorigin>' . "\n",
				esc_url( MAHAN_URI . 'assets/fonts/vazirmatn/Vazirmatn-' . $weight . '.woff2' )
			);
		}
	}

	/**
	 * Applies the stored dark-mode choice before first paint so the page never
	 * flashes the light palette.
	 */
	public function dark_mode_bootstrap() {
		$mode = mahan_option( 'dark_mode' );

		if ( 'off' === $mode ) {
			return;
		}

		$script = "(function(){try{var m='" . esc_js( $mode ) . "',s=localStorage.getItem('mahan-theme'),d=false;" .
			"if(m==='always'){d=true;}else if(s==='dark'){d=true;}else if(s!=='light'&&m==='auto'){" .
			"d=window.matchMedia('(prefers-color-scheme: dark)').matches;}" .
			"if(d){document.documentElement.classList.add('mahan-dark');}}catch(e){}})();";

		printf( '<script>%s</script>' . "\n", $script ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Built from an escaped option above.
	}

	/**
	 * Loads the WooCommerce stylesheet without blocking first paint.
	 *
	 * @param string $tag    Link tag.
	 * @param string $handle Style handle.
	 * @param string $href   Stylesheet URL.
	 * @param string $media  Media attribute.
	 * @return string
	 */
	public function defer_noncritical_css( $tag, $handle, $href, $media ) {
		if ( 'mahan-woocommerce' !== $handle || is_admin() || mahan_is_elementor_editor() ) {
			return $tag;
		}

		// The shop pages need it immediately; everywhere else it can wait.
		if ( mahan_has_woocommerce() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			return $tag;
		}

		return str_replace(
			"media='" . $media . "'",
			"media='print' onload=\"this.media='" . $media . "'\"",
			$tag
		);
	}

	/**
	 * Builds the CSS custom properties from the theme options.
	 *
	 * @param string $scope Selector to attach the light palette to.
	 * @return string
	 */
	public function dynamic_css( $scope = ':root' ) {
		$primary   = mahan_option( 'color_primary' );
		$secondary = mahan_option( 'color_secondary' );
		$accent    = mahan_option( 'color_accent' );
		$radius    = (int) mahan_option( 'radius', 18 );
		$base      = (float) mahan_option( 'font_size_base', 16 );
		$scale     = (float) mahan_option( 'font_scale', 1.22 );

		$shadow_map = array(
			'none'   => array( 'none', 'none', 'none' ),
			'soft'   => array(
				'0 1px 2px rgba(15, 23, 42, .04), 0 2px 8px rgba(15, 23, 42, .05)',
				'0 4px 14px rgba(15, 23, 42, .07), 0 12px 32px rgba(15, 23, 42, .06)',
				'0 18px 50px rgba(15, 23, 42, .12)',
			),
			'strong' => array(
				'0 2px 4px rgba(15, 23, 42, .08), 0 4px 12px rgba(15, 23, 42, .08)',
				'0 8px 24px rgba(15, 23, 42, .12), 0 20px 48px rgba(15, 23, 42, .1)',
				'0 28px 70px rgba(15, 23, 42, .2)',
			),
		);

		$strength = mahan_option( 'shadow_strength', 'soft' );
		$shadows  = isset( $shadow_map[ $strength ] ) ? $shadow_map[ $strength ] : $shadow_map['soft'];

		$vars = array(
			'--mahan-primary'          => $primary,
			'--mahan-primary-rgb'      => mahan_hex_to_rgb( $primary ),
			'--mahan-primary-dark'     => mahan_shade_color( $primary, -18 ),
			'--mahan-primary-light'    => mahan_shade_color( $primary, 82 ),
			'--mahan-primary-contrast' => mahan_contrast_color( $primary ),
			'--mahan-secondary'        => $secondary,
			'--mahan-secondary-rgb'    => mahan_hex_to_rgb( $secondary ),
			'--mahan-secondary-light'  => mahan_shade_color( $secondary, 84 ),
			'--mahan-accent'           => $accent,
			'--mahan-accent-rgb'       => mahan_hex_to_rgb( $accent ),
			'--mahan-accent-light'     => mahan_shade_color( $accent, 84 ),
			'--mahan-success'          => mahan_option( 'color_success' ),
			'--mahan-danger'           => mahan_option( 'color_danger' ),
			'--mahan-text'             => mahan_option( 'color_text' ),
			'--mahan-muted'            => mahan_option( 'color_muted' ),
			'--mahan-surface'          => mahan_option( 'color_surface' ),
			'--mahan-surface-2'        => mahan_shade_color( mahan_option( 'color_background' ), 45 ),
			'--mahan-bg'               => mahan_option( 'color_background' ),
			'--mahan-border'           => mahan_option( 'color_border' ),
			'--mahan-gradient'         => sprintf( 'linear-gradient(135deg, %s 0%%, %s 100%%)', $primary, $secondary ),
			'--mahan-gradient-soft'    => sprintf(
				'linear-gradient(135deg, rgba(%s, .12) 0%%, rgba(%s, .12) 100%%)',
				mahan_hex_to_rgb( $primary ),
				mahan_hex_to_rgb( $secondary )
			),
			'--mahan-font'             => Mahan_Options::font_stack(),
			'--mahan-fs-base'          => $base . 'px',
			'--mahan-fs-sm'            => round( $base / $scale, 2 ) . 'px',
			'--mahan-fs-xs'            => round( $base / ( $scale * 1.12 ), 2 ) . 'px',
			'--mahan-fs-lg'            => round( $base * $scale, 2 ) . 'px',
			'--mahan-fs-h6'            => round( $base * pow( $scale, 1 ), 2 ) . 'px',
			'--mahan-fs-h5'            => round( $base * pow( $scale, 1.6 ), 2 ) . 'px',
			'--mahan-fs-h4'            => round( $base * pow( $scale, 2.2 ), 2 ) . 'px',
			'--mahan-fs-h3'            => round( $base * pow( $scale, 2.9 ), 2 ) . 'px',
			'--mahan-fs-h2'            => round( $base * pow( $scale, 3.6 ), 2 ) . 'px',
			'--mahan-fs-h1'            => round( $base * pow( $scale, 4.4 ), 2 ) . 'px',
			'--mahan-lh'               => (float) mahan_option( 'line_height', 1.9 ),
			'--mahan-heading-weight'   => (int) mahan_option( 'heading_weight', 700 ),
			'--mahan-body-weight'      => (int) mahan_option( 'body_weight', 400 ),
			'--mahan-container'        => (int) mahan_option( 'container_width', 1280 ) . 'px',
			'--mahan-radius'           => $radius . 'px',
			'--mahan-radius-sm'        => max( 4, round( $radius * 0.5 ) ) . 'px',
			'--mahan-radius-lg'        => round( $radius * 1.5 ) . 'px',
			'--mahan-radius-pill'      => '999px',
			'--mahan-section-gap'      => (int) mahan_option( 'section_spacing', 88 ) . 'px',
			'--mahan-shadow-sm'        => $shadows[0],
			'--mahan-shadow'           => $shadows[1],
			'--mahan-shadow-lg'        => $shadows[2],
		);

		$css = $scope . '{' . $this->stringify( $vars ) . '}';

		$dark = array(
			'--mahan-text'      => mahan_option( 'dark_color_text' ),
			'--mahan-muted'     => mahan_option( 'dark_color_muted' ),
			'--mahan-surface'   => mahan_option( 'dark_color_surface' ),
			'--mahan-surface-2' => mahan_shade_color( mahan_option( 'dark_color_surface' ), 8 ),
			'--mahan-bg'        => mahan_option( 'dark_color_background' ),
			'--mahan-border'    => mahan_option( 'dark_color_border' ),
			'--mahan-primary-light'   => mahan_shade_color( $primary, -55 ),
			'--mahan-secondary-light' => mahan_shade_color( $secondary, -55 ),
			'--mahan-accent-light'    => mahan_shade_color( $accent, -55 ),
			'--mahan-shadow-sm' => '0 1px 2px rgba(0, 0, 0, .3)',
			'--mahan-shadow'    => '0 6px 24px rgba(0, 0, 0, .38)',
			'--mahan-shadow-lg' => '0 24px 64px rgba(0, 0, 0, .5)',
		);

		$css .= '.mahan-dark{' . $this->stringify( $dark ) . '}';

		if ( mahan_option( 'container_width' ) ) {
			$css .= '.elementor-section.elementor-section-boxed > .elementor-container{max-width:var(--mahan-container);}';
		}

		/**
		 * Filters the generated custom-property block.
		 *
		 * @param string $css   Generated CSS.
		 * @param string $scope Selector the light palette was attached to.
		 */
		return apply_filters( 'mahan_dynamic_css', $css, $scope );
	}

	/**
	 * Turns a property map into a CSS declaration list.
	 *
	 * @param array $vars Property name mapped to value.
	 * @return string
	 */
	private function stringify( array $vars ) {
		$out = '';

		foreach ( $vars as $name => $value ) {
			$out .= $name . ':' . $value . ';';
		}

		return $out;
	}
}
