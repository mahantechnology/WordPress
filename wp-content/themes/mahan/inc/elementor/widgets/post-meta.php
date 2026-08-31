<?php
/**
 * Post meta element: author, date, reading time and category.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_post_meta extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-post-meta';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'اطلاعات نوشته', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-info';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'meta_section',
			array(
				'label' => __( 'اطلاعات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'parts',
			array(
				'label'    => __( 'بخش‌ها', 'mahan' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'default'  => array( 'author', 'date', 'reading', 'category' ),
				'options'  => array(
					'author'   => __( 'نویسنده', 'mahan' ),
					'date'     => __( 'تاریخ', 'mahan' ),
					'reading'  => __( 'زمان مطالعه', 'mahan' ),
					'category' => __( 'دسته‌بندی', 'mahan' ),
					'comments' => __( 'شمار دیدگاه‌ها', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'show_avatar',
			array(
				'label'        => __( 'نمایش تصویر نویسنده', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'رنگ متن', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-postmeta' => 'color: {{VALUE}};',
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
		$parts    = array_filter( (array) $settings['parts'] );

		if ( ! $parts ) {
			return;
		}
		?>
		<div class="mahan-postmeta">
			<?php if ( in_array( 'author', $parts, true ) ) : ?>
				<span class="mahan-postmeta__item mahan-postmeta__item--author">
					<?php if ( 'yes' === $settings['show_avatar'] ) : ?>
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 28, '', '', array( 'class' => 'mahan-postmeta__avatar' ) ); ?>
					<?php else : ?>
						<?php $this->render_icon( 'user', 16 ); ?>
					<?php endif; ?>
					<?php echo esc_html( get_the_author() ); ?>
				</span>
			<?php endif; ?>

			<?php if ( in_array( 'date', $parts, true ) ) : ?>
				<span class="mahan-postmeta__item">
					<?php $this->render_icon( 'calendar', 16 ); ?>
					<?php echo esc_html( mahan_fa_numbers( get_the_date() ) ); ?>
				</span>
			<?php endif; ?>

			<?php if ( in_array( 'reading', $parts, true ) ) : ?>
				<span class="mahan-postmeta__item">
					<?php $this->render_icon( 'clock', 16 ); ?>
					<?php
					printf(
						/* translators: %s: number of minutes. */
						esc_html__( '%s دقیقه مطالعه', 'mahan' ),
						esc_html( mahan_fa_numbers( (string) mahan_reading_time() ) )
					);
					?>
				</span>
			<?php endif; ?>

			<?php if ( in_array( 'category', $parts, true ) ) : ?>
				<?php $terms = get_the_category(); ?>
				<?php if ( $terms ) : ?>
					<span class="mahan-postmeta__item">
						<?php $this->render_icon( 'folder', 16 ); ?>
						<a href="<?php echo esc_url( get_category_link( $terms[0] ) ); ?>"><?php echo esc_html( $terms[0]->name ); ?></a>
					</span>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( in_array( 'comments', $parts, true ) ) : ?>
				<span class="mahan-postmeta__item">
					<?php $this->render_icon( 'comment', 16 ); ?>
					<?php echo esc_html( mahan_fa_numbers( (string) get_comments_number() ) ); ?>
				</span>
			<?php endif; ?>
		</div>
		<?php
	}
}
