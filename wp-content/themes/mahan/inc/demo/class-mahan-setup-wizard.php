<?php
/**
 * The admin screen where a starter site is picked and installed.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Setup_Wizard {

	/**
	 * Admin page slug.
	 */
	const SLUG = 'mahan-setup';

	/**
	 * Option that records the welcome redirect has already happened.
	 */
	const REDIRECT_OPTION = 'mahan_did_welcome_redirect';

	/**
	 * Hooks the admin page.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'after_switch_theme', array( $this, 'flag_redirect' ) );
		add_action( 'admin_init', array( $this, 'maybe_redirect' ) );
		add_filter( 'admin_body_class', array( $this, 'body_class' ) );
	}

	/**
	 * Adds the page under Appearance.
	 */
	public function register_page() {
		add_theme_page(
			__( 'راه‌انداز ماهان', 'mahan' ),
			__( 'راه‌انداز ماهان', 'mahan' ),
			'edit_theme_options',
			self::SLUG,
			array( $this, 'render' )
		);
	}

	/**
	 * Loads the wizard styles and script on its own page only.
	 *
	 * @param string $hook Current admin page.
	 */
	public function assets( $hook ) {
		if ( 'appearance_page_' . self::SLUG !== $hook ) {
			return;
		}

		wp_enqueue_style( 'mahan-wizard', MAHAN_URI . 'assets/css/wizard.css', array(), MAHAN_VERSION );
		wp_enqueue_script( 'mahan-wizard', MAHAN_URI . 'assets/js/wizard.js', array(), MAHAN_VERSION, true );

		wp_localize_script(
			'mahan-wizard',
			'mahanWizard',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'mahan_setup_wizard' ),
				'steps'   => Mahan_Demo_Importer::steps(),
				'i18n'    => array(
					'importing'  => __( 'در حال نصب…', 'mahan' ),
					'done'       => __( 'نصب کامل شد!', 'mahan' ),
					'failed'     => __( 'نصب با خطا روبه‌رو شد.', 'mahan' ),
					'visit'      => __( 'مشاهدهٔ سایت', 'mahan' ),
					'confirm'    => __( 'با نصب این قالب آماده، تنظیمات فعلی قالب بازنویسی می‌شود. ادامه می‌دهید؟', 'mahan' ),
					'rollback'   => __( 'همهٔ محتوای درون‌ریزی‌شده حذف خواهد شد. مطمئن هستید؟', 'mahan' ),
					'removing'   => __( 'در حال حذف…', 'mahan' ),
				),
			)
		);
	}

	/**
	 * Marks that the welcome redirect should run once.
	 */
	public function flag_redirect() {
		if ( ! is_network_admin() && ! isset( $_GET['activate-multi'] ) ) {
			update_option( self::REDIRECT_OPTION, 0 );
		}
	}

	/**
	 * Sends the site owner to the wizard the first time after activation.
	 */
	public function maybe_redirect() {
		if ( '0' !== (string) get_option( self::REDIRECT_OPTION, '1' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) || wp_doing_ajax() ) {
			return;
		}

		update_option( self::REDIRECT_OPTION, 1 );

		wp_safe_redirect( admin_url( 'themes.php?page=' . self::SLUG ) );
		exit;
	}

	/**
	 * Adds a class so the wizard styles can hide the admin chrome.
	 *
	 * @param string $classes Existing classes.
	 * @return string
	 */
	public function body_class( $classes ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( $screen && 'appearance_page_' . self::SLUG === $screen->id ) {
			$classes .= ' mahan-wizard-page';
		}

		return $classes;
	}

	/**
	 * Prints the wizard.
	 */
	public function render() {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( esc_html__( 'دسترسی لازم را ندارید.', 'mahan' ) );
		}

		$demos    = Mahan_Demo_Library::index();
		$imported = Mahan_Demo_Importer::imported_demo();
		$can_import = current_user_can( 'import' );
		?>
		<div class="mahan-wizard" dir="rtl">
			<header class="mahan-wizard__header">
				<div class="mahan-wizard__brand">
					<span class="mahan-wizard__logo">ماهان</span>
					<div>
						<h1><?php esc_html_e( 'به قالب ماهان خوش آمدید', 'mahan' ); ?></h1>
						<p><?php esc_html_e( 'یکی از قالب‌های آماده را انتخاب کنید تا در چند ثانیه یک سایت کامل و فارسی داشته باشید.', 'mahan' ); ?></p>
					</div>
				</div>
				<nav class="mahan-wizard__links">
					<a class="button" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
						<?php esc_html_e( 'سفارشی‌سازی', 'mahan' ); ?>
					</a>
					<a class="button" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener">
						<?php esc_html_e( 'مشاهدهٔ سایت', 'mahan' ); ?>
					</a>
				</nav>
			</header>

			<?php $this->render_plugin_bar(); ?>

			<?php if ( $imported ) : ?>
				<div class="mahan-wizard__notice mahan-wizard__notice--info">
					<p>
						<?php
						printf(
							/* translators: %s: demo name. */
							esc_html__( 'آخرین قالب آمادهٔ نصب‌شده: %s', 'mahan' ),
							'<strong>' . esc_html( isset( $demos[ $imported ] ) ? $demos[ $imported ]['label'] : $imported ) . '</strong>'
						);
						?>
					</p>
					<?php if ( current_user_can( 'delete_others_pages' ) ) : ?>
						<button type="button" class="button button-link-delete" data-mahan-rollback>
							<?php esc_html_e( 'حذف محتوای درون‌ریزی‌شده', 'mahan' ); ?>
						</button>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="mahan-wizard__grid">
				<?php foreach ( $demos as $id => $demo ) : ?>
					<?php $missing = Mahan_Demo_Library::missing_plugins( $id ); ?>
					<article class="mahan-demo-card<?php echo $imported === $id ? ' is-installed' : ''; ?>" data-demo="<?php echo esc_attr( $id ); ?>">
						<div class="mahan-demo-card__preview">
							<img src="<?php echo esc_url( $demo['preview'] ); ?>" alt="<?php echo esc_attr( $demo['label'] ); ?>" loading="lazy" />
							<?php if ( $imported === $id ) : ?>
								<span class="mahan-demo-card__installed"><?php esc_html_e( 'نصب‌شده', 'mahan' ); ?></span>
							<?php endif; ?>
						</div>

						<div class="mahan-demo-card__body">
							<h2 class="mahan-demo-card__title"><?php echo esc_html( $demo['label'] ); ?></h2>
							<p class="mahan-demo-card__text"><?php echo esc_html( $demo['description'] ); ?></p>

							<ul class="mahan-demo-card__tags">
								<?php foreach ( $demo['tags'] as $tag ) : ?>
									<li><?php echo esc_html( $tag ); ?></li>
								<?php endforeach; ?>
							</ul>

							<?php if ( $missing ) : ?>
								<div class="mahan-demo-card__requires">
									<strong><?php esc_html_e( 'نیازمند نصب:', 'mahan' ); ?></strong>
									<?php foreach ( $missing as $plugin ) : ?>
										<a class="button button-small" href="<?php echo esc_url( Mahan_Plugin_Notice::install_url( $plugin['slug'] ) ); ?>">
											<?php echo esc_html( $plugin['name'] ); ?>
										</a>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>

						<footer class="mahan-demo-card__footer">
							<button
								type="button"
								class="button button-primary mahan-demo-card__install"
								data-mahan-install="<?php echo esc_attr( $id ); ?>"
								<?php disabled( ! $can_import || ! empty( $missing ) ); ?>
							>
								<?php esc_html_e( 'نصب این قالب آماده', 'mahan' ); ?>
							</button>
						</footer>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="mahan-wizard__modal" data-mahan-progress hidden>
				<div class="mahan-wizard__modal-inner" role="dialog" aria-modal="true" aria-labelledby="mahan-progress-title">
					<h2 id="mahan-progress-title"><?php esc_html_e( 'در حال نصب قالب آماده', 'mahan' ); ?></h2>
					<ol class="mahan-wizard__steps">
						<?php foreach ( Mahan_Demo_Importer::steps() as $key => $label ) : ?>
							<li data-step="<?php echo esc_attr( $key ); ?>">
								<span class="mahan-wizard__step-icon" aria-hidden="true"></span>
								<span class="mahan-wizard__step-label"><?php echo esc_html( $label ); ?></span>
							</li>
						<?php endforeach; ?>
					</ol>
					<p class="mahan-wizard__status" role="status" aria-live="polite"></p>
					<div class="mahan-wizard__actions" hidden>
						<a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener">
							<?php esc_html_e( 'مشاهدهٔ سایت', 'mahan' ); ?>
						</a>
						<button type="button" class="button" data-mahan-close-progress><?php esc_html_e( 'بستن', 'mahan' ); ?></button>
					</div>
				</div>
			</div>

			<?php $this->render_help(); ?>
		</div>
		<?php
	}

	/**
	 * Prints the recommended-plugin strip at the top of the wizard.
	 */
	private function render_plugin_bar() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugins = Mahan_Plugin_Notice::recommended();
		?>
		<div class="mahan-wizard__plugins">
			<?php foreach ( $plugins as $plugin ) : ?>
				<?php $active = is_plugin_active( $plugin['file'] ); ?>
				<div class="mahan-wizard__plugin<?php echo $active ? ' is-active' : ''; ?>">
					<span class="mahan-wizard__plugin-state" aria-hidden="true"><?php echo $active ? '✓' : '!'; ?></span>
					<div>
						<strong><?php echo esc_html( $plugin['name'] ); ?></strong>
						<span><?php echo esc_html( $active ? __( 'فعال است', 'mahan' ) : $plugin['why'] ); ?></span>
					</div>
					<?php if ( ! $active && current_user_can( 'install_plugins' ) ) : ?>
						<a class="button button-small" href="<?php echo esc_url( Mahan_Plugin_Notice::install_url( $plugin['slug'] ) ); ?>">
							<?php esc_html_e( 'نصب', 'mahan' ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Prints the short help section at the bottom of the wizard.
	 */
	private function render_help() {
		$cards = array(
			array(
				'title' => __( 'رنگ‌ها و فونت', 'mahan' ),
				'text'  => __( 'از «نمایش ← سفارشی‌سازی ← تنظیمات قالب ماهان» می‌توانید پالت رنگی، فونت و اندازهٔ متن را تغییر دهید.', 'mahan' ),
				'url'   => admin_url( 'customize.php?autofocus[section]=mahan_colors' ),
				'link'  => __( 'باز کردن تنظیمات رنگ', 'mahan' ),
			),
			array(
				'title' => __( 'هدر و فوتر', 'mahan' ),
				'text'  => __( 'پنج چیدمان هدر و چهار چیدمان فوتر آماده دارید؛ همه را می‌توانید بدون کدنویسی جابه‌جا کنید.', 'mahan' ),
				'url'   => admin_url( 'customize.php?autofocus[section]=mahan_header' ),
				'link'  => __( 'تنظیم هدر', 'mahan' ),
			),
			array(
				'title' => __( 'المان‌های المنتور', 'mahan' ),
				'text'  => __( 'در ویرایشگر المنتور، دسته‌های «المان‌های ماهان» و «فروشگاه ماهان» را باز کنید.', 'mahan' ),
				'url'   => admin_url( 'edit.php?post_type=page' ),
				'link'  => __( 'ویرایش برگه‌ها', 'mahan' ),
			),
			array(
				'title' => __( 'منوها', 'mahan' ),
				'text'  => __( 'برای هر آیتم منو می‌توانید آیکون، برچسب و مگا منو تعریف کنید.', 'mahan' ),
				'url'   => admin_url( 'nav-menus.php' ),
				'link'  => __( 'مدیریت منوها', 'mahan' ),
			),
		);
		?>
		<section class="mahan-wizard__help">
			<h2><?php esc_html_e( 'قدم‌های بعدی', 'mahan' ); ?></h2>
			<div class="mahan-wizard__help-grid">
				<?php foreach ( $cards as $card ) : ?>
					<div class="mahan-wizard__help-card">
						<h3><?php echo esc_html( $card['title'] ); ?></h3>
						<p><?php echo esc_html( $card['text'] ); ?></p>
						<a href="<?php echo esc_url( $card['url'] ); ?>"><?php echo esc_html( $card['link'] ); ?></a>
					</div>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}
}
