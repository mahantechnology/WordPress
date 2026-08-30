<?php
/**
 * WooCommerce product carousel.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Widget_product_carousel extends Mahan_Widget_product_grid {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-product-carousel';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'اسلایدر محصولات', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-related';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();
		$this->add_query_controls( 'product', 'product_cat', 12 );

		$this->start_controls_section(
			'product_display',
			array(
				'label' => __( 'نمایش', 'mahan' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'filter',
			array(
				'label'   => __( 'فیلتر ویژه', 'mahan' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
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

		$this->end_controls_section();

		$this->add_carousel_controls( 4 );
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
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-carousel mahan-product-carousel"<?php $this->carousel_attributes( $settings ); ?>>
			<div class="mahan-carousel__viewport">
				<ul class="products mahan-products mahan-carousel__track" data-mahan-carousel-track>
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();
						echo '<li class="mahan-carousel__slide product">';
						wc_get_template_part( 'content', 'product-inner' );
						echo '</li>';
					}
					?>
				</ul>
			</div>
			<?php $this->render_carousel_nav( $settings ); ?>
		</div>
		<?php

		wp_reset_postdata();
	}
}
