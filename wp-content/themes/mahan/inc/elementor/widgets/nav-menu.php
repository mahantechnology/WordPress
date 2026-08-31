<?php
/**
 * Navigation menu element, for building a header in Elementor.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_nav_menu extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-nav-menu';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'منوی سایت', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-nav-menu';
	}

	/**
	 * The menus available to pick from.
	 *
	 * @return array<string,string>
	 */
	private function menu_options() {
		$options = array( '' => __( '— جایگاه منوی اصلی —', 'mahan' ) );

		foreach ( wp_get_nav_menus() as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'menu_section',
			array(
				'label' => __( 'منو', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'menu',
			array(
				'label'   => __( 'منو', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->menu_options(),
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'underline',
				'options' => array(
					'underline' => __( 'با خط زیر', 'mahan' ),
					'pill'      => __( 'قرصی', 'mahan' ),
					'plain'     => __( 'ساده', 'mahan' ),
				),
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'چینش', 'mahan' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => 'flex-start',
				'options'   => array(
					'flex-start' => array(
						'title' => __( 'راست', 'mahan' ),
						'icon'  => 'eicon-text-align-right',
					),
					'center'     => array(
						'title' => __( 'وسط', 'mahan' ),
						'icon'  => 'eicon-text-align-center',
					),
					'flex-end'   => array(
						'title' => __( 'چپ', 'mahan' ),
						'icon'  => 'eicon-text-align-left',
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .mahan-menu' => 'justify-content: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'     => __( 'رنگ لینک‌ها', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-menu__link' => 'color: {{VALUE}};',
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

		$args = array(
			'container'   => false,
			'menu_class'  => 'mahan-menu mahan-menu--' . sanitize_html_class( $settings['style'] ),
			'walker'      => new Mahan_Nav_Walker(),
			'fallback_cb' => 'mahan_menu_fallback',
		);

		if ( $settings['menu'] ) {
			$args['menu'] = $settings['menu'];
		} else {
			$args['theme_location'] = 'primary';
		}
		?>
		<nav class="mahan-nav-element" aria-label="<?php esc_attr_e( 'منوی سایت', 'mahan' ); ?>">
			<?php wp_nav_menu( $args ); ?>
		</nav>
		<?php
	}
}
