<?php
/**
 * Job list element: the vacancies a careers page needs.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_job_list extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-job-list';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'فرصت‌های شغلی', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'jobs_section',
			array(
				'label' => __( 'موقعیت‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'عنوان شغلی', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'توسعه‌دهندهٔ وردپرس', 'mahan' ),
			)
		);

		$repeater->add_control(
			'department',
			array(
				'label'   => __( 'واحد', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'فنی', 'mahan' ),
			)
		);

		$repeater->add_control(
			'location',
			array(
				'label'   => __( 'محل کار', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تهران', 'mahan' ),
			)
		);

		$repeater->add_control(
			'type',
			array(
				'label'   => __( 'نوع همکاری', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'تمام‌وقت', 'mahan' ),
			)
		);

		$repeater->add_control(
			'link',
			array(
				'label'       => __( 'لینک آگهی', 'mahan' ),
				'type'        => Controls_Manager::URL,
				'placeholder' => 'https://',
			)
		);

		$this->add_control(
			'jobs',
			array(
				'label'       => __( 'موقعیت‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title'      => __( 'توسعه‌دهندهٔ وردپرس', 'mahan' ),
						'department' => __( 'فنی', 'mahan' ),
						'location'   => __( 'تهران', 'mahan' ),
						'type'       => __( 'تمام‌وقت', 'mahan' ),
					),
					array(
						'title'      => __( 'طراح رابط کاربری', 'mahan' ),
						'department' => __( 'محصول', 'mahan' ),
						'location'   => __( 'دورکاری', 'mahan' ),
						'type'       => __( 'پاره‌وقت', 'mahan' ),
					),
				),
			)
		);

		$this->add_control(
			'cta_label',
			array(
				'label'   => __( 'متن دکمه', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'ارسال رزومه', 'mahan' ),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['jobs'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-jobs">
			<?php foreach ( $settings['jobs'] as $job ) : ?>
				<div class="mahan-job">
					<div class="mahan-job__main">
						<h3 class="mahan-job__title"><?php echo esc_html( $job['title'] ); ?></h3>

						<ul class="mahan-job__tags">
							<?php foreach ( array( 'department' => 'grid', 'location' => 'map-pin', 'type' => 'clock' ) as $field => $icon ) : ?>
								<?php if ( $job[ $field ] ) : ?>
									<li>
										<?php $this->render_icon( $icon, 15 ); ?>
										<?php echo esc_html( $job[ $field ] ); ?>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>

					<?php if ( ! empty( $job['link']['url'] ) ) : ?>
						<a class="mahan-job__cta"<?php echo $this->link_attributes( $job['link'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in helper. ?>>
							<?php echo esc_html( $settings['cta_label'] ); ?>
							<?php $this->render_icon( 'arrow-left', 16 ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
