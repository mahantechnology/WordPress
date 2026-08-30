<?php
/**
 * The quick-view dialog body, returned by the AJAX handler.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product instanceof WC_Product ) {
	return;
}
?>
<div class="mahan-quick-view">
	<div class="mahan-quick-view__media">
		<?php echo $product->get_image( 'woocommerce_single' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup. ?>

		<?php
		$mahan_gallery = $product->get_gallery_image_ids();

		if ( $mahan_gallery ) :
			?>
			<div class="mahan-quick-view__thumbs">
				<?php foreach ( array_slice( $mahan_gallery, 0, 4 ) as $mahan_image_id ) : ?>
					<?php echo wp_get_attachment_image( $mahan_image_id, 'mahan-thumb', false, array( 'loading' => 'lazy' ) ); ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="mahan-quick-view__body">
		<h2 class="mahan-quick-view__title"><?php echo esc_html( $product->get_name() ); ?></h2>

		<?php if ( wc_review_ratings_enabled() && $product->get_average_rating() > 0 ) : ?>
			<div class="mahan-quick-view__rating">
				<?php mahan_stars( (float) $product->get_average_rating(), (int) $product->get_review_count() ); ?>
			</div>
		<?php endif; ?>

		<div class="mahan-quick-view__price"><?php echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WooCommerce markup. ?></div>

		<?php if ( $product->get_short_description() ) : ?>
			<div class="mahan-quick-view__excerpt"><?php echo wp_kses_post( $product->get_short_description() ); ?></div>
		<?php endif; ?>

		<div class="mahan-quick-view__meta">
			<span>
				<?php esc_html_e( 'وضعیت:', 'mahan' ); ?>
				<strong class="<?php echo $product->is_in_stock() ? 'is-in-stock' : 'is-out-of-stock'; ?>">
					<?php echo esc_html( $product->is_in_stock() ? __( 'موجود', 'mahan' ) : __( 'ناموجود', 'mahan' ) ); ?>
				</strong>
			</span>

			<?php if ( $product->get_sku() ) : ?>
				<span><?php esc_html_e( 'کد کالا:', 'mahan' ); ?> <strong><?php echo esc_html( $product->get_sku() ); ?></strong></span>
			<?php endif; ?>
		</div>

		<div class="mahan-quick-view__actions">
			<?php woocommerce_template_loop_add_to_cart( array( 'class' => 'mahan-btn mahan-btn--primary mahan-btn--lg' ) ); ?>
			<a class="mahan-btn mahan-btn--outline mahan-btn--lg" href="<?php echo esc_url( get_permalink( $product->get_id() ) ); ?>">
				<?php esc_html_e( 'مشاهدهٔ کامل', 'mahan' ); ?>
			</a>
		</div>
	</div>
</div>
