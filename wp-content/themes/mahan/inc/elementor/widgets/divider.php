<?php
/**
 * Decorative divider.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_divider extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-divider';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'جداکنندهٔ تزئینی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-divider';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'divider_section',
			array(
				'label' => __( 'تنظیمات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'gradient',
				'options' => array(
					'gradient' => __( 'گرادیانی', 'mahan' ),
					'dashed'   => __( 'خط‌چین', 'mahan' ),
					'dots'     => __( 'نقطه‌چین', 'mahan' ),
					'icon'     => __( 'با آیکون میانی', 'mahan' ),
					'zigzag'   => __( 'زیگزاگ', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'     => __( 'آیکون میانی', 'mahan' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'sparkles',
				'options'   => mahan_icon_choices(),
				'condition' => array( 'style' => 'icon' ),
			)
		);

		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'عرض', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( '%', 'px' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-divider' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'رنگ', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-divider' => '--mahan-divider-color: {{VALUE}};',
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
		?>
		<div class="mahan-divider mahan-divider--<?php echo esc_attr( $settings['style'] ); ?>" role="separator">
			<?php if ( 'icon' === $settings['style'] && $settings['icon'] ) : ?>
				<span class="mahan-divider__icon"><?php $this->render_icon( $settings['icon'], 22 ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}
}
