<?php
/**
 * Client logo carousel.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_logo_carousel extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-logo-carousel';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'لوگوی مشتریان', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-logo';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'logos_section',
			array(
				'label' => __( 'لوگوها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'logo',
			array(
				'label'   => __( 'تصویر', 'mahan' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$repeater->add_control(
			'name',
			array(
				'label'   => __( 'نام', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'برند نمونه', 'mahan' ),
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

		$this->add_control(
			'logos',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array_fill( 0, 6, array( 'name' => __( 'برند نمونه', 'mahan' ) ) ),
			)
		);

		$this->add_control(
			'grayscale',
			array(
				'label'        => __( 'سیاه‌وسفید تا زمان هاور', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->add_carousel_controls( 5 );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['logos'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-carousel mahan-logos<?php echo 'yes' === $settings['grayscale'] ? ' mahan-logos--grayscale' : ''; ?>"<?php $this->carousel_attributes( $settings ); ?>>
			<div class="mahan-carousel__viewport">
				<div class="mahan-carousel__track" data-mahan-carousel-track>
					<?php foreach ( $settings['logos'] as $logo ) : ?>
						<?php
						$has_link = ! empty( $logo['link']['url'] );
						$tag      = $has_link ? 'a' : 'span';
						?>
						<div class="mahan-carousel__slide">
							<<?php echo esc_html( $tag ); ?> class="mahan-logos__item"<?php echo $has_link ? $this->link_attributes( $logo['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
								<?php $image = $this->image_url( $logo['logo'] ); ?>
								<?php if ( $image ) : ?>
									<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $logo['name'] ); ?>" loading="lazy" />
								<?php else : ?>
									<span class="mahan-logos__name"><?php echo esc_html( $logo['name'] ); ?></span>
								<?php endif; ?>
							</<?php echo esc_html( $tag ); ?>>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php $this->render_carousel_nav( $settings ); ?>
		</div>
		<?php
	}
}
