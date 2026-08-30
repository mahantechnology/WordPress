<?php
/**
 * Content tabs.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_tabs extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-tabs';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'تب‌های محتوا', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-tabs';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'tabs_section',
			array(
				'label' => __( 'تب‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'orientation',
			array(
				'label'   => __( 'جهت', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'horizontal',
				'options' => array(
					'horizontal' => __( 'افقی', 'mahan' ),
					'vertical'   => __( 'عمودی', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'عنوان تب', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تب تازه', 'mahan' ),
			)
		);

		$repeater->add_control(
			'content',
			array(
				'label'   => __( 'محتوا', 'mahan' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'محتوای این تب را این‌جا بنویسید.', 'mahan' ),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ label }}}',
				'default'     => array(
					array(
						'label'   => __( 'معرفی', 'mahan' ),
						'icon'    => 'sparkles',
						'content' => __( 'در این بخش دربارهٔ خودتان بنویسید.', 'mahan' ),
					),
					array(
						'label'   => __( 'خدمات', 'mahan' ),
						'icon'    => 'layers',
						'content' => __( 'فهرست خدمات‌تان را این‌جا بیاورید.', 'mahan' ),
					),
					array(
						'label'   => __( 'تماس', 'mahan' ),
						'icon'    => 'phone',
						'content' => __( 'راه‌های ارتباطی را این‌جا بنویسید.', 'mahan' ),
					),
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tabs_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => __( 'رنگ تب فعال', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-tabs__tab[aria-selected="true"]' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_text_color',
			array(
				'label'     => __( 'رنگ متن تب', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-tabs__tab' => 'color: {{VALUE}};',
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

		if ( empty( $settings['items'] ) ) {
			return;
		}

		$this->render_heading( $settings );

		$uid = 'mahan-tabs-' . $this->get_id();
		?>
		<div class="mahan-tabs mahan-tabs--<?php echo esc_attr( $settings['orientation'] ); ?>" data-mahan-tabs>
			<div class="mahan-tabs__list" role="tablist">
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<button
						type="button"
						class="mahan-tabs__tab"
						role="tab"
						id="<?php echo esc_attr( $uid . '-tab-' . $index ); ?>"
						aria-controls="<?php echo esc_attr( $uid . '-panel-' . $index ); ?>"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>"
					>
						<?php if ( $item['icon'] ) : ?>
							<?php $this->render_icon( $item['icon'], 18 ); ?>
						<?php endif; ?>
						<span><?php echo esc_html( $item['label'] ); ?></span>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="mahan-tabs__panels">
				<?php foreach ( $settings['items'] as $index => $item ) : ?>
					<div
						class="mahan-tabs__panel"
						role="tabpanel"
						id="<?php echo esc_attr( $uid . '-panel-' . $index ); ?>"
						aria-labelledby="<?php echo esc_attr( $uid . '-tab-' . $index ); ?>"
						<?php echo 0 === $index ? '' : 'hidden'; ?>
					>
						<?php echo wp_kses_post( $item['content'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}
}
