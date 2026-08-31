<?php
/**
 * Download list element: files offered for download, with type and size.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

use Elementor\Controls_Manager;
use Elementor\Repeater;

class Mahan_Widget_download_list extends Mahan_Widget_Base {

	/**
	 * Element slug.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'mahan-download-list';
	}

	/**
	 * Panel title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'فایل‌های دانلود', 'mahan' );
	}

	/**
	 * Panel icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-download-button';
	}

	/**
	 * Registers the controls.
	 */
	protected function register_controls() {
		$this->add_heading_controls();

		$this->start_controls_section(
			'files_section',
			array(
				'label' => __( 'فایل‌ها', 'mahan' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title',
			array(
				'label'   => __( 'نام فایل', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => __( 'کاتالوگ محصولات', 'mahan' ),
			)
		);

		$repeater->add_control(
			'text',
			array(
				'label' => __( 'توضیح', 'mahan' ),
				'type'  => Controls_Manager::TEXT,
			)
		);

		$repeater->add_control(
			'file',
			array(
				'label'      => __( 'فایل', 'mahan' ),
				'type'       => Controls_Manager::MEDIA,
				'media_type' => array( 'application', 'image', 'video' ),
			)
		);

		$repeater->add_control(
			'kind',
			array(
				'label'   => __( 'نوع', 'mahan' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'PDF',
			)
		);

		$repeater->add_control(
			'size',
			array(
				'label'       => __( 'حجم', 'mahan' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( '۲٫۴ مگابایت', 'mahan' ),
			)
		);

		$this->add_control(
			'files',
			array(
				'label'       => __( 'فایل‌ها', 'mahan' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
				'default'     => array(
					array(
						'title' => __( 'کاتالوگ محصولات', 'mahan' ),
						'kind'  => 'PDF',
						'size'  => __( '۲٫۴ مگابایت', 'mahan' ),
					),
					array(
						'title' => __( 'راهنمای نصب', 'mahan' ),
						'kind'  => 'PDF',
						'size'  => __( '۸۰۰ کیلوبایت', 'mahan' ),
					),
				),
			)
		);

		$this->add_columns_control( 2 );

		$this->end_controls_section();
	}

	/**
	 * Prints the element.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		if ( empty( $settings['files'] ) ) {
			return;
		}

		$this->render_heading( $settings );
		?>
		<div class="mahan-grid mahan-downloads">
			<?php foreach ( $settings['files'] as $file ) : ?>
				<?php $url = ! empty( $file['file']['url'] ) ? $file['file']['url'] : ''; ?>
				<a class="mahan-download" href="<?php echo esc_url( $url ? $url : '#' ); ?>" <?php echo $url ? 'download' : ''; ?>>
					<span class="mahan-download__icon">
						<?php $this->render_icon( 'download', 22 ); ?>
					</span>

					<span class="mahan-download__body">
						<strong class="mahan-download__title"><?php echo esc_html( $file['title'] ); ?></strong>

						<?php if ( $file['text'] ) : ?>
							<span class="mahan-download__text"><?php echo esc_html( $file['text'] ); ?></span>
						<?php endif; ?>

						<span class="mahan-download__meta">
							<?php if ( $file['kind'] ) : ?>
								<span class="mahan-download__kind"><?php echo esc_html( $file['kind'] ); ?></span>
							<?php endif; ?>

							<?php if ( $file['size'] ) : ?>
								<span><?php echo esc_html( $file['size'] ); ?></span>
							<?php endif; ?>
						</span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
	}
}
