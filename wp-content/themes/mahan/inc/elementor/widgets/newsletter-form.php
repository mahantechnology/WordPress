<?php
/**
 * Newsletter subscription form.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_newsletter_form extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-newsletter-form';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'فرم خبرنامه', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-email-field';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'newsletter_section',
			array(
				'label' => __( 'محتوا', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'از تازه‌ها باخبر شوید', 'mahan' ),
			)
		);

		$this->add_control(
			'text',
			array(
				'label'   => __( 'توضیح', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'ایمیل‌تان را بنویسید تا مطالب تازه و تخفیف‌های ویژه را برایتان بفرستیم.', 'mahan' ),
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'   => __( 'متن راهنمای فیلد', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'نشانی ایمیل شما', 'mahan' ),
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'متن دکمه', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'عضویت', 'mahan' ),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => array(
					'inline'  => __( 'یک خطی', 'mahan' ),
					'stacked' => __( 'دو خطی', 'mahan' ),
					'boxed'   => __( 'داخل کارت', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'consent',
			array(
				'label'   => __( 'متن رضایت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'با عضویت، قوانین حریم خصوصی را می‌پذیرم.', 'mahan' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		?>
		<div class="mahan-newsletter mahan-newsletter--<?php echo esc_attr( $settings['style'] ); ?>">
			<div class="mahan-newsletter__intro">
				<?php if ( $settings['title'] ) : ?>
					<h3 class="mahan-newsletter__title"><?php echo esc_html( $settings['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( $settings['text'] ) : ?>
					<p class="mahan-newsletter__text"><?php echo esc_html( $settings['text'] ); ?></p>
				<?php endif; ?>
			</div>

			<form class="mahan-newsletter__form" data-mahan-newsletter novalidate>
				<label class="screen-reader-text" for="mahan-newsletter-<?php echo esc_attr( $this->get_id() ); ?>">
					<?php echo esc_html( $settings['placeholder'] ); ?>
				</label>
				<input
					type="email"
					id="mahan-newsletter-<?php echo esc_attr( $this->get_id() ); ?>"
					name="email"
					required
					autocomplete="email"
					placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>"
				/>
				<button type="submit" class="mahan-btn mahan-btn--primary">
					<span class="mahan-newsletter__label"><?php echo esc_html( $settings['button_text'] ); ?></span>
					<span class="mahan-spinner" aria-hidden="true"></span>
				</button>
				<p class="mahan-newsletter__message" role="status" aria-live="polite"></p>
			</form>

			<?php if ( $settings['consent'] ) : ?>
				<p class="mahan-newsletter__consent"><?php echo esc_html( $settings['consent'] ); ?></p>
			<?php endif; ?>
		</div>
		<?php
	}
}
