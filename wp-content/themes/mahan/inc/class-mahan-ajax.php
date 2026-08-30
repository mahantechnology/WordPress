<?php
/**
 * The theme's AJAX endpoints.
 *
 * Every handler verifies the `mahan_frontend` nonce; the ones that write also
 * check a capability or scope the write to the current visitor's own data.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Ajax {

	/**
	 * Registers the endpoints for both logged-in and anonymous visitors.
	 */
	public function __construct() {
		$actions = array(
			'mahan_live_search'   => 'live_search',
			'mahan_load_more'     => 'load_more',
			'mahan_newsletter'    => 'newsletter',
			'mahan_toggle_wishlist' => 'toggle_wishlist',
			'mahan_quick_view'    => 'quick_view',
		);

		foreach ( $actions as $action => $method ) {
			add_action( 'wp_ajax_' . $action, array( $this, $method ) );
			add_action( 'wp_ajax_nopriv_' . $action, array( $this, $method ) );
		}
	}

	/**
	 * Rejects the request unless the front-end nonce checks out.
	 */
	private function verify() {
		if ( ! check_ajax_referer( 'mahan_frontend', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => __( 'درخواست نامعتبر است.', 'mahan' ) ), 403 );
		}
	}

	/**
	 * Returns matching posts and products for the header search field.
	 */
	public function live_search() {
		$this->verify();

		$term = isset( $_POST['term'] ) ? sanitize_text_field( wp_unslash( $_POST['term'] ) ) : '';

		if ( mb_strlen( $term ) < 3 ) {
			wp_send_json_success( array( 'items' => array() ) );
		}

		$post_types = array( 'post', 'page' );

		if ( mahan_has_woocommerce() ) {
			array_unshift( $post_types, 'product' );
		}

		$query = new WP_Query(
			array(
				's'                   => $term,
				'post_type'           => $post_types,
				'post_status'         => 'publish',
				'posts_per_page'      => 8,
				'ignore_sticky_posts' => true,
				'no_found_rows'       => true,
			)
		);

		$items = array();

		while ( $query->have_posts() ) {
			$query->the_post();

			$item = array(
				'title' => get_the_title(),
				'url'   => get_permalink(),
				'type'  => get_post_type(),
				'image' => get_the_post_thumbnail_url( get_the_ID(), 'mahan-thumb' ),
				'price' => '',
			);

			if ( 'product' === $item['type'] && function_exists( 'wc_get_product' ) ) {
				$product = wc_get_product( get_the_ID() );

				if ( $product ) {
					$item['price'] = wp_strip_all_tags( $product->get_price_html() );
				}
			}

			$items[] = $item;
		}

		wp_reset_postdata();

		wp_send_json_success(
			array(
				'items'   => $items,
				'moreUrl' => add_query_arg( 's', rawurlencode( $term ), home_url( '/' ) ),
			)
		);
	}

	/**
	 * Returns the next page of archive cards.
	 */
	public function load_more() {
		$this->verify();

		$page      = isset( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
		$per_page  = isset( $_POST['perPage'] ) ? min( 24, absint( $_POST['perPage'] ) ) : 9;
		$post_type = isset( $_POST['postType'] ) ? sanitize_key( wp_unslash( $_POST['postType'] ) ) : 'post';
		$style     = isset( $_POST['style'] ) ? sanitize_key( wp_unslash( $_POST['style'] ) ) : 'grid';
		$term_id   = isset( $_POST['term'] ) ? absint( $_POST['term'] ) : 0;
		$taxonomy  = isset( $_POST['taxonomy'] ) ? sanitize_key( wp_unslash( $_POST['taxonomy'] ) ) : '';

		$object = get_post_type_object( $post_type );

		if ( ! $object || ! $object->public ) {
			wp_send_json_error( array( 'message' => __( 'نوع محتوا نامعتبر است.', 'mahan' ) ), 400 );
		}

		$args = array(
			'post_type'      => $post_type,
			'post_status'    => 'publish',
			'posts_per_page' => $per_page,
			'paged'          => $page + 1,
		);

		if ( $term_id && $taxonomy && taxonomy_exists( $taxonomy ) ) {
			$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Paged archive request.
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			);
		}

		$query = new WP_Query( $args );

		ob_start();

		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'template-parts/content/card', $style );
		}

		$html = ob_get_clean();

		wp_reset_postdata();

		wp_send_json_success(
			array(
				'html'    => $html,
				'hasMore' => $query->max_num_pages > ( $page + 1 ),
			)
		);
	}

	/**
	 * Stores a newsletter subscriber.
	 *
	 * Subscribers are kept as theme options so the feature works without a
	 * mailing plugin; a plugin can take over through the filter below.
	 */
	public function newsletter() {
		$this->verify();

		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';

		if ( ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => __( 'ایمیل وارد شده معتبر نیست.', 'mahan' ) ), 400 );
		}

		/**
		 * Lets a mailing plugin handle the subscription instead of the theme.
		 *
		 * Return true to signal the address was stored elsewhere.
		 *
		 * @param bool   $handled Whether the subscription was handled.
		 * @param string $email   Subscriber address.
		 */
		if ( apply_filters( 'mahan_newsletter_subscribe', false, $email ) ) {
			wp_send_json_success( array( 'message' => __( 'عضویت شما ثبت شد. سپاسگزاریم!', 'mahan' ) ) );
		}

		$list = get_option( 'mahan_newsletter_list', array() );
		$list = is_array( $list ) ? $list : array();

		if ( in_array( $email, $list, true ) ) {
			wp_send_json_success( array( 'message' => __( 'این ایمیل قبلاً ثبت شده است.', 'mahan' ) ) );
		}

		if ( count( $list ) >= 5000 ) {
			wp_send_json_error( array( 'message' => __( 'ظرفیت فهرست تکمیل است.', 'mahan' ) ), 429 );
		}

		$list[] = $email;
		update_option( 'mahan_newsletter_list', $list, false );

		wp_send_json_success( array( 'message' => __( 'عضویت شما ثبت شد. سپاسگزاریم!', 'mahan' ) ) );
	}

	/**
	 * Adds or removes a product from the visitor's wishlist.
	 */
	public function toggle_wishlist() {
		$this->verify();

		if ( ! mahan_has_woocommerce() ) {
			wp_send_json_error( array( 'message' => __( 'ووکامرس فعال نیست.', 'mahan' ) ), 400 );
		}

		$product_id = isset( $_POST['product'] ) ? absint( $_POST['product'] ) : 0;

		if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
			wp_send_json_error( array( 'message' => __( 'محصول یافت نشد.', 'mahan' ) ), 404 );
		}

		$list  = Mahan_WooCommerce::wishlist_ids();
		$added = ! in_array( $product_id, $list, true );

		if ( $added ) {
			$list[] = $product_id;
		} else {
			$list = array_values( array_diff( $list, array( $product_id ) ) );
		}

		Mahan_WooCommerce::save_wishlist( $list );

		wp_send_json_success(
			array(
				'added'   => $added,
				'count'   => count( $list ),
				'message' => $added
					? __( 'به علاقه‌مندی‌ها اضافه شد.', 'mahan' )
					: __( 'از علاقه‌مندی‌ها حذف شد.', 'mahan' ),
			)
		);
	}

	/**
	 * Returns the quick-view markup for a product.
	 */
	public function quick_view() {
		$this->verify();

		if ( ! mahan_has_woocommerce() ) {
			wp_send_json_error( array( 'message' => __( 'ووکامرس فعال نیست.', 'mahan' ) ), 400 );
		}

		$product_id = isset( $_POST['product'] ) ? absint( $_POST['product'] ) : 0;
		$product    = $product_id ? wc_get_product( $product_id ) : null;

		if ( ! $product || 'publish' !== get_post_status( $product_id ) ) {
			wp_send_json_error( array( 'message' => __( 'محصول یافت نشد.', 'mahan' ) ), 404 );
		}

		$GLOBALS['post']    = get_post( $product_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited -- Required so WooCommerce template tags resolve.
		$GLOBALS['product'] = $product;

		setup_postdata( $GLOBALS['post'] );

		ob_start();
		get_template_part( 'template-parts/woo/quick-view' );
		$html = ob_get_clean();

		wp_reset_postdata();

		wp_send_json_success( array( 'html' => $html ) );
	}
}
