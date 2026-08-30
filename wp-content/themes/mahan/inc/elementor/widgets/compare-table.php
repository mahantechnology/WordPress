<?php
/**
 * Feature comparison table.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_compare_table extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-compare-table';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'جدول مقایسه', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-table';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'table_section',
			array(
				'label' => __( 'جدول', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'columns',
			array(
				'label'       => __( 'عنوان ستون‌ها', 'mahan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'ویژگی, پایه, حرفه‌ای, سازمانی', 'mahan' ),
				'description' => __( 'عنوان‌ها را با ویرگول انگلیسی از هم جدا کنید.', 'mahan' ),
				'label_block' => true,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'cells',
			array(
				'label'       => __( 'سلول‌ها', 'mahan' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => __( 'فضای میزبانی, ۱۰ گیگ, ۱۰۰ گیگ, نامحدود', 'mahan' ),
				'description' => __( 'مقدارها را با ویرگول انگلیسی جدا کنید. برای تیک از «+» و برای ضربدر از «-» استفاده کنید.', 'mahan' ),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'highlight',
			array(
				'label'        => __( 'برجسته‌سازی این سطر', 'mahan' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);

		$this->add_control(
			'rows',
			array(
				'label'       => __( 'سطرها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ cells }}}',
				'default'     => array(
					array( 'cells' => __( 'فضای میزبانی, ۱۰ گیگ, ۱۰۰ گیگ, نامحدود', 'mahan' ) ),
					array( 'cells' => __( 'پشتیبانی تلفنی, -, +, +', 'mahan' ) ),
					array( 'cells' => __( 'گزارش‌های پیشرفته, -, +, +', 'mahan' ) ),
					array( 'cells' => __( 'مدیر اختصاصی, -, -, +', 'mahan' ) ),
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
		$headers  = array_filter( array_map( 'trim', explode( ',', (string) $settings['columns'] ) ) );

		if ( ! $headers || empty( $settings['rows'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-table-wrap">
			<table class="mahan-table">
				<thead>
					<tr>
						<?php foreach ( $headers as $header ) : ?>
							<th scope="col"><?php echo esc_html( $header ); ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $settings['rows'] as $row ) : ?>
						<?php $cells = array_map( 'trim', explode( ',', (string) $row['cells'] ) ); ?>
						<tr<?php echo 'yes' === $row['highlight'] ? ' class="is-highlighted"' : ''; ?>>
							<?php foreach ( $cells as $index => $cell ) : ?>
								<?php $tag = 0 === $index ? 'th' : 'td'; ?>
								<<?php echo esc_html( $tag ); ?><?php echo 0 === $index ? ' scope="row"' : ''; ?>>
									<?php if ( '+' === $cell ) : ?>
										<span class="mahan-table__yes"><?php $this->render_icon( 'check', 18 ); ?></span>
									<?php elseif ( '-' === $cell ) : ?>
										<span class="mahan-table__no"><?php $this->render_icon( 'close', 18 ); ?></span>
									<?php else : ?>
										<?php echo esc_html( $cell ); ?>
									<?php endif; ?>
								</<?php echo esc_html( $tag ); ?>>
							<?php endforeach; ?>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php
	}
}
