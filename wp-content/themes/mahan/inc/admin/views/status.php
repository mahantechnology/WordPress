<?php
/**
 * A short system report, so a support question can be answered with facts.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

global $wp_version;

$mahan_theme = wp_get_theme( 'mahan' );

/**
 * A pass / warn / fail row.
 *
 * @param bool $ok Whether the check passes.
 * @return string
 */
$mahan_state = static function ( $ok ) {
	return $ok ? 'ok' : 'warn';
};

$mahan_php_ok    = version_compare( PHP_VERSION, '7.4', '>=' );
$mahan_wp_ok     = version_compare( $wp_version, '6.0', '>=' );
$mahan_memory    = (int) ini_get( 'memory_limit' );
$mahan_memory_ok = $mahan_memory <= 0 || $mahan_memory >= 128;
$mahan_uploads   = wp_upload_dir();
$mahan_writable  = empty( $mahan_uploads['error'] ) && wp_is_writable( $mahan_uploads['basedir'] );

$mahan_rows = array(
	array(
		'label' => __( 'نسخهٔ قالب', 'mahan' ),
		'value' => mahan_fa_numbers( MAHAN_VERSION ),
		'state' => 'ok',
	),
	array(
		'label' => __( 'نسخهٔ وردپرس', 'mahan' ),
		'value' => mahan_fa_numbers( $wp_version ),
		'state' => $mahan_state( $mahan_wp_ok ),
		'note'  => $mahan_wp_ok ? '' : __( 'قالب به وردپرس ۶.۰ یا بالاتر نیاز دارد.', 'mahan' ),
	),
	array(
		'label' => __( 'نسخهٔ PHP', 'mahan' ),
		'value' => mahan_fa_numbers( PHP_VERSION ),
		'state' => $mahan_state( $mahan_php_ok ),
		'note'  => $mahan_php_ok ? '' : __( 'قالب به PHP نسخهٔ ۷.۴ یا بالاتر نیاز دارد.', 'mahan' ),
	),
	array(
		'label' => __( 'محدودیت حافظه', 'mahan' ),
		// PHP reports no limit as -1, which reads as "۱-" once the digits are
		// converted, so it gets a word instead.
		'value' => $mahan_memory <= 0
			? __( 'نامحدود', 'mahan' )
			: mahan_fa_numbers( ini_get( 'memory_limit' ) ),
		'state' => $mahan_state( $mahan_memory_ok ),
		'note'  => $mahan_memory_ok ? '' : __( 'برای درون‌ریزی قالب آماده، ۱۲۸ مگابایت یا بیشتر پیشنهاد می‌شود.', 'mahan' ),
	),
	array(
		'label' => __( 'حداکثر زمان اجرا', 'mahan' ),
		'value' => (int) ini_get( 'max_execution_time' ) <= 0
			? __( 'نامحدود', 'mahan' )
			: mahan_fa_numbers( ini_get( 'max_execution_time' ) ) . ' ' . __( 'ثانیه', 'mahan' ),
		'state' => 'ok',
	),
	array(
		'label' => __( 'حداکثر حجم بارگذاری', 'mahan' ),
		'value' => mahan_fa_numbers( size_format( wp_max_upload_size() ) ),
		'state' => 'ok',
	),
	array(
		'label' => __( 'پوشهٔ بارگذاری قابل نوشتن', 'mahan' ),
		'value' => $mahan_writable ? __( 'بله', 'mahan' ) : __( 'خیر', 'mahan' ),
		'state' => $mahan_state( $mahan_writable ),
		'note'  => $mahan_writable ? '' : __( 'بدون دسترسی نوشتن، تصاویر قالب آماده درون‌ریزی نمی‌شوند.', 'mahan' ),
	),
	array(
		'label' => __( 'راست‌چین فعال', 'mahan' ),
		'value' => is_rtl() ? __( 'بله', 'mahan' ) : __( 'خیر', 'mahan' ),
		'state' => $mahan_state( is_rtl() ),
	),
	array(
		'label' => __( 'المنتور', 'mahan' ),
		'value' => mahan_has_elementor()
			? ( defined( 'ELEMENTOR_VERSION' ) ? mahan_fa_numbers( ELEMENTOR_VERSION ) : __( 'فعال', 'mahan' ) )
			: __( 'نصب نشده', 'mahan' ),
		'state' => $mahan_state( mahan_has_elementor() ),
	),
	array(
		'label' => __( 'ووکامرس', 'mahan' ),
		'value' => mahan_has_woocommerce()
			? ( defined( 'WC_VERSION' ) ? mahan_fa_numbers( WC_VERSION ) : __( 'فعال', 'mahan' ) )
			: __( 'نصب نشده', 'mahan' ),
		'state' => $mahan_state( mahan_has_woocommerce() ),
	),
	array(
		'label' => __( 'قالب فرزند', 'mahan' ),
		'value' => is_child_theme() ? $mahan_theme->get( 'Name' ) : __( 'استفاده نمی‌شود', 'mahan' ),
		'state' => 'ok',
	),
	array(
		'label' => __( 'تصاویر درون‌ریزی‌شده', 'mahan' ),
		'value' => mahan_fa_numbers( count( Mahan_Demo_Media::existing() ) ),
		'state' => 'ok',
	),
);
?>

<section class="mahan-panel__section-head">
	<div>
		<h1><?php esc_html_e( 'وضعیت سیستم', 'mahan' ); ?></h1>
		<p><?php esc_html_e( 'اگر هنگام پشتیبانی از شما گزارش خواستند، این جدول را کپی کنید.', 'mahan' ); ?></p>
	</div>

	<button type="button" class="mahan-panel__ghost-btn" data-mahan-copy-report>
		<?php mahan_icon_e( 'external', 16 ); ?>
		<span><?php esc_html_e( 'کپی گزارش', 'mahan' ); ?></span>
	</button>
</section>

<section class="mahan-panel__card">
	<table class="mahan-panel__table" data-mahan-report>
		<tbody>
			<?php foreach ( $mahan_rows as $mahan_row ) : ?>
				<tr>
					<th scope="row"><?php echo esc_html( $mahan_row['label'] ); ?></th>
					<td>
						<span class="mahan-panel__pill mahan-panel__pill--<?php echo esc_attr( $mahan_row['state'] ); ?>">
							<?php echo esc_html( $mahan_row['value'] ); ?>
						</span>
						<?php if ( ! empty( $mahan_row['note'] ) ) : ?>
							<em><?php echo esc_html( $mahan_row['note'] ); ?></em>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</section>

<section class="mahan-panel__card">
	<h2><?php esc_html_e( 'آنچه قالب ساخته است', 'mahan' ); ?></h2>

	<div class="mahan-panel__counts">
		<?php
		$mahan_counts = array(
			array( 'book', __( 'نوشته', 'mahan' ), wp_count_posts( 'post' )->publish, admin_url( 'edit.php' ) ),
			array( 'list', __( 'برگه', 'mahan' ), wp_count_posts( 'page' )->publish, admin_url( 'edit.php?post_type=page' ) ),
			array( 'layers', __( 'خدمت', 'mahan' ), wp_count_posts( 'mahan_service' )->publish, admin_url( 'edit.php?post_type=mahan_service' ) ),
			array( 'camera', __( 'نمونه‌کار', 'mahan' ), wp_count_posts( 'mahan_portfolio' )->publish, admin_url( 'edit.php?post_type=mahan_portfolio' ) ),
			array( 'user', __( 'عضو تیم', 'mahan' ), wp_count_posts( 'mahan_team' )->publish, admin_url( 'edit.php?post_type=mahan_team' ) ),
			array( 'quote', __( 'نظر مشتری', 'mahan' ), wp_count_posts( 'mahan_testimonial' )->publish, admin_url( 'edit.php?post_type=mahan_testimonial' ) ),
		);

		if ( mahan_has_woocommerce() ) {
			$mahan_counts[] = array( 'cart', __( 'محصول', 'mahan' ), wp_count_posts( 'product' )->publish, admin_url( 'edit.php?post_type=product' ) );
		}

		foreach ( $mahan_counts as $mahan_count ) :
			?>
			<a class="mahan-panel__count" href="<?php echo esc_url( $mahan_count[3] ); ?>">
				<span class="mahan-panel__count-icon"><?php mahan_icon_e( $mahan_count[0], 18 ); ?></span>
				<strong><?php echo esc_html( mahan_fa_numbers( (int) $mahan_count[2] ) ); ?></strong>
				<span><?php echo esc_html( $mahan_count[1] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
