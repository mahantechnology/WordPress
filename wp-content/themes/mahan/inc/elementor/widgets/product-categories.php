<?php
/**
 * WooCommerce product category cards.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_product_categories extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-product-categories';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'دسته‌بندی محصولات', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-categories';
	}

	/**
	 * Panel categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'mahan-woo' );
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'cats_section',
			array(
				'label' => __( 'دسته‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'count',
			array(
				'label'   => __( 'تعداد', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => 1,
				'max'     => 24,
			)
		);

		$this->add_control(
			'parent_only',
			array(
				'label'        => __( 'فقط دسته‌های اصلی', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'tile',
				'options' => array(
					'tile'    => __( 'کاشی', 'mahan' ),
					'circle'  => __( 'تصویر دایره‌ای', 'mahan' ),
					'overlay' => __( 'متن روی تصویر', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => __( 'نمایش تعداد محصول', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_columns_control( 6 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-cat-card' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		if ( ! mahan_has_woocommerce() ) {
			return;
		}

		$settings = $this->get_settings_for_display();

		$args = array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'number'     => (int) $settings['count'],
			'orderby'    => 'menu_order',
		);

		if ( 'yes' === $settings['parent_only'] ) {
			$args['parent'] = 0;
		}

		$terms = get_terms( $args );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-cats mahan-cats--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $terms as $term ) : ?>
				<?php
				$thumbnail_id = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
				$image        = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'mahan-square' ) : wc_placeholder_img_src( 'mahan-square' );
				?>
				<a class="mahan-cat-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<span class="mahan-cat-card__media">
						<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy" />
					</span>
					<span class="mahan-cat-card__body">
						<span class="mahan-cat-card__name"><?php echo esc_html( $term->name ); ?></span>
						<?php if ( 'yes' === $settings['show_count'] ) : ?>
							<span class="mahan-cat-card__count">
								<?php
								printf(
									/* translators: %s: product count. */
									esc_html__( '%s کالا', 'mahan' ),
									esc_html( mahan_fa_numbers( $term->count ) )
								);
								?>
							</span>
						<?php endif; ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
