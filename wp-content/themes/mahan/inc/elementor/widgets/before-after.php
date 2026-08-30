<?php
/**
 * Before/after image comparison slider.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Utils;

class Mahan_Widget_before_after extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-before-after';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'مقایسهٔ قبل و بعد', 'mahan' );
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
		$this->start_controls_section(
			'images_section',
			array(
				'label' => __( 'تصاویر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'before_image',
			array(
				'label'   => __( 'تصویر «قبل»', 'mahan' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$this->add_control(
			'before_label',
			array(
				'label'   => __( 'برچسب «قبل»', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'قبل', 'mahan' ),
			)
		);

		$this->add_control(
			'after_image',
			array(
				'label'   => __( 'تصویر «بعد»', 'mahan' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array( 'url' => Utils::get_placeholder_image_src() ),
			)
		);

		$this->add_control(
			'after_label',
			array(
				'label'   => __( 'برچسب «بعد»', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'بعد', 'mahan' ),
			)
		);

		$this->add_control(
			'start',
			array(
				'label'   => __( 'موقعیت اولیهٔ دستگیره', 'mahan' ),
				'type'    => Controls_Manager::SLIDER,
				'range'   => array(
					'%' => array(
						'min' => 5,
						'max' => 95,
					),
				),
				'default' => array(
					'unit' => '%',
					'size' => 50,
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
		$before   = $this->image_url( $settings['before_image'] );
		$after    = $this->image_url( $settings['after_image'] );

		if ( ! $before || ! $after ) {
			return;
		}

		$start = isset( $settings['start']['size'] ) ? (float) $settings['start']['size'] : 50;
		?>
		<div class="mahan-compare" data-mahan-compare style="--mahan-compare-pos:<?php echo esc_attr( $start ); ?>%;">
			<img class="mahan-compare__after" src="<?php echo esc_url( $after ); ?>" alt="<?php echo esc_attr( $settings['after_label'] ); ?>" loading="lazy" />
			<div class="mahan-compare__before-wrap">
				<img class="mahan-compare__before" src="<?php echo esc_url( $before ); ?>" alt="<?php echo esc_attr( $settings['before_label'] ); ?>" loading="lazy" />
			</div>

			<?php if ( $settings['before_label'] ) : ?>
				<span class="mahan-compare__label mahan-compare__label--before"><?php echo esc_html( $settings['before_label'] ); ?></span>
			<?php endif; ?>
			<?php if ( $settings['after_label'] ) : ?>
				<span class="mahan-compare__label mahan-compare__label--after"><?php echo esc_html( $settings['after_label'] ); ?></span>
			<?php endif; ?>

			<input
				class="mahan-compare__range"
				type="range"
				min="0"
				max="100"
				value="<?php echo esc_attr( $start ); ?>"
				aria-label="<?php esc_attr_e( 'جابه‌جایی مقایسهٔ تصاویر', 'mahan' ); ?>"
				data-mahan-compare-range
			/>
			<span class="mahan-compare__handle" aria-hidden="true">
				<?php $this->render_icon( 'compare', 20 ); ?>
			</span>
		</div>
		<?php
	}
}
