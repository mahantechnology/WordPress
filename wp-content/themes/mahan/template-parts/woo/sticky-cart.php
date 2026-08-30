<?php
/**
 * The sticky buy bar shown on the product page once the form scrolls away.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}
?>
<div class="mahan-sticky-cart" data-mahan-sticky-cart hidden>
	<div class="mahan-container mahan-sticky-cart__inner">
		<div class="mahan-sticky-cart__product">
			<?php echo $product->get_image( 'mahan-thumb' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup. ?>
			<div>
				<strong><?php echo esc_html( $product->get_name() ); ?></strong>
				<span class="mahan-sticky-cart__price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup. ?></span>
			</div>
		</div>

		<div class="mahan-sticky-cart__actions">
			<?php if ( $product->is_type( 'simple' ) && $product->is_in_stock() ) : ?>
				<a class="mahan-btn mahan-btn--primary mahan-btn--lg add_to_cart_button ajax_add_to_cart"
					href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
					data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
					data-quantity="1">
					<?php mahan_icon_e( 'cart', 20 ); ?>
					<span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span>
				</a>
			<?php else : ?>
				<button type="button" class="mahan-btn mahan-btn--primary mahan-btn--lg" data-mahan-scroll-to=".summary form.cart, .summary .single_add_to_cart_button">
					<?php mahan_icon_e( 'cart', 20 ); ?>
					<span><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span>
				</button>
			<?php endif; ?>
		</div>
	</div>
</div>
