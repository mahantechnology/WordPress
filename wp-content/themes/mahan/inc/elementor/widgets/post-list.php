<?php
/**
 * Compact post list element, for sidebars and footers.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_post_list extends Mahan_Widget_Base {

	use Mahan_Query_Trait;

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-post-list';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'فهرست نوشته‌ها', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();
		$this->add_query_controls( 'post', 'category', 5 );

		$this->start_controls_section(
			'display_section',
			array(
				'label' => __( 'نمایش', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'numbered',
			array(
				'label'        => __( 'شماره‌گذاری آیتم‌ها', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_thumb',
			array(
				'label'        => __( 'نمایش تصویر', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'show_date',
			array(
				'label'        => __( 'نمایش تاریخ', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->end_controls_section();

		$this->add_text_style_controls( '.mahan-post-list__title', '.mahan-post-list__date' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$query    = $this->build_query( $settings, 'post', 'category' );

		if ( ! $query->have_posts() ) {
			return;
		}

		$this->render_heading( $settings );

		$numbered = 'yes' === $settings['numbered'];
		$index    = 0;
		?>
		<ol class="mahan-post-list<?php echo $numbered ? ' mahan-post-list--numbered' : ''; ?>">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				++$index;
				?>
				<li class="mahan-post-list__item">
					<?php if ( $numbered ) : ?>
						<span class="mahan-post-list__index"><?php echo esc_html( mahan_fa_numbers( $index ) ); ?></span>
					<?php endif; ?>

					<?php if ( 'yes' === $settings['show_thumb'] ) : ?>
						<a class="mahan-post-list__thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
							<?php echo mahan_thumbnail( get_the_ID(), 'mahan-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup. ?>
						</a>
					<?php endif; ?>

					<div class="mahan-post-list__body">
						<a class="mahan-post-list__title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						<?php if ( 'yes' === $settings['show_date'] ) : ?>
							<span class="mahan-post-list__date">
								<?php $this->render_icon( 'clock', 14 ); ?>
								<?php echo esc_html( mahan_time_ago() ); ?>
							</span>
						<?php endif; ?>
					</div>
				</li>
			<?php endwhile; ?>
		</ol>
		<?php

		wp_reset_postdata();
	}
}
