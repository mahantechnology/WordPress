<?php
/**
 * Posts grouped into tabs, one tab per category.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_post_tabs extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-post-tabs';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نوشته‌ها در تب دسته‌بندی', 'mahan' );
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

		$categories = array();

		foreach ( get_categories( array( 'hide_empty' => true ) ) as $category ) {
			$categories[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'categories',
			array(
				'label'       => __( 'دسته‌بندی‌ها', 'mahan' ),
				'type'        => Controls_Manager::SELECT2,
				'multiple'    => true,
				'label_block' => true,
				'options'     => $categories,
				'default'     => array_slice( array_keys( $categories ), 0, 4 ),
			)
		);

		$this->add_control(
			'posts_per_tab',
			array(
				'label'   => __( 'تعداد نوشته در هر تب', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 4,
				'min'     => 1,
				'max'     => 12,
			)
		);

		$this->add_columns_control( 4 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-card' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$categories = array_filter( array_map( 'absint', (array) $settings['categories'] ) );

		if ( ! $categories ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'ابتدا دسته‌بندی‌ها را انتخاب کنید.', 'mahan' ) . '</p>';
			return;
		}

		$this->render_heading( $settings );

		$uid = 'mahan-post-tabs-' . $this->get_id();
		?>
		<div class="mahan-tabs mahan-tabs--horizontal mahan-post-tabs" data-mahan-tabs>
			<div class="mahan-tabs__list" role="tablist">
				<?php foreach ( $categories as $index => $term_id ) : ?>
					<?php
					$term = get_term( $term_id, 'category' );

					if ( ! $term instanceof WP_Term ) {
						continue;
					}
					?>
					<button
						type="button"
						class="mahan-tabs__tab"
						role="tab"
						id="<?php echo esc_attr( $uid . '-tab-' . $index ); ?>"
						aria-controls="<?php echo esc_attr( $uid . '-panel-' . $index ); ?>"
						aria-selected="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						tabindex="<?php echo 0 === $index ? '0' : '-1'; ?>"
					>
						<?php echo esc_html( $term->name ); ?>
					</button>
				<?php endforeach; ?>
			</div>

			<div class="mahan-tabs__panels">
				<?php foreach ( $categories as $index => $term_id ) : ?>
					<div
						class="mahan-tabs__panel"
						role="tabpanel"
						id="<?php echo esc_attr( $uid . '-panel-' . $index ); ?>"
						aria-labelledby="<?php echo esc_attr( $uid . '-tab-' . $index ); ?>"
						<?php echo 0 === $index ? '' : 'hidden'; ?>
					>
						<?php $this->render_posts( $term_id, (int) $settings['posts_per_tab'] ); ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Prints the cards for one category.
	 *
	 * @param int $term_id Category ID.
	 * @param int $count   How many posts to show.
	 */
	private function render_posts( $term_id, $count ) {
		$query = new WP_Query(
			array(
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => max( 1, $count ),
				'cat'            => $term_id,
				'no_found_rows'  => true,
			)
		);

		if ( ! $query->have_posts() ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'نوشته‌ای در این دسته نیست.', 'mahan' ) . '</p>';
			return;
		}

		echo '<div class="mahan-grid">';

		while ( $query->have_posts() ) {
			$query->the_post();
			mahan_render_post_card(
				array(
					'show_excerpt' => false,
					'show_more'    => false,
				)
			);
		}

		echo '</div>';

		wp_reset_postdata();
	}
}
