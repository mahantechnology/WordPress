<?php
/**
 * Custom post types and taxonomies the demos build on.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Post_Types {

	/**
	 * Hooks registration.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
		add_filter( 'pre_get_posts', array( $this, 'archive_query' ) );
	}

	/**
	 * Registers the portfolio, service, team and testimonial types.
	 */
	public function register() {
		$this->register_portfolio();
		$this->register_service();
		$this->register_team();
		$this->register_testimonial();
	}

	/**
	 * Portfolio projects, with categories and skills.
	 */
	private function register_portfolio() {
		register_post_type(
			'mahan_portfolio',
			array(
				'labels'        => array(
					'name'               => __( 'نمونه‌کارها', 'mahan' ),
					'singular_name'      => __( 'نمونه‌کار', 'mahan' ),
					'add_new'            => __( 'افزودن نمونه‌کار', 'mahan' ),
					'add_new_item'       => __( 'افزودن نمونه‌کار تازه', 'mahan' ),
					'edit_item'          => __( 'ویرایش نمونه‌کار', 'mahan' ),
					'search_items'       => __( 'جستجوی نمونه‌کارها', 'mahan' ),
					'not_found'          => __( 'نمونه‌کاری ثبت نشده است.', 'mahan' ),
					'all_items'          => __( 'همهٔ نمونه‌کارها', 'mahan' ),
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_icon'     => 'dashicons-portfolio',
				'menu_position' => 22,
				'rewrite'       => array( 'slug' => 'portfolio' ),
				'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom-fields', 'page-attributes' ),
				'show_in_rest'  => true,
			)
		);

		register_taxonomy(
			'mahan_portfolio_cat',
			'mahan_portfolio',
			array(
				'labels'            => array(
					'name'          => __( 'دسته‌های نمونه‌کار', 'mahan' ),
					'singular_name' => __( 'دستهٔ نمونه‌کار', 'mahan' ),
				),
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'portfolio-category' ),
			)
		);

		register_taxonomy(
			'mahan_portfolio_skill',
			'mahan_portfolio',
			array(
				'labels'            => array(
					'name'          => __( 'مهارت‌ها', 'mahan' ),
					'singular_name' => __( 'مهارت', 'mahan' ),
				),
				'hierarchical'      => false,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'portfolio-skill' ),
			)
		);
	}

	/**
	 * Services, used by the corporate and agency demos.
	 */
	private function register_service() {
		register_post_type(
			'mahan_service',
			array(
				'labels'        => array(
					'name'          => __( 'خدمات', 'mahan' ),
					'singular_name' => __( 'خدمت', 'mahan' ),
					'add_new_item'  => __( 'افزودن خدمت تازه', 'mahan' ),
					'edit_item'     => __( 'ویرایش خدمت', 'mahan' ),
					'all_items'     => __( 'همهٔ خدمات', 'mahan' ),
				),
				'public'        => true,
				'has_archive'   => true,
				'menu_icon'     => 'dashicons-screenoptions',
				'menu_position' => 23,
				'rewrite'       => array( 'slug' => 'services' ),
				'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
				'show_in_rest'  => true,
			)
		);

		register_taxonomy(
			'mahan_service_cat',
			'mahan_service',
			array(
				'labels'            => array(
					'name'          => __( 'دسته‌های خدمات', 'mahan' ),
					'singular_name' => __( 'دستهٔ خدمت', 'mahan' ),
				),
				'hierarchical'      => true,
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'service-category' ),
			)
		);
	}

	/**
	 * Team members.
	 */
	private function register_team() {
		register_post_type(
			'mahan_team',
			array(
				'labels'        => array(
					'name'          => __( 'اعضای تیم', 'mahan' ),
					'singular_name' => __( 'عضو تیم', 'mahan' ),
					'add_new_item'  => __( 'افزودن عضو تازه', 'mahan' ),
					'all_items'     => __( 'همهٔ اعضا', 'mahan' ),
				),
				'public'        => true,
				'has_archive'   => false,
				'menu_icon'     => 'dashicons-groups',
				'menu_position' => 24,
				'rewrite'       => array( 'slug' => 'team' ),
				'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
				'show_in_rest'  => true,
			)
		);
	}

	/**
	 * Customer testimonials.
	 */
	private function register_testimonial() {
		register_post_type(
			'mahan_testimonial',
			array(
				'labels'        => array(
					'name'          => __( 'نظرات مشتریان', 'mahan' ),
					'singular_name' => __( 'نظر مشتری', 'mahan' ),
					'add_new_item'  => __( 'افزودن نظر تازه', 'mahan' ),
					'all_items'     => __( 'همهٔ نظرات', 'mahan' ),
				),
				'public'        => false,
				'show_ui'       => true,
				'menu_icon'     => 'dashicons-format-quote',
				'menu_position' => 25,
				'supports'      => array( 'title', 'editor', 'thumbnail' ),
				'show_in_rest'  => true,
			)
		);
	}

	/**
	 * Uses the theme's per-page counts on the custom archives.
	 *
	 * @param WP_Query $query Query about to run.
	 */
	public function archive_query( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( $query->is_post_type_archive( 'mahan_portfolio' ) || $query->is_tax( array( 'mahan_portfolio_cat', 'mahan_portfolio_skill' ) ) ) {
			$query->set( 'posts_per_page', 12 );
		}

		if ( $query->is_post_type_archive( 'mahan_service' ) ) {
			$query->set( 'posts_per_page', 9 );
			$query->set( 'orderby', 'menu_order' );
			$query->set( 'order', 'ASC' );
		}
	}
}
