<?php
/**
 * Filterable portfolio grid.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_portfolio_grid extends Mahan_Widget_Base {

	use Mahan_Query_Trait;

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-portfolio-grid';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نمونه‌کارها', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-gallery-justified';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();
		$this->add_query_controls( 'mahan_portfolio', 'mahan_portfolio_cat', 6 );

		$this->start_controls_section(
			'display_section',
			array(
				'label' => __( 'نمایش', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'overlay',
				'options' => array(
					'overlay' => __( 'اطلاعات روی تصویر', 'mahan' ),
					'card'    => __( 'کارت', 'mahan' ),
					'masonry' => __( 'آجری', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'show_filter',
			array(
				'label'        => __( 'نمایش فیلتر دسته‌بندی', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_columns_control( 3 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-portfolio__item' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$query    = $this->build_query( $settings, 'mahan_portfolio', 'mahan_portfolio_cat' );

		if ( ! $query->have_posts() ) {
			echo '<p class="mahan-empty__text">' . esc_html__( 'هنوز نمونه‌کاری ثبت نشده است.', 'mahan' ) . '</p>';
			return;
		}

		$this->render_heading( $settings );

		if ( 'yes' === $settings['show_filter'] ) {
			$this->render_filter( $query );
		}
		?>
		<div class="mahan-grid mahan-portfolio mahan-portfolio--<?php echo esc_attr( $settings['style'] ); ?>" data-mahan-filter-grid>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();

				$terms = get_the_terms( get_the_ID(), 'mahan_portfolio_cat' );
				$slugs = ( $terms && ! is_wp_error( $terms ) ) ? wp_list_pluck( $terms, 'slug' ) : array();
				?>
				<article class="mahan-portfolio__item" data-terms="<?php echo esc_attr( implode( ' ', $slugs ) ); ?>">
					<a class="mahan-portfolio__link" href="<?php the_permalink(); ?>">
						<span class="mahan-portfolio__media">
							<?php echo mahan_thumbnail( get_the_ID(), 'mahan-card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup. ?>
						</span>
						<span class="mahan-portfolio__body">
							<?php if ( $slugs && $terms ) : ?>
								<span class="mahan-portfolio__cat"><?php echo esc_html( reset( $terms )->name ); ?></span>
							<?php endif; ?>
							<span class="mahan-portfolio__title"><?php the_title(); ?></span>
							<span class="mahan-portfolio__icon"><?php $this->render_icon( 'arrow-left', 20 ); ?></span>
						</span>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
		<?php

		wp_reset_postdata();
	}

	/**
	 * Prints the term filter buttons for the posts in the query.
	 *
	 * @param WP_Query $query Query being rendered.
	 */
	private function render_filter( $query ) {
		$terms = array();

		foreach ( $query->posts as $post ) {
			$post_terms = get_the_terms( $post->ID, 'mahan_portfolio_cat' );

			if ( ! $post_terms || is_wp_error( $post_terms ) ) {
				continue;
			}

			foreach ( $post_terms as $term ) {
				$terms[ $term->slug ] = $term->name;
			}
		}

		if ( count( $terms ) < 2 ) {
			return;
		}
		?>
		<div class="mahan-filter" role="group" aria-label="<?php esc_attr_e( 'فیلتر نمونه‌کارها', 'mahan' ); ?>">
			<button type="button" class="mahan-filter__btn is-active" data-mahan-filter="*">
				<?php esc_html_e( 'همه', 'mahan' ); ?>
			</button>
			<?php foreach ( $terms as $slug => $name ) : ?>
				<button type="button" class="mahan-filter__btn" data-mahan-filter="<?php echo esc_attr( $slug ); ?>">
					<?php echo esc_html( $name ); ?>
				</button>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
