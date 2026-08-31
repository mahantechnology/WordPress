<?php
/**
 * Alert element: a coloured callout for a notice, a tip or a warning.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_alert_box extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-alert-box';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'جعبهٔ توجه', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-alert';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'alert_section',
			array(
				'label' => __( 'محتوا', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'tone',
			array(
				'label'   => __( 'نوع پیام', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'info',
				'options' => array(
					'info'    => __( 'اطلاع‌رسانی', 'mahan' ),
					'success' => __( 'موفقیت', 'mahan' ),
					'warning' => __( 'هشدار', 'mahan' ),
					'danger'  => __( 'خطر', 'mahan' ),
					'tip'     => __( 'نکته', 'mahan' ),
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
				'default' => __( 'خوب است بدانید', 'mahan' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'متن', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => __( 'این بخش برای یادآوری یک نکتهٔ مهم به بازدیدکننده در نظر گرفته شده است.', 'mahan' ),
			)
		);

		$this->add_control(
			'dismissible',
			array(
				'label'        => __( 'قابل بستن', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['title'] && ! $settings['text'] ) {
			return;
		}
		?>
		<div class="mahan-alert mahan-alert--<?php echo esc_attr( $settings['tone'] ); ?>" role="note">
			<?php if ( $settings['icon'] ) : ?>
				<span class="mahan-alert__icon" aria-hidden="true"><?php $this->render_icon( $settings['icon'], 22 ); ?></span>
			<?php endif; ?>

			<div class="mahan-alert__body">
				<?php if ( $settings['title'] ) : ?>
					<strong class="mahan-alert__title"><?php echo esc_html( $settings['title'] ); ?></strong>
				<?php endif; ?>

				<?php if ( $settings['text'] ) : ?>
					<p class="mahan-alert__text"><?php echo esc_html( $settings['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( 'yes' === $settings['dismissible'] ) : ?>
				<button type="button" class="mahan-alert__close" data-mahan-dismiss aria-label="<?php esc_attr_e( 'بستن', 'mahan' ); ?>">
					<?php $this->render_icon( 'close', 18 ); ?>
				</button>
			<?php endif; ?>
		</div>
		<?php
	}
}
