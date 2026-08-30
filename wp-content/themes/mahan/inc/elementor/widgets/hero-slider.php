<?php
/**
 * Full-width hero slider.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_hero_slider extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-hero-slider';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'اسلایدر تمام‌عرض', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-slider-full-screen';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'slides_section',
			array(
				'label' => __( 'اسلایدها', 'mahan' ),
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
				'label' => __( 'برچسب', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'فروش ویژهٔ این هفته', 'mahan' ),
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

		$repeater->add_control(
			'button_text',
			array(
				'label'   => __( 'متن دکمه', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مشاهدهٔ محصولات', 'mahan' ),
			)
		);

		$repeater->add_control(
			'button_link',
			array(
				'label'       => __( 'لینک دکمه', 'mahan' ),
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

		$repeater->add_control(
			'overlay',
			array(
				'label'     => __( 'رنگ پوشش', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(15, 23, 42, .45)',
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .mahan-slide__overlay' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'slides',
			array(
				'label'       => __( 'اسلایدها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => __( 'فروش ویژهٔ این هفته', 'mahan' ) ),
					array( 'title' => __( 'محصولات تازه رسیده', 'mahan' ) ),
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'ارتفاع', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 240,
						'max' => 900,
					),
					'vh' => array(
						'min' => 30,
						'max' => 100,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 520,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-slide' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_carousel_controls( 1 );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['slides'] ) ) {
			return;
		}

		$settings['slides_to_show']        = 1;
		$settings['slides_to_show_tablet'] = 1;
		$settings['slides_to_show_mobile'] = 1;
		?>
		<div class="mahan-carousel mahan-hero-slider"<?php $this->carousel_attributes( $settings ); ?>>
			<div class="mahan-carousel__viewport">
				<div class="mahan-carousel__track" data-mahan-carousel-track>
					<?php foreach ( $settings['slides'] as $slide ) : ?>
						<div class="mahan-carousel__slide elementor-repeater-item-<?php echo esc_attr( $slide['_id'] ); ?>">
							<div class="mahan-slide mahan-slide--<?php echo esc_attr( $slide['align'] ); ?>">
								<?php if ( ! empty( $slide['image']['url'] ) ) : ?>
									<img class="mahan-slide__image" src="<?php echo esc_url( $slide['image']['url'] ); ?>" alt="<?php echo esc_attr( $slide['title'] ); ?>" loading="lazy" />
								<?php endif; ?>
								<span class="mahan-slide__overlay" role="presentation"></span>
								<div class="mahan-slide__content">
									<?php if ( $slide['eyebrow'] ) : ?>
										<span class="mahan-slide__eyebrow"><?php echo esc_html( $slide['eyebrow'] ); ?></span>
									<?php endif; ?>
									<?php if ( $slide['title'] ) : ?>
										<h2 class="mahan-slide__title"><?php echo esc_html( $slide['title'] ); ?></h2>
									<?php endif; ?>
									<?php if ( $slide['text'] ) : ?>
										<p class="mahan-slide__text"><?php echo esc_html( $slide['text'] ); ?></p>
									<?php endif; ?>
									<?php if ( $slide['button_text'] ) : ?>
										<a class="mahan-btn mahan-btn--primary mahan-btn--lg"<?php echo $this->link_attributes( $slide['button_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
											<?php echo esc_html( $slide['button_text'] ); ?>
										</a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php $this->render_carousel_nav( $settings ); ?>
		</div>
		<?php
	}
}
