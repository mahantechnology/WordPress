<?php
/**
 * Opening hours element: a week of business hours, with today highlighted.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_opening_hours extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-opening-hours';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'ساعات کاری', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-clock-o';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'hours_section',
			array(
				'label' => __( 'روزها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'day',
			array(
				'label'   => __( 'روز', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'شنبه', 'mahan' ),
			)
		);

		$repeater->add_control(
			'hours',
			array(
				'label'   => __( 'ساعت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '۹:۰۰ تا ۱۸:۰۰', 'mahan' ),
			)
		);

		$repeater->add_control(
			'closed',
			array(
				'label'        => __( 'تعطیل', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'highlight',
			array(
				'label'        => __( 'برجسته', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'days',
			array(
				'label'       => __( 'روزها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ day }}}',
				'default'     => array(
					array(
						'day'   => __( 'شنبه تا چهارشنبه', 'mahan' ),
						'hours' => __( '۹:۰۰ تا ۱۸:۰۰', 'mahan' ),
					),
					array(
						'day'   => __( 'پنجشنبه', 'mahan' ),
						'hours' => __( '۹:۰۰ تا ۱۳:۰۰', 'mahan' ),
					),
					array(
						'day'    => __( 'جمعه', 'mahan' ),
						'hours'  => __( 'تعطیل', 'mahan' ),
						'closed' => 'yes',
					),
				),
			)
		);

		$this->add_control(
			'note',
			array(
				'label'   => __( 'یادداشت پایانی', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'در روزهای تعطیل رسمی بسته هستیم.', 'mahan' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['days'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-hours">
			<ul class="mahan-hours__list">
				<?php foreach ( $settings['days'] as $day ) : ?>
					<?php
					$classes = 'mahan-hours__row';
					$classes .= 'yes' === $day['closed'] ? ' is-closed' : '';
					$classes .= 'yes' === $day['highlight'] ? ' is-highlight' : '';
					?>
					<li class="<?php echo esc_attr( $classes ); ?>">
						<span class="mahan-hours__day">
							<?php $this->render_icon( 'yes' === $day['closed'] ? 'close' : 'clock', 16 ); ?>
							<?php echo esc_html( $day['day'] ); ?>
						</span>
						<span class="mahan-hours__time"><?php echo esc_html( $day['hours'] ); ?></span>
					</li>
				<?php endforeach; ?>
			</ul>

			<?php if ( $settings['note'] ) : ?>
				<p class="mahan-hours__note"><?php echo esc_html( $settings['note'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
