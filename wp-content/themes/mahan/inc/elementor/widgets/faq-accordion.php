<?php
/**
 * FAQ accordion with FAQPage structured data.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_faq_accordion extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-faq-accordion';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'پرسش‌های متداول', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-help-o';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'faq_section',
			array(
				'label' => __( 'پرسش‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'question',
			array(
				'label'   => __( 'پرسش', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'آیا قالب با ووکامرس سازگار است؟', 'mahan' ),
			)
		);

		$repeater->add_control(
			'answer',
			array(
				'label'   => __( 'پاسخ', 'mahan' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => __( 'بله، تمام صفحه‌های فروشگاه، سبد خرید و تسویه‌حساب به‌صورت اختصاصی برای این قالب طراحی شده‌اند.', 'mahan' ),
			)
		);

		$repeater->add_control(
			'open',
			array(
				'label'        => __( 'باز بودن به‌صورت پیش‌فرض', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'faqs',
			array(
				'label'       => __( 'آیتم‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ question }}}',
				'default'     => array(
					array(
						'question' => __( 'آیا قالب با ووکامرس سازگار است؟', 'mahan' ),
						'answer'   => __( 'بله، تمام صفحه‌های فروشگاه به‌صورت اختصاصی طراحی شده‌اند.', 'mahan' ),
						'open'     => 'yes',
					),
					array(
						'question' => __( 'آیا می‌توانم قالب‌های آماده را نصب کنم؟', 'mahan' ),
						'answer'   => __( 'بله، از مسیر «نمایش ← راه‌انداز ماهان» می‌توانید یکی از قالب‌های آماده را با یک کلیک نصب کنید.', 'mahan' ),
					),
					array(
						'question' => __( 'آیا قالب سرعت سایت را کم می‌کند؟', 'mahan' ),
						'answer'   => __( 'خیر. ماهان بدون جی‌کوئری نوشته شده و فایل‌های اضافی را تنها در صفحه‌های لازم بارگذاری می‌کند.', 'mahan' ),
					),
				),
			)
		);

		$this->add_control(
			'layout',
			array(
				'label'   => __( 'چیدمان', 'mahan' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'stack',
				'options' => array(
					'stack' => __( 'یک ستونه', 'mahan' ),
					'two'   => __( 'دو ستونه', 'mahan' ),
				),
			)
		);

		$this->add_control(
			'schema',
			array(
				'label'        => __( 'افزودن دادهٔ ساختاریافته FAQ', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'yes',
				'return_value' => 'yes',
				'description'  => __( 'به گوگل کمک می‌کند پرسش‌ها را در نتایج جستجو نمایش دهد.', 'mahan' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'faq_style',
			array(
				'label' => __( 'ظاهر', 'mahan' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'question_color',
			array(
				'label'     => __( 'رنگ پرسش', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-faq__question' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'answer_color',
			array(
				'label'     => __( 'رنگ پاسخ', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-faq__answer' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'item_bg',
			array(
				'label'     => __( 'پس‌زمینهٔ آیتم', 'mahan' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .mahan-faq__item' => 'background-color: {{VALUE}};',
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

		if ( empty( $settings['faqs'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-faq mahan-faq--<?php echo esc_attr( $settings['layout'] ); ?>">
			<?php foreach ( $settings['faqs'] as $faq ) : ?>
				<details class="mahan-faq__item"<?php echo 'yes' === $faq['open'] ? ' open' : ''; ?>>
					<summary class="mahan-faq__question">
						<span><?php echo esc_html( $faq['question'] ); ?></span>
						<span class="mahan-faq__marker"><?php $this->render_icon( 'plus', 20 ); ?></span>
					</summary>
					<div class="mahan-faq__answer"><?php echo wp_kses_post( $faq['answer'] ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
		<?php

		if ( 'yes' === $settings['schema'] ) {
			$this->render_schema( $settings['faqs'] );
		}
	}

	/**
	 * Prints FAQPage structured data for the questions.
	 *
	 * @param array $faqs Repeater rows.
	 */
	private function render_schema( array $faqs ) {
		$entities = array();

		foreach ( $faqs as $faq ) {
			if ( empty( $faq['question'] ) ) {
				continue;
			}

			$entities[] = array(
				'@type'          => 'Question',
				'name'           => wp_strip_all_tags( $faq['question'] ),
				'acceptedAnswer' => array(
					'@type' => 'Answer',
					'text'  => wp_strip_all_tags( $faq['answer'] ),
				),
			);
		}

		if ( ! $entities ) {
			return;
		}

		printf(
			'<script type="application/ld+json">%s</script>',
			wp_json_encode( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded.
				array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $entities,
				),
				JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
			)
		);
	}
}
