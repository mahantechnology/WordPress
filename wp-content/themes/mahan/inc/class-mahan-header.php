<?php
/**
 * Header rendering: picks the layout part and prints the utility rows.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Header {

	/**
	 * Hooks the header parts.
	 */
	public function __construct() {
		add_action( 'mahan_header', array( $this, 'topbar' ), 10 );
		add_action( 'mahan_header', array( $this, 'main' ), 20 );
		add_action( 'mahan_after_header', array( $this, 'page_hero' ), 10 );
		add_action( 'wp_footer', array( $this, 'mobile_drawer' ) );
		add_action( 'wp_footer', array( $this, 'search_overlay' ) );
		add_action( 'wp_footer', array( $this, 'bottom_bar' ) );
		add_action( 'wp_footer', array( $this, 'back_to_top' ) );
	}

	/**
	 * Whether the header should be printed for the current view.
	 *
	 * @return bool
	 */
	public static function is_hidden() {
		if ( ! is_singular() ) {
			return false;
		}

		$layout = get_post_meta( get_the_ID(), '_mahan_layout', true );

		return 'blank' === $layout || (bool) get_post_meta( get_the_ID(), '_mahan_hide_header', true );
	}

	/**
	 * Prints the announcement bar.
	 */
	public function topbar() {
		if ( ! mahan_option( 'topbar_enabled' ) ) {
			return;
		}

		$text  = mahan_option( 'topbar_text' );
		$phone = mahan_option( 'topbar_phone' );

		if ( ! $text && ! $phone && ! has_nav_menu( 'secondary' ) ) {
			return;
		}

		get_template_part( 'template-parts/header/topbar', null, compact( 'text', 'phone' ) );
	}

	/**
	 * Prints the main header row for the configured layout.
	 */
	public function main() {
		$layout = mahan_option( 'header_layout', 'classic' );
		$layout = mahan_sanitize_choice( $layout, array( 'classic', 'centered', 'split', 'minimal', 'shop', 'glass', 'gradient', 'stack' ) );

		get_template_part( 'template-parts/header/layout', $layout );
	}

	/**
	 * Prints the page hero (title + breadcrumb) on views that need one.
	 */
	public function page_hero() {
		if ( is_front_page() || mahan_is_built_with_elementor() ) {
			return;
		}

		if ( is_singular() && get_post_meta( get_the_ID(), '_mahan_hide_title', true ) ) {
			return;
		}

		if ( is_singular( 'post' ) || ( mahan_has_woocommerce() && is_product() ) ) {
			return; // These templates print their own hero.
		}

		get_template_part( 'template-parts/header/hero' );
	}

	/**
	 * The heading shown in the page hero.
	 *
	 * @return string
	 */
	public static function hero_title() {
		if ( is_singular() ) {
			$custom = get_post_meta( get_the_ID(), '_mahan_hero_title', true );

			if ( $custom ) {
				return $custom;
			}
		}

		if ( mahan_has_woocommerce() && is_shop() ) {
			return woocommerce_page_title( false );
		}

		if ( is_home() && ! is_front_page() ) {
			return single_post_title( '', false );
		}

		if ( is_search() ) {
			/* translators: %s: search term. */
			return sprintf( __( 'نتایج جستجو برای «%s»', 'mahan' ), get_search_query() );
		}

		if ( is_404() ) {
			return __( 'صفحه پیدا نشد', 'mahan' );
		}

		if ( is_archive() ) {
			return get_the_archive_title();
		}

		if ( is_singular() ) {
			return get_the_title();
		}

		return get_bloginfo( 'name' );
	}

	/**
	 * Prints the off-canvas mobile navigation.
	 */
	public function mobile_drawer() {
		if ( self::is_hidden() ) {
			return;
		}

		get_template_part( 'template-parts/header/drawer' );
	}

	/**
	 * Prints the full-screen search panel.
	 */
	public function search_overlay() {
		if ( ! mahan_option( 'header_search' ) || self::is_hidden() ) {
			return;
		}

		get_template_part( 'template-parts/header/search-overlay' );
	}

	/**
	 * Prints the sticky bottom navigation shown on phones.
	 */
	public function bottom_bar() {
		if ( ! mahan_option( 'mobile_bottom_bar' ) || self::is_hidden() ) {
			return;
		}

		get_template_part( 'template-parts/header/bottom-bar' );
	}

	/**
	 * Prints the back-to-top button.
	 */
	public function back_to_top() {
		if ( ! mahan_option( 'back_to_top' ) ) {
			return;
		}

		printf(
			'<button type="button" class="mahan-to-top" data-mahan-to-top aria-label="%1$s">%2$s</button>',
			esc_attr__( 'بازگشت به بالا', 'mahan' ),
			mahan_icon( 'arrow-up', 22 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
		);
	}

	/**
	 * Prints the header's call-to-action button, when one is configured.
	 */
	public static function cta() {
		$text = (string) mahan_option( 'header_cta_text' );

		if ( '' === trim( $text ) ) {
			return;
		}

		$url = (string) mahan_option( 'header_cta_url' );

		printf(
			'<a class="mahan-header__cta" href="%1$s">%2$s<span>%3$s</span></a>',
			esc_url( $url ? $url : home_url( '/' ) ),
			mahan_icon( 'lightning', 17 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
			esc_html( $text )
		);
	}

	/**
	 * Prints the header action buttons (search, account, wishlist, cart).
	 */
	public static function actions() {
		echo '<div class="mahan-header__actions">';

		if ( mahan_option( 'header_search' ) ) {
			printf(
				'<button type="button" class="mahan-header__action" data-mahan-open="search" aria-label="%1$s">%2$s</button>',
				esc_attr__( 'جستجو', 'mahan' ),
				mahan_icon( 'search', 22 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
			);
		}

		if ( mahan_option( 'header_account' ) ) {
			$account_url = mahan_has_woocommerce() && wc_get_page_id( 'myaccount' ) > 0
				? wc_get_page_permalink( 'myaccount' )
				: wp_login_url();

			printf(
				'<a class="mahan-header__action mahan-header__action--account" href="%1$s" aria-label="%2$s">%3$s<span class="mahan-header__action-label">%4$s</span></a>',
				esc_url( $account_url ),
				esc_attr__( 'حساب کاربری', 'mahan' ),
				mahan_icon( 'user', 22 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
				esc_html( is_user_logged_in() ? __( 'حساب من', 'mahan' ) : __( 'ورود / ثبت‌نام', 'mahan' ) )
			);
		}

		if ( mahan_has_woocommerce() && mahan_option( 'header_wishlist' ) ) {
			$wishlist = Mahan_WooCommerce::wishlist_count();

			printf(
				'<a class="mahan-header__action mahan-header__action--wishlist" href="%1$s" aria-label="%2$s">%3$s<span class="mahan-header__count" data-mahan-wishlist-count%4$s>%5$s</span></a>',
				esc_url( Mahan_WooCommerce::wishlist_url() ),
				esc_attr__( 'علاقه‌مندی‌ها', 'mahan' ),
				mahan_icon( 'heart', 22 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
				$wishlist > 0 ? '' : ' hidden',
				esc_html( mahan_fa_numbers( $wishlist ) )
			);
		}

		if ( mahan_has_woocommerce() && mahan_option( 'header_cart' ) ) {
			get_template_part( 'template-parts/header/cart-button' );
		}

		self::cta();

		printf(
			'<button type="button" class="mahan-header__action mahan-header__burger" data-mahan-open="drawer" aria-label="%1$s" aria-expanded="false">%2$s</button>',
			esc_attr__( 'منو', 'mahan' ),
			mahan_icon( 'menu', 24 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
		);

		echo '</div>';
	}

	/**
	 * Prints the dark-mode switch when the option allows it.
	 */
	public static function dark_toggle() {
		if ( ! in_array( mahan_option( 'dark_mode' ), array( 'toggle', 'auto' ), true ) ) {
			return;
		}

		printf(
			'<button type="button" class="mahan-dark-toggle" data-mahan-dark-toggle aria-label="%1$s"><span class="mahan-dark-toggle__sun">%2$s</span><span class="mahan-dark-toggle__moon">%3$s</span></button>',
			esc_attr__( 'تغییر حالت روشن و تاریک', 'mahan' ),
			mahan_icon( 'sun', 20 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
			mahan_icon( 'moon', 20 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
		);
	}
}
