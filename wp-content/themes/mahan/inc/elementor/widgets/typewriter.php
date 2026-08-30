<?php
/**
 * Typewriter heading.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Mahan_Widget_typewriter extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-typewriter';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'عنوان تایپی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-animation-text';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'typewriter_section',
			array(
				'label' => __( 'تنظیمات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'before',
			array(
				'label'   => __( 'متن ثابت ابتدا', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ما در ماهان', 'mahan' ),
			)
		);

		$this->add_control(
			'words',
			array(
				'label'       => __( 'کلمه‌های متغیر', 'mahan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'default'     => __( "طراحی می‌کنیم\nتوسعه می‌دهیم\nپشتیبانی می‌کنیم", 'mahan' ),
				'description' => __( 'هر خط یک عبارت.', 'mahan' ),
			)
		);

		$this->add_control(
			'after',
			array(
				'label' => __( 'متن ثابت انتها', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'speed',
			array(
				'label'   => __( 'سرعت تایپ (میلی‌ثانیه)', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 90,
				'min'     => 20,
				'max'     => 400,
			)
		);

		$this->add_control(
			'pause',
			array(
				'label'   => __( 'مکث بین عبارت‌ها (میلی‌ثانیه)', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 1600,
				'min'     => 300,
				'max'     => 6000,
				'step'    => 100,
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'   => __( 'تگ', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'div'  => 'div',
					'span' => 'span',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'typewriter_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'     => __( 'رنگ متن ثابت', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-typewriter' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'word_color',
			array(
				'label'     => __( 'رنگ کلمهٔ متغیر', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-typewriter__word' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'typography',
				'selector' => '{{WRAPPER}} .mahan-typewriter',
			)
		);

		$this->add_responsive_control(
			'align',
			array(
				'label'     => __( 'چینش', 'mahan' ),
				'type'      => Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .mahan-typewriter' => 'text-align: {{VALUE}};',
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
		$words    = array_values( array_filter( array_map( 'trim', explode( "\n", (string) $settings['words'] ) ) ) );

		if ( ! $words ) {
			return;
		}

		$tag = in_array( $settings['tag'], array( 'h1', 'h2', 'h3', 'div', 'span' ), true ) ? $settings['tag'] : 'h2';
		?>
		<<?php echo esc_html( $tag ); ?> class="mahan-typewriter"
			data-mahan-typewriter="<?php echo esc_attr( wp_json_encode( $words ) ); ?>"
			data-speed="<?php echo esc_attr( (int) $settings['speed'] ); ?>"
			data-pause="<?php echo esc_attr( (int) $settings['pause'] ); ?>">
			<?php if ( $settings['before'] ) : ?>
				<span class="mahan-typewriter__before"><?php echo esc_html( $settings['before'] ); ?></span>
			<?php endif; ?>
			<span class="mahan-typewriter__word" data-mahan-typewriter-target><?php echo esc_html( $words[0] ); ?></span><span class="mahan-typewriter__caret" aria-hidden="true"></span>
			<?php if ( $settings['after'] ) : ?>
				<span class="mahan-typewriter__after"><?php echo esc_html( $settings['after'] ); ?></span>
			<?php endif; ?>
		</<?php echo esc_html( $tag ); ?>>
		<?php
	}
}
