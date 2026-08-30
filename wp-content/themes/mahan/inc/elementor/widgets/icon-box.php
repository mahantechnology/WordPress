<?php
/**
 * Icon box grid element.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_icon_box extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-icon-box';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'باکس آیکون‌دار', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-icon-box';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'items_section',
			array(
				'label' => __( 'باکس‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک باکس', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'card'    => __( 'کارت', 'mahan' ),
					'plain'   => __( 'ساده', 'mahan' ),
					'bordered'=> __( 'خط‌دار', 'mahan' ),
					'gradient'=> __( 'گرادیانی', 'mahan' ),
					'inline'  => __( 'آیکون کنار متن', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'sparkles',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ویژگی برجسته', 'mahan' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'توضیح', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 3,
				'default' => __( 'در این‌جا توضیح کوتاهی دربارهٔ این ویژگی بنویسید.', 'mahan' ),
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
			'icon_color',
			array(
				'label'     => __( 'رنگ آیکون', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .mahan-iconbox__icon' => 'color: {{VALUE}}; --mahan-iconbox-tint: {{VALUE}};',
				),
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
						'icon'  => 'lightning',
						'title' => __( 'سرعت بالا', 'mahan' ),
						'text'  => __( 'کدی سبک و بهینه که صفحه‌ها را در کسری از ثانیه بالا می‌آورد.', 'mahan' ),
					),
					array(
						'icon'  => 'shield',
						'title' => __( 'امنیت و پایداری', 'mahan' ),
						'text'  => __( 'ساخته‌شده بر پایهٔ استانداردهای وردپرس با اعتبارسنجی کامل ورودی‌ها.', 'mahan' ),
					),
					array(
						'icon'  => 'headphones',
						'title' => __( 'پشتیبانی همیشگی', 'mahan' ),
						'text'  => __( 'مستندات فارسی و راهنمای گام‌به‌گام برای هر بخش از قالب.', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 3 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-iconbox' );
		$this->add_text_style_controls( '.mahan-iconbox__title', '.mahan-iconbox__text' );
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
		<div class="mahan-grid mahan-iconbox-grid mahan-iconbox-grid--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['items'] as $item ) : ?>
				<?php
				$has_link = ! empty( $item['link']['url'] );
				$tag      = $has_link ? 'a' : 'div';
				?>
				<<?php echo esc_html( $tag ); ?>
					class="mahan-iconbox elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>"
					<?php echo $has_link ? $this->link_attributes( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
				>
					<?php if ( $item['icon'] ) : ?>
						<span class="mahan-iconbox__icon"><?php $this->render_icon( $item['icon'], 30 ); ?></span>
					<?php endif; ?>

					<?php if ( $item['title'] ) : ?>
						<h3 class="mahan-iconbox__title"><?php echo esc_html( $item['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( $item['text'] ) : ?>
						<p class="mahan-iconbox__text"><?php echo esc_html( $item['text'] ); ?></p>
					<?php endif; ?>

					<?php if ( $has_link ) : ?>
						<span class="mahan-iconbox__more">
							<?php esc_html_e( 'بیشتر', 'mahan' ); ?>
							<?php $this->render_icon( 'arrow-left', 16 ); ?>
						</span>
					<?php endif; ?>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
