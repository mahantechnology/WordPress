<?php
/**
 * Contact detail cards.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_contact_info extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-contact-info';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'اطلاعات تماس', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-envelope';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'contacts_section',
			array(
				'label' => __( 'آیتم‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'phone',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'label',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تلفن تماس', 'mahan' ),
			)
		);

		$repeater->add_control(
			'value',
			array(
				'label'   => __( 'مقدار', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '۰۲۱-۱۲۳۴۵۶۷۸',
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'لینک', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'tel:02112345678',
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
						'icon'  => 'phone',
						'label' => __( 'تلفن تماس', 'mahan' ),
						'value' => '۰۲۱-۱۲۳۴۵۶۷۸',
					),
					array(
						'icon'  => 'mail',
						'label' => __( 'ایمیل', 'mahan' ),
						'value' => 'info@example.com',
					),
					array(
						'icon'  => 'map-pin',
						'label' => __( 'نشانی', 'mahan' ),
						'value' => __( 'تهران، خیابان ولیعصر، پلاک ۱۲۰', 'mahan' ),
					),
					array(
						'icon'  => 'clock',
						'label' => __( 'ساعت کاری', 'mahan' ),
						'value' => __( 'شنبه تا چهارشنبه، ۹ تا ۱۸', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 4 );

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'card',
				'options' => array(
					'card'   => __( 'کارت', 'mahan' ),
					'inline' => __( 'خطی', 'mahan' ),
					'plain'  => __( 'ساده', 'mahan' ),
				),
			)
		);

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-contact-card' );
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
		<div class="mahan-grid mahan-contacts mahan-contacts--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['items'] as $item ) : ?>
				<?php
				$has_link = ! empty( $item['link']['url'] );
				$tag      = $has_link ? 'a' : 'div';
				?>
				<<?php echo esc_html( $tag ); ?> class="mahan-contact-card"<?php echo $has_link ? $this->link_attributes( $item['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
					<span class="mahan-contact-card__icon"><?php $this->render_icon( $item['icon'], 26 ); ?></span>
					<span class="mahan-contact-card__body">
						<span class="mahan-contact-card__label"><?php echo esc_html( $item['label'] ); ?></span>
						<span class="mahan-contact-card__value"><?php echo esc_html( $item['value'] ); ?></span>
					</span>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
