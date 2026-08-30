<?php
/**
 * Video poster that opens the clip in a dialog.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Utils;

class Mahan_Widget_video_popup extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-video-popup';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'ویدیو با پخش‌کنندهٔ پاپ‌آپ', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-play';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'video_section',
			array(
				'label' => __( 'ویدیو', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'poster',
			array(
				'label'   => __( 'تصویر پوستر', 'mahan' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$this->add_control(
			'video_url',
			array(
				'label'       => __( 'نشانی ویدیو', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://www.aparat.com/v/…',
				'description' => __( 'نشانی آپارات، یوتیوب یا یک فایل mp4.', 'mahan' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label' => __( 'عنوان روی پوستر', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'text',
			array(
				'label' => __( 'توضیح روی پوستر', 'mahan' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 2,
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
						'min' => 200,
						'max' => 800,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 420,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-video' => 'min-height: {{SIZE}}{{UNIT}};',
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
		$url      = isset( $settings['video_url']['url'] ) ? $settings['video_url']['url'] : '';
		$poster   = $this->image_url( $settings['poster'] );
		?>
		<div class="mahan-video" <?php echo $poster ? 'style="background-image:url(\'' . esc_url( $poster ) . '\')"' : ''; ?>>
			<span class="mahan-video__overlay" role="presentation"></span>
			<div class="mahan-video__content">
				<?php if ( $url ) : ?>
					<button type="button" class="mahan-video__play" data-mahan-video="<?php echo esc_url( $url ); ?>" aria-label="<?php esc_attr_e( 'پخش ویدیو', 'mahan' ); ?>">
						<?php $this->render_icon( 'play', 30 ); ?>
					</button>
				<?php endif; ?>
				<?php if ( $settings['title'] ) : ?>
					<h3 class="mahan-video__title"><?php echo esc_html( $settings['title'] ); ?></h3>
				<?php endif; ?>
				<?php if ( $settings['text'] ) : ?>
					<p class="mahan-video__text"><?php echo esc_html( $settings['text'] ); ?></p>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
