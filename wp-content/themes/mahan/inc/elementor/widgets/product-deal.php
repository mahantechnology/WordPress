<?php
/**
 * Deal of the day: one product beside a countdown.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;

class Mahan_Widget_product_deal extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-product-deal';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'پیشنهاد شگفت‌انگیز', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-countdown';
	}

	/**
	 * Panel categories.
	 *
	 * @return string[]
	 */
	public function get_categories() {
		return array( 'mahan-woo' );
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'deal_section',
			array(
				'label' => __( 'تنظیمات', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'عنوان', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'پیشنهاد شگفت‌انگیز', 'mahan' ),
			)
		);

		$this->add_control(
			'product_id',
			array(
				'label'       => __( 'شناسهٔ محصول', 'mahan' ),
				'type'        => Controls_Manager::NUMBER,
				'description' => __( 'خالی بگذارید تا یکی از محصولات حراج به‌صورت خودکار انتخاب شود.', 'mahan' ),
			)
		);

		$this->add_control(
			'due_date',
			array(
				'label'   => __( 'پایان پیشنهاد', 'mahan' ),
				'type'    => Controls_Manager::DATE_TIME,
				'default' => gmdate( 'Y-m-d H:i', time() + DAY_IN_SECONDS ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		if ( ! mahan_has_woocommerce() ) {
			return;
		}

		$settings   = $this->get_settings_for_display();
		$product_id = (int) $settings['product_id'];

		if ( ! $product_id ) {
			$on_sale = wc_get_product_ids_on_sale();

			if ( ! $on_sale ) {
				echo '<p class="mahan-empty__text">' . esc_html__( 'در حال حاضر محصول حراجی وجود ندارد.', 'mahan' ) . '</p>';
				return;
			}

			$product_id = (int) reset( $on_sale );
		}

		$product = wc_get_product( $product_id );

		if ( ! $product || 'publish' !== get_post_status( $product_id ) ) {
			return;
		}

		$due   = $settings['due_date'] ? strtotime( $settings['due_date'] ) : 0;
		$units = array(
			'days'    => __( 'روز', 'mahan' ),
			'hours'   => __( 'ساعت', 'mahan' ),
			'minutes' => __( 'دقیقه', 'mahan' ),
			'seconds' => __( 'ثانیه', 'mahan' ),
		);
		?>
		<div class="mahan-deal">
			<div class="mahan-deal__aside">
				<h2 class="mahan-deal__title"><?php echo esc_html( $settings['title'] ); ?></h2>
				<?php if ( $due ) : ?>
					<div class="mahan-countdown mahan-countdown--boxes" data-mahan-countdown="<?php echo esc_attr( $due ); ?>" data-expired="<?php esc_attr_e( 'پایان یافت', 'mahan' ); ?>">
						<div class="mahan-countdown__units">
							<?php foreach ( $units as $key => $label ) : ?>
								<div class="mahan-countdown__unit">
									<span class="mahan-countdown__value" data-unit="<?php echo esc_attr( $key ); ?>">۰۰</span>
									<span class="mahan-countdown__label"><?php echo esc_html( $label ); ?></span>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				<a class="mahan-btn mahan-btn--contrast" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
					<?php esc_html_e( 'مشاهده و خرید', 'mahan' ); ?>
				</a>
			</div>

			<div class="mahan-deal__product">
				<a class="mahan-deal__media" href="<?php echo esc_url( get_permalink( $product_id ) ); ?>">
					<?php echo $product->get_image( 'mahan-square' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup. ?>
				</a>
				<div class="mahan-deal__body">
					<h3 class="mahan-deal__name">
						<a href="<?php echo esc_url( get_permalink( $product_id ) ); ?>"><?php echo esc_html( $product->get_name() ); ?></a>
					</h3>
					<div class="mahan-deal__price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup. ?></div>
					<?php if ( $product->get_short_description() ) : ?>
						<p class="mahan-deal__text"><?php echo esc_html( wp_trim_words( wp_strip_all_tags( $product->get_short_description() ), 22, '…' ) ); ?></p>
					<?php endif; ?>
					<?php if ( $product->managing_stock() ) : ?>
						<?php
						$stock = (int) $product->get_stock_quantity();
						$sold  = (int) $product->get_total_sales();
						$total = max( 1, $stock + $sold );
						?>
						<div class="mahan-stock-bar">
							<span class="mahan-stock-bar__fill" style="width:<?php echo esc_attr( min( 100, ( $sold / $total ) * 100 ) ); ?>%"></span>
							<span class="mahan-stock-bar__label">
								<?php
								printf(
									/* translators: %s: remaining stock. */
									esc_html__( '%s عدد باقی مانده', 'mahan' ),
									esc_html( mahan_fa_numbers( $stock ) )
								);
								?>
							</span>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}
