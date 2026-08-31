<?php
/**
 * Circular counters: percentage rings drawn with SVG.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_counter_circle extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-counter-circle';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'شمارندهٔ دایره‌ای', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-counter-circle';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'rings_section',
			array(
				'label' => __( 'حلقه‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'value',
			array(
				'label'   => __( 'درصد', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'default' => 75,
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'برچسب', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'رضایت مشتریان', 'mahan' ),
			)
		);

		$repeater->add_control(
			'color',
			array(
				'label'     => __( 'رنگ حلقه', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .mahan-ring__bar' => 'stroke: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'rings',
			array(
				'label'       => __( 'حلقه‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'value' => 92,
						'label' => __( 'رضایت مشتریان', 'mahan' ),
					),
					array(
						'value' => 78,
						'label' => __( 'تحویل به‌موقع', 'mahan' ),
					),
					array(
						'value' => 64,
						'label' => __( 'سفارش دوباره', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 3 );

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['rings'] ) ) {
			return;
		}

		$this->render_heading( $settings );

		// r=52 gives a 326.7px circumference; the dash offset animates from it to zero.
		$circumference = 2 * M_PI * 52;
		?>
		<div class="mahan-grid mahan-rings">
			<?php foreach ( $settings['rings'] as $ring ) : ?>
				<?php
				$value  = max( 0, min( 100, (int) $ring['value'] ) );
				$offset = $circumference - ( $circumference * $value / 100 );
				?>
				<div class="mahan-ring elementor-repeater-item-<?php echo esc_attr( $ring['_id'] ); ?>" data-mahan-ring="<?php echo esc_attr( $value ); ?>">
					<svg class="mahan-ring__svg" viewBox="0 0 120 120" role="img" aria-label="<?php echo esc_attr( $ring['label'] ); ?>">
						<circle class="mahan-ring__track" cx="60" cy="60" r="52" fill="none" stroke-width="10" />
						<circle
							class="mahan-ring__bar"
							cx="60" cy="60" r="52" fill="none" stroke-width="10" stroke-linecap="round"
							style="stroke-dasharray:<?php echo esc_attr( round( $circumference, 2 ) ); ?>;stroke-dashoffset:<?php echo esc_attr( round( $offset, 2 ) ); ?>;"
						/>
					</svg>

					<span class="mahan-ring__value"><?php echo esc_html( mahan_fa_numbers( (string) $value ) ); ?>٪</span>

					<?php if ( $ring['label'] ) : ?>
						<span class="mahan-ring__label"><?php echo esc_html( $ring['label'] ); ?></span>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
