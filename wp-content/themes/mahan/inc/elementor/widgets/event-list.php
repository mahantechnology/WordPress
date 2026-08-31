<?php
/**
 * Event list element: a dated schedule of classes, sessions or events.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_event_list extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-event-list';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'برنامه و رویدادها', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-calendar';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'events_section',
			array(
				'label' => __( 'رویدادها', 'mahan' ),
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
			'date',
			array(
				'label'   => __( 'تاریخ یا ساعت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '۱۸:۰۰', 'mahan' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'عنوان برنامه', 'mahan' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => __( 'توضیح', 'mahan' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 2,
			)
		);

		$repeater->add_control(
			'meta',
			array(
				'label'       => __( 'مجری یا مکان', 'mahan' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'نام مربی، مدرس یا سالن', 'mahan' ),
			)
		);

		$repeater->add_control(
			'badge',
			array(
				'label' => __( 'برچسب', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'لینک ثبت‌نام', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'events',
			array(
				'label'       => __( 'رویدادها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'day'   => __( 'شنبه', 'mahan' ),
						'date'  => __( '۱۸:۰۰', 'mahan' ),
						'title' => __( 'جلسهٔ نخست', 'mahan' ),
						'meta'  => __( 'سالن اصلی', 'mahan' ),
					),
					array(
						'day'   => __( 'دوشنبه', 'mahan' ),
						'date'  => __( '۱۹:۳۰', 'mahan' ),
						'title' => __( 'جلسهٔ دوم', 'mahan' ),
						'meta'  => __( 'سالن اصلی', 'mahan' ),
						'badge' => __( 'ظرفیت محدود', 'mahan' ),
					),
					array(
						'day'   => __( 'چهارشنبه', 'mahan' ),
						'date'  => __( '۱۷:۰۰', 'mahan' ),
						'title' => __( 'جلسهٔ سوم', 'mahan' ),
						'meta'  => __( 'سالن دوم', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 1 );

		$this->end_controls_section();

		$this->add_text_style_controls( '.mahan-event__title', '.mahan-event__text' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['events'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-events">
			<?php foreach ( $settings['events'] as $event ) : ?>
				<?php
				$has_link = ! empty( $event['link']['url'] );
				?>
				<div class="mahan-event">
					<div class="mahan-event__when">
						<span class="mahan-event__day"><?php echo esc_html( $event['day'] ); ?></span>
						<span class="mahan-event__date"><?php echo esc_html( $event['date'] ); ?></span>
					</div>

					<div class="mahan-event__body">
						<h3 class="mahan-event__title">
							<?php echo esc_html( $event['title'] ); ?>

							<?php if ( $event['badge'] ) : ?>
								<span class="mahan-event__badge"><?php echo esc_html( $event['badge'] ); ?></span>
							<?php endif; ?>
						</h3>

						<?php if ( $event['text'] ) : ?>
							<p class="mahan-event__text"><?php echo esc_html( $event['text'] ); ?></p>
						<?php endif; ?>

						<?php if ( $event['meta'] ) : ?>
							<span class="mahan-event__meta">
								<?php $this->render_icon( 'map-pin', 15 ); ?>
								<?php echo esc_html( $event['meta'] ); ?>
							</span>
						<?php endif; ?>
					</div>

					<?php if ( $has_link ) : ?>
						<a class="mahan-event__cta"<?php echo $this->link_attributes( $event['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
							<?php esc_html_e( 'ثبت‌نام', 'mahan' ); ?>
							<?php $this->render_icon( 'arrow-left', 16 ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
