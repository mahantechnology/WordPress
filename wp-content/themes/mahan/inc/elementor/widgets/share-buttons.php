<?php
/**
 * Share buttons element: pass the current page to the usual networks.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_share_buttons extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-share-buttons';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'دکمه‌های اشتراک‌گذاری', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-share';
	}

	/**
	 * The networks this element can share to.
	 *
	 * @return array<string,array{label:string,icon:string,url:string}>
	 */
	private function networks() {
		return array(
			'telegram' => array(
				'label' => __( 'تلگرام', 'mahan' ),
				'icon'  => 'telegram',
				'url'   => 'https://t.me/share/url?url=%1$s&text=%2$s',
			),
			'whatsapp' => array(
				'label' => __( 'واتساپ', 'mahan' ),
				'icon'  => 'whatsapp',
				'url'   => 'https://api.whatsapp.com/send?text=%2$s%%20%1$s',
			),
			'twitter'  => array(
				'label' => __( 'ایکس', 'mahan' ),
				'icon'  => 'twitter',
				'url'   => 'https://twitter.com/intent/tweet?url=%1$s&text=%2$s',
			),
			'linkedin' => array(
				'label' => __( 'لینکدین', 'mahan' ),
				'icon'  => 'linkedin',
				'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=%1$s',
			),
			'mail'     => array(
				'label' => __( 'ایمیل', 'mahan' ),
				'icon'  => 'mail',
				'url'   => 'mailto:?subject=%2$s&body=%1$s',
			),
		);
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'share_section',
			array(
				'label' => __( 'اشتراک‌گذاری', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'label',
			array(
				'label'   => __( 'برچسب', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'این مطلب را به اشتراک بگذارید', 'mahan' ),
			)
		);

		$choices = array();

		foreach ( $this->networks() as $key => $network ) {
			$choices[ $key ] = $network['label'];
		}

		$this->add_control(
			'networks',
			array(
				'label'    => __( 'شبکه‌ها', 'mahan' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => true,
				'default'  => array( 'telegram', 'whatsapp', 'twitter', 'mail' ),
				'options'  => $choices,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'round',
				'options' => array(
					'round'  => __( 'دایره‌ای', 'mahan' ),
					'square' => __( 'مربعی', 'mahan' ),
					'label'  => __( 'با نام شبکه', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'copy_link',
			array(
				'label'        => __( 'دکمهٔ کپی نشانی', 'mahan' ),
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
		$networks = $this->networks();
		$picked   = array_filter( (array) $settings['networks'] );

		if ( ! $picked && 'yes' !== $settings['copy_link'] ) {
			return;
		}

		$url   = rawurlencode( get_permalink() ? get_permalink() : home_url( '/' ) );
		$title = rawurlencode( wp_strip_all_tags( get_the_title() ) );
		?>
		<div class="mahan-share mahan-share--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php if ( $settings['label'] ) : ?>
				<span class="mahan-share__label"><?php echo esc_html( $settings['label'] ); ?></span>
			<?php endif; ?>

			<div class="mahan-share__links">
				<?php foreach ( $picked as $key ) : ?>
					<?php if ( ! isset( $networks[ $key ] ) ) : ?>
						<?php continue; ?>
					<?php endif; ?>
					<a
						class="mahan-share__link mahan-share__link--<?php echo esc_attr( $key ); ?>"
						href="<?php echo esc_url( sprintf( $networks[ $key ]['url'], $url, $title ) ); ?>"
						target="_blank"
						rel="noopener nofollow"
					>
						<?php $this->render_icon( $networks[ $key ]['icon'], 18 ); ?>
						<span class="mahan-share__name"><?php echo esc_html( $networks[ $key ]['label'] ); ?></span>
					</a>
				<?php endforeach; ?>

				<?php if ( 'yes' === $settings['copy_link'] ) : ?>
					<button
						type="button"
						class="mahan-share__link mahan-share__link--copy"
						data-mahan-copy="<?php echo esc_url( get_permalink() ? get_permalink() : home_url( '/' ) ); ?>"
					>
						<?php $this->render_icon( 'code', 18 ); ?>
						<span class="mahan-share__name"><?php esc_html_e( 'کپی نشانی', 'mahan' ); ?></span>
					</button>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}
