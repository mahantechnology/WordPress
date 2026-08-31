<?php
/**
 * Author box element: the byline card that closes a post.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_author_box extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-author-box';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'جعبهٔ نویسنده', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-person';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'author_section',
			array(
				'label' => __( 'نویسنده', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'eyebrow',
			array(
				'label'   => __( 'برچسب بالا', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'نوشتهٔ', 'mahan' ),
			)
		);

		$this->add_control(
			'show_posts_link',
			array(
				'label'        => __( 'لینک «همهٔ نوشته‌ها»', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'avatar_size',
			array(
				'label'   => __( 'اندازهٔ تصویر', 'mahan' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 48,
				'max'     => 160,
				'default' => 88,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$author   = (int) get_the_author_meta( 'ID' );

		if ( ! $author ) {
			return;
		}

		$bio  = get_the_author_meta( 'description', $author );
		$size = max( 48, (int) $settings['avatar_size'] );
		?>
		<div class="mahan-authorbox">
			<div class="mahan-authorbox__avatar">
				<?php echo get_avatar( $author, $size, '', '', array( 'class' => 'mahan-authorbox__img' ) ); ?>
			</div>

			<div class="mahan-authorbox__body">
				<?php if ( $settings['eyebrow'] ) : ?>
					<span class="mahan-authorbox__eyebrow"><?php echo esc_html( $settings['eyebrow'] ); ?></span>
				<?php endif; ?>

				<h3 class="mahan-authorbox__name"><?php echo esc_html( get_the_author_meta( 'display_name', $author ) ); ?></h3>

				<?php if ( $bio ) : ?>
					<p class="mahan-authorbox__bio"><?php echo esc_html( $bio ); ?></p>
				<?php endif; ?>

				<?php if ( 'yes' === $settings['show_posts_link'] ) : ?>
					<a class="mahan-authorbox__link" href="<?php echo esc_url( get_author_posts_url( $author ) ); ?>">
						<?php esc_html_e( 'همهٔ نوشته‌های این نویسنده', 'mahan' ); ?>
						<?php $this->render_icon( 'arrow-left', 16 ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
