<?php
/**
 * Points the site owner at the plugins the theme is built around.
 *
 * The theme works without them, so this is a dismissible notice rather than a
 * bundled installer.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Plugin_Notice {

	/**
	 * User meta key recording that the notice was dismissed.
	 */
	const DISMISS_KEY = 'mahan_dismissed_plugin_notice';

	/**
	 * Hooks the notice.
	 */
	public function __construct() {
		add_action( 'admin_notices', array( $this, 'render' ) );
		add_action( 'admin_post_mahan_dismiss_plugin_notice', array( $this, 'dismiss' ) );
	}

	/**
	 * The plugins the theme integrates with.
	 *
	 * @return array<string,array{name:string,slug:string,file:string,why:string}>
	 */
	public static function recommended() {
		return array(
			'elementor'   => array(
				'name' => __( 'المنتور', 'mahan' ),
				'slug' => 'elementor',
				'file' => 'elementor/elementor.php',
				'why'  => __( 'برای استفاده از ده‌ها المان اختصاصی ماهان و صفحه‌سازی بصری.', 'mahan' ),
			),
			'woocommerce' => array(
				'name' => __( 'ووکامرس', 'mahan' ),
				'slug' => 'woocommerce',
				'file' => 'woocommerce/woocommerce.php',
				'why'  => __( 'برای راه‌اندازی فروشگاه و استفاده از قالب‌های آمادهٔ فروشگاهی.', 'mahan' ),
			),
			'contact-form-7' => array(
				'name' => __( 'تماس با ما فرم ۷', 'mahan' ),
				'slug' => 'contact-form-7',
				'file' => 'contact-form-7/wp-contact-form-7.php',
				'why'  => __( 'فرم‌های تماس دموها با این افزونه ساخته می‌شوند.', 'mahan' ),
			),
		);
	}

	/**
	 * The recommended plugins that are not active yet.
	 *
	 * @return array
	 */
	public static function missing() {
		$missing = array();

		foreach ( self::recommended() as $key => $plugin ) {
			if ( ! is_plugin_active( $plugin['file'] ) ) {
				$missing[ $key ] = $plugin;
			}
		}

		return $missing;
	}

	/**
	 * Prints the notice.
	 */
	public function render() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return;
		}

		if ( get_user_meta( get_current_user_id(), self::DISMISS_KEY, true ) ) {
			return;
		}

		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$missing = self::missing();

		if ( ! $missing ) {
			return;
		}

		$dismiss_url = wp_nonce_url(
			admin_url( 'admin-post.php?action=mahan_dismiss_plugin_notice' ),
			'mahan_dismiss_plugin_notice'
		);
		?>
		<div class="notice notice-info mahan-plugin-notice">
			<h3><?php esc_html_e( 'قالب ماهان با این افزونه‌ها کامل می‌شود', 'mahan' ); ?></h3>
			<ul>
				<?php foreach ( $missing as $plugin ) : ?>
					<li>
						<strong><?php echo esc_html( $plugin['name'] ); ?></strong>
						— <?php echo esc_html( $plugin['why'] ); ?>
						<a class="button button-small" href="<?php echo esc_url( self::install_url( $plugin['slug'] ) ); ?>">
							<?php esc_html_e( 'نصب', 'mahan' ); ?>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<p>
				<a class="button button-primary" href="<?php echo esc_url( admin_url( 'themes.php?page=mahan-setup' ) ); ?>">
					<?php esc_html_e( 'رفتن به راه‌انداز ماهان', 'mahan' ); ?>
				</a>
				<a class="button" href="<?php echo esc_url( $dismiss_url ); ?>"><?php esc_html_e( 'بستن این پیام', 'mahan' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * The one-click install URL for a plugin slug.
	 *
	 * @param string $slug Plugin slug on WordPress.org.
	 * @return string
	 */
	public static function install_url( $slug ) {
		return wp_nonce_url(
			add_query_arg(
				array(
					'action' => 'install-plugin',
					'plugin' => $slug,
				),
				admin_url( 'update.php' )
			),
			'install-plugin_' . $slug
		);
	}

	/**
	 * Records the dismissal and returns to the previous screen.
	 */
	public function dismiss() {
		check_admin_referer( 'mahan_dismiss_plugin_notice' );

		if ( current_user_can( 'install_plugins' ) ) {
			update_user_meta( get_current_user_id(), self::DISMISS_KEY, 1 );
		}

		wp_safe_redirect( wp_get_referer() ? wp_get_referer() : admin_url() );
		exit;
	}
}
