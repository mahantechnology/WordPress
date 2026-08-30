<?php
/**
 * Promotional shop banners.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_product_banner extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-product-banner';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'بنرهای تبلیغاتی فروشگاه', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image-rollover';
	}

	/**
	 * Panel categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'mahan-woo' );
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'banners_section',
			array(
				'label' => __( 'بنرها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label'   => __( 'تصویر', 'mahan' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$repeater->add_control(
			'eyebrow',
			array(
				'label'   => __( 'برچسب', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تا ۴۰٪ تخفیف', 'mahan' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'کالای دیجیتال', 'mahan' ),
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'   => __( 'متن دکمه', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'خرید کنید', 'mahan' ),
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
			'align',
			array(
				'label'   => __( 'چینش متن', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => array(
					'right'  => __( 'راست', 'mahan' ),
					'center' => __( 'وسط', 'mahan' ),
					'left'   => __( 'چپ', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'banners',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => __( 'کالای دیجیتال', 'mahan' ) ),
					array( 'title' => __( 'پوشاک', 'mahan' ) ),
					array( 'title' => __( 'خانه و آشپزخانه', 'mahan' ) ),
				),
			)
		);

		$this->add_columns_control( 3 );

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'ارتفاع', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 120,
						'max' => 520,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 220,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-promo' => 'min-height: {{SIZE}}{{UNIT}};',
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

		if ( empty( $settings['banners'] ) ) {
			return;
		}
		?>
		<div class="mahan-grid mahan-promos">
			<?php foreach ( $settings['banners'] as $banner ) : ?>
				<a class="mahan-promo mahan-promo--<?php echo esc_attr( $banner['align'] ); ?>"<?php echo $this->link_attributes( $banner['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
					<?php $image = $this->image_url( $banner['image'] ); ?>
					<?php if ( $image ) : ?>
						<img class="mahan-promo__image" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $banner['title'] ); ?>" loading="lazy" />
					<?php endif; ?>
					<span class="mahan-promo__content">
						<?php if ( $banner['eyebrow'] ) : ?>
							<span class="mahan-promo__eyebrow"><?php echo esc_html( $banner['eyebrow'] ); ?></span>
						<?php endif; ?>
						<span class="mahan-promo__title"><?php echo esc_html( $banner['title'] ); ?></span>
						<?php if ( $banner['button_text'] ) : ?>
							<span class="mahan-promo__btn">
								<?php echo esc_html( $banner['button_text'] ); ?>
								<?php $this->render_icon( 'arrow-left', 16 ); ?>
							</span>
						<?php endif; ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
