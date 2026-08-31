<?php
/**
 * Table of contents element: builds itself from the headings on the page.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_table_of_contents extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-table-of-contents';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'فهرست مطالب', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-table-of-contents';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'toc_section',
			array(
				'label' => __( 'فهرست', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'در این نوشته می‌خوانید', 'mahan' ),
			)
		);

		$this->add_control(
			'levels',
			array(
				'label'    => __( 'سطح عنوان‌ها', 'mahan' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'default'  => array( 'h2', 'h3' ),
				'options'  => array(
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
				),
			)
		);

		$this->add_control(
			'source',
			array(
				'label'       => __( 'محدودهٔ جستجو', 'mahan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '.mahan-entry, .entry-content, .elementor-widget-theme-post-content',
				'description' => __( 'انتخابگر CSS بخشی که عنوان‌ها در آن هستند.', 'mahan' ),
			)
		);

		$this->add_control(
			'collapsible',
			array(
				'label'        => __( 'قابل جمع‌شدن', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'numbered',
			array(
				'label'        => __( 'شماره‌گذاری', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$levels   = array_filter( (array) $settings['levels'] );

		if ( ! $levels ) {
			$levels = array( 'h2', 'h3' );
		}

		$config = array(
			'levels' => array_values( $levels ),
			'source' => $settings['source'],
		);

		$classes = 'mahan-toc';
		$classes .= 'yes' === $settings['numbered'] ? ' mahan-toc--numbered' : '';
		?>
		<nav class="<?php echo esc_attr( $classes ); ?>" data-mahan-toc="<?php echo esc_attr( wp_json_encode( $config ) ); ?>" aria-label="<?php esc_attr_e( 'فهرست مطالب', 'mahan' ); ?>" hidden>
			<div class="mahan-toc__head">
				<span class="mahan-toc__title">
					<?php $this->render_icon( 'list', 18 ); ?>
					<?php echo esc_html( $settings['title'] ); ?>
				</span>

				<?php if ( 'yes' === $settings['collapsible'] ) : ?>
					<button type="button" class="mahan-toc__toggle" data-mahan-toc-toggle aria-expanded="true">
						<?php $this->render_icon( 'chevron-up', 18 ); ?>
						<span class="screen-reader-text"><?php esc_html_e( 'باز و بسته کردن فهرست', 'mahan' ); ?></span>
					</button>
				<?php endif; ?>
			</div>

			<ol class="mahan-toc__list" data-mahan-toc-list></ol>
		</nav>
		<?php
	}
}
