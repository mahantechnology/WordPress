<?php
/**
 * The licence screen: a full-page takeover that asks for a key, and turns
 * into a congratulations page once the key checks out.
 *
 * It is also the gate. While the theme is unlicensed every other screen in
 * the Mahan panel bounces here, so activation is the first thing a new
 * customer sees and the only thing they can do in the panel.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_License_Screen {

	/**
	 * Page slug.
	 */
	const SLUG = 'mahan-license';

	/**
	 * Hooks the screen.
	 */
	public function __construct() {
		// After Mahan_Admin::register_menu() at 99, or the parent menu this page
		// hangs off does not exist yet and wp-admin refuses the screen.
		add_action( 'admin_menu', array( $this, 'register' ), 100 );
		add_action( 'admin_init', array( $this, 'gate' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'admin_notices', array( $this, 'notice' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 81 );
		add_filter( 'admin_body_class', array( $this, 'body_class' ) );
	}

	/**
	 * The screen's admin URL.
	 *
	 * @return string
	 */
	public static function url() {
		return add_query_arg( 'page', self::SLUG, admin_url( 'admin.php' ) );
	}

	/**
	 * Registers the page under the Mahan panel.
	 */
	public function register() {
		add_submenu_page(
			Mahan_Admin::SLUG,
			__( 'لایسنس قالب ماهان', 'mahan' ),
			mahan_license()->is_active() ? __( 'لایسنس', 'mahan' ) : __( 'فعال‌سازی لایسنس', 'mahan' ),
			Mahan_Admin::CAPABILITY,
			self::SLUG,
			array( $this, 'render' )
		);
	}

	/**
	 * Sends anyone browsing the locked panel to this screen.
	 */
	public function gate() {
		if ( wp_doing_ajax() || mahan_license()->is_active() ) {
			return;
		}

		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

		if ( '' === $page || self::SLUG === $page ) {
			return;
		}

		// Only the theme's own screens are gated; the rest of wp-admin is not ours to block.
		if ( Mahan_Admin::SLUG !== $page && 0 !== strpos( $page, Mahan_Admin::SLUG . '-' ) ) {
			return;
		}

		if ( ! current_user_can( Mahan_Admin::CAPABILITY ) ) {
			return;
		}

		wp_safe_redirect( self::url() );
		exit;
	}

	/**
	 * Whether the current request is this screen.
	 *
	 * @return bool
	 */
	private function is_screen() {
		return isset( $_GET['page'] ) && self::SLUG === sanitize_key( wp_unslash( $_GET['page'] ) );
	}

	/**
	 * Loads the screen's styles and script.
	 */
	public function assets() {
		if ( ! $this->is_screen() ) {
			return;
		}

		wp_enqueue_style( 'mahan-fonts', MAHAN_URI . 'assets/css/fonts.css', array(), MAHAN_VERSION );
		wp_enqueue_style( 'mahan-license', MAHAN_URI . 'assets/css/license.css', array( 'mahan-fonts' ), MAHAN_VERSION );
		wp_enqueue_script( 'mahan-license', MAHAN_URI . 'assets/js/license.js', array(), MAHAN_VERSION, true );

		wp_localize_script(
			'mahan-license',
			'mahanLicense',
			array(
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'mahan_license' ),
				'panelUrl' => Mahan_Admin::url(),
				'i18n'     => array(
					'checking'  => __( 'در حال بررسی لایسنس…', 'mahan' ),
					'empty'     => __( 'کلید لایسنس را وارد کنید.', 'mahan' ),
					'malformed' => __( 'کلید باید به شکل MT-XXXX-XXXX-XXXX-XXXX-XXXX باشد.', 'mahan' ),
					'network'   => __( 'ارتباط با سرور برقرار نشد. اتصال اینترنت سایت را بررسی کنید.', 'mahan' ),
					'confirm'   => __( 'با حذف لایسنس، امکانات قالب دوباره قفل می‌شود. مطمئن هستید؟', 'mahan' ),
					'removing'  => __( 'در حال حذف…', 'mahan' ),
				),
			)
		);
	}

	/**
	 * Marks the body so the screen can take the page over.
	 *
	 * @param string $classes Existing classes.
	 * @return string
	 */
	public function body_class( $classes ) {
		return $this->is_screen() ? $classes . ' mahan-license-page' : $classes;
	}

	/**
	 * Nags on other admin screens while the theme is locked or lapsing.
	 */
	public function notice() {
		if ( ! current_user_can( Mahan_Admin::CAPABILITY ) || $this->is_screen() ) {
			return;
		}

		$license   = mahan_license();
		$condition = $license->condition();

		if ( 'active' === $condition && ! $license->expires_soon() ) {
			return;
		}

		$messages = array(
			'empty'       => __( 'قالب ماهان هنوز فعال نشده است. برای باز شدن قالب‌های آماده، المان‌های المنتور و تنظیمات قالب، لایسنس خود را وارد کنید.', 'mahan' ),
			'invalid'     => __( 'لایسنس قالب ماهان معتبر شناخته نشد و امکانات قالب قفل شده است.', 'mahan' ),
			'expired'     => __( 'اعتبار لایسنس قالب ماهان به پایان رسیده است. برای ادامهٔ استفاده، آن را تمدید کنید.', 'mahan' ),
			'unbound'     => __( 'نشانی سایت تغییر کرده است. برای فعال ماندن قالب ماهان، لایسنس را دوباره بررسی کنید.', 'mahan' ),
			'unreachable' => __( 'چند روز است سرور لایسنس در دسترس نبوده و امکانات قالب ماهان موقتاً قفل شده است.', 'mahan' ),
		);

		if ( 'active' === $condition ) {
			$days = $license->days_left();

			printf(
				'<div class="notice notice-warning"><p><strong>%1$s</strong> %2$s <a href="%3$s">%4$s</a></p></div>',
				esc_html__( 'قالب ماهان:', 'mahan' ),
				esc_html(
					sprintf(
						/* translators: %s: number of days. */
						_n( 'اعتبار لایسنس شما %s روز دیگر تمام می‌شود.', 'اعتبار لایسنس شما %s روز دیگر تمام می‌شود.', (int) $days, 'mahan' ),
						mahan_fa_numbers( (string) $days )
					)
				),
				esc_url( self::url() ),
				esc_html__( 'مشاهدهٔ لایسنس', 'mahan' )
			);

			return;
		}

		printf(
			'<div class="notice notice-error"><p><strong>%1$s</strong> %2$s <a class="button button-primary" href="%3$s">%4$s</a></p></div>',
			esc_html__( 'قالب ماهان:', 'mahan' ),
			esc_html( isset( $messages[ $condition ] ) ? $messages[ $condition ] : $messages['empty'] ),
			esc_url( self::url() ),
			esc_html__( 'فعال‌سازی لایسنس', 'mahan' )
		);
	}

	/**
	 * Shows the licence state in the toolbar.
	 *
	 * @param WP_Admin_Bar $bar Toolbar instance.
	 */
	public function admin_bar( $bar ) {
		if ( ! current_user_can( Mahan_Admin::CAPABILITY ) ) {
			return;
		}

		$active = mahan_license()->is_active();

		$bar->add_node(
			array(
				'parent' => 'mahan-panel',
				'id'     => 'mahan-license',
				'title'  => $active ? __( 'لایسنس: فعال', 'mahan' ) : __( 'لایسنس: فعال نشده', 'mahan' ),
				'href'   => self::url(),
			)
		);
	}

	/* --------------------------------------------------------------------
	 * Rendering
	 * ----------------------------------------------------------------- */

	/**
	 * Prints the screen.
	 */
	public function render() {
		if ( ! current_user_can( Mahan_Admin::CAPABILITY ) ) {
			wp_die( esc_html__( 'دسترسی لازم را ندارید.', 'mahan' ) );
		}

		$license = mahan_license();
		$active  = $license->is_active();
		?>
		<div class="mahan-lic<?php echo $active ? ' is-active' : ''; ?>" dir="rtl" data-mahan-license>
			<div class="mahan-lic__aurora" aria-hidden="true">
				<span></span><span></span><span></span>
			</div>

			<div class="mahan-lic__inner">
				<?php if ( $active ) : ?>
					<?php $this->render_success( $license ); ?>
				<?php else : ?>
					<?php $this->render_form( $license ); ?>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}

	/**
	 * The activation form.
	 *
	 * @param Mahan_License $license Licence client.
	 */
	private function render_form( Mahan_License $license ) {
		$condition = $license->condition();

		$headline = array(
			'empty'       => __( 'قالب ماهان را فعال کنید', 'mahan' ),
			'invalid'     => __( 'لایسنس تأیید نشد', 'mahan' ),
			'expired'     => __( 'اعتبار لایسنس تمام شده است', 'mahan' ),
			'unbound'     => __( 'نشانی سایت تغییر کرده است', 'mahan' ),
			'unreachable' => __( 'لایسنس بررسی نشد', 'mahan' ),
		);

		$lede = array(
			'empty'       => __( 'کلید لایسنسی که هنگام خرید دریافت کرده‌اید را وارد کنید تا همهٔ امکانات قالب باز شود. این کار فقط یک بار انجام می‌شود.', 'mahan' ),
			'invalid'     => __( 'کلید واردشده برای این محصول معتبر نبود. آن را دوباره از پنل کاربری خود کپی کنید و امتحان کنید.', 'mahan' ),
			'expired'     => __( 'برای باز شدن دوبارهٔ امکانات قالب، لایسنس را تمدید کنید و کلید تازه را وارد کنید.', 'mahan' ),
			'unbound'     => __( 'لایسنس به نشانی قبلی سایت گره خورده بود. کلید را دوباره وارد کنید تا روی نشانی تازه ثبت شود.', 'mahan' ),
			'unreachable' => __( 'چند روز است پاسخی از سرور لایسنس نگرفته‌ایم. اتصال سایت را بررسی کنید و دوباره تلاش کنید.', 'mahan' ),
		);

		$benefits = array(
			array( 'layers', __( 'قالب‌های آمادهٔ کامل', 'mahan' ), __( 'با برگه‌ها، منوها، ابزارک‌ها و تصاویر، تنها با یک کلیک.', 'mahan' ) ),
			array( 'sparkles', __( 'المان‌های اختصاصی المنتور', 'mahan' ), __( 'اسلایدر، محصول، نمونه‌کار، شمارنده، تایم‌لاین و ده‌ها المان دیگر.', 'mahan' ) ),
			array( 'grid', __( 'پنل تنظیمات کامل', 'mahan' ), __( 'رنگ، فونت، هدر، فوتر، فروشگاه و بلاگ، همه در یک‌جا.', 'mahan' ) ),
			array( 'shield', __( 'به‌روزرسانی و پشتیبانی', 'mahan' ), __( 'نسخه‌های تازه و پاسخ تیم ماهان تکنولوژی.', 'mahan' ) ),
		);
		?>
		<div class="mahan-lic__grid">
			<section class="mahan-lic__card">
				<div class="mahan-lic__brand">
					<span class="mahan-lic__mark" aria-hidden="true"><?php mahan_icon_e( 'layers', 26 ); ?></span>
					<div>
						<strong><?php esc_html_e( 'قالب ماهان', 'mahan' ); ?></strong>
						<span>
							<?php
							printf(
								/* translators: %s: version number. */
								esc_html__( 'نسخهٔ %s · ماهان تکنولوژی', 'mahan' ),
								esc_html( mahan_fa_numbers( MAHAN_VERSION ) )
							);
							?>
						</span>
					</div>
				</div>

				<span class="mahan-lic__pill mahan-lic__pill--locked">
					<?php mahan_icon_e( 'lock', 15 ); ?>
					<?php esc_html_e( 'فعال نشده', 'mahan' ); ?>
				</span>

				<h1 class="mahan-lic__title">
					<?php echo esc_html( isset( $headline[ $condition ] ) ? $headline[ $condition ] : $headline['empty'] ); ?>
				</h1>

				<p class="mahan-lic__lede">
					<?php echo esc_html( isset( $lede[ $condition ] ) ? $lede[ $condition ] : $lede['empty'] ); ?>
				</p>

				<?php if ( $license->message() ) : ?>
					<div class="mahan-lic__server-note">
						<?php mahan_icon_e( 'close', 16 ); ?>
						<span><?php echo esc_html( $license->message() ); ?></span>
					</div>
				<?php endif; ?>

				<form class="mahan-lic__form" data-mahan-license-form novalidate>
					<label class="mahan-lic__label" for="mahan-license-key">
						<?php esc_html_e( 'کلید لایسنس', 'mahan' ); ?>
					</label>

					<div class="mahan-lic__field">
						<span class="mahan-lic__field-icon" aria-hidden="true"><?php mahan_icon_e( 'key', 20 ); ?></span>
						<input
							type="text"
							id="mahan-license-key"
							name="license_key"
							dir="ltr"
							inputmode="latin"
							spellcheck="false"
							autocomplete="off"
							autocapitalize="characters"
							maxlength="29"
							placeholder="MT-XXXX-XXXX-XXXX-XXXX-XXXX"
							value="<?php echo esc_attr( $license->key() ); ?>"
							data-mahan-license-input
						/>
					</div>

					<p class="mahan-lic__hint">
						<?php esc_html_e( 'کلید را می‌توانید از پنل کاربری خود در ماهان تکنولوژی یا از ایمیل خرید بردارید.', 'mahan' ); ?>
					</p>

					<button type="submit" class="mahan-lic__submit" data-mahan-license-submit>
						<span class="mahan-lic__submit-label"><?php esc_html_e( 'فعال‌سازی قالب', 'mahan' ); ?></span>
						<span class="mahan-lic__spinner" aria-hidden="true"></span>
					</button>

					<p class="mahan-lic__message" role="status" aria-live="polite" data-mahan-license-message></p>
				</form>
			</section>

			<aside class="mahan-lic__aside">
				<h2><?php esc_html_e( 'با فعال‌سازی چه چیزی باز می‌شود؟', 'mahan' ); ?></h2>

				<ul class="mahan-lic__benefits">
					<?php foreach ( $benefits as $benefit ) : ?>
						<li>
							<span aria-hidden="true"><?php mahan_icon_e( $benefit[0], 20 ); ?></span>
							<div>
								<strong><?php echo esc_html( $benefit[1] ); ?></strong>
								<span><?php echo esc_html( $benefit[2] ); ?></span>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>

				<div class="mahan-lic__aside-foot">
					<p><?php esc_html_e( 'کلید لایسنس ندارید؟', 'mahan' ); ?></p>
					<a class="mahan-lic__aside-link" href="https://mahantechnology.com/" target="_blank" rel="noopener">
						<?php esc_html_e( 'تهیهٔ لایسنس از ماهان تکنولوژی', 'mahan' ); ?>
						<?php mahan_icon_e( 'external', 15 ); ?>
					</a>
				</div>
			</aside>
		</div>
		<?php
	}

	/**
	 * The congratulations state.
	 *
	 * @param Mahan_License $license Licence client.
	 */
	private function render_success( Mahan_License $license ) {
		$days   = $license->days_left();
		$expiry = $license->expiry();
		?>
		<div class="mahan-lic__done">
			<div class="mahan-lic__confetti" aria-hidden="true" data-mahan-confetti></div>

			<div class="mahan-lic__seal" aria-hidden="true">
				<span class="mahan-lic__seal-ring"></span>
				<span class="mahan-lic__seal-ring"></span>
				<?php mahan_icon_e( 'check', 46 ); ?>
			</div>

			<span class="mahan-lic__pill mahan-lic__pill--ok">
				<?php mahan_icon_e( 'badge', 15 ); ?>
				<?php esc_html_e( 'لایسنس معتبر', 'mahan' ); ?>
			</span>

			<h1 class="mahan-lic__title"><?php esc_html_e( 'تبریک! قالب ماهان فعال شد', 'mahan' ); ?></h1>

			<p class="mahan-lic__lede">
				<?php esc_html_e( 'همهٔ قالب‌های آماده، المان‌های المنتور و تنظیمات قالب از همین حالا در دسترس شماست. بهترین شروع، نصب یکی از قالب‌های آماده است.', 'mahan' ); ?>
			</p>

			<dl class="mahan-lic__facts">
				<div>
					<dt><?php esc_html_e( 'کلید لایسنس', 'mahan' ); ?></dt>
					<dd dir="ltr"><?php echo esc_html( $license->masked_key() ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'وضعیت', 'mahan' ); ?></dt>
					<dd><span class="mahan-lic__dot"></span><?php esc_html_e( 'فعال', 'mahan' ); ?></dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'اعتبار تا', 'mahan' ); ?></dt>
					<dd>
						<?php if ( $expiry ) : ?>
							<?php echo esc_html( mahan_fa_numbers( $expiry ) ); ?>
							<?php if ( null !== $days ) : ?>
								<small>
									<?php
									printf(
										/* translators: %s: number of days. */
										esc_html__( '(%s روز باقی مانده)', 'mahan' ),
										esc_html( mahan_fa_numbers( (string) max( 0, $days ) ) )
									);
									?>
								</small>
							<?php endif; ?>
						<?php else : ?>
							<?php esc_html_e( 'بدون محدودیت زمانی', 'mahan' ); ?>
						<?php endif; ?>
					</dd>
				</div>
				<div>
					<dt><?php esc_html_e( 'آخرین بررسی', 'mahan' ); ?></dt>
					<dd>
						<?php
						echo esc_html(
							$license->checked_at()
								? mahan_fa_numbers( wp_date( 'Y/m/d H:i', $license->checked_at() ) )
								: __( '—', 'mahan' )
						);
						?>
					</dd>
				</div>
			</dl>

			<div class="mahan-lic__cta">
				<a class="mahan-lic__submit mahan-lic__submit--link" href="<?php echo esc_url( Mahan_Admin::url( 'starter-sites' ) ); ?>">
					<?php mahan_icon_e( 'layers', 18 ); ?>
					<span><?php esc_html_e( 'نصب قالب آماده', 'mahan' ); ?></span>
				</a>
				<a class="mahan-lic__ghost" href="<?php echo esc_url( Mahan_Admin::url( 'settings' ) ); ?>">
					<?php mahan_icon_e( 'grid', 18 ); ?>
					<span><?php esc_html_e( 'تنظیمات قالب', 'mahan' ); ?></span>
				</a>
				<a class="mahan-lic__ghost" href="<?php echo esc_url( Mahan_Admin::url() ); ?>">
					<?php mahan_icon_e( 'home', 18 ); ?>
					<span><?php esc_html_e( 'پیشخوان قالب', 'mahan' ); ?></span>
				</a>
			</div>

			<p class="mahan-lic__message" role="status" aria-live="polite" data-mahan-license-message></p>

			<div class="mahan-lic__minor">
				<button type="button" class="mahan-lic__text-btn" data-mahan-license-refresh>
					<?php mahan_icon_e( 'refresh', 15 ); ?>
					<span><?php esc_html_e( 'بررسی دوباره', 'mahan' ); ?></span>
				</button>
				<button type="button" class="mahan-lic__text-btn mahan-lic__text-btn--danger" data-mahan-license-remove>
					<?php mahan_icon_e( 'close', 15 ); ?>
					<span><?php esc_html_e( 'حذف لایسنس از این سایت', 'mahan' ); ?></span>
				</button>
			</div>
		</div>
		<?php
	}
}
