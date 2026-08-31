<?php
/**
 * Trust badges element: the reassurance row a shop puts near checkout.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_trust_badges extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-trust-badges';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نمادهای اعتماد', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-lock-user';
	}

	/**
	 * Panel categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return mahan_has_woocommerce() ? array( 'mahan', 'mahan-woo' ) : array( 'mahan' );
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'badges_section',
			array(
				'label' => __( 'نمادها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'boxed',
				'options' => array(
					'boxed'  => __( 'کادردار', 'mahan' ),
					'plain'  => __( 'ساده', 'mahan' ),
					'ribbon' => __( 'نواری', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'icon',
			array(
				'label'   => __( 'آیکون', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'shield',
				'options' => mahan_icon_choices(),
			)
		);

		$repeater->add_control(
			'image',
			array(
				'label'       => __( 'تصویر نماد', 'mahan' ),
				'type'        => Controls_Manager::MEDIA,
				'description' => __( 'اگر تصویری بگذارید، به‌جای آیکون نمایش داده می‌شود.', 'mahan' ),
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ضمانت اصالت', 'mahan' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => __( 'توضیح', 'mahan' ),
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

		$this->add_control(
			'badges',
			array(
				'label'       => __( 'نمادها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'icon'  => 'shield',
						'title' => __( 'ضمانت اصالت', 'mahan' ),
						'text'  => __( 'کالای اورجینال', 'mahan' ),
					),
					array(
						'icon'  => 'truck',
						'title' => __( 'ارسال سریع', 'mahan' ),
						'text'  => __( 'به سراسر کشور', 'mahan' ),
					),
					array(
						'icon'  => 'refresh',
						'title' => __( 'بازگشت کالا', 'mahan' ),
						'text'  => __( 'تا هفت روز', 'mahan' ),
					),
					array(
						'icon'  => 'lock',
						'title' => __( 'پرداخت امن', 'mahan' ),
						'text'  => __( 'درگاه معتبر بانکی', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 4 );

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['badges'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-badges mahan-badges--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['badges'] as $badge ) : ?>
				<?php
				$image    = $this->image_url( $badge['image'] );
				$has_link = ! empty( $badge['link']['url'] );
				$tag      = $has_link ? 'a' : 'div';
				?>
				<<?php echo esc_html( $tag ); ?>
					class="mahan-badge"
					<?php echo $has_link ? $this->link_attributes( $badge['link'] ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>
				>
					<span class="mahan-badge__mark">
						<?php if ( $image ) : ?>
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $badge['title'] ); ?>" loading="lazy" />
						<?php else : ?>
							<?php $this->render_icon( $badge['icon'], 26 ); ?>
						<?php endif; ?>
					</span>

					<span class="mahan-badge__body">
						<strong><?php echo esc_html( $badge['title'] ); ?></strong>
						<?php if ( $badge['text'] ) : ?>
							<span><?php echo esc_html( $badge['text'] ); ?></span>
						<?php endif; ?>
					</span>
				</<?php echo esc_html( $tag ); ?>>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
