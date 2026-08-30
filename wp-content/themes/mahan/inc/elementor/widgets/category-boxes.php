<?php
/**
 * Taxonomy term cards.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_category_boxes extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-category-boxes';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'باکس دسته‌بندی‌ها', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-folder-o';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'terms_section',
			array(
				'label' => __( 'دسته‌بندی‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$taxonomies = array();

		foreach ( get_taxonomies( array( 'public' => true ), 'objects' ) as $taxonomy ) {
			$taxonomies[ $taxonomy->name ] = $taxonomy->label;
		}

		$this->add_control(
			'taxonomy',
			array(
				'label'   => __( 'نوع دسته‌بندی', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'category',
				'options' => $taxonomies,
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
			'orderby',
			array(
				'label'   => __( 'مرتب‌سازی', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'count',
				'options' => array(
					'count' => __( 'تعداد مطالب', 'mahan' ),
					'name'  => __( 'نام', 'mahan' ),
					'term_id' => __( 'ترتیب ایجاد', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'image'  => __( 'با تصویر', 'mahan' ),
					'icon'   => __( 'با آیکون', 'mahan' ),
					'pill'   => __( 'قرصی', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'show_count',
			array(
				'label'        => __( 'نمایش تعداد مطالب', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_columns_control( 3 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-term-card' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! taxonomy_exists( $settings['taxonomy'] ) ) {
			return;
		}

		$terms = get_terms(
			array(
				'taxonomy'   => $settings['taxonomy'],
				'hide_empty' => true,
				'number'     => (int) $settings['count'],
				'orderby'    => $settings['orderby'],
				'order'      => 'count' === $settings['orderby'] ? 'DESC' : 'ASC',
			)
		);

		if ( ! $terms || is_wp_error( $terms ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-terms mahan-terms--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $terms as $term ) : ?>
				<?php $image = $this->term_image( $term ); ?>
				<a class="mahan-term-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
					<?php if ( 'image' === $settings['style'] ) : ?>
						<span class="mahan-term-card__media">
							<?php if ( $image ) : ?>
								<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $term->name ); ?>" loading="lazy" />
							<?php else : ?>
								<img src="<?php echo esc_url( mahan_placeholder_image() ); ?>" alt="" loading="lazy" />
							<?php endif; ?>
						</span>
					<?php elseif ( 'icon' === $settings['style'] ) : ?>
						<span class="mahan-term-card__icon"><?php $this->render_icon( 'folder', 26 ); ?></span>
					<?php endif; ?>

					<span class="mahan-term-card__body">
						<span class="mahan-term-card__name"><?php echo esc_html( $term->name ); ?></span>
						<?php if ( 'yes' === $settings['show_count'] ) : ?>
							<span class="mahan-term-card__count">
								<?php
								printf(
									/* translators: %s: number of items. */
									esc_html__( '%s مورد', 'mahan' ),
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

	/**
	 * The thumbnail stored on a term, when one exists.
	 *
	 * @param WP_Term $term Term to read.
	 * @return string
	 */
	private function term_image( $term ) {
		$meta_keys = array( 'thumbnail_id', '_mahan_term_image', 'category_image_id' );

		foreach ( $meta_keys as $key ) {
			$id = (int) get_term_meta( $term->term_id, $key, true );

			if ( $id ) {
				$url = wp_get_attachment_image_url( $id, 'mahan-card' );

				if ( $url ) {
					return $url;
				}
			}
		}

		return '';
	}
}
