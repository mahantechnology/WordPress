<?php
/**
 * Post grid element.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_post_grid extends Mahan_Widget_Base {

	use Mahan_Query_Trait;

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-post-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'شبکهٔ نوشته‌ها', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-grid';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();
		$this->add_query_controls( 'post', 'category', 6 );

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

		$this->add_columns_control( 3 );

		foreach ( array(
			'show_image'    => __( 'نمایش تصویر شاخص', 'mahan' ),
			'show_category' => __( 'نمایش دسته‌بندی', 'mahan' ),
			'show_meta'     => __( 'نمایش تاریخ و نویسنده', 'mahan' ),
			'show_excerpt'  => __( 'نمایش خلاصه', 'mahan' ),
			'show_more'     => __( 'نمایش دکمهٔ ادامه', 'mahan' ),
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

		$this->add_control(
			'excerpt_words',
			array(
				'label'     => __( 'تعداد کلمات خلاصه', 'mahan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 20,
				'min'       => 5,
				'max'       => 60,
				'condition' => array( 'show_excerpt' => 'yes' ),
			)
		);

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-card' );
		$this->add_text_style_controls( '.mahan-card__title', '.mahan-card__excerpt' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$query    = $this->build_query( $settings, 'post', 'category' );

		if ( ! $query->have_posts() ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'نوشته‌ای برای نمایش وجود ندارد.', 'mahan' ) . '</p>';
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-post-grid mahan-post-grid--<?php echo esc_attr( $settings['card_style'] ); ?>">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				mahan_render_post_card(
					array(
						'style'        => $settings['card_style'],
						'show_image'   => 'yes' === $settings['show_image'],
						'show_category'=> 'yes' === $settings['show_category'],
						'show_meta'    => 'yes' === $settings['show_meta'],
						'show_excerpt' => 'yes' === $settings['show_excerpt'],
						'show_more'    => 'yes' === $settings['show_more'],
						'excerpt_words'=> (int) $settings['excerpt_words'],
					)
				);
			endwhile;
			?>
		</div>
		<?php

		wp_reset_postdata();
	}
}
