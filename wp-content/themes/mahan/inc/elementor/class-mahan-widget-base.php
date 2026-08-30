<?php
/**
 * Base class every Mahan Elementor element extends.
 *
 * It supplies the shared control groups (section heading, grid, carousel,
 * query) so the individual widgets only describe what makes them different.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;

abstract class Mahan_Widget_Base extends Widget_Base {

	/**
	 * Panel categories the element appears in.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'mahan' );
	}

	/**
	 * Keywords used by the panel search.
	 *
	 * @return string[]
	 */
	public function get_keywords() {
		return array( 'mahan', 'ماهان' );
	}

	/**
	 * Stylesheet handles the element depends on.
	 *
	 * @return string[]
	 */
	public function get_style_depends() {
		return array( 'mahan-elementor' );
	}

	/**
	 * Script handles the element depends on.
	 *
	 * @return string[]
	 */
	public function get_script_depends() {
		return array( 'mahan-main' );
	}

	/**
	 * Adds the shared "section heading" controls.
	 *
	 * @param string $section_id Control section ID.
	 * @param string $label      Control section label.
	 */
	protected function add_heading_controls( $section_id = 'heading_section', $label = null ) {
		$this->start_controls_section(
			$section_id,
			array(
				'label' => $label ? $label : __( 'سربرگ بخش', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'show_heading',
			array(
				'label'        => __( 'نمایش سربرگ', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'بله', 'mahan' ),
				'label_off'    => __( 'خیر', 'mahan' ),
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'     => __( 'برچسب بالای عنوان', 'mahan' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'     => __( 'عنوان', 'mahan' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'یک عنوان جذاب بنویسید', 'mahan' ),
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'title_highlight',
			array(
				'label'       => __( 'تعداد کلمات برجسته', 'mahan' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 5,
				'default'     => 1,
				'description' => __( 'این تعداد کلمهٔ ابتدای عنوان با رنگ اصلی نمایش داده می‌شود.', 'mahan' ),
				'condition'   => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'subtitle',
			array(
				'label'     => __( 'زیرعنوان', 'mahan' ),
				'type'      => Controls_Manager::TEXTAREA,
				'rows'      => 3,
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->add_control(
			'heading_align',
			array(
				'label'   => __( 'چینش سربرگ', 'mahan' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'right'  => array(
						'title' => __( 'راست', 'mahan' ),
						'icon'  => 'eicon-text-align-right',
					),
					'center' => array(
						'title' => __( 'وسط', 'mahan' ),
						'icon'  => 'eicon-text-align-center',
					),
					'left'   => array(
						'title' => __( 'چپ', 'mahan' ),
						'icon'  => 'eicon-text-align-left',
					),
				),
				'default'   => 'center',
				'condition' => array( 'show_heading' => 'yes' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Adds the shared column-count controls.
	 *
	 * @param int $default Default desktop column count.
	 */
	protected function add_columns_control( $default = 3 ) {
		$this->add_responsive_control(
			'columns',
			array(
				'label'          => __( 'تعداد ستون', 'mahan' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => (string) $default,
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '۱',
					'2' => '۲',
					'3' => '۳',
					'4' => '۴',
					'5' => '۵',
					'6' => '۶',
				),
				'selectors'      => array(
					'{{WRAPPER}} .mahan-grid' => 'grid-template-columns: repeat({{VALUE}}, minmax(0, 1fr));',
				),
			)
		);

		$this->add_responsive_control(
			'grid_gap',
			array(
				'label'      => __( 'فاصلهٔ بین آیتم‌ها', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 80,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 24,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-grid' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);
	}

	/**
	 * Adds the shared carousel behaviour controls.
	 *
	 * @param int $default Default visible slide count.
	 */
	protected function add_carousel_controls( $default = 3 ) {
		$this->start_controls_section(
			'carousel_section',
			array(
				'label' => __( 'اسلایدر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_responsive_control(
			'slides_to_show',
			array(
				'label'          => __( 'تعداد نمایش هم‌زمان', 'mahan' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => (string) $default,
				'tablet_default' => '2',
				'mobile_default' => '1',
				'options'        => array(
					'1' => '۱',
					'2' => '۲',
					'3' => '۳',
					'4' => '۴',
					'5' => '۵',
					'6' => '۶',
				),
			)
		);

		$this->add_control(
			'autoplay',
			array(
				'label'        => __( 'پخش خودکار', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'autoplay_speed',
			array(
				'label'     => __( 'مکث بین اسلایدها (میلی‌ثانیه)', 'mahan' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 4000,
				'min'       => 1000,
				'max'       => 15000,
				'step'      => 500,
				'condition' => array( 'autoplay' => 'yes' ),
			)
		);

		$this->add_control(
			'show_arrows',
			array(
				'label'        => __( 'دکمه‌های جهت', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_dots',
			array(
				'label'        => __( 'نقطه‌های صفحه‌بندی', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => __( 'چرخش بی‌پایان', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Adds the shared card styling controls.
	 *
	 * @param string $selector CSS selector the styles apply to.
	 */
	protected function add_card_style_controls( $selector = '.mahan-card' ) {
		$this->start_controls_section(
			'card_style_section',
			array(
				'label' => __( 'ظاهر کارت', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'card_bg',
			array(
				'label'     => __( 'رنگ پس‌زمینه', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $selector => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			array(
				'name'     => 'card_border',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->add_responsive_control(
			'card_radius',
			array(
				'label'      => __( 'گردی گوشه', 'mahan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'card_padding',
			array(
				'label'      => __( 'فاصلهٔ داخلی', 'mahan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} ' . $selector => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'card_shadow',
				'selector' => '{{WRAPPER}} ' . $selector,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Adds typography controls for the element's title and text.
	 *
	 * @param string $title_selector Selector for the title.
	 * @param string $text_selector  Selector for the body text.
	 */
	protected function add_text_style_controls( $title_selector = '.mahan-card__title', $text_selector = '.mahan-card__text' ) {
		$this->start_controls_section(
			'text_style_section',
			array(
				'label' => __( 'متن‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => __( 'رنگ عنوان', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $title_selector => 'color: {{VALUE}};',
					'{{WRAPPER}} ' . $title_selector . ' a' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} ' . $title_selector,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'رنگ متن', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} ' . $text_selector => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'text_typography',
				'selector' => '{{WRAPPER}} ' . $text_selector,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the shared section heading from the control values.
	 *
	 * @param array $settings Widget settings.
	 */
	protected function render_heading( array $settings ) {
		if ( empty( $settings['show_heading'] ) || 'yes' !== $settings['show_heading'] ) {
			return;
		}

		mahan_section_title(
			array(
				'eyebrow'   => isset( $settings['eyebrow'] ) ? $settings['eyebrow'] : '',
				'title'     => isset( $settings['title'] ) ? $settings['title'] : '',
				'highlight' => isset( $settings['title_highlight'] ) ? (int) $settings['title_highlight'] : 0,
				'subtitle'  => isset( $settings['subtitle'] ) ? $settings['subtitle'] : '',
				'align'     => isset( $settings['heading_align'] ) ? $settings['heading_align'] : 'center',
			)
		);
	}

	/**
	 * Prints the data attributes a carousel needs, from the control values.
	 *
	 * @param array $settings Widget settings.
	 */
	protected function carousel_attributes( array $settings ) {
		$config = array(
			'perView'       => isset( $settings['slides_to_show'] ) ? (int) $settings['slides_to_show'] : 3,
			'perViewTablet' => isset( $settings['slides_to_show_tablet'] ) && $settings['slides_to_show_tablet'] ? (int) $settings['slides_to_show_tablet'] : 2,
			'perViewMobile' => isset( $settings['slides_to_show_mobile'] ) && $settings['slides_to_show_mobile'] ? (int) $settings['slides_to_show_mobile'] : 1,
			'autoplay'      => ! empty( $settings['autoplay'] ) && 'yes' === $settings['autoplay'],
			'interval'      => isset( $settings['autoplay_speed'] ) ? (int) $settings['autoplay_speed'] : 4000,
			'loop'          => ! empty( $settings['loop'] ) && 'yes' === $settings['loop'],
			'rtl'           => is_rtl(),
		);

		printf( ' data-mahan-carousel="%s"', esc_attr( wp_json_encode( $config ) ) );
	}

	/**
	 * Prints the carousel arrows and dots.
	 *
	 * @param array $settings Widget settings.
	 */
	protected function render_carousel_nav( array $settings ) {
		if ( ! empty( $settings['show_arrows'] ) && 'yes' === $settings['show_arrows'] ) {
			printf(
				'<div class="mahan-carousel__arrows">
					<button type="button" class="mahan-carousel__arrow" data-mahan-carousel-prev aria-label="%1$s">%2$s</button>
					<button type="button" class="mahan-carousel__arrow" data-mahan-carousel-next aria-label="%3$s">%4$s</button>
				</div>',
				esc_attr__( 'قبلی', 'mahan' ),
				mahan_icon( 'chevron-right', 20 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
				esc_attr__( 'بعدی', 'mahan' ),
				mahan_icon( 'chevron-left', 20 ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
			);
		}

		if ( ! empty( $settings['show_dots'] ) && 'yes' === $settings['show_dots'] ) {
			echo '<div class="mahan-carousel__dots" data-mahan-carousel-dots></div>';
		}
	}

	/**
	 * Renders one of the theme's inline icons from an icon-name control.
	 *
	 * @param string $name  Icon name.
	 * @param int    $size  Pixel size.
	 * @param string $class Extra classes.
	 */
	protected function render_icon( $name, $size = 28, $class = '' ) {
		echo mahan_icon( $name, $size, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
	}

	/**
	 * Builds link attributes from an Elementor URL control value.
	 *
	 * @param array $link Link control value.
	 * @return string
	 */
	protected function link_attributes( $link ) {
		if ( empty( $link['url'] ) ) {
			return '';
		}

		$attributes = ' href="' . esc_url( $link['url'] ) . '"';

		if ( ! empty( $link['is_external'] ) ) {
			$attributes .= ' target="_blank"';
		}

		if ( ! empty( $link['nofollow'] ) ) {
			$attributes .= ' rel="nofollow"';
		}

		return $attributes;
	}
}
