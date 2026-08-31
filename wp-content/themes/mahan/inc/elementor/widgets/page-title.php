<?php
/**
 * Page title element: the heading of whatever is being viewed.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_page_title extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-page-title';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'عنوان صفحه', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-post-title';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'title_section',
			array(
				'label' => __( 'عنوان', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'   => __( 'تگ HTML', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h1',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->add_control(
			'show_breadcrumb',
			array(
				'label'        => __( 'نمایش مسیر صفحه', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'چینش', 'mahan' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'right',
				'options'   => array(
					'right'  => array(
						'title' => __( 'راست', 'mahan' ),
						'icon'  => 'eicon-text-align-right',
					),
					'center' => array(
						'title' => __( 'وسط', 'mahan' ),
						'icon'  => 'eicon-text-align-center',
					),
					'left'   => array(
						'title' => __( 'چپ', 'mahan' ),
						'icon'  => 'eicon-text-align-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mahan-page-title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'color',
			array(
				'label'     => __( 'رنگ', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-page-title__text' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * The title for the view being rendered.
	 *
	 * @return string
	 */
	private function current_title() {
		if ( is_search() ) {
			/* translators: %s: search term. */
			return sprintf( __( 'نتایج جستجو برای «%s»', 'mahan' ), get_search_query() );
		}

		if ( is_archive() ) {
			return wp_strip_all_tags( get_the_archive_title() );
		}

		if ( is_home() && ! is_front_page() ) {
			return get_the_title( (int) get_option( 'page_for_posts' ) );
		}

		if ( is_404() ) {
			return __( 'صفحه پیدا نشد', 'mahan' );
		}

		return get_the_title();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$title    = $this->current_title();

		if ( '' === trim( (string) $title ) ) {
			return;
		}

		$tag = mahan_sanitize_choice( $settings['tag'], array( 'h1', 'h2', 'h3', 'div', 'span' ) );
		?>
		<div class="mahan-page-title">
			<?php if ( 'yes' === $settings['show_breadcrumb'] ) : ?>
				<?php mahan_breadcrumb(); ?>
			<?php endif; ?>

			<<?php echo esc_html( $tag ); ?> class="mahan-page-title__text"><?php echo esc_html( $title ); ?></<?php echo esc_html( $tag ); ?>>
		</div>
		<?php
	}
}
