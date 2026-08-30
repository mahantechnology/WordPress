<?php
/**
 * Lottie animation player.
 *
 * The player library is not bundled; the element loads it from the site's own
 * uploads when present and otherwise falls back to a still image, so the theme
 * never depends on a third-party CDN.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_lottie_player extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-lottie-player';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'انیمیشن لوتی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-lottie';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'lottie_section',
			array(
				'label' => __( 'انیمیشن', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'json_url',
			array(
				'label'       => __( 'نشانی فایل JSON', 'mahan' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'https://…/animation.json',
				'description' => __( 'فایل لوتی را در کتابخانهٔ رسانه بارگذاری و نشانی آن را این‌جا وارد کنید.', 'mahan' ),
			)
		);

		$this->add_control(
			'fallback',
			array(
				'label'       => __( 'تصویر جایگزین', 'mahan' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'اگر پخش‌کنندهٔ لوتی در دسترس نباشد این تصویر نمایش داده می‌شود.', 'mahan' ),
			)
		);

		$this->add_control(
			'loop',
			array(
				'label'        => __( 'تکرار', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
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

		$this->add_responsive_control(
			'width',
			array(
				'label'      => __( 'عرض', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'default'    => array(
					'unit' => '%',
					'size' => 100,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-lottie' => 'width: {{SIZE}}{{UNIT}};',
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
		$json     = esc_url_raw( $settings['json_url'] );
		$fallback = $this->image_url( $settings['fallback'] );

		if ( ! $json && ! $fallback ) {
			return;
		}
		?>
		<div class="mahan-lottie"
			<?php if ( $json ) : ?>
				data-mahan-lottie="<?php echo esc_url( $json ); ?>"
				data-loop="<?php echo 'yes' === $settings['loop'] ? '1' : '0'; ?>"
				data-autoplay="<?php echo 'yes' === $settings['autoplay'] ? '1' : '0'; ?>"
			<?php endif; ?>
		>
			<?php if ( $fallback ) : ?>
				<img class="mahan-lottie__fallback" src="<?php echo esc_url( $fallback ); ?>" alt="" loading="lazy" />
			<?php endif; ?>
		</div>
		<?php
	}
}
