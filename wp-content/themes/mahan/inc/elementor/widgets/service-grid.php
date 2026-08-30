<?php
/**
 * Service grid, reading from the service post type.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_service_grid extends Mahan_Widget_Base {

	use Mahan_Query_Trait;

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-service-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'شبکهٔ خدمات', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-nested-carousel';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();
		$this->add_query_controls( 'mahan_service', 'mahan_service_cat', 6 );

		$this->start_controls_section(
			'display_section',
			array(
				'label' => __( 'نمایش', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_columns_control( 3 );

		$this->add_control(
			'show_excerpt',
			array(
				'label'        => __( 'نمایش خلاصه', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-card' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$query    = $this->build_query( $settings, 'mahan_service', 'mahan_service_cat' );

		if ( ! $query->have_posts() ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'هنوز خدمتی ثبت نشده است.', 'mahan' ) . '</p>';
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-services">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				mahan_render_cpt_card( array( 'show_excerpt' => 'yes' === $settings['show_excerpt'] ) );
			endwhile;
			?>
		</div>
		<?php

		wp_reset_postdata();
	}
}
