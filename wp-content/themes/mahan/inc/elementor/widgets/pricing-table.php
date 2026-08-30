<?php
/**
 * Pricing table element.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_pricing_table extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-pricing-table';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'جدول قیمت‌گذاری', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-price-table';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'plans_section',
			array(
				'label' => __( 'پلن‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'name',
			array(
				'label'   => __( 'نام پلن', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'پایه', 'mahan' ),
			)
		);

		$repeater->add_control(
			'description',
			array(
				'label' => __( 'توضیح کوتاه', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'price',
			array(
				'label'   => __( 'قیمت', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '۴۹۰,۰۰۰',
			)
		);

		$repeater->add_control(
			'currency',
			array(
				'label'   => __( 'واحد پول', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تومان', 'mahan' ),
			)
		);

		$repeater->add_control(
			'period',
			array(
				'label'   => __( 'دوره', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ماهانه', 'mahan' ),
			)
		);

		$repeater->add_control(
			'features',
			array(
				'label'       => __( 'ویژگی‌ها (هر خط یک مورد)', 'mahan' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 6,
				'default'     => __( "۱۰ گیگابایت فضا\nپشتیبانی ایمیلی\nدامنهٔ رایگان", 'mahan' ),
				'description' => __( 'برای مشخص کردن موارد غیرفعال، خط را با علامت منها (-) شروع کنید.', 'mahan' ),
			)
		);

		$repeater->add_control(
			'button_text',
			array(
				'label'   => __( 'متن دکمه', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'انتخاب پلن', 'mahan' ),
			)
		);

		$repeater->add_control(
			'button_link',
			array(
				'label'       => __( 'لینک دکمه', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$repeater->add_control(
			'featured',
			array(
				'label'        => __( 'پلن پیشنهادی', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$repeater->add_control(
			'badge',
			array(
				'label'     => __( 'برچسب', 'mahan' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => __( 'محبوب‌ترین', 'mahan' ),
				'condition' => array( 'featured' => 'yes' ),
			)
		);

		$this->add_control(
			'plans',
			array(
				'label'       => __( 'پلن‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default'     => array(
					array(
						'name'        => __( 'پایه', 'mahan' ),
						'description' => __( 'برای شروع کسب‌وکار', 'mahan' ),
						'price'       => '۴۹۰,۰۰۰',
						'features'    => __( "۱۰ گیگابایت فضا\nپشتیبانی ایمیلی\n-گزارش‌های پیشرفته", 'mahan' ),
					),
					array(
						'name'        => __( 'حرفه‌ای', 'mahan' ),
						'description' => __( 'مناسب تیم‌های در حال رشد', 'mahan' ),
						'price'       => '۹۹۰,۰۰۰',
						'features'    => __( "۱۰۰ گیگابایت فضا\nپشتیبانی تلفنی\nگزارش‌های پیشرفته", 'mahan' ),
						'featured'    => 'yes',
					),
					array(
						'name'        => __( 'سازمانی', 'mahan' ),
						'description' => __( 'برای سازمان‌های بزرگ', 'mahan' ),
						'price'       => '۲,۴۹۰,۰۰۰',
						'features'    => __( "فضای نامحدود\nمدیر اختصاصی\nقرارداد SLA", 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 3 );

		$this->end_controls_section();

		$this->add_card_style_controls( '.mahan-plan' );
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['plans'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-plans">
			<?php foreach ( $settings['plans'] as $plan ) : ?>
				<?php $featured = 'yes' === $plan['featured']; ?>
				<div class="mahan-plan<?php echo $featured ? ' mahan-plan--featured' : ''; ?>">
					<?php if ( $featured && $plan['badge'] ) : ?>
						<span class="mahan-plan__badge"><?php echo esc_html( $plan['badge'] ); ?></span>
					<?php endif; ?>

					<h3 class="mahan-plan__name"><?php echo esc_html( $plan['name'] ); ?></h3>

					<?php if ( $plan['description'] ) : ?>
						<p class="mahan-plan__desc"><?php echo esc_html( $plan['description'] ); ?></p>
					<?php endif; ?>

					<div class="mahan-plan__price">
						<span class="mahan-plan__amount"><?php echo esc_html( $plan['price'] ); ?></span>
						<span class="mahan-plan__currency"><?php echo esc_html( $plan['currency'] ); ?></span>
						<?php if ( $plan['period'] ) : ?>
							<span class="mahan-plan__period">/ <?php echo esc_html( $plan['period'] ); ?></span>
						<?php endif; ?>
					</div>

					<?php
					$lines = array_filter( array_map( 'trim', explode( "\n", (string) $plan['features'] ) ) );

					if ( $lines ) :
						?>
						<ul class="mahan-plan__features">
							<?php foreach ( $lines as $line ) : ?>
								<?php
								$disabled = 0 === strpos( $line, '-' );
								$label    = $disabled ? ltrim( $line, '- ' ) : $line;
								?>
								<li class="mahan-plan__feature<?php echo $disabled ? ' is-disabled' : ''; ?>">
									<?php $this->render_icon( $disabled ? 'close' : 'check', 18 ); ?>
									<span><?php echo esc_html( $label ); ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if ( $plan['button_text'] ) : ?>
						<a class="mahan-btn <?php echo $featured ? 'mahan-btn--primary' : 'mahan-btn--outline'; ?> mahan-btn--block"<?php echo $this->link_attributes( $plan['button_link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
							<?php echo esc_html( $plan['button_text'] ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
