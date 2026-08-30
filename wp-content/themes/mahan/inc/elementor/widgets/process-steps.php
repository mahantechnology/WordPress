<?php
/**
 * Numbered process steps.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_process_steps extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-process-steps';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'مراحل کار', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-number-field';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'steps_section',
			array(
				'label' => __( 'مراحل', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'connected',
				'options' => array(
					'connected' => __( 'با خط اتصال', 'mahan' ),
					'cards'     => __( 'کارتی', 'mahan' ),
					'numbers'   => __( 'شمارهٔ درشت', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'target',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مشاورهٔ اولیه', 'mahan' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => __( 'توضیح', 'mahan' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 3,
			)
		);

		$this->add_control(
			'steps',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'icon'  => 'phone',
						'title' => __( 'مشاورهٔ اولیه', 'mahan' ),
						'text'  => __( 'نیازهای شما را می‌شنویم و بهترین مسیر را پیشنهاد می‌دهیم.', 'mahan' ),
					),
					array(
						'icon'  => 'pen',
						'title' => __( 'طراحی و برنامه‌ریزی', 'mahan' ),
						'text'  => __( 'طرح اولیه را می‌سازیم و با شما نهایی می‌کنیم.', 'mahan' ),
					),
					array(
						'icon'  => 'code',
						'title' => __( 'اجرا', 'mahan' ),
						'text'  => __( 'پروژه را با کیفیت و در زمان مقرر پیاده‌سازی می‌کنیم.', 'mahan' ),
					),
					array(
						'icon'  => 'check-circle',
						'title' => __( 'تحویل و پشتیبانی', 'mahan' ),
						'text'  => __( 'پس از تحویل هم کنار شما هستیم.', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 4 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-step' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['steps'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-steps mahan-steps--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['steps'] as $index => $step ) : ?>
				<div class="mahan-step">
					<span class="mahan-step__number"><?php echo esc_html( mahan_fa_numbers( $index + 1 ) ); ?></span>
					<?php if ( $step['icon'] ) : ?>
						<span class="mahan-step__icon"><?php $this->render_icon( $step['icon'], 26 ); ?></span>
					<?php endif; ?>
					<h3 class="mahan-step__title"><?php echo esc_html( $step['title'] ); ?></h3>
					<?php if ( $step['text'] ) : ?>
						<p class="mahan-step__text"><?php echo esc_html( $step['text'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
