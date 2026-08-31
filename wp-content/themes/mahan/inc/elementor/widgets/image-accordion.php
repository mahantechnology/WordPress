<?php
/**
 * Image accordion element: panels that widen as the visitor points at them.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_image_accordion extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-image-accordion';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'آکاردئون تصویری', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image-before-after';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'panels_section',
			array(
				'label' => __( 'پنل‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label' => __( 'تصویر', 'mahan' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'عنوان پنل', 'mahan' ),
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

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'لینک', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'panels',
			array(
				'label'       => __( 'پنل‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array( 'title' => __( 'پنل نخست', 'mahan' ) ),
					array( 'title' => __( 'پنل دوم', 'mahan' ) ),
					array( 'title' => __( 'پنل سوم', 'mahan' ) ),
					array( 'title' => __( 'پنل چهارم', 'mahan' ) ),
				),
			)
		);

		$this->add_responsive_control(
			'height',
			array(
				'label'      => __( 'ارتفاع', 'mahan' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'range'      => array(
					'px' => array(
						'min' => 240,
						'max' => 720,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 440,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-imgacc' => 'height: {{SIZE}}{{UNIT}};',
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

		if ( empty( $settings['panels'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-imgacc" data-mahan-image-accordion>
			<?php foreach ( $settings['panels'] as $index => $panel ) : ?>
				<?php
				$image    = $this->image_url( $panel['image'] );
				$has_link = ! empty( $panel['link']['url'] );
				$tag      = $has_link ? 'a' : 'div';
				?>
				<<?php echo esc_html( $tag ); ?>
					class="mahan-imgacc__panel<?php echo 0 === $index ? ' is-open' : ''; ?>"
					<?php echo $image ? 'style="background-image:url(\'' . esc_url( $image ) . '\')"' : ''; ?>
					<?php echo $has_link ? $this->link_attributes( $panel['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
					data-mahan-image-accordion-panel
				>
					<span class="mahan-imgacc__veil" aria-hidden="true"></span>

					<span class="mahan-imgacc__body">
						<?php if ( $panel['title'] ) : ?>
							<span class="mahan-imgacc__title"><?php echo esc_html( $panel['title'] ); ?></span>
						<?php endif; ?>

						<?php if ( $panel['text'] ) : ?>
							<span class="mahan-imgacc__text"><?php echo esc_html( $panel['text'] ); ?></span>
						<?php endif; ?>
					</span>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
