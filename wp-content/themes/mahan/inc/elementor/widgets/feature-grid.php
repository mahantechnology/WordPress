<?php
/**
 * Feature grid: a numbered list of selling points beside an image.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_feature_grid extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-feature-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'فهرست ویژگی‌ها با تصویر', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'features_section',
			array(
				'label' => __( 'ویژگی‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'media_position',
			array(
				'label'   => __( 'جای تصویر', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => array(
					'right' => __( 'راست', 'mahan' ),
					'left'  => __( 'چپ', 'mahan' ),
					'none'  => __( 'بدون تصویر', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'image',
			array(
				'label'     => __( 'تصویر', 'mahan' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => array( 'url' => Utils::get_placeholder_image_src() ),
				'condition' => array( 'media_position!' => 'none' ),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'check-circle',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'یک مزیت کلیدی', 'mahan' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => __( 'توضیح', 'mahan' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 2,
			)
		);

		$this->add_control(
			'features',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'icon'  => 'check-circle',
						'title' => __( 'راست‌چین و کاملاً فارسی', 'mahan' ),
						'text'  => __( 'همهٔ بخش‌ها از ابتدا برای زبان فارسی طراحی شده‌اند.', 'mahan' ),
					),
					array(
						'icon'  => 'layers',
						'title' => __( 'سازگار با المنتور', 'mahan' ),
						'text'  => __( 'ده‌ها المان اختصاصی برای ساخت هر صفحه‌ای که بخواهید.', 'mahan' ),
					),
					array(
						'icon'  => 'cart',
						'title' => __( 'آمادهٔ فروشگاه', 'mahan' ),
						'text'  => __( 'یکپارچگی کامل با ووکامرس و صفحه‌های بهینهٔ خرید.', 'mahan' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->add_text_style_controls( '.mahan-feature__title', '.mahan-feature__text' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$position = $settings['media_position'];
		$image    = $this->image_url( $settings['image'] );
		?>
		<div class="mahan-features mahan-features--media-<?php echo esc_attr( $position ); ?>">
			<?php if ( 'none' !== $position && $image ) : ?>
				<div class="mahan-features__media">
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['title'] ); ?>" loading="lazy" />
				</div>
			<?php endif; ?>

			<div class="mahan-features__body">
				<?php $this->render_heading( $settings ); ?>

				<?php if ( ! empty( $settings['features'] ) ) : ?>
					<ul class="mahan-features__list">
						<?php foreach ( $settings['features'] as $feature ) : ?>
							<li class="mahan-feature">
								<?php if ( $feature['icon'] ) : ?>
									<span class="mahan-feature__icon"><?php $this->render_icon( $feature['icon'], 22 ); ?></span>
								<?php endif; ?>
								<div class="mahan-feature__body">
									<h4 class="mahan-feature__title"><?php echo esc_html( $feature['title'] ); ?></h4>
									<?php if ( $feature['text'] ) : ?>
										<p class="mahan-feature__text"><?php echo esc_html( $feature['text'] ); ?></p>
									<?php endif; ?>
								</div>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
