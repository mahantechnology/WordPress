<?php
/**
 * Social icon row.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_social_icons extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-social-icons';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'شبکه‌های اجتماعی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-social-icons';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'social_section',
			array(
				'label' => __( 'شبکه‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'source',
			array(
				'label'   => __( 'منبع', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'customizer',
				'options' => array(
					'customizer' => __( 'از تنظیمات قالب', 'mahan' ),
					'manual'     => __( 'ورود دستی', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'network',
			array(
				'label'   => __( 'شبکه', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'instagram',
				'options' => array(
					'instagram' => __( 'اینستاگرام', 'mahan' ),
					'telegram'  => __( 'تلگرام', 'mahan' ),
					'whatsapp'  => __( 'واتساپ', 'mahan' ),
					'linkedin'  => __( 'لینکدین', 'mahan' ),
					'twitter'   => __( 'ایکس', 'mahan' ),
					'youtube'   => __( 'یوتیوب', 'mahan' ),
					'aparat'    => __( 'آپارات', 'mahan' ),
				),
			)
		);

		$repeater->add_control(
			'url',
			array(
				'label'       => __( 'نشانی', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ network }}}',
				'condition'   => array( 'source' => 'manual' ),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'circle',
				'options' => array(
					'circle' => __( 'دایره‌ای', 'mahan' ),
					'square' => __( 'مربعی', 'mahan' ),
					'plain'  => __( 'بدون پس‌زمینه', 'mahan' ),
					'brand'  => __( 'با رنگ برند', 'mahan' ),
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
					'{{WRAPPER}} .mahan-social' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_size',
			array(
				'label'      => __( 'اندازهٔ آیکون', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 14,
						'max' => 40,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 20,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-social__link svg' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
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

		if ( 'customizer' === $settings['source'] ) {
			mahan_social_links( 'mahan-social--' . $settings['style'] );
			return;
		}

		if ( empty( $settings['items'] ) ) {
			return;
		}
		?>
		<div class="mahan-social mahan-social--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['items'] as $item ) : ?>
				<?php if ( empty( $item['url']['url'] ) ) : ?>
					<?php continue; ?>
				<?php endif; ?>
				<a class="mahan-social__link mahan-social__link--<?php echo esc_attr( $item['network'] ); ?>"<?php echo $this->link_attributes( $item['url'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?> aria-label="<?php echo esc_attr( $item['network'] ); ?>">
					<?php $this->render_icon( $item['network'], 20 ); ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
