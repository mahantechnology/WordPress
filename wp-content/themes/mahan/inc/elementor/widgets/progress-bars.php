<?php
/**
 * Skill progress bars.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_progress_bars extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-progress-bars';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نوارهای مهارت', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-skill-bar';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'bars_section',
			array(
				'label' => __( 'مهارت‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'طراحی رابط کاربری', 'mahan' ),
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label'   => __( 'درصد', 'mahan' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'%' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 85,
				),
			)
		);

		$repeater->add_control(
			'color',
			array(
				'label'     => __( 'رنگ نوار', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .mahan-progress__fill' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'bars',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'label' => __( 'طراحی رابط کاربری', 'mahan' ),
						'value' => array(
							'unit' => '%',
							'size' => 92,
						),
					),
					array(
						'label' => __( 'توسعهٔ فرانت‌اند', 'mahan' ),
						'value' => array(
							'unit' => '%',
							'size' => 85,
						),
					),
					array(
						'label' => __( 'سئو و بهینه‌سازی', 'mahan' ),
						'value' => array(
							'unit' => '%',
							'size' => 78,
						),
					),
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

		if ( empty( $settings['bars'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-progress-list">
			<?php foreach ( $settings['bars'] as $bar ) : ?>
				<?php $value = isset( $bar['value']['size'] ) ? max( 0, min( 100, (float) $bar['value']['size'] ) ) : 0; ?>
				<div class="mahan-progress elementor-repeater-item-<?php echo esc_attr( $bar['_id'] ); ?>">
					<div class="mahan-progress__head">
						<span class="mahan-progress__label"><?php echo esc_html( $bar['label'] ); ?></span>
						<span class="mahan-progress__value"><?php echo esc_html( mahan_fa_numbers( $value ) ); ?>٪</span>
					</div>
					<div class="mahan-progress__track" role="progressbar" aria-valuenow="<?php echo esc_attr( $value ); ?>" aria-valuemin="0" aria-valuemax="100">
						<span class="mahan-progress__fill" data-mahan-progress="<?php echo esc_attr( $value ); ?>"></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
