<?php
/**
 * Image box grid.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;

class Mahan_Widget_image_box extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-image-box';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'باکس تصویری', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image-box';
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
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'stacked',
				'options' => array(
					'stacked' => __( 'تصویر بالا، متن پایین', 'mahan' ),
					'overlay' => __( 'متن روی تصویر', 'mahan' ),
					'side'    => __( 'تصویر کنار متن', 'mahan' ),
				),
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
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'عنوان باکس', 'mahan' ),
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
			'link',
			array(
				'label'       => __( 'لینک', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
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
					array( 'title' => __( 'خدمت نخست', 'mahan' ) ),
					array( 'title' => __( 'خدمت دوم', 'mahan' ) ),
					array( 'title' => __( 'خدمت سوم', 'mahan' ) ),
				),
			)
		);

		$this->add_columns_control( 3 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-imgbox' );
		$this->add_text_style_controls( '.mahan-imgbox__title', '.mahan-imgbox__text' );
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
		<div class="mahan-grid mahan-imgboxes mahan-imgboxes--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['items'] as $item ) : ?>
				<?php
				$has_link = ! empty( $item['link']['url'] );
				$tag      = $has_link ? 'a' : 'div';
				?>
				<<?php echo esc_html( $tag ); ?> class="mahan-imgbox"<?php echo $has_link ? $this->link_attributes( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
					<?php if ( ! empty( $item['image']['url'] ) ) : ?>
						<span class="mahan-imgbox__media">
							<img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" loading="lazy" />
						</span>
					<?php endif; ?>
					<span class="mahan-imgbox__body">
						<?php if ( $item['title'] ) : ?>
							<span class="mahan-imgbox__title"><?php echo esc_html( $item['title'] ); ?></span>
						<?php endif; ?>
						<?php if ( $item['text'] ) : ?>
							<span class="mahan-imgbox__text"><?php echo esc_html( $item['text'] ); ?></span>
						<?php endif; ?>
					</span>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
