<?php
/**
 * Call-to-action banner.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Mahan_Widget_cta_banner extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-cta-banner';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'بنر فراخوان', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-call-to-action';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'محتوا', 'mahan' ),
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
					'solid'    => __( 'یک‌رنگ', 'mahan' ),
					'outline'  => __( 'خط‌دار', 'mahan' ),
					'glass'    => __( 'شیشه‌ای', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sparkles',
				'options' => mahan_icon_choices(),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'آمادهٔ شروع هستید؟', 'mahan' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'توضیح', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => __( 'همین حالا با ما تماس بگیرید و مشاورهٔ رایگان دریافت کنید.', 'mahan' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'متن دکمه', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تماس با ما', 'mahan' ),
			)
		);

		$this->add_control(
			'button_link',
			array(
				'label'       => __( 'لینک دکمه', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'secondary_text',
			array(
				'label' => __( 'متن دکمهٔ دوم', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'secondary_link',
			array(
				'label'       => __( 'لینک دکمهٔ دوم', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
				'condition'   => array( 'secondary_text!' => '' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'cta_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => __( 'رنگ پس‌زمینه', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-cta' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'cta_text_color',
			array(
				'label'     => __( 'رنگ متن', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-cta' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'cta_title_typography',
				'selector' => '{{WRAPPER}} .mahan-cta__title',
			)
		);

		$this->add_responsive_control(
			'cta_padding',
			array(
				'label'      => __( 'فاصلهٔ داخلی', 'mahan' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', 'em' ),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-cta' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		<div class="mahan-cta mahan-cta--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php if ( $settings['icon'] ) : ?>
				<span class="mahan-cta__icon"><?php $this->render_icon( $settings['icon'], 40 ); ?></span>
			<?php endif; ?>

			<div class="mahan-cta__body">
				<?php if ( $settings['title'] ) : ?>
					<h2 class="mahan-cta__title"><?php echo esc_html( $settings['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( $settings['text'] ) : ?>
					<p class="mahan-cta__text"><?php echo esc_html( $settings['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<div class="mahan-cta__actions">
				<?php if ( $settings['button_text'] ) : ?>
					<a class="mahan-btn mahan-btn--contrast mahan-btn--lg"<?php echo $this->link_attributes( $settings['button_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
						<?php echo esc_html( $settings['button_text'] ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $settings['secondary_text'] ) : ?>
					<a class="mahan-btn mahan-btn--ghost mahan-btn--lg"<?php echo $this->link_attributes( $settings['secondary_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
						<?php echo esc_html( $settings['secondary_text'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
