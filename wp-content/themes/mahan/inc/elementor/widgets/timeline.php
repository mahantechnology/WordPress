<?php
/**
 * Vertical timeline.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_timeline extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-timeline';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'خط زمانی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-time-line';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'timeline_section',
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
				'default' => 'alternate',
				'options' => array(
					'alternate' => __( 'یک‌درمیان', 'mahan' ),
					'single'    => __( 'یک‌طرفه', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'date',
			array(
				'label'   => __( 'تاریخ یا مرحله', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '۱۴۰۰', 'mahan' ),
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'check',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'آغاز فعالیت', 'mahan' ),
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
			'items',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'date'  => '۱۳۹۸',
						'title' => __( 'آغاز فعالیت', 'mahan' ),
						'text'  => __( 'کار را با یک تیم سه‌نفره در یک دفتر کوچک شروع کردیم.', 'mahan' ),
					),
					array(
						'date'  => '۱۴۰۰',
						'title' => __( 'گسترش تیم', 'mahan' ),
						'text'  => __( 'تیم به بیست نفر رسید و اولین محصول سازمانی را عرضه کردیم.', 'mahan' ),
					),
					array(
						'date'  => '۱۴۰۳',
						'title' => __( 'حضور بین‌المللی', 'mahan' ),
						'text'  => __( 'خدمات‌مان را به بازارهای منطقه گسترش دادیم.', 'mahan' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->add_text_style_controls( '.mahan-timeline__title', '.mahan-timeline__text' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['items'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<ol class="mahan-timeline mahan-timeline--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['items'] as $item ) : ?>
				<li class="mahan-timeline__item">
					<span class="mahan-timeline__marker"><?php $this->render_icon( $item['icon'], 18 ); ?></span>
					<div class="mahan-timeline__card">
						<?php if ( $item['date'] ) : ?>
							<span class="mahan-timeline__date"><?php echo esc_html( $item['date'] ); ?></span>
						<?php endif; ?>
						<h3 class="mahan-timeline__title"><?php echo esc_html( $item['title'] ); ?></h3>
						<?php if ( $item['text'] ) : ?>
							<p class="mahan-timeline__text"><?php echo esc_html( $item['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
		<?php
	}
}
