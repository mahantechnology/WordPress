<?php
/**
 * Section heading element.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Mahan_Widget_section_heading extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-section-heading';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'سربرگ بخش', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-heading';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls( 'content_section', __( 'محتوا', 'mahan' ) );

		$this->start_controls_section(
			'extra_section',
			array(
				'label' => __( 'تنظیمات بیشتر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title_tag',
			array(
				'label'   => __( 'تگ عنوان', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
				),
			)
		);

		$this->add_control(
			'link',
			array(
				'label'       => __( 'لینک «مشاهدهٔ همه»', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'link_text',
			array(
				'label'     => __( 'متن لینک', 'mahan' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'مشاهدهٔ همه', 'mahan' ),
				'condition' => array( 'link[url]!' => '' ),
			)
		);

		$this->add_control(
			'divider_style',
			array(
				'label'   => __( 'خط تزئینی زیر عنوان', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'line',
				'options' => array(
					''      => __( 'بدون خط', 'mahan' ),
					'line'  => __( 'خط ساده', 'mahan' ),
					'dots'  => __( 'نقطه‌چین', 'mahan' ),
					'wave'  => __( 'موجی', 'mahan' ),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'heading_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'eyebrow_color',
			array(
				'label'     => __( 'رنگ برچسب', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-section-title__eyebrow' => 'color: {{VALUE}}; border-color: currentColor;',
				),
			)
		);

		$this->add_control(
			'heading_color',
			array(
				'label'     => __( 'رنگ عنوان', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-section-title__heading' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'highlight_color',
			array(
				'label'     => __( 'رنگ کلمات برجسته', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'heading_typography',
				'selector' => '{{WRAPPER}} .mahan-section-title__heading',
			)
		);

		$this->add_control(
			'subtitle_color',
			array(
				'label'     => __( 'رنگ زیرعنوان', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-section-title__subtitle' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'subtitle_width',
			array(
				'label'      => __( 'بیشینه عرض زیرعنوان', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 240,
						'max' => 900,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-section-title__subtitle' => 'max-width: {{SIZE}}{{UNIT}};',
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
		$divider  = $settings['divider_style'];

		printf(
			'<div class="mahan-heading-element%s">',
			$divider ? esc_attr( ' mahan-heading-element--' . $divider ) : ''
		);

		mahan_section_title(
			array(
				'eyebrow'   => $settings['eyebrow'],
				'title'     => $settings['title'],
				'highlight' => (int) $settings['title_highlight'],
				'subtitle'  => $settings['subtitle'],
				'align'     => $settings['heading_align'],
				'tag'       => $settings['title_tag'],
				'link'      => isset( $settings['link']['url'] ) ? $settings['link']['url'] : '',
				'link_text' => $settings['link_text'],
			)
		);

		echo '</div>';
	}
}
