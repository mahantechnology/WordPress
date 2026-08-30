<?php
/**
 * Hero banner element: headline, copy, buttons, stats and a media slot.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_hero_banner extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-hero-banner';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'بنر سربرگ (هیرو)', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-banner';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'محتوا', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'چیدمان', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'split',
				'options' => array(
					'split'   => __( 'متن کنار تصویر', 'mahan' ),
					'center'  => __( 'وسط‌چین با تصویر پس‌زمینه', 'mahan' ),
					'overlay' => __( 'متن روی تصویر', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => __( 'برچسب بالای عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'به ماهان خوش آمدید', 'mahan' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'عنوان اصلی', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 2,
				'default' => __( 'کسب‌وکار خود را حرفه‌ای آنلاین کنید', 'mahan' ),
			)
		);

		$this->add_control(
			'title_highlight',
			array(
				'label'   => __( 'تعداد کلمات برجسته', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 6,
				'default' => 2,
			)
		);

		$this->add_control(
			'description',
			array(
				'label'   => __( 'توضیح', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'با ابزارهای آمادهٔ ماهان، در کمترین زمان یک وب‌سایت سریع، زیبا و کاملاً فارسی بسازید.', 'mahan' ),
			)
		);

		$this->add_control(
			'primary_text',
			array(
				'label'   => __( 'متن دکمهٔ اصلی', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'شروع کنید', 'mahan' ),
			)
		);

		$this->add_control(
			'primary_link',
			array(
				'label'       => __( 'لینک دکمهٔ اصلی', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'secondary_text',
			array(
				'label'   => __( 'متن دکمهٔ دوم', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'بیشتر بدانید', 'mahan' ),
			)
		);

		$this->add_control(
			'secondary_link',
			array(
				'label'       => __( 'لینک دکمهٔ دوم', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'image',
			array(
				'label'   => __( 'تصویر', 'mahan' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'stats_section',
			array(
				'label' => __( 'آمار زیر بنر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'number',
			array(
				'label'   => __( 'عدد', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '۱۲۰۰',
			)
		);

		$repeater->add_control(
			'suffix',
			array(
				'label'   => __( 'پسوند', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '+',
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'برچسب', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'مشتری راضی', 'mahan' ),
			)
		);

		$this->add_control(
			'stats',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'number' => '۱۲۰۰',
						'suffix' => '+',
						'label'  => __( 'مشتری راضی', 'mahan' ),
					),
					array(
						'number' => '۹۸',
						'suffix' => '٪',
						'label'  => __( 'رضایت کاربران', 'mahan' ),
					),
					array(
						'number' => '۱۰',
						'suffix' => __( ' سال', 'mahan' ),
						'label'  => __( 'سابقهٔ فعالیت', 'mahan' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'hero_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'رنگ متن', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-hero' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'accent_color',
			array(
				'label'     => __( 'رنگ کلمات برجسته', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-highlight' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .mahan-hero__title',
			)
		);

		$this->add_responsive_control(
			'min_height',
			array(
				'label'      => __( 'کمینه ارتفاع', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'vh' ),
				'range'      => array(
					'px' => array(
						'min' => 300,
						'max' => 1000,
					),
					'vh' => array(
						'min' => 40,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-hero' => 'min-height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'overlay_color',
			array(
				'label'     => __( 'رنگ پوشش روی تصویر', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(15, 23, 42, .55)',
				'selectors' => array(
					'{{WRAPPER}} .mahan-hero__overlay' => 'background-color: {{VALUE}};',
				),
				'condition' => array( 'layout' => array( 'center', 'overlay' ) ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$layout   = $settings['layout'];
		$image    = $this->image_url( $settings['image'] );
		?>
		<div class="mahan-hero mahan-hero--<?php echo esc_attr( $layout ); ?>">
			<?php if ( 'split' !== $layout && $image ) : ?>
				<div class="mahan-hero__bg" style="background-image:url('<?php echo esc_url( $image ); ?>');" role="presentation"></div>
				<div class="mahan-hero__overlay" role="presentation"></div>
			<?php endif; ?>

			<div class="mahan-hero__inner">
				<div class="mahan-hero__content">
					<?php if ( $settings['eyebrow'] ) : ?>
						<span class="mahan-hero__eyebrow"><?php echo esc_html( $settings['eyebrow'] ); ?></span>
					<?php endif; ?>

					<?php if ( $settings['title'] ) : ?>
						<h1 class="mahan-hero__title">
							<?php
							echo $settings['title_highlight'] // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escapes internally.
								? mahan_highlight_words( $settings['title'], (int) $settings['title_highlight'] )
								: esc_html( $settings['title'] );
							?>
						</h1>
					<?php endif; ?>

					<?php if ( $settings['description'] ) : ?>
						<p class="mahan-hero__text"><?php echo esc_html( $settings['description'] ); ?></p>
					<?php endif; ?>

					<?php if ( $settings['primary_text'] || $settings['secondary_text'] ) : ?>
						<div class="mahan-hero__buttons">
							<?php if ( $settings['primary_text'] ) : ?>
								<a class="mahan-btn mahan-btn--primary mahan-btn--lg"<?php echo $this->link_attributes( $settings['primary_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
									<?php echo esc_html( $settings['primary_text'] ); ?>
									<?php $this->render_icon( 'arrow-left', 20 ); ?>
								</a>
							<?php endif; ?>

							<?php if ( $settings['secondary_text'] ) : ?>
								<a class="mahan-btn mahan-btn--ghost mahan-btn--lg"<?php echo $this->link_attributes( $settings['secondary_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
									<?php echo esc_html( $settings['secondary_text'] ); ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( ! empty( $settings['stats'] ) ) : ?>
						<div class="mahan-hero__stats">
							<?php foreach ( $settings['stats'] as $stat ) : ?>
								<div class="mahan-hero__stat">
									<strong class="mahan-hero__stat-number">
										<?php echo esc_html( $stat['number'] ); ?><span><?php echo esc_html( $stat['suffix'] ); ?></span>
									</strong>
									<span class="mahan-hero__stat-label"><?php echo esc_html( $stat['label'] ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>

				<?php if ( 'split' === $layout && $image ) : ?>
					<div class="mahan-hero__media">
						<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>" loading="eager" decoding="async" />
						<span class="mahan-hero__blob" role="presentation"></span>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
