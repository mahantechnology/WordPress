<?php
/**
 * Search box with live results.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_search_box extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-search-box';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'جعبهٔ جستجو', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-site-search';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'search_section',
			array(
				'label' => __( 'تنظیمات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'placeholder',
			array(
				'label'   => __( 'متن راهنما', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'دنبال چه چیزی می‌گردید؟', 'mahan' ),
			)
		);

		$this->add_control(
			'live',
			array(
				'label'        => __( 'نمایش نتایج زنده', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'button_text',
			array(
				'label'   => __( 'متن دکمه', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'جستجو', 'mahan' ),
			)
		);

		$this->add_control(
			'size',
			array(
				'label'   => __( 'اندازه', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'md',
				'options' => array(
					'sm' => __( 'کوچک', 'mahan' ),
					'md' => __( 'معمولی', 'mahan' ),
					'lg' => __( 'بزرگ', 'mahan' ),
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
		?>
		<form
			role="search"
			method="get"
			class="mahan-search mahan-search--<?php echo esc_attr( $settings['size'] ); ?>"
			action="<?php echo esc_url( home_url( '/' ) ); ?>"
			<?php echo 'yes' === $settings['live'] ? 'data-mahan-live-search' : ''; ?>
		>
			<label class="screen-reader-text" for="mahan-search-<?php echo esc_attr( $this->get_id() ); ?>">
				<?php esc_html_e( 'جستجو', 'mahan' ); ?>
			</label>
			<?php $this->render_icon( 'search', 20, 'mahan-search__icon' ); ?>
			<input
				type="search"
				id="mahan-search-<?php echo esc_attr( $this->get_id() ); ?>"
				name="s"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				placeholder="<?php echo esc_attr( $settings['placeholder'] ); ?>"
				autocomplete="off"
			/>
			<button type="submit" class="mahan-btn mahan-btn--primary">
				<?php echo esc_html( $settings['button_text'] ); ?>
			</button>
			<?php if ( 'yes' === $settings['live'] ) : ?>
				<div class="mahan-search__results" data-mahan-search-results hidden></div>
			<?php endif; ?>
		</form>
		<?php
	}
}
