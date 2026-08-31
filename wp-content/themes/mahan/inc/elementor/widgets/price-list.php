<?php
/**
 * Price list element: a menu or tariff sheet, one line per item.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_price_list extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-price-list';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'لیست قیمت و منو', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-price-list';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'rows_section',
			array(
				'label' => __( 'ردیف‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'style',
			array(
				'label'   => __( 'سبک', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'dotted',
				'options' => array(
					'dotted' => __( 'با نقطه‌چین', 'mahan' ),
					'card'   => __( 'کارتی', 'mahan' ),
					'plain'  => __( 'ساده', 'mahan' ),
				),
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image',
			array(
				'label' => __( 'تصویر', 'mahan' ),
				'type'  => Controls_Manager::MEDIA,
			)
		);

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'نام', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'نام آیتم', 'mahan' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => __( 'توضیح', 'mahan' ),
				'type'  => Controls_Manager::TEXTAREA,
				'rows'  => 2,
			)
		);

		$repeater->add_control(
			'price',
			array(
				'label'   => __( 'قیمت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( '۱۲۰,۰۰۰', 'mahan' ),
			)
		);

		$repeater->add_control(
			'unit',
			array(
				'label'   => __( 'واحد', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تومان', 'mahan' ),
			)
		);

		$repeater->add_control(
			'badge',
			array(
				'label' => __( 'برچسب', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title' => __( 'آیتم نخست', 'mahan' ),
						'text'  => __( 'توضیح کوتاه دربارهٔ این آیتم.', 'mahan' ),
						'price' => __( '۱۲۰,۰۰۰', 'mahan' ),
						'unit'  => __( 'تومان', 'mahan' ),
					),
					array(
						'title' => __( 'آیتم دوم', 'mahan' ),
						'text'  => __( 'توضیح کوتاه دربارهٔ این آیتم.', 'mahan' ),
						'price' => __( '۱۸۰,۰۰۰', 'mahan' ),
						'unit'  => __( 'تومان', 'mahan' ),
						'badge' => __( 'پیشنهاد ما', 'mahan' ),
					),
					array(
						'title' => __( 'آیتم سوم', 'mahan' ),
						'text'  => __( 'توضیح کوتاه دربارهٔ این آیتم.', 'mahan' ),
						'price' => __( '۹۵,۰۰۰', 'mahan' ),
						'unit'  => __( 'تومان', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 1 );

		$this->end_controls_section();

		$this->add_text_style_controls( '.mahan-pricelist__title', '.mahan-pricelist__text' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['rows'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-pricelist mahan-pricelist--<?php echo esc_attr( $settings['style'] ); ?>">
			<?php foreach ( $settings['rows'] as $row ) : ?>
				<?php $image = $this->image_url( $row['image'] ); ?>
				<div class="mahan-pricelist__row">
					<?php if ( $image ) : ?>
						<span class="mahan-pricelist__thumb">
							<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $row['title'] ); ?>" loading="lazy" />
						</span>
					<?php endif; ?>

					<div class="mahan-pricelist__body">
						<div class="mahan-pricelist__head">
							<h3 class="mahan-pricelist__title">
								<?php echo esc_html( $row['title'] ); ?>

								<?php if ( $row['badge'] ) : ?>
									<span class="mahan-pricelist__badge"><?php echo esc_html( $row['badge'] ); ?></span>
								<?php endif; ?>
							</h3>

							<span class="mahan-pricelist__dots" aria-hidden="true"></span>

							<span class="mahan-pricelist__price">
								<?php echo esc_html( $row['price'] ); ?>
								<?php if ( $row['unit'] ) : ?>
									<small><?php echo esc_html( $row['unit'] ); ?></small>
								<?php endif; ?>
							</span>
						</div>

						<?php if ( $row['text'] ) : ?>
							<p class="mahan-pricelist__text"><?php echo esc_html( $row['text'] ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
