<?php
/**
 * Panel home: what is set up, what is missing, and where to go next.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'is_plugin_active' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$mahan_demo    = Mahan_Demo_Importer::imported_demo();
$mahan_demos   = Mahan_Demo_Library::index();
$mahan_plugins = Mahan_Plugin_Notice::recommended();

$mahan_stats = array(
	array(
		'icon'  => 'layers',
		'value' => count( $mahan_demos ),
		'label' => __( 'قالب آمادهٔ همراه', 'mahan' ),
	),
	array(
		'icon'  => 'sparkles',
		'value' => Mahan_Elements_Catalog::count(),
		'label' => __( 'المان اختصاصی المنتور', 'mahan' ),
	),
	array(
		'icon'  => 'grid',
		'value' => count( Mahan_Schema::keys() ),
		'label' => __( 'گزینهٔ قابل تنظیم', 'mahan' ),
	),
	array(
		'icon'  => 'star',
		'value' => count( Mahan_Options::palettes() ),
		'label' => __( 'پالت رنگی آماده', 'mahan' ),
	),
);
?>

<section class="mahan-panel__hero">
	<div class="mahan-panel__hero-copy">
		<span class="mahan-panel__eyebrow"><?php esc_html_e( 'خوش آمدید', 'mahan' ); ?></span>
		<h1><?php esc_html_e( 'سایت‌تان را در چند دقیقه بسازید', 'mahan' ); ?></h1>
		<p>
			<?php esc_html_e( 'یکی از قالب‌های آماده را نصب کنید تا برگه‌ها، منوها، تصاویر و تنظیمات یک‌جا ساخته شوند؛ بعد رنگ و فونت را به سلیقهٔ خودتان تغییر دهید.', 'mahan' ); ?>
		</p>

		<div class="mahan-panel__hero-actions">
			<a class="mahan-panel__btn" href="<?php echo esc_url( Mahan_Admin::url( 'starter-sites' ) ); ?>">
				<?php mahan_icon_e( 'layers', 18 ); ?>
				<span><?php esc_html_e( 'انتخاب قالب آماده', 'mahan' ); ?></span>
			</a>
			<a class="mahan-panel__btn mahan-panel__btn--outline" href="<?php echo esc_url( Mahan_Admin::url( 'settings' ) ); ?>">
				<?php mahan_icon_e( 'grid', 18 ); ?>
				<span><?php esc_html_e( 'تنظیمات قالب', 'mahan' ); ?></span>
			</a>
		</div>
	</div>

	<div class="mahan-panel__hero-art" aria-hidden="true">
		<span class="mahan-panel__orb mahan-panel__orb--a"></span>
		<span class="mahan-panel__orb mahan-panel__orb--b"></span>
		<div class="mahan-panel__palette-preview">
			<?php foreach ( array( 'color_primary', 'color_secondary', 'color_accent' ) as $mahan_key ) : ?>
				<span style="background: <?php echo esc_attr( mahan_option( $mahan_key ) ); ?>"></span>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="mahan-panel__stats">
	<?php foreach ( $mahan_stats as $mahan_stat ) : ?>
		<div class="mahan-panel__stat">
			<span class="mahan-panel__stat-icon"><?php mahan_icon_e( $mahan_stat['icon'], 22 ); ?></span>
			<strong><?php echo esc_html( mahan_fa_numbers( $mahan_stat['value'] ) ); ?></strong>
			<span class="mahan-panel__stat-label"><?php echo esc_html( $mahan_stat['label'] ); ?></span>
		</div>
	<?php endforeach; ?>
</section>

<?php
$mahan_license = mahan_license();
$mahan_days    = $mahan_license->days_left();
?>
<section class="mahan-panel__license">
	<span class="mahan-panel__license-mark" aria-hidden="true"><?php mahan_icon_e( 'badge', 24 ); ?></span>

	<div class="mahan-panel__license-body">
		<strong><?php esc_html_e( 'لایسنس فعال است', 'mahan' ); ?></strong>
		<span>
			<span dir="ltr"><?php echo esc_html( $mahan_license->masked_key() ); ?></span>
			<?php if ( $mahan_license->expiry() ) : ?>
				·
				<?php
				printf(
					/* translators: 1: expiry date, 2: number of days. */
					esc_html__( 'اعتبار تا %1$s (%2$s روز دیگر)', 'mahan' ),
					esc_html( mahan_fa_numbers( $mahan_license->expiry() ) ),
					esc_html( mahan_fa_numbers( (string) max( 0, (int) $mahan_days ) ) )
				);
				?>
			<?php else : ?>
				· <?php esc_html_e( 'بدون محدودیت زمانی', 'mahan' ); ?>
			<?php endif; ?>
		</span>
	</div>

	<a class="mahan-panel__ghost-btn" href="<?php echo esc_url( Mahan_License_Screen::url() ); ?>">
		<?php mahan_icon_e( 'key', 16 ); ?>
		<span><?php esc_html_e( 'مدیریت لایسنس', 'mahan' ); ?></span>
	</a>
</section>

<div class="mahan-panel__columns">
	<section class="mahan-panel__card">
		<h2><?php esc_html_e( 'وضعیت راه‌اندازی', 'mahan' ); ?></h2>

		<?php
		$mahan_steps = array(
			array(
				'done'  => (bool) $mahan_demo,
				'title' => __( 'نصب یک قالب آماده', 'mahan' ),
				'text'  => $mahan_demo && isset( $mahan_demos[ $mahan_demo ] )
					? sprintf(
						/* translators: %s: starter site name. */
						__( 'قالب «%s» نصب شده است.', 'mahan' ),
						$mahan_demos[ $mahan_demo ]['label']
					)
					: __( 'هنوز قالب آماده‌ای نصب نشده است.', 'mahan' ),
				'url'   => Mahan_Admin::url( 'starter-sites' ),
				'cta'   => $mahan_demo ? __( 'تغییر قالب آماده', 'mahan' ) : __( 'نصب کنید', 'mahan' ),
			),
			array(
				'done'  => (bool) get_theme_mod( 'custom_logo' ),
				'title' => __( 'بارگذاری لوگو', 'mahan' ),
				'text'  => __( 'لوگوی سایت در هدر، فوتر و منوی موبایل استفاده می‌شود.', 'mahan' ),
				'url'   => admin_url( 'customize.php?autofocus[control]=custom_logo' ),
				'cta'   => __( 'انتخاب لوگو', 'mahan' ),
			),
			array(
				'done'  => (bool) has_nav_menu( 'primary' ),
				'title' => __( 'ساخت منوی اصلی', 'mahan' ),
				'text'  => __( 'برای هر آیتم منو می‌توانید آیکون، برچسب و مگا منو تعریف کنید.', 'mahan' ),
				'url'   => admin_url( 'nav-menus.php' ),
				'cta'   => __( 'مدیریت منوها', 'mahan' ),
			),
			array(
				'done'  => (bool) mahan_option( 'contact_phone' ) || (bool) mahan_option( 'contact_email' ),
				'title' => __( 'تکمیل اطلاعات تماس', 'mahan' ),
				'text'  => __( 'شماره و ایمیل در فوتر، ابزارک تماس و المان‌ها نمایش داده می‌شوند.', 'mahan' ),
				'url'   => Mahan_Admin::url( 'settings', array( 'tab' => 'social' ) ),
				'cta'   => __( 'تکمیل کنید', 'mahan' ),
			),
		);

		$mahan_done = count( array_filter( wp_list_pluck( $mahan_steps, 'done' ) ) );
		?>

		<div class="mahan-panel__progress">
			<div class="mahan-panel__progress-track">
				<span style="width: <?php echo esc_attr( ( $mahan_done / count( $mahan_steps ) ) * 100 ); ?>%"></span>
			</div>
			<span class="mahan-panel__progress-label">
				<?php
				printf(
					/* translators: 1: completed steps, 2: total steps. */
					esc_html__( '%1$s از %2$s گام انجام شده', 'mahan' ),
					esc_html( mahan_fa_numbers( $mahan_done ) ),
					esc_html( mahan_fa_numbers( count( $mahan_steps ) ) )
				);
				?>
			</span>
		</div>

		<ul class="mahan-panel__checklist">
			<?php foreach ( $mahan_steps as $mahan_step ) : ?>
				<li class="<?php echo $mahan_step['done'] ? 'is-done' : ''; ?>">
					<span class="mahan-panel__check"><?php mahan_icon_e( $mahan_step['done'] ? 'check' : 'plus', 15 ); ?></span>
					<div>
						<strong><?php echo esc_html( $mahan_step['title'] ); ?></strong>
						<span><?php echo esc_html( $mahan_step['text'] ); ?></span>
					</div>
					<a href="<?php echo esc_url( $mahan_step['url'] ); ?>"><?php echo esc_html( $mahan_step['cta'] ); ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class="mahan-panel__card">
		<h2><?php esc_html_e( 'افزونه‌های پیشنهادی', 'mahan' ); ?></h2>
		<p class="mahan-panel__card-note">
			<?php esc_html_e( 'قالب بدون این افزونه‌ها هم کار می‌کند، اما با آن‌ها کامل می‌شود.', 'mahan' ); ?>
		</p>

		<ul class="mahan-panel__plugins">
			<?php foreach ( $mahan_plugins as $mahan_plugin ) : ?>
				<?php $mahan_active = is_plugin_active( $mahan_plugin['file'] ); ?>
				<li class="<?php echo $mahan_active ? 'is-active' : ''; ?>">
					<span class="mahan-panel__check"><?php mahan_icon_e( $mahan_active ? 'check' : 'plus', 15 ); ?></span>
					<div>
						<strong><?php echo esc_html( $mahan_plugin['name'] ); ?></strong>
						<span><?php echo esc_html( $mahan_active ? __( 'فعال است', 'mahan' ) : $mahan_plugin['why'] ); ?></span>
					</div>
					<?php if ( ! $mahan_active && current_user_can( 'install_plugins' ) ) : ?>
						<a href="<?php echo esc_url( Mahan_Plugin_Notice::install_url( $mahan_plugin['slug'] ) ); ?>">
							<?php esc_html_e( 'نصب', 'mahan' ); ?>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
</div>

<section class="mahan-panel__card">
	<h2><?php esc_html_e( 'میان‌برها', 'mahan' ); ?></h2>

	<div class="mahan-panel__shortcuts">
		<?php
		$mahan_shortcuts = array(
			array( 'sparkles', __( 'رنگ‌ها و پالت', 'mahan' ), __( 'پالت آماده یا رنگ دلخواه', 'mahan' ), Mahan_Admin::url( 'settings', array( 'tab' => 'colors' ) ) ),
			array( 'pen', __( 'تایپوگرافی', 'mahan' ), __( 'فونت، اندازه و ارتفاع خط', 'mahan' ), Mahan_Admin::url( 'settings', array( 'tab' => 'typography' ) ) ),
			array( 'grid', __( 'هدر', 'mahan' ), __( 'پنج چیدمان آماده', 'mahan' ), Mahan_Admin::url( 'settings', array( 'tab' => 'header' ) ) ),
			array( 'list', __( 'فوتر', 'mahan' ), __( 'چهار چیدمان آماده', 'mahan' ), Mahan_Admin::url( 'settings', array( 'tab' => 'footer' ) ) ),
			array( 'book', __( 'بلاگ', 'mahan' ), __( 'آرشیو و تک‌نوشته', 'mahan' ), Mahan_Admin::url( 'settings', array( 'tab' => 'blog' ) ) ),
			array( 'cart', __( 'فروشگاه', 'mahan' ), __( 'کارت محصول و صفحهٔ خرید', 'mahan' ), Mahan_Admin::url( 'settings', array( 'tab' => 'shop' ) ) ),
			array( 'menu', __( 'منوها', 'mahan' ), __( 'آیکون، برچسب و مگا منو', 'mahan' ), admin_url( 'nav-menus.php' ) ),
			array( 'layers', __( 'ابزارک‌ها', 'mahan' ), __( 'ستون کناری و فوتر', 'mahan' ), admin_url( 'widgets.php' ) ),
		);

		foreach ( $mahan_shortcuts as $mahan_shortcut ) :
			?>
			<a class="mahan-panel__shortcut" href="<?php echo esc_url( $mahan_shortcut[3] ); ?>">
				<span class="mahan-panel__shortcut-icon"><?php mahan_icon_e( $mahan_shortcut[0], 20 ); ?></span>
				<strong><?php echo esc_html( $mahan_shortcut[1] ); ?></strong>
				<span><?php echo esc_html( $mahan_shortcut[2] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</section>
