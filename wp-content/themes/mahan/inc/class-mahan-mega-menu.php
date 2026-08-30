<?php
/**
 * Per-menu-item settings: mega menu, icon and badge.
 *
 * The fields are added to the classic menu editor because that is where the
 * theme's navigation is configured, and they are stored as post meta on the
 * menu item so the walker can read them cheaply.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Mega_Menu {

	/**
	 * The meta keys this class owns.
	 *
	 * @var string[]
	 */
	private $fields = array( '_mahan_mega_menu', '_mahan_mega_columns', '_mahan_menu_icon', '_mahan_menu_badge' );

	/**
	 * Hooks the menu editor fields.
	 */
	public function __construct() {
		add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'render_fields' ), 10, 4 );
		add_action( 'wp_update_nav_menu_item', array( $this, 'save_fields' ), 10, 2 );
	}

	/**
	 * Prints the extra fields inside a menu item panel.
	 *
	 * @param int      $item_id Menu item ID.
	 * @param WP_Post  $item    Menu item.
	 * @param int      $depth   Depth of the item.
	 * @param stdClass $args    Menu args.
	 */
	public function render_fields( $item_id, $item, $depth, $args ) {
		$mega    = get_post_meta( $item_id, '_mahan_mega_menu', true );
		$columns = get_post_meta( $item_id, '_mahan_mega_columns', true );
		$icon    = get_post_meta( $item_id, '_mahan_menu_icon', true );
		$badge   = get_post_meta( $item_id, '_mahan_menu_badge', true );

		wp_nonce_field( 'mahan_menu_item_' . $item_id, 'mahan_menu_nonce_' . $item_id );
		?>
		<div class="mahan-menu-fields description-wide">
			<?php if ( 0 === (int) $depth ) : ?>
				<p class="description">
					<label>
						<input type="checkbox" name="mahan_mega_menu[<?php echo esc_attr( $item_id ); ?>]" value="yes" <?php checked( 'yes', $mega ); ?> />
						<?php esc_html_e( 'نمایش زیرمنو به‌صورت مگا منو', 'mahan' ); ?>
					</label>
				</p>
				<p class="description">
					<label for="mahan-mega-columns-<?php echo esc_attr( $item_id ); ?>">
						<?php esc_html_e( 'تعداد ستون‌های مگا منو', 'mahan' ); ?><br />
						<select id="mahan-mega-columns-<?php echo esc_attr( $item_id ); ?>" name="mahan_mega_columns[<?php echo esc_attr( $item_id ); ?>]">
							<?php foreach ( array( 2, 3, 4, 5 ) as $option ) : ?>
								<option value="<?php echo esc_attr( $option ); ?>" <?php selected( (int) $columns, $option ); ?>>
									<?php echo esc_html( mahan_fa_numbers( $option ) ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					</label>
				</p>
			<?php endif; ?>
			<p class="description">
				<label for="mahan-menu-icon-<?php echo esc_attr( $item_id ); ?>">
					<?php esc_html_e( 'آیکون', 'mahan' ); ?><br />
					<select id="mahan-menu-icon-<?php echo esc_attr( $item_id ); ?>" name="mahan_menu_icon[<?php echo esc_attr( $item_id ); ?>]">
						<?php foreach ( mahan_icon_choices() as $value => $label ) : ?>
							<option value="<?php echo esc_attr( $value ); ?>" <?php selected( $icon, $value ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</p>
			<p class="description">
				<label for="mahan-menu-badge-<?php echo esc_attr( $item_id ); ?>">
					<?php esc_html_e( 'برچسب (مثلاً جدید یا تخفیف)', 'mahan' ); ?><br />
					<input type="text" id="mahan-menu-badge-<?php echo esc_attr( $item_id ); ?>" class="widefat" name="mahan_menu_badge[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $badge ); ?>" />
				</label>
			</p>
		</div>
		<?php
	}

	/**
	 * Stores the extra fields.
	 *
	 * @param int $menu_id      Menu ID.
	 * @param int $menu_item_id Menu item ID.
	 */
	public function save_fields( $menu_id, $menu_item_id ) {
		$nonce = 'mahan_menu_nonce_' . $menu_item_id;

		if ( empty( $_POST[ $nonce ] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST[ $nonce ] ) ), 'mahan_menu_item_' . $menu_item_id ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_theme_options' ) ) {
			return;
		}

		$mega = isset( $_POST['mahan_mega_menu'][ $menu_item_id ] ) ? 'yes' : '';
		update_post_meta( $menu_item_id, '_mahan_mega_menu', $mega );

		$columns = isset( $_POST['mahan_mega_columns'][ $menu_item_id ] )
			? absint( $_POST['mahan_mega_columns'][ $menu_item_id ] )
			: 4;
		update_post_meta( $menu_item_id, '_mahan_mega_columns', min( 5, max( 2, $columns ) ) );

		$icon  = isset( $_POST['mahan_menu_icon'][ $menu_item_id ] ) ? sanitize_key( wp_unslash( $_POST['mahan_menu_icon'][ $menu_item_id ] ) ) : '';
		$icons = mahan_icon_set();
		update_post_meta( $menu_item_id, '_mahan_menu_icon', isset( $icons[ $icon ] ) ? $icon : '' );

		$badge = isset( $_POST['mahan_menu_badge'][ $menu_item_id ] ) ? sanitize_text_field( wp_unslash( $_POST['mahan_menu_badge'][ $menu_item_id ] ) ) : '';
		update_post_meta( $menu_item_id, '_mahan_menu_badge', $badge );
	}
}
