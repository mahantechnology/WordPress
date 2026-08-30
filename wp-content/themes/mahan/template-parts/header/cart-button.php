<?php
/**
 * The header cart button. Refreshed as a WooCommerce cart fragment.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

if ( ! mahan_has_woocommerce() || ! WC()->cart ) {
	return;
}

$mahan_count = WC()->cart->get_cart_contents_count();
?>
<a class="mahan-header__action mahan-cart-button" href="<?php echo esc_url( wc_get_cart_url() ); ?>" aria-label="<?php esc_attr_e( 'سبد خرید', 'mahan' ); ?>">
	<?php mahan_icon_e( 'cart', 22 ); ?>
	<span class="mahan-header__count" <?php echo $mahan_count > 0 ? '' : 'hidden'; ?>>
		<?php echo esc_html( mahan_fa_numbers( $mahan_count ) ); ?>
	</span>
	<?php if ( $mahan_count > 0 ) : ?>
		<span class="mahan-cart-button__total"><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
	<?php endif; ?>
</a>
