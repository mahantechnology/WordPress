<?php
/**
 * WooCommerce integration: layout hooks, card markup, wishlist and quick view.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_WooCommerce {

	/**
	 * Cookie the guest wishlist is stored in.
	 */
	const WISHLIST_COOKIE = 'mahan_wishlist';

	/**
	 * User meta key the logged-in wishlist is stored in.
	 */
	const WISHLIST_META = '_mahan_wishlist';

	/**
	 * Rewires the WooCommerce templates.
	 */
	public function __construct() {
		$this->rewire_wrappers();
		$this->rewire_loop();
		$this->rewire_single();

		add_filter( 'loop_shop_columns', array( $this, 'loop_columns' ) );
		add_filter( 'loop_shop_per_page', array( $this, 'per_page' ), 20 );
		add_filter( 'woocommerce_product_thumbnails_columns', array( $this, 'gallery_columns' ) );
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_args' ) );
		add_filter( 'woocommerce_pagination_args', array( $this, 'pagination_args' ) );
		add_filter( 'woocommerce_get_price_html', array( $this, 'persian_price' ), 20 );
		add_filter( 'woocommerce_format_sale_price', array( $this, 'sale_price_html' ), 10, 3 );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_fragments' ) );
		add_filter( 'woocommerce_product_loop_start', array( $this, 'loop_start' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );

		add_action( 'wp_footer', array( $this, 'quick_view_modal' ) );
		add_action( 'wp_footer', array( $this, 'sticky_add_to_cart' ) );
	}

	/**
	 * Replaces the default content wrappers with the theme's.
	 */
	private function rewire_wrappers() {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		add_action( 'woocommerce_before_main_content', array( $this, 'wrapper_start' ), 10 );
		add_action( 'woocommerce_after_main_content', array( $this, 'wrapper_end' ), 10 );
	}

	/**
	 * Rebuilds the product card so it matches the theme's card style.
	 */
	private function rewire_loop() {
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'card_open' ), 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'card_media' ), 10 );
		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'card_title' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'card_rating' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'card_price' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'card_stock_bar' ), 15 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'card_actions' ), 10 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'card_close' ), 20 );
	}

	/**
	 * Adds the trust badges and sticky bar to the product page.
	 */
	private function rewire_single() {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );

		add_action( 'woocommerce_single_product_summary', array( $this, 'single_badges' ), 25 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'single_trust' ), 45 );
	}

	/**
	 * Opens the shop content wrapper.
	 */
	public function wrapper_start() {
		$position = mahan_current_sidebar_position();

		printf(
			'<div class="mahan-container mahan-shop mahan-shop--sidebar-%s"><div class="mahan-shop__inner">',
			esc_attr( $position )
		);

		if ( 'none' !== $position && is_active_sidebar( mahan_current_sidebar_id() ) ) {
			echo '<aside class="mahan-sidebar mahan-shop__sidebar">';
			printf(
				'<button type="button" class="mahan-shop__filter-toggle" data-mahan-open="filters">%s<span>%s</span></button>',
				mahan_icon( 'filter', 20 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
				esc_html__( 'فیلترها', 'mahan' )
			);
			dynamic_sidebar( mahan_current_sidebar_id() );
			echo '</aside>';
		}

		echo '<main class="mahan-shop__main" id="main">';
	}

	/**
	 * Closes the shop content wrapper.
	 */
	public function wrapper_end() {
		echo '</main></div></div>';
	}

	/**
	 * Adds the theme's grid class to the product loop.
	 *
	 * @param string $html Loop opening markup.
	 * @return string
	 */
	public function loop_start( $html ) {
		$style   = mahan_option( 'shop_card_style', 'modern' );
		$columns = $this->loop_columns( 4 );

		return sprintf(
			'<ul class="products mahan-products mahan-products--%1$s mahan-grid mahan-grid--%2$d">',
			esc_attr( $style ),
			(int) $columns
		);
	}

	/**
	 * Opens one product card.
	 */
	public function card_open() {
		echo '<div class="mahan-product-card">';
	}

	/**
	 * Closes one product card.
	 */
	public function card_close() {
		echo '</div>';
	}

	/**
	 * Prints the card image, badges and hover actions.
	 */
	public function card_media() {
		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		echo '<div class="mahan-product-card__media">';

		printf( '<a class="mahan-product-card__link" href="%s">', esc_url( get_permalink() ) );

		echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup.

		if ( mahan_option( 'shop_hover_gallery' ) ) {
			$gallery = $product->get_gallery_image_ids();

			if ( $gallery ) {
				echo '<span class="mahan-product-card__hover">';
				echo wp_get_attachment_image( $gallery[0], 'woocommerce_thumbnail', false, array( 'loading' => 'lazy' ) );
				echo '</span>';
			}
		}

		echo '</a>';

		$this->card_badges( $product );
		$this->card_hover_actions( $product );

		echo '</div>';
	}

	/**
	 * Prints the sale, new and out-of-stock badges.
	 *
	 * @param WC_Product $product Product being rendered.
	 */
	private function card_badges( $product ) {
		$badges = array();

		if ( ! $product->is_in_stock() ) {
			$badges[] = array( 'out', __( 'ناموجود', 'mahan' ) );
		} elseif ( $product->is_on_sale() && mahan_option( 'shop_badge_discount' ) ) {
			$percent = $this->discount_percent( $product );

			$badges[] = array(
				'sale',
				$percent
					/* translators: %s: discount percentage. */
					? sprintf( __( '%s٪ تخفیف', 'mahan' ), mahan_fa_numbers( $percent ) )
					: __( 'حراج', 'mahan' ),
			);
		}

		$created = strtotime( $product->get_date_created() ? $product->get_date_created()->date( 'Y-m-d H:i:s' ) : 'now' );

		if ( $created > strtotime( '-14 days' ) ) {
			$badges[] = array( 'new', __( 'جدید', 'mahan' ) );
		}

		if ( ! $badges ) {
			return;
		}

		echo '<div class="mahan-product-card__badges">';

		foreach ( $badges as $badge ) {
			printf(
				'<span class="mahan-badge mahan-badge--%1$s">%2$s</span>',
				esc_attr( $badge[0] ),
				esc_html( $badge[1] )
			);
		}

		echo '</div>';
	}

	/**
	 * Prints the wishlist and quick-view buttons that appear on hover.
	 *
	 * @param WC_Product $product Product being rendered.
	 */
	private function card_hover_actions( $product ) {
		$buttons = array();

		if ( mahan_option( 'shop_wishlist' ) ) {
			$active = in_array( $product->get_id(), self::wishlist_ids(), true );

			$buttons[] = sprintf(
				'<button type="button" class="mahan-product-card__icon %1$s" data-mahan-wishlist="%2$d" aria-label="%3$s">%4$s</button>',
				$active ? 'is-active' : '',
				(int) $product->get_id(),
				esc_attr__( 'افزودن به علاقه‌مندی‌ها', 'mahan' ),
				mahan_icon( 'heart', 20 )
			);
		}

		if ( mahan_option( 'shop_quick_view' ) ) {
			$buttons[] = sprintf(
				'<button type="button" class="mahan-product-card__icon" data-mahan-quick-view="%1$d" aria-label="%2$s">%3$s</button>',
				(int) $product->get_id(),
				esc_attr__( 'مشاهده سریع', 'mahan' ),
				mahan_icon( 'eye', 20 )
			);
		}

		if ( ! $buttons ) {
			return;
		}

		echo '<div class="mahan-product-card__hover-actions">' . implode( '', $buttons ) . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above.
	}

	/**
	 * Prints the card title and its category line.
	 */
	public function card_title() {
		$terms = get_the_terms( get_the_ID(), 'product_cat' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			$term = reset( $terms );

			printf(
				'<a class="mahan-product-card__cat" href="%1$s">%2$s</a>',
				esc_url( get_term_link( $term ) ),
				esc_html( $term->name )
			);
		}

		printf(
			'<h3 class="mahan-product-card__title"><a href="%1$s">%2$s</a></h3>',
			esc_url( get_permalink() ),
			esc_html( get_the_title() )
		);
	}

	/**
	 * Prints the star rating on the card.
	 */
	public function card_rating() {
		global $product;

		if ( ! $product instanceof WC_Product || ! wc_review_ratings_enabled() ) {
			return;
		}

		$average = (float) $product->get_average_rating();

		if ( $average <= 0 ) {
			return;
		}

		echo '<div class="mahan-product-card__rating">';
		mahan_stars( $average, (int) $product->get_review_count() );
		echo '</div>';
	}

	/**
	 * Prints the price block.
	 */
	public function card_price() {
		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		printf(
			'<div class="mahan-product-card__price">%s</div>',
			$product->get_price_html() // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup.
		);
	}

	/**
	 * Prints a "only N left" bar for products with managed low stock.
	 */
	public function card_stock_bar() {
		global $product;

		if ( ! mahan_option( 'shop_stock_bar' ) || ! $product instanceof WC_Product ) {
			return;
		}

		if ( ! $product->managing_stock() || ! $product->is_in_stock() ) {
			return;
		}

		$stock = (int) $product->get_stock_quantity();

		if ( $stock <= 0 || $stock > 10 ) {
			return;
		}

		printf(
			'<div class="mahan-stock-bar"><span class="mahan-stock-bar__fill" style="width:%1$d%%"></span><span class="mahan-stock-bar__label">%2$s</span></div>',
			(int) min( 100, $stock * 10 ),
			esc_html(
				sprintf(
					/* translators: %s: remaining stock. */
					__( 'تنها %s عدد در انبار', 'mahan' ),
					mahan_fa_numbers( $stock )
				)
			)
		);
	}

	/**
	 * Prints the add-to-cart button row.
	 */
	public function card_actions() {
		echo '<div class="mahan-product-card__actions">';
		woocommerce_template_loop_add_to_cart(
			array(
				'class' => 'mahan-btn mahan-btn--primary mahan-btn--block',
			)
		);
		echo '</div>';
	}

	/**
	 * Prints the sale flash and stock line on the product page.
	 */
	public function single_badges() {
		global $product;

		if ( ! $product instanceof WC_Product ) {
			return;
		}

		echo '<div class="mahan-single-product__badges">';

		if ( $product->is_on_sale() ) {
			$percent = $this->discount_percent( $product );

			printf(
				'<span class="mahan-badge mahan-badge--sale">%s</span>',
				esc_html(
					$percent
						/* translators: %s: discount percentage. */
						? sprintf( __( '%s٪ تخفیف', 'mahan' ), mahan_fa_numbers( $percent ) )
						: __( 'حراج', 'mahan' )
				)
			);
		}

		if ( $product->managing_stock() && $product->is_in_stock() ) {
			printf(
				'<span class="mahan-badge mahan-badge--stock">%s</span>',
				esc_html(
					sprintf(
						/* translators: %s: stock quantity. */
						__( '%s عدد موجود', 'mahan' ),
						mahan_fa_numbers( (int) $product->get_stock_quantity() )
					)
				)
			);
		}

		echo '</div>';
	}

	/**
	 * Prints the shipping and guarantee badges under the add-to-cart form.
	 */
	public function single_trust() {
		if ( ! mahan_option( 'shop_trust_badges' ) ) {
			return;
		}

		$badges = array(
			array( 'truck', __( 'ارسال سریع', 'mahan' ), __( 'تحویل ۲۴ تا ۷۲ ساعته', 'mahan' ) ),
			array( 'shield', __( 'ضمانت اصالت', 'mahan' ), __( 'کالای اورجینال', 'mahan' ) ),
			array( 'refresh', __( 'بازگشت کالا', 'mahan' ), __( 'تا ۷ روز', 'mahan' ) ),
			array( 'headphones', __( 'پشتیبانی', 'mahan' ), __( 'همه‌روزه', 'mahan' ) ),
		);

		echo '<div class="mahan-trust">';

		foreach ( $badges as $badge ) {
			printf(
				'<div class="mahan-trust__item">%1$s<div class="mahan-trust__body"><strong>%2$s</strong><span>%3$s</span></div></div>',
				mahan_icon( $badge[0], 26 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
				esc_html( $badge[1] ),
				esc_html( $badge[2] )
			);
		}

		echo '</div>';
	}

	/**
	 * The rounded discount percentage for a product.
	 *
	 * @param WC_Product $product Product to measure.
	 * @return int Percentage, or 0 when it cannot be worked out.
	 */
	private function discount_percent( $product ) {
		$regular = (float) $product->get_regular_price();
		$sale    = (float) $product->get_price();

		if ( $product->is_type( 'variable' ) ) {
			$regular = (float) $product->get_variation_regular_price( 'max' );
			$sale    = (float) $product->get_variation_sale_price( 'min' );
		}

		if ( $regular <= 0 || $sale <= 0 || $sale >= $regular ) {
			return 0;
		}

		return (int) round( ( ( $regular - $sale ) / $regular ) * 100 );
	}

	/**
	 * Uses the configured column count.
	 *
	 * @param int $columns Default columns.
	 * @return int
	 */
	public function loop_columns( $columns ) {
		return max( 2, min( 6, (int) mahan_option( 'shop_columns', 4 ) ) );
	}

	/**
	 * Uses the configured products-per-page count.
	 *
	 * @param int $per_page Default count.
	 * @return int
	 */
	public function per_page( $per_page ) {
		return max( 4, (int) mahan_option( 'shop_per_page', 12 ) );
	}

	/**
	 * Four thumbnails per row in the product gallery.
	 *
	 * @param int $columns Default columns.
	 * @return int
	 */
	public function gallery_columns( $columns ) {
		return 4;
	}

	/**
	 * Matches the related-products grid to the shop grid.
	 *
	 * @param array $args Related product args.
	 * @return array
	 */
	public function related_args( $args ) {
		$columns = $this->loop_columns( 4 );

		$args['posts_per_page'] = $columns;
		$args['columns']        = $columns;

		return $args;
	}

	/**
	 * Persian arrows for the shop pagination.
	 *
	 * @param array $args Pagination args.
	 * @return array
	 */
	public function pagination_args( $args ) {
		$args['prev_text'] = mahan_icon( 'chevron-right', 18 );
		$args['next_text'] = mahan_icon( 'chevron-left', 18 );

		return $args;
	}

	/**
	 * Converts the digits inside a price to Persian.
	 *
	 * @param string $html Price markup.
	 * @return string
	 */
	public function persian_price( $html ) {
		if ( ! mahan_option( 'persian_digits' ) || is_admin() ) {
			return $html;
		}

		return preg_replace_callback(
			'/>([^<]*\d[^<]*)</',
			static function ( $matches ) {
				// Convert the digits in the text node, but leave HTML entities
				// such as &#36; alone: rewriting their digits breaks them.
				$text = preg_replace_callback(
					'/&#?[a-z0-9]+;|\d+/i',
					static function ( $token ) {
						return '&' === $token[0][0] ? $token[0] : mahan_fa_numbers( $token[0] );
					},
					$matches[1]
				);

				return '>' . $text . '<';
			},
			$html
		);
	}

	/**
	 * Puts the sale price first so it reads correctly in RTL.
	 *
	 * @param string     $html    Default markup.
	 * @param string     $from    Regular price.
	 * @param string     $to      Sale price.
	 * @return string
	 */
	public function sale_price_html( $html, $from, $to ) {
		return sprintf(
			'<ins>%1$s</ins> <del aria-hidden="true">%2$s</del>',
			is_numeric( $to ) ? wc_price( $to ) : $to,
			is_numeric( $from ) ? wc_price( $from ) : $from
		);
	}

	/**
	 * Refreshes the header cart button after an AJAX add-to-cart.
	 *
	 * @param array $fragments Cart fragments.
	 * @return array
	 */
	public function cart_fragments( $fragments ) {
		ob_start();
		get_template_part( 'template-parts/header/cart-button' );
		$fragments['.mahan-cart-button'] = ob_get_clean();

		return $fragments;
	}

	/**
	 * Adds shop-specific body classes.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		if ( is_product() ) {
			$classes[] = 'mahan-product-' . sanitize_html_class( mahan_option( 'single_product_layout', 'modern' ) );
		}

		if ( is_shop() || is_product_taxonomy() ) {
			$classes[] = 'mahan-shop-card-' . sanitize_html_class( mahan_option( 'shop_card_style', 'modern' ) );
		}

		return $classes;
	}

	/**
	 * Prints the empty quick-view dialog the script fills in.
	 */
	public function quick_view_modal() {
		if ( ! mahan_option( 'shop_quick_view' ) ) {
			return;
		}
		?>
		<div class="mahan-modal" id="mahan-quick-view" hidden>
			<div class="mahan-modal__backdrop" data-mahan-close></div>
			<div class="mahan-modal__dialog" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'مشاهده سریع محصول', 'mahan' ); ?>">
				<button type="button" class="mahan-modal__close" data-mahan-close aria-label="<?php esc_attr_e( 'بستن', 'mahan' ); ?>">
					<?php mahan_icon_e( 'close', 22 ); ?>
				</button>
				<div class="mahan-modal__body" data-mahan-quick-view-body>
					<div class="mahan-spinner" aria-hidden="true"></div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Prints the sticky buy bar shown on product pages when scrolled.
	 */
	public function sticky_add_to_cart() {
		if ( ! is_product() || ! mahan_option( 'shop_sticky_add_to_cart' ) ) {
			return;
		}

		global $product;

		if ( ! $product instanceof WC_Product || ! $product->is_purchasable() ) {
			return;
		}

		get_template_part( 'template-parts/woo/sticky-cart' );
	}

	/**
	 * The visitor's wishlist product IDs.
	 *
	 * @return int[]
	 */
	public static function wishlist_ids() {
		if ( is_user_logged_in() ) {
			$ids = get_user_meta( get_current_user_id(), self::WISHLIST_META, true );
		} else {
			$raw = isset( $_COOKIE[ self::WISHLIST_COOKIE ] ) ? sanitize_text_field( wp_unslash( $_COOKIE[ self::WISHLIST_COOKIE ] ) ) : '';
			$ids = $raw ? explode( ',', $raw ) : array();
		}

		if ( ! is_array( $ids ) ) {
			return array();
		}

		return array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
	}

	/**
	 * Stores the wishlist for the current visitor.
	 *
	 * @param int[] $ids Product IDs.
	 */
	public static function save_wishlist( array $ids ) {
		$ids = array_slice( array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) ), 0, 100 );

		if ( is_user_logged_in() ) {
			update_user_meta( get_current_user_id(), self::WISHLIST_META, $ids );
			return;
		}

		setcookie(
			self::WISHLIST_COOKIE,
			implode( ',', $ids ),
			time() + MONTH_IN_SECONDS,
			COOKIEPATH ? COOKIEPATH : '/',
			COOKIE_DOMAIN,
			is_ssl(),
			true
		);

		$_COOKIE[ self::WISHLIST_COOKIE ] = implode( ',', $ids );
	}

	/**
	 * How many products are on the wishlist.
	 *
	 * @return int
	 */
	public static function wishlist_count() {
		return count( self::wishlist_ids() );
	}

	/**
	 * Where the wishlist page lives.
	 *
	 * @return string
	 */
	public static function wishlist_url() {
		$page = get_page_by_path( 'wishlist' );

		if ( $page ) {
			return get_permalink( $page );
		}

		return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/' );
	}
}
