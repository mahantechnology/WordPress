<?php
/**
 * Countdown timer.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_countdown extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-countdown';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'شمارش معکوس', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'countdown_section',
			array(
				'label' => __( 'تنظیمات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'پایان فروش ویژه', 'mahan' ),
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'       => __( 'تاریخ پایان', 'mahan' ),
				'type'        => Controls_Manager::DATE_TIME,
				'default'     => gmdate( 'Y-m-d H:i', time() + WEEK_IN_SECONDS ),
				'description' => __( 'زمان بر اساس ساعت سایت محاسبه می‌شود.', 'mahan' ),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'boxes',
				'options' => array(
					'boxes'  => __( 'باکسی', 'mahan' ),
					'inline' => __( 'خطی', 'mahan' ),
					'circle' => __( 'دایره‌ای', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'expired_text',
			array(
				'label'   => __( 'متن پس از پایان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'فرصت به پایان رسید', 'mahan' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'countdown_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'digit_color',
			array(
				'label'     => __( 'رنگ اعداد', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-countdown__value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'box_bg',
			array(
				'label'     => __( 'پس‌زمینهٔ باکس', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-countdown__unit' => 'background-color: {{VALUE}};',
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
		$due      = $settings['due_date'] ? strtotime( $settings['due_date'] ) : 0;

		if ( ! $due ) {
			return;
		}

		$units = array(
			'days'    => __( 'روز', 'mahan' ),
			'hours'   => __( 'ساعت', 'mahan' ),
			'minutes' => __( 'دقیقه', 'mahan' ),
			'seconds' => __( 'ثانیه', 'mahan' ),
		);
		?>
		<div class="mahan-countdown mahan-countdown--<?php echo esc_attr( $settings['style'] ); ?>"
			data-mahan-countdown="<?php echo esc_attr( $due ); ?>"
			data-expired="<?php echo esc_attr( $settings['expired_text'] ); ?>">
			<?php if ( $settings['title'] ) : ?>
				<span class="mahan-countdown__title"><?php echo esc_html( $settings['title'] ); ?></span>
			<?php endif; ?>
			<div class="mahan-countdown__units">
				<?php foreach ( $units as $key => $label ) : ?>
					<div class="mahan-countdown__unit">
						<span class="mahan-countdown__value" data-unit="<?php echo esc_attr( $key ); ?>">۰۰</span>
						<span class="mahan-countdown__label"><?php echo esc_html( $label ); ?></span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
