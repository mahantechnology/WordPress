<?php
/**
 * Products grouped into tabs by category.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_product_tabs extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-product-tabs';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'محصولات در تب دسته‌بندی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-tabs';
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
			'tabs_section',
			array(
				'label' => __( 'تب‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$options = array();

		if ( taxonomy_exists( 'product_cat' ) ) {
			$terms = get_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => true,
					'number'     => 100,
				)
			);

			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$options[ $term->term_id ] = $term->name;
				}
			}
		}

		$this->add_control(
			'categories',
			array(
				'label'       => __( 'دسته‌بندی‌ها', 'mahan' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $options,
				'default'     => array_slice( array_keys( $options ), 0, 4 ),
			)
		);

		$this->add_control(
			'per_tab',
			array(
				'label'   => __( 'تعداد محصول در هر تب', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 8,
				'min'     => 1,
				'max'     => 24,
			)
		);

		$this->add_columns_control( 4 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-product-card' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		if ( ! mahan_has_woocommerce() ) {
			return;
		}

		$settings   = $this->get_settings_for_display();
		$categories = array_filter( array_map( 'absint', (array) $settings['categories'] ) );

		if ( ! $categories ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'ابتدا دسته‌بندی‌ها را انتخاب کنید.', 'mahan' ) . '</p>';
			return;
		}

		$this->render_heading( $settings );

		$uid = 'mahan-product-tabs-' . $this->get_id();
		?>
		<div class="mahan-tabs mahan-tabs--horizontal" data-mahan-tabs>
			<div class="mahan-tabs__list" role="tablist">
				<?php foreach ( $categories as $index => $term_id ) : ?>
					<?php
					$term = get_term( $term_id, 'product_cat' );

					if ( ! $term instanceof WP_Term ) {
						continue;
					}
					?>
					<button type="button" class="mahan-tabs__tab" role="tab"
						id="<?php echo esc_attr( $uid . '-tab-' . $index ); ?>"
						aria-controls="<?php echo esc_attr( $uid . '-panel-' . $index ); ?>"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>">
						<?php echo esc_html( $term->name ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="mahan-tabs__panels">
				<?php foreach ( $categories as $index => $term_id ) : ?>
					<div class="mahan-tabs__panel" role="tabpanel"
						id="<?php echo esc_attr( $uid . '-panel-' . $index ); ?>"
						aria-labelledby="<?php echo esc_attr( $uid . '-tab-' . $index ); ?>"
						<?php echo 0 === $index ? '' : 'hidden'; ?>>
						<?php $this->render_products( $term_id, (int) $settings['per_tab'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Prints the products in one category.
	 *
	 * @param int $term_id Category ID.
	 * @param int $count   How many products to show.
	 */
	private function render_products( $term_id, $count ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => max( 1, $count ),
				'no_found_rows'  => true,
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query -- Bounded by posts_per_page.
					array(
						'taxonomy' => 'product_cat',
						'field'    => 'term_id',
						'terms'    => $term_id,
					),
				),
			)
		);

		if ( ! $query->have_posts() ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'محصولی در این دسته نیست.', 'mahan' ) . '</p>';
			return;
		}

		echo '<ul class="products mahan-products mahan-grid">';

		while ( $query->have_posts() ) {
			$query->the_post();
			wc_get_template_part( 'content', 'product' );
		}

		echo '</ul>';

		wp_reset_postdata();
	}
}
