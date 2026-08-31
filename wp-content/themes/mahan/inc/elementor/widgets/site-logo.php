<?php
/**
 * Site logo element, for building a header in Elementor.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_site_logo extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-site-logo';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'لوگوی سایت', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-site-logo';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'logo_section',
			array(
				'label' => __( 'لوگو', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_tagline',
			array(
				'label'        => __( 'نمایش شعار سایت', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'چینش', 'mahan' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'راست', 'mahan' ),
						'icon'  => 'eicon-text-align-right',
					),
					'center'     => array(
						'title' => __( 'وسط', 'mahan' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'چپ', 'mahan' ),
						'icon'  => 'eicon-text-align-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mahan-logo' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'logo_height',
			array(
				'label'      => __( 'ارتفاع تصویر لوگو', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 16,
						'max' => 120,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-logo__image' => 'height: {{SIZE}}{{UNIT}}; width: auto;',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'رنگ نام سایت', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-logo__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		mahan_site_logo(
			array(
				'class'   => 'mahan-logo--element',
				'tagline' => 'yes' === $settings['show_tagline'],
			)
		);
	}
}
