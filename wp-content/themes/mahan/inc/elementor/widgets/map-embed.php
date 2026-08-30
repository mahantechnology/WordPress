<?php
/**
 * Map embed with a lazy-loaded iframe.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_map_embed extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-map-embed';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نقشه', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'map_section',
			array(
				'label' => __( 'تنظیمات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'provider',
			array(
				'label'   => __( 'سرویس نقشه', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'osm',
				'options' => array(
					'osm'    => __( 'OpenStreetMap (بدون نیاز به کلید)', 'mahan' ),
					'custom' => __( 'کد جاسازی دلخواه', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'latitude',
			array(
				'label'     => __( 'عرض جغرافیایی', 'mahan' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '35.6997',
				'condition' => array( 'provider' => 'osm' ),
			)
		);

		$this->add_control(
			'longitude',
			array(
				'label'     => __( 'طول جغرافیایی', 'mahan' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '51.3380',
				'condition' => array( 'provider' => 'osm' ),
			)
		);

		$this->add_control(
			'zoom',
			array(
				'label'     => __( 'بزرگ‌نمایی', 'mahan' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => array(
					'px' => array(
						'min' => 1,
						'max' => 19,
					),
				),
				'default'   => array( 'size' => 14 ),
				'condition' => array( 'provider' => 'osm' ),
			)
		);

		$this->add_control(
			'embed_url',
			array(
				'label'       => __( 'نشانی جاسازی', 'mahan' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'placeholder' => 'https://…',
				'description' => __( 'فقط نشانی iframe را وارد کنید، نه کل کد HTML.', 'mahan' ),
				'condition'   => array( 'provider' => 'custom' ),
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
						'min' => 200,
						'max' => 800,
					),
				),
				'default'    => array(
					'unit' => 'px',
					'size' => 400,
				),
				'selectors'  => array(
					'{{WRAPPER}} .mahan-map' => 'height: {{SIZE}}{{UNIT}};',
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

		if ( 'custom' === $settings['provider'] ) {
			$url = esc_url_raw( $settings['embed_url'] );
		} else {
			$lat  = (float) $settings['latitude'];
			$lng  = (float) $settings['longitude'];
			$zoom = isset( $settings['zoom']['size'] ) ? (int) $settings['zoom']['size'] : 14;
			$span = max( 0.002, 0.25 / max( 1, $zoom - 8 ) );

			$url = add_query_arg(
				array(
					'bbox'    => implode( ',', array( $lng - $span, $lat - $span, $lng + $span, $lat + $span ) ),
					'layer'   => 'mapnik',
					'marker'  => $lat . ',' . $lng,
				),
				'https://www.openstreetmap.org/export/embed.html'
			);
		}

		if ( ! $url ) {
			return;
		}
		?>
		<div class="mahan-map">
			<iframe
				src="<?php echo esc_url( $url ); ?>"
				title="<?php esc_attr_e( 'نقشهٔ موقعیت', 'mahan' ); ?>"
				loading="lazy"
				referrerpolicy="no-referrer-when-downgrade"
				style="border:0;width:100%;height:100%;"
			></iframe>
		</div>
		<?php
	}
}
