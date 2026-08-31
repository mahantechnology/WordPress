<?php
/**
 * Pull-quote element: a short quotation given room to breathe.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_blockquote extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-blockquote';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'نقل‌قول شاخص', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-blockquote';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'quote_section',
			array(
				'label' => __( 'نقل‌قول', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'line',
				'options' => array(
					'line'     => __( 'خط کناری', 'mahan' ),
					'card'     => __( 'کارت', 'mahan' ),
					'gradient' => __( 'گرادیانی', 'mahan' ),
					'plain'    => __( 'ساده و بزرگ', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'quote',
			array(
				'label'   => __( 'متن', 'mahan' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 4,
				'default' => __( 'طراحی خوب، آن چیزی است که وقتی درست کار می‌کند دیده نمی‌شود.', 'mahan' ),
			)
		);

		$this->add_control(
			'author',
			array(
				'label'   => __( 'گوینده', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'نام گوینده', 'mahan' ),
			)
		);

		$this->add_control(
			'role',
			array(
				'label' => __( 'سمت', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'avatar',
			array(
				'label' => __( 'تصویر گوینده', 'mahan' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$this->add_control(
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
				),
				'selectors' => array(
					'{{WRAPPER}} .mahan-quote' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

		$this->add_text_style_controls( '.mahan-quote__text', '.mahan-quote__author' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( ! $settings['quote'] ) {
			return;
		}

		$avatar = $this->image_url( $settings['avatar'] );
		?>
		<figure class="mahan-quote mahan-quote--<?php echo esc_attr( $settings['style'] ); ?>">
			<span class="mahan-quote__mark" aria-hidden="true"><?php $this->render_icon( 'quote', 34 ); ?></span>

			<blockquote class="mahan-quote__text"><?php echo esc_html( $settings['quote'] ); ?></blockquote>

			<?php if ( $settings['author'] || $avatar ) : ?>
				<figcaption class="mahan-quote__by">
					<?php if ( $avatar ) : ?>
						<img class="mahan-quote__avatar" src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $settings['author'] ); ?>" loading="lazy" />
					<?php endif; ?>

					<span>
						<?php if ( $settings['author'] ) : ?>
							<strong class="mahan-quote__author"><?php echo esc_html( $settings['author'] ); ?></strong>
						<?php endif; ?>

						<?php if ( $settings['role'] ) : ?>
							<span class="mahan-quote__role"><?php echo esc_html( $settings['role'] ); ?></span>
						<?php endif; ?>
					</span>
				</figcaption>
			<?php endif; ?>
		</figure>
		<?php
	}
}
