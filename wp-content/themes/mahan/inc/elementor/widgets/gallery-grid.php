<?php
/**
 * Image gallery with a lightbox.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_gallery_grid extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-gallery-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'گالری تصاویر', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'gallery_section',
			array(
				'label' => __( 'تصاویر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'images',
			array(
				'label'   => __( 'گالری', 'mahan' ),
				'type'    => Controls_Manager::GALLERY,
				'default' => array(),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'چیدمان', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'grid',
				'options' => array(
					'grid'    => __( 'شبکه‌ای یکنواخت', 'mahan' ),
					'masonry' => __( 'آجری', 'mahan' ),
					'mosaic'  => __( 'موزائیکی', 'mahan' ),
				),
			)
		);

		$this->add_columns_control( 3 );

		$this->add_control(
			'lightbox',
			array(
				'label'        => __( 'باز شدن در نمای بزرگ', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_caption',
			array(
				'label'        => __( 'نمایش عنوان تصویر', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['images'] ) ) {
			return;
		}

		$this->render_heading( $settings );

		$lightbox = 'yes' === $settings['lightbox'];
		?>
		<div class="mahan-grid mahan-gallery mahan-gallery--<?php echo esc_attr( $settings['layout'] ); ?>"<?php echo $lightbox ? ' data-mahan-lightbox' : ''; ?>>
			<?php foreach ( $settings['images'] as $image ) : ?>
				<?php
				$id       = (int) $image['id'];
				$full     = $id ? wp_get_attachment_image_url( $id, 'full' ) : $image['url'];
				$caption  = $id ? wp_get_attachment_caption( $id ) : '';
				$alt      = $id ? get_post_meta( $id, '_wp_attachment_image_alt', true ) : '';
				?>
				<figure class="mahan-gallery__item">
					<a href="<?php echo esc_url( $full ); ?>" class="mahan-gallery__link"<?php echo $lightbox ? ' data-mahan-lightbox-item' : ' target="_blank" rel="noopener"'; ?>>
						<?php
						if ( $id ) {
							echo wp_get_attachment_image( $id, 'mahan-card', false, array( 'loading' => 'lazy' ) );
						} else {
							printf( '<img src="%s" alt="" loading="lazy" />', esc_url( $image['url'] ) );
						}
						?>
						<span class="mahan-gallery__zoom"><?php $this->render_icon( 'search', 22 ); ?></span>
					</a>
					<?php if ( 'yes' === $settings['show_caption'] && ( $caption || $alt ) ) : ?>
						<figcaption class="mahan-gallery__caption"><?php echo esc_html( $caption ? $caption : $alt ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
