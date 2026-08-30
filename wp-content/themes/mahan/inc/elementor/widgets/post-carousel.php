<?php
/**
 * Post carousel element.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_post_carousel extends Mahan_Widget_Base {

	use Mahan_Query_Trait;

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-post-carousel';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'اسلایدر نوشته‌ها', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-slider';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();
		$this->add_query_controls( 'post', 'category', 9 );

		$this->start_controls_section(
			'display_section',
			array(
				'label' => __( 'نمایش', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'card_style',
			array(
				'label'   => __( 'سبک کارت', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'    => __( 'استاندارد', 'mahan' ),
					'overlay' => __( 'متن روی تصویر', 'mahan' ),
					'minimal' => __( 'مینیمال', 'mahan' ),
				),
			)
		);

		foreach ( array(
			'show_category' => __( 'نمایش دسته‌بندی', 'mahan' ),
			'show_meta'     => __( 'نمایش تاریخ و نویسنده', 'mahan' ),
			'show_excerpt'  => __( 'نمایش خلاصه', 'mahan' ),
		) as $key => $label ) {
			$this->add_control(
				$key,
				array(
					'label'        => $label,
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'return_value' => 'yes',
				)
			);
		}

		$this->end_controls_section();

		$this->add_carousel_controls( 3 );
		$this->add_card_style_controls( '.mahan-card' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$query    = $this->build_query( $settings, 'post', 'category' );

		if ( ! $query->have_posts() ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-carousel mahan-post-carousel"<?php $this->carousel_attributes( $settings ); ?>>
			<div class="mahan-carousel__viewport">
				<div class="mahan-carousel__track" data-mahan-carousel-track>
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						echo '<div class="mahan-carousel__slide">';
						mahan_render_post_card(
							array(
								'style'         => $settings['card_style'],
								'show_category' => 'yes' === $settings['show_category'],
								'show_meta'     => 'yes' === $settings['show_meta'],
								'show_excerpt'  => 'yes' === $settings['show_excerpt'],
								'show_more'     => false,
							)
						);
						echo '</div>';
					endwhile;
					?>
				</div>
			</div>
			<?php $this->render_carousel_nav( $settings ); ?>
		</div>
		<?php

		wp_reset_postdata();
	}
}
