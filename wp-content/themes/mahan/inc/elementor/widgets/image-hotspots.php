<?php
/**
 * Image hotspots element: labelled pins placed over a picture.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_image_hotspots extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-image-hotspots';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نقاط تعاملی روی تصویر', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-image-hotspot';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'hotspot_section',
			array(
				'label' => __( 'تصویر و نقاط', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'image',
			array(
				'label' => __( 'تصویر پس‌زمینه', 'mahan' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'نقطهٔ توجه', 'mahan' ),
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
			'x',
			array(
				'label'   => __( 'موقعیت افقی (٪)', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'default' => 30,
			)
		);

		$repeater->add_control(
			'y',
			array(
				'label'   => __( 'موقعیت عمودی (٪)', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 100,
				'default' => 40,
			)
		);

		$this->add_control(
			'spots',
			array(
				'label'       => __( 'نقاط', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title' => __( 'نقطهٔ نخست', 'mahan' ),
						'x'     => 28,
						'y'     => 34,
					),
					array(
						'title' => __( 'نقطهٔ دوم', 'mahan' ),
						'x'     => 66,
						'y'     => 58,
					),
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
		$image    = $this->image_url( $settings['image'] );

		if ( ! $image ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-hotspots" data-mahan-hotspots>
			<img class="mahan-hotspots__image" src="<?php echo esc_url( $image ); ?>" alt="" loading="lazy" />

			<?php foreach ( (array) $settings['spots'] as $spot ) : ?>
				<div
					class="mahan-hotspots__spot"
					style="inset-inline-start:<?php echo (float) $spot['x']; ?>%;top:<?php echo (float) $spot['y']; ?>%;"
				>
					<button type="button" class="mahan-hotspots__pin" data-mahan-hotspot aria-label="<?php echo esc_attr( $spot['title'] ); ?>">
						<span></span>
					</button>

					<div class="mahan-hotspots__tip" role="tooltip">
						<strong><?php echo esc_html( $spot['title'] ); ?></strong>
						<?php if ( $spot['text'] ) : ?>
							<span><?php echo esc_html( $spot['text'] ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
