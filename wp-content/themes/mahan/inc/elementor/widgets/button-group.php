<?php
/**
 * Button group.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_button_group extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-button-group';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'گروه دکمه‌ها', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'buttons_section',
			array(
				'label' => __( 'دکمه‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'متن', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'دکمه', 'mahan' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'لینک', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'variant',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'primary',
				'options' => array(
					'primary' => __( 'اصلی', 'mahan' ),
					'outline' => __( 'خط‌دار', 'mahan' ),
					'ghost'   => __( 'شفاف', 'mahan' ),
					'soft'    => __( 'ملایم', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'buttons',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array(
						'text'    => __( 'شروع کنید', 'mahan' ),
						'variant' => 'primary',
						'icon'    => 'arrow-left',
					),
					array(
						'text'    => __( 'تماس با ما', 'mahan' ),
						'variant' => 'outline',
					),
				),
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => __( 'اندازه', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => array(
					'sm' => __( 'کوچک', 'mahan' ),
					'md' => __( 'معمولی', 'mahan' ),
					'lg' => __( 'بزرگ', 'mahan' ),
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'چینش', 'mahan' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'راست', 'mahan' ),
						'icon'  => 'eicon-text-align-right',
					),
					'center'     => array(
						'title' => __( 'وسط', 'mahan' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'چپ', 'mahan' ),
						'icon'  => 'eicon-text-align-left',
					),
				),
				'default'   => 'flex-start',
				'selectors' => array(
					'{{WRAPPER}} .mahan-btn-group' => 'justify-content: {{VALUE}};',
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

		if ( empty( $settings['buttons'] ) ) {
			return;
		}
		?>
		<div class="mahan-btn-group">
			<?php foreach ( $settings['buttons'] as $button ) : ?>
				<a class="mahan-btn mahan-btn--<?php echo esc_attr( $button['variant'] ); ?> mahan-btn--<?php echo esc_attr( $settings['size'] ); ?>"<?php echo $this->link_attributes( $button['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
					<span><?php echo esc_html( $button['text'] ); ?></span>
					<?php if ( $button['icon'] ) : ?>
						<?php $this->render_icon( $button['icon'], 18 ); ?>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
