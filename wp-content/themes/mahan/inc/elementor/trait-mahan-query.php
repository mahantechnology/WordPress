<?php
/**
 * Shared post-query controls and query building for the content elements.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

trait Mahan_Query_Trait {

	/**
	 * Adds the query controls for a post type.
	 *
	 * @param string $post_type Post type the element lists.
	 * @param string $taxonomy  Taxonomy offered as a filter.
	 * @param int    $default   Default number of posts.
	 */
	protected function add_query_controls( $post_type = 'post', $taxonomy = 'category', $default = 6 ) {
		$this->start_controls_section(
			'query_section',
			array(
				'label' => __( 'واکشی محتوا', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'posts_per_page',
			array(
				'label'   => __( 'تعداد', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 36,
				'default' => $default,
			)
		);

		$this->add_control(
			'query_terms',
			array(
				'label'       => __( 'محدود به دسته‌ها', 'mahan' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $this->term_options( $taxonomy ),
				'description' => __( 'خالی بگذارید تا از همهٔ دسته‌ها واکشی شود.', 'mahan' ),
			)
		);

		$this->add_control(
			'orderby',
			array(
				'label'   => __( 'مرتب‌سازی بر اساس', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => array(
					'date'          => __( 'تاریخ انتشار', 'mahan' ),
					'title'         => __( 'عنوان', 'mahan' ),
					'comment_count' => __( 'تعداد دیدگاه', 'mahan' ),
					'menu_order'    => __( 'ترتیب دستی', 'mahan' ),
					'rand'          => __( 'تصادفی', 'mahan' ),
					'views'         => __( 'پربازدیدترین', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'order',
			array(
				'label'   => __( 'ترتیب', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => array(
					'DESC' => __( 'نزولی', 'mahan' ),
					'ASC'  => __( 'صعودی', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'offset',
			array(
				'label'       => __( 'رد کردن n مورد اول', 'mahan' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 20,
				'default'     => 0,
			)
		);

		$this->add_control(
			'exclude_current',
			array(
				'label'        => __( 'حذف نوشتهٔ جاری', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * The term choices for a taxonomy, for the SELECT2 control.
	 *
	 * @param string $taxonomy Taxonomy name.
	 * @return array<int,string>
	 */
	protected function term_options( $taxonomy ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			return array();
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
				'number'     => 200,
			)
		);

		if ( is_wp_error( $terms ) ) {
			return array();
		}

		$options = array();

		foreach ( $terms as $term ) {
			$options[ $term->term_id ] = $term->name;
		}

		return $options;
	}

	/**
	 * Builds the WP_Query for the element from its settings.
	 *
	 * @param array  $settings  Widget settings.
	 * @param string $post_type Post type to query.
	 * @param string $taxonomy  Taxonomy the term filter applies to.
	 * @return WP_Query
	 */
	protected function build_query( array $settings, $post_type = 'post', $taxonomy = 'category' ) {
		$args = array(
			'post_type'           => $post_type,
			'post_status'         => 'publish',
			'posts_per_page'      => isset( $settings['posts_per_page'] ) ? (int) $settings['posts_per_page'] : 6,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
			'order'               => isset( $settings['order'] ) && 'ASC' === $settings['order'] ? 'ASC' : 'DESC',
		);

		if ( ! empty( $settings['offset'] ) ) {
			$args['offset'] = (int) $settings['offset'];
		}

		$orderby = isset( $settings['orderby'] ) ? $settings['orderby'] : 'date';

		if ( 'views' === $orderby ) {
			$args['meta_key'] = '_mahan_views'; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key -- Bounded by posts_per_page.
			$args['orderby']  = 'meta_value_num';
		} else {
			$args['orderby'] = $orderby;
		}

		if ( ! empty( $settings['query_terms'] ) && taxonomy_exists( $taxonomy ) ) {
			$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Bounded by posts_per_page.
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'term_id',
					'terms'    => array_map( 'absint', (array) $settings['query_terms'] ),
				),
			);
		}

		if ( ! empty( $settings['exclude_current'] ) && 'yes' === $settings['exclude_current'] && is_singular() ) {
			$args['post__not_in'] = array( get_the_ID() );
		}

		/**
		 * Filters the query arguments a Mahan content element uses.
		 *
		 * @param array  $args      Query arguments.
		 * @param array  $settings  Widget settings.
		 * @param string $post_type Post type being queried.
		 */
		$args = apply_filters( 'mahan_element_query_args', $args, $settings, $post_type );

		return new WP_Query( $args );
	}
}
