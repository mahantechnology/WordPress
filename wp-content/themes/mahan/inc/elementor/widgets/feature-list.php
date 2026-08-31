<?php
/**
 * Feature list element: a checklist of short lines, each with its own icon.
 *
 * Named feature-list rather than icon-list because Elementor ships a core
 * widget under that name and the demo builder treats it as a core type.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_feature_list extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-feature-list';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'فهرست ویژگی‌ها', 'mahan' );
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
			'items_section',
			array(
				'label' => __( 'آیتم‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'check',
				'options' => array(
					'check'   => __( 'تیک ساده', 'mahan' ),
					'circle'  => __( 'آیکون در دایره', 'mahan' ),
					'boxed'   => __( 'کادردار', 'mahan' ),
					'divided' => __( 'با خط جداکننده', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'check',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label'   => __( 'متن', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'یک ویژگی یا مزیت', 'mahan' ),
			)
		);

		$repeater->add_control(
			'note',
			array(
				'label' => __( 'توضیح کوتاه', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
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

		$repeater->add_control(
			'icon_color',
			array(
				'label'     => __( 'رنگ آیکون', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} {{CURRENT_ITEM}} .mahan-featurelist__icon' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'items',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
				'default'     => array(
					array(
						'icon' => 'check',
						'text' => __( 'پشتیبانی کامل از راست‌چین', 'mahan' ),
					),
					array(
						'icon' => 'check',
						'text' => __( 'سازگار با المنتور و ووکامرس', 'mahan' ),
					),
					array(
						'icon' => 'check',
						'text' => __( 'به‌روزرسانی رایگان', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 1 );

		$this->end_controls_section();

		$this->add_text_style_controls( '.mahan-featurelist__text', '.mahan-featurelist__note' );
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
		<ul class="mahan-grid mahan-featurelist mahan-featurelist--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['items'] as $item ) : ?>
				<?php
				$has_link = ! empty( $item['link']['url'] );
				$tag      = $has_link ? 'a' : 'span';
				?>
				<li class="mahan-featurelist__item elementor-repeater-item-<?php echo esc_attr( $item['_id'] ); ?>">
					<<?php echo esc_html( $tag ); ?>
						class="mahan-featurelist__row"
						<?php echo $has_link ? $this->link_attributes( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
					>
						<span class="mahan-featurelist__icon"><?php $this->render_icon( $item['icon'], 18 ); ?></span>

						<span class="mahan-featurelist__body">
							<span class="mahan-featurelist__text"><?php echo esc_html( $item['text'] ); ?></span>

							<?php if ( $item['note'] ) : ?>
								<span class="mahan-featurelist__note"><?php echo esc_html( $item['note'] ); ?></span>
							<?php endif; ?>
						</span>
					</<?php echo esc_html( $tag ); ?>>
				</li>
			<?php endforeach; ?>
		</ul>
		<?php
	}
}
