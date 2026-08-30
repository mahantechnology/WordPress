<?php
/**
 * WooCommerce product grid.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_product_grid extends Mahan_Widget_Base {

	use Mahan_Query_Trait;

	/**
	 * The special filter selected for the current render.
	 *
	 * @var string
	 */
	protected $current_filter = '';

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-product-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'شبکهٔ محصولات', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-products';
	}

	/**
	 * Panel categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'mahan-woo' );
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();
		$this->add_query_controls( 'product', 'product_cat', 8 );

		$this->start_controls_section(
			'product_display',
			array(
				'label' => __( 'نمایش', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'filter',
			array(
				'label'   => __( 'فیلتر ویژه', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''          => __( 'بدون فیلتر', 'mahan' ),
					'featured'  => __( 'محصولات ویژه', 'mahan' ),
					'on_sale'   => __( 'محصولات حراج', 'mahan' ),
					'best'      => __( 'پرفروش‌ترین‌ها', 'mahan' ),
					'top_rated' => __( 'بیشترین امتیاز', 'mahan' ),
					'in_stock'  => __( 'فقط موجودها', 'mahan' ),
				),
			)
		);

		$this->add_columns_control( 4 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-product-card' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		if ( ! mahan_has_woocommerce() ) {
			return;
		}

		$settings = $this->get_settings_for_display();
		$query    = $this->build_product_query( $settings );

		if ( ! $query->have_posts() ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'محصولی برای نمایش وجود ندارد.', 'mahan' ) . '</p>';
			return;
		}

		$this->render_heading( $settings );

		echo '<ul class="products mahan-products mahan-grid">';

		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}

		echo '</ul>';

		wp_reset_postdata();
	}

	/**
	 * Builds the product query, applying the special filters.
	 *
	 * @param array $settings Widget settings.
	 * @return WP_Query
	 */
	protected function build_product_query( array $settings ) {
		add_filter( 'mahan_element_query_args', array( $this, 'apply_product_filter' ), 10, 3 );

		$this->current_filter = $settings['filter'];
		$query                = $this->build_query( $settings, 'product', 'product_cat' );

		remove_filter( 'mahan_element_query_args', array( $this, 'apply_product_filter' ), 10 );

		return $query;
	}

	/**
	 * Applies the special filter to the query arguments.
	 *
	 * @param array  $args      Query arguments.
	 * @param array  $settings  Widget settings.
	 * @param string $post_type Post type being queried.
	 * @return array
	 */
	public function apply_product_filter( $args, $settings, $post_type ) {
		if ( 'product' !== $post_type ) {
			return $args;
		}

		$args['tax_query']  = isset( $args['tax_query'] ) ? $args['tax_query'] : array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Bounded by posts_per_page.
		$args['meta_query'] = isset( $args['meta_query'] ) ? $args['meta_query'] : array(); // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query -- Bounded by posts_per_page.

		switch ( $this->current_filter ) {
			case 'featured':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'featured',
				);
				break;

			case 'on_sale':
				$args['post__in'] = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
				break;

			case 'best':
				$args['meta_key'] = 'total_sales'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Bounded by posts_per_page.
				$args['orderby']  = 'meta_value_num';
				break;

			case 'top_rated':
				$args['meta_key'] = '_wc_average_rating'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Bounded by posts_per_page.
				$args['orderby']  = 'meta_value_num';
				break;

			case 'in_stock':
				$args['tax_query'][] = array(
					'taxonomy' => 'product_visibility',
					'field'    => 'name',
					'terms'    => 'outofstock',
					'operator' => 'NOT IN',
				);
				break;
		}

		return $args;
	}
}
