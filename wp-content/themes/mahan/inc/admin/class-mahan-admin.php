<?php
/**
 * The «قالب ماهان» panel: a top-level admin menu with the theme's dashboard,
 * starter sites, settings, element list and system report.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Admin {

	/**
	 * Top-level menu slug.
	 */
	const SLUG = 'mahan';

	/**
	 * Capability every screen in the panel requires.
	 */
	const CAPABILITY = 'edit_theme_options';

	/**
	 * Hooks the panel.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ), 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'assets' ) );
		add_action( 'admin_post_mahan_save_settings', array( $this, 'save_settings' ) );
		add_action( 'admin_post_mahan_reset_settings', array( $this, 'reset_settings' ) );
		add_action( 'admin_post_mahan_export_settings', array( $this, 'export_settings' ) );
		add_action( 'admin_post_mahan_import_settings', array( $this, 'import_settings' ) );
		add_filter( 'admin_body_class', array( $this, 'body_class' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar' ), 80 );
	}

	/**
	 * The panel's screens, in menu order.
	 *
	 * @return array<string,array{title:string,menu:string,icon:string}>
	 */
	public static function screens() {
		$screens = array(
			'dashboard'     => array(
				'title' => __( 'پیشخوان قالب ماهان', 'mahan' ),
				'menu'  => __( 'پیشخوان', 'mahan' ),
				'icon'  => 'home',
			),
			'starter-sites' => array(
				'title' => __( 'قالب‌های آماده', 'mahan' ),
				'menu'  => __( 'قالب‌های آماده', 'mahan' ),
				'icon'  => 'layers',
			),
			'settings'      => array(
				'title' => __( 'تنظیمات قالب', 'mahan' ),
				'menu'  => __( 'تنظیمات', 'mahan' ),
				'icon'  => 'grid',
			),
			'elements'      => array(
				'title' => __( 'المان‌های ماهان', 'mahan' ),
				'menu'  => __( 'المان‌ها', 'mahan' ),
				'icon'  => 'sparkles',
			),
			'status'        => array(
				'title' => __( 'وضعیت سیستم', 'mahan' ),
				'menu'  => __( 'وضعیت سیستم', 'mahan' ),
				'icon'  => 'chart',
			),
		);

		/**
		 * Filters the screens the Mahan panel offers.
		 *
		 * @param array $screens Screen definitions keyed by slug.
		 */
		return apply_filters( 'mahan_admin_screens', $screens );
	}

	/**
	 * Registers the top-level menu and its subpages.
	 */
	public function register_menu() {
		$screens = self::screens();

		add_menu_page(
			__( 'قالب ماهان', 'mahan' ),
			__( 'قالب ماهان', 'mahan' ),
			self::CAPABILITY,
			self::SLUG,
			array( $this, 'render' ),
			$this->menu_icon(),
			// Sits below Settings so the panel reads as the last item in the menu.
			99
		);

		foreach ( $screens as $slug => $screen ) {
			add_submenu_page(
				self::SLUG,
				$screen['title'],
				$screen['menu'],
				self::CAPABILITY,
				'dashboard' === $slug ? self::SLUG : self::SLUG . '-' . $slug,
				array( $this, 'render' )
			);
		}
	}

	/**
	 * The menu icon, as an inline data URI so it inherits the admin colours.
	 *
	 * @return string
	 */
	private function menu_icon() {
		$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">'
			. '<path d="m12 3 9 4.6-9 4.6-9-4.6Z"/><path d="m3 12.4 9 4.6 9-4.6M3 16.9l9 4.6 9-4.6"/></svg>';

		return 'data:image/svg+xml;base64,' . base64_encode( $svg );
	}

	/**
	 * The screen slug being viewed.
	 *
	 * @return string
	 */
	public static function current_screen() {
		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : '';

		if ( self::SLUG === $page ) {
			return 'dashboard';
		}

		$slug = str_replace( self::SLUG . '-', '', $page );

		return array_key_exists( $slug, self::screens() ) ? $slug : 'dashboard';
	}

	/**
	 * The admin URL for one of the panel's screens.
	 *
	 * @param string $screen Screen slug.
	 * @param array  $args   Extra query arguments.
	 * @return string
	 */
	public static function url( $screen = 'dashboard', array $args = array() ) {
		$page = 'dashboard' === $screen ? self::SLUG : self::SLUG . '-' . $screen;

		return add_query_arg( array_merge( array( 'page' => $page ), $args ), admin_url( 'admin.php' ) );
	}

	/**
	 * Whether the request is on one of the panel's screens.
	 *
	 * @param string $hook Current admin page hook.
	 * @return bool
	 */
	private function is_panel( $hook ) {
		return false !== strpos( $hook, 'page_' . self::SLUG ) || false !== strpos( $hook, self::SLUG . '-' );
	}

	/**
	 * Loads the panel styles and script on the panel's screens only.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public function assets( $hook ) {
		if ( ! $this->is_panel( $hook ) ) {
			return;
		}

		wp_enqueue_style( 'mahan-fonts', MAHAN_URI . 'assets/css/fonts.css', array(), MAHAN_VERSION );
		wp_enqueue_style( 'mahan-admin-panel', MAHAN_URI . 'assets/css/admin-panel.css', array( 'mahan-fonts' ), MAHAN_VERSION );
		wp_enqueue_script( 'mahan-admin-panel', MAHAN_URI . 'assets/js/admin-panel.js', array(), MAHAN_VERSION, true );

		wp_localize_script(
			'mahan-admin-panel',
			'mahanPanel',
			array(
				'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
				'nonce'     => wp_create_nonce( 'mahan_setup_wizard' ),
				'steps'     => Mahan_Demo_Importer::steps(),
				'palettes'  => Mahan_Options::palettes(),
				'homeUrl'   => home_url( '/' ),
				'i18n'      => array(
					'importing' => __( 'در حال نصب…', 'mahan' ),
					'done'      => __( 'نصب کامل شد!', 'mahan' ),
					'failed'    => __( 'نصب با خطا روبه‌رو شد.', 'mahan' ),
					'confirm'   => __( 'با نصب این قالب آماده، تنظیمات فعلی قالب بازنویسی می‌شود. ادامه می‌دهید؟', 'mahan' ),
					'rollback'  => __( 'همهٔ محتوای درون‌ریزی‌شده، شامل تصاویر و محصولات نمونه، حذف خواهد شد. مطمئن هستید؟', 'mahan' ),
					'removing'  => __( 'در حال حذف…', 'mahan' ),
					'search'    => __( 'موردی پیدا نشد.', 'mahan' ),
					'copied'    => __( 'کپی شد', 'mahan' ),
				),
			)
		);
	}

	/**
	 * Marks the body so the panel styles can take over the page.
	 *
	 * @param string $classes Existing classes.
	 * @return string
	 */
	public function body_class( $classes ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;

		if ( $screen && false !== strpos( $screen->id, self::SLUG ) ) {
			$classes .= ' mahan-panel-page';
		}

		return $classes;
	}

	/**
	 * Adds a shortcut to the panel in the toolbar.
	 *
	 * @param WP_Admin_Bar $bar Toolbar instance.
	 */
	public function admin_bar( $bar ) {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		$bar->add_node(
			array(
				'id'    => 'mahan-panel',
				'title' => __( 'قالب ماهان', 'mahan' ),
				'href'  => self::url(),
			)
		);

		foreach ( self::screens() as $slug => $screen ) {
			$bar->add_node(
				array(
					'parent' => 'mahan-panel',
					'id'     => 'mahan-panel-' . $slug,
					'title'  => $screen['menu'],
					'href'   => self::url( $slug ),
				)
			);
		}
	}

	/**
	 * Renders the panel shell and the active screen.
	 */
	public function render() {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'دسترسی لازم را ندارید.', 'mahan' ) );
		}

		$screen  = self::current_screen();
		$screens = self::screens();
		$view    = MAHAN_INC . 'admin/views/' . $screen . '.php';
		?>
		<div class="mahan-panel" dir="rtl">
			<?php $this->render_notice(); ?>

			<header class="mahan-panel__masthead">
				<div class="mahan-panel__brand">
					<span class="mahan-panel__logo" aria-hidden="true"><?php mahan_icon_e( 'layers', 26 ); ?></span>
					<div>
						<strong><?php esc_html_e( 'قالب ماهان', 'mahan' ); ?></strong>
						<span>
							<?php
							printf(
								/* translators: %s: version number. */
								esc_html__( 'نسخهٔ %s', 'mahan' ),
								esc_html( mahan_fa_numbers( MAHAN_VERSION ) )
							);
							?>
						</span>
					</div>
				</div>

				<nav class="mahan-panel__tabs" aria-label="<?php esc_attr_e( 'بخش‌های پنل', 'mahan' ); ?>">
					<?php foreach ( $screens as $slug => $item ) : ?>
						<a
							class="mahan-panel__tab<?php echo $slug === $screen ? ' is-active' : ''; ?>"
							href="<?php echo esc_url( self::url( $slug ) ); ?>"
							<?php echo $slug === $screen ? 'aria-current="page"' : ''; ?>
						>
							<?php mahan_icon_e( $item['icon'], 18 ); ?>
							<span><?php echo esc_html( $item['menu'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</nav>

				<div class="mahan-panel__masthead-actions">
					<a class="mahan-panel__ghost-btn" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>">
						<?php mahan_icon_e( 'pen', 16 ); ?>
						<span><?php esc_html_e( 'سفارشی‌سازی زنده', 'mahan' ); ?></span>
					</a>
					<a class="mahan-panel__ghost-btn" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener">
						<?php mahan_icon_e( 'external', 16 ); ?>
						<span><?php esc_html_e( 'مشاهدهٔ سایت', 'mahan' ); ?></span>
					</a>
				</div>
			</header>

			<main class="mahan-panel__body">
				<?php
				if ( file_exists( $view ) ) {
					include $view;
				} else {
					printf( '<p>%s</p>', esc_html__( 'این بخش در دسترس نیست.', 'mahan' ) );
				}
				?>
			</main>
		</div>
		<?php
	}

	/**
	 * Prints the result of the last settings action.
	 */
	private function render_notice() {
		$notice = isset( $_GET['mahan-notice'] ) ? sanitize_key( wp_unslash( $_GET['mahan-notice'] ) ) : '';

		$messages = array(
			'saved'     => array( 'success', __( 'تنظیمات ذخیره شد.', 'mahan' ) ),
			'reset'     => array( 'success', __( 'تنظیمات به حالت پیش‌فرض برگشت.', 'mahan' ) ),
			'imported'  => array( 'success', __( 'تنظیمات با موفقیت درون‌ریزی شد.', 'mahan' ) ),
			'badimport' => array( 'error', __( 'فایل تنظیمات معتبر نبود.', 'mahan' ) ),
		);

		if ( ! isset( $messages[ $notice ] ) ) {
			return;
		}

		printf(
			'<div class="mahan-panel__notice mahan-panel__notice--%1$s">%2$s<span>%3$s</span></div>',
			esc_attr( $messages[ $notice ][0] ),
			mahan_icon( 'success' === $messages[ $notice ][0] ? 'check-circle' : 'close', 20 ), // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Fixed icon set.
			esc_html( $messages[ $notice ][1] )
		);
	}

	/**
	 * Stores the settings form.
	 */
	public function save_settings() {
		check_admin_referer( 'mahan_save_settings' );

		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'دسترسی لازم را ندارید.', 'mahan' ) );
		}

		$group  = isset( $_POST['mahan_group'] ) ? sanitize_key( wp_unslash( $_POST['mahan_group'] ) ) : '';
		$fields = Mahan_Schema::group( $group );

		if ( ! $fields ) {
			$this->redirect( 'settings', 'badimport' );
		}

		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Each value is sanitized per field below.
		$submitted = isset( $_POST['mahan'] ) && is_array( $_POST['mahan'] ) ? wp_unslash( $_POST['mahan'] ) : array();
		$values    = array();

		foreach ( $fields['fields'] as $key => $field ) {
			if ( 'checkbox' === $field['type'] ) {
				$values[ $key ] = ! empty( $submitted[ $key ] );
				continue;
			}

			if ( ! isset( $submitted[ $key ] ) ) {
				continue;
			}

			$values[ $key ] = Mahan_Options::sanitize( $key, $submitted[ $key ] );
		}

		// Choosing a palette should move the three brand colours with it, unless
		// the same request also edited one of them by hand.
		if ( isset( $values['palette'] ) && $values['palette'] !== mahan_option( 'palette' ) ) {
			$palettes = Mahan_Options::palettes();

			if ( isset( $palettes[ $values['palette'] ] ) ) {
				$values['color_primary']   = $palettes[ $values['palette'] ]['primary'];
				$values['color_secondary'] = $palettes[ $values['palette'] ]['secondary'];
				$values['color_accent']    = $palettes[ $values['palette'] ]['accent'];
			}
		}

		Mahan_Options::merge( $values );

		$this->redirect( 'settings', 'saved', array( 'tab' => $group ) );
	}

	/**
	 * Restores the defaults.
	 */
	public function reset_settings() {
		check_admin_referer( 'mahan_reset_settings' );

		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'دسترسی لازم را ندارید.', 'mahan' ) );
		}

		Mahan_Options::reset();

		$this->redirect( 'settings', 'reset' );
	}

	/**
	 * Sends the current settings as a JSON download.
	 */
	public function export_settings() {
		check_admin_referer( 'mahan_export_settings' );

		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'دسترسی لازم را ندارید.', 'mahan' ) );
		}

		$payload = array(
			'theme'    => 'mahan',
			'version'  => MAHAN_VERSION,
			'exported' => gmdate( 'c' ),
			'settings' => Mahan_Options::all(),
		);

		nocache_headers();
		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=mahan-settings-' . gmdate( 'Y-m-d' ) . '.json' );

		echo wp_json_encode( $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		exit;
	}

	/**
	 * Reads a settings file back in.
	 */
	public function import_settings() {
		check_admin_referer( 'mahan_import_settings' );

		if ( ! current_user_can( self::CAPABILITY ) ) {
			wp_die( esc_html__( 'دسترسی لازم را ندارید.', 'mahan' ) );
		}

		if ( empty( $_FILES['mahan_settings_file']['tmp_name'] ) ) {
			$this->redirect( 'settings', 'badimport' );
		}

		$file = sanitize_text_field( $_FILES['mahan_settings_file']['tmp_name'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Upload path.

		if ( ! is_uploaded_file( $file ) ) {
			$this->redirect( 'settings', 'badimport' );
		}

		$raw     = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Reading an uploaded temp file.
		$payload = json_decode( (string) $raw, true );

		if ( ! is_array( $payload ) || empty( $payload['settings'] ) || ! is_array( $payload['settings'] ) ) {
			$this->redirect( 'settings', 'badimport' );
		}

		// Mahan_Options::save() drops any key the theme does not know about.
		Mahan_Options::save( $payload['settings'] );

		$this->redirect( 'settings', 'imported' );
	}

	/**
	 * Sends the browser back to a panel screen with a notice.
	 *
	 * @param string $screen Screen slug.
	 * @param string $notice Notice key.
	 * @param array  $args   Extra query arguments.
	 */
	private function redirect( $screen, $notice, array $args = array() ) {
		wp_safe_redirect( self::url( $screen, array_merge( $args, array( 'mahan-notice' => $notice ) ) ) );
		exit;
	}
}
