<?php
/**
 * Animated statistics counters.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;

class Mahan_Widget_stats_counter extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-stats-counter';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'شمارندهٔ آماری', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-counter';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'counters_section',
			array(
				'label' => __( 'شمارنده‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'user',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label'   => __( 'عدد پایانی', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1200,
			)
		);

		$repeater->add_control(
			'prefix',
			array(
				'label' => __( 'پیشوند', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'suffix',
			array(
				'label'   => __( 'پسوند', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '+',
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'برچسب', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مشتری راضی', 'mahan' ),
			)
		);

		$this->add_control(
			'counters',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'icon'  => 'user',
						'value' => 1200,
						'label' => __( 'مشتری راضی', 'mahan' ),
					),
					array(
						'icon'  => 'check-circle',
						'value' => 480,
						'label' => __( 'پروژهٔ موفق', 'mahan' ),
					),
					array(
						'icon'  => 'star',
						'value' => 98,
						'suffix'=> '٪',
						'label' => __( 'رضایت مشتریان', 'mahan' ),
					),
					array(
						'icon'  => 'headphones',
						'value' => 24,
						'suffix'=> '/۷',
						'label' => __( 'پشتیبانی', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 4 );

		$this->add_control(
			'duration',
			array(
				'label'   => __( 'مدت انیمیشن (میلی‌ثانیه)', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1800,
				'min'     => 400,
				'max'     => 6000,
				'step'    => 100,
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'counter_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'number_color',
			array(
				'label'     => __( 'رنگ عدد', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-counter__value' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'number_typography',
				'selector' => '{{WRAPPER}} .mahan-counter__value',
			)
		);

		$this->add_control(
			'label_color',
			array(
				'label'     => __( 'رنگ برچسب', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-counter__label' => 'color: {{VALUE}};',
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

		if ( empty( $settings['counters'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-counters">
			<?php foreach ( $settings['counters'] as $counter ) : ?>
				<div class="mahan-counter">
					<?php if ( $counter['icon'] ) : ?>
						<span class="mahan-counter__icon"><?php $this->render_icon( $counter['icon'], 28 ); ?></span>
					<?php endif; ?>
					<strong class="mahan-counter__value"
						data-mahan-counter="<?php echo esc_attr( (float) $counter['value'] ); ?>"
						data-duration="<?php echo esc_attr( (int) $settings['duration'] ); ?>">
						<?php if ( $counter['prefix'] ) : ?>
							<span class="mahan-counter__affix"><?php echo esc_html( $counter['prefix'] ); ?></span>
						<?php endif; ?>
						<span class="mahan-counter__number">۰</span>
						<?php if ( $counter['suffix'] ) : ?>
							<span class="mahan-counter__affix"><?php echo esc_html( $counter['suffix'] ); ?></span>
						<?php endif; ?>
					</strong>
					<span class="mahan-counter__label"><?php echo esc_html( $counter['label'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
