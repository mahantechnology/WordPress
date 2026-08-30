<?php
/**
 * Scrolling text ribbon.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Mahan_Widget_marquee extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-marquee';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نوار متن متحرک', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-navigation-horizontal';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'marquee_section',
			array(
				'label' => __( 'تنظیمات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'عبارت‌ها', 'mahan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => __( "ارسال رایگان\nضمانت بازگشت وجه\nپشتیبانی ۲۴ ساعته\nپرداخت امن", 'mahan' ),
				'description' => __( 'هر خط یک عبارت.', 'mahan' ),
			)
		);

		$this->add_control(
			'separator_icon',
			array(
				'label'   => __( 'آیکون جداکننده', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sparkles',
				'options' => mahan_icon_choices(),
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => __( 'مدت یک دور (ثانیه)', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 22,
				'min'     => 5,
				'max'     => 90,
			)
		);

		$this->add_control(
			'direction',
			array(
				'label'   => __( 'جهت', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'normal',
				'options' => array(
					'normal'  => __( 'راست به چپ', 'mahan' ),
					'reverse' => __( 'چپ به راست', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'pause_on_hover',
			array(
				'label'        => __( 'توقف با نگه داشتن نشانگر', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'marquee_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'     => __( 'رنگ پس‌زمینه', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-marquee' => 'background: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'رنگ متن', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-marquee' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .mahan-marquee',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$items    = array_filter( array_map( 'trim', explode( "\n", (string) $settings['items'] ) ) );

		if ( ! $items ) {
			return;
		}

		$classes = 'mahan-marquee';

		if ( 'yes' === $settings['pause_on_hover'] ) {
			$classes .= ' mahan-marquee--pausable';
		}
		?>
		<div class="<?php echo esc_attr( $classes ); ?>" style="--mahan-marquee-duration:<?php echo esc_attr( (int) $settings['speed'] ); ?>s;--mahan-marquee-direction:<?php echo esc_attr( $settings['direction'] ); ?>;">
			<div class="mahan-marquee__track">
				<?php for ( $copy = 0; $copy < 2; $copy++ ) : ?>
					<div class="mahan-marquee__group" <?php echo 1 === $copy ? 'aria-hidden="true"' : ''; ?>>
						<?php foreach ( $items as $item ) : ?>
							<span class="mahan-marquee__item">
								<?php if ( $settings['separator_icon'] ) : ?>
									<?php $this->render_icon( $settings['separator_icon'], 18 ); ?>
								<?php endif; ?>
								<span><?php echo esc_html( $item ); ?></span>
							</span>
						<?php endforeach; ?>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}
}
