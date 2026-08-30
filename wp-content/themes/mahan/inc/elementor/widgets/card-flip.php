<?php
/**
 * Flip cards.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_card_flip extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-card-flip';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'کارت چرخشی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-flip-box';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'cards_section',
			array(
				'label' => __( 'کارت‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون روی', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'target',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'front_title',
			array(
				'label'   => __( 'عنوان روی', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مأموریت ما', 'mahan' ),
			)
		);

		$repeater->add_control(
			'back_title',
			array(
				'label'   => __( 'عنوان پشت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مأموریت ما', 'mahan' ),
			)
		);

		$repeater->add_control(
			'back_text',
			array(
				'label'   => __( 'متن پشت', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'توضیح کامل را این‌جا بنویسید؛ با نگه داشتن نشانگر روی کارت نمایش داده می‌شود.', 'mahan' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'لینک پشت کارت', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'cards',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ front_title }}}',
				'default'     => array(
					array(
						'icon'        => 'target',
						'front_title' => __( 'مأموریت ما', 'mahan' ),
						'back_title'  => __( 'مأموریت ما', 'mahan' ),
					),
					array(
						'icon'        => 'eye',
						'front_title' => __( 'چشم‌انداز', 'mahan' ),
						'back_title'  => __( 'چشم‌انداز', 'mahan' ),
					),
					array(
						'icon'        => 'heart',
						'front_title' => __( 'ارزش‌ها', 'mahan' ),
						'back_title'  => __( 'ارزش‌ها', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 3 );

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'ارتفاع کارت', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 180,
						'max' => 520,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 280,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-flip' => 'min-height: {{SIZE}}{{UNIT}};',
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

		if ( empty( $settings['cards'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-flips">
			<?php foreach ( $settings['cards'] as $card ) : ?>
				<div class="mahan-flip" tabindex="0">
					<div class="mahan-flip__inner">
						<div class="mahan-flip__face mahan-flip__face--front">
							<?php if ( $card['icon'] ) : ?>
								<span class="mahan-flip__icon"><?php $this->render_icon( $card['icon'], 34 ); ?></span>
							<?php endif; ?>
							<h3 class="mahan-flip__title"><?php echo esc_html( $card['front_title'] ); ?></h3>
						</div>
						<div class="mahan-flip__face mahan-flip__face--back">
							<h3 class="mahan-flip__title"><?php echo esc_html( $card['back_title'] ); ?></h3>
							<?php if ( $card['back_text'] ) : ?>
								<p class="mahan-flip__text"><?php echo esc_html( $card['back_text'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $card['link']['url'] ) ) : ?>
								<a class="mahan-btn mahan-btn--contrast mahan-btn--sm"<?php echo $this->link_attributes( $card['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
									<?php esc_html_e( 'بیشتر بدانید', 'mahan' ); ?>
								</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
