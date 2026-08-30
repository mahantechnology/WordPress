<?php
/**
 * The sticky bottom navigation shown on phones.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_items = array(
	array(
		'icon'  => 'home',
		'label' => __( 'خانه', 'mahan' ),
		'url'   => home_url( '/' ),
		'match' => is_front_page(),
	),
	array(
		'icon'  => 'grid',
		'label' => __( 'دسته‌ها', 'mahan' ),
		'url'   => '#',
		'panel' => 'drawer',
		'match' => false,
	),
	array(
		'icon'  => 'search',
		'label' => __( 'جستجو', 'mahan' ),
		'url'   => '#',
		'panel' => 'search',
		'match' => is_search(),
	),
);

if ( mahan_has_woocommerce() ) {
	$mahan_items[] = array(
		'icon'  => 'cart',
		'label' => __( 'سبد خرید', 'mahan' ),
		'url'   => wc_get_cart_url(),
		'count' => WC()->cart ? WC()->cart->get_cart_contents_count() : 0,
		'match' => is_cart(),
	);
}

$mahan_items[] = array(
	'icon'  => 'user',
	'label' => __( 'حساب من', 'mahan' ),
	'url'   => mahan_has_woocommerce() && wc_get_page_id( 'myaccount' ) > 0 ? wc_get_page_permalink( 'myaccount' ) : wp_login_url(),
	'match' => mahan_has_woocommerce() && is_account_page(),
);
?>
<nav class="mahan-bottom-bar" aria-label="<?php esc_attr_e( 'پیمایش سریع', 'mahan' ); ?>">
	<?php foreach ( $mahan_items as $mahan_item ) : ?>
		<?php if ( ! empty( $mahan_item['panel'] ) ) : ?>
			<button type="button" class="mahan-bottom-bar__item" data-mahan-open="<?php echo esc_attr( $mahan_item['panel'] ); ?>">
				<?php mahan_icon_e( $mahan_item['icon'], 22 ); ?>
				<span><?php echo esc_html( $mahan_item['label'] ); ?></span>
			</button>
		<?php else : ?>
			<a class="mahan-bottom-bar__item<?php echo $mahan_item['match'] ? ' is-active' : ''; ?>" href="<?php echo esc_url( $mahan_item['url'] ); ?>">
				<?php mahan_icon_e( $mahan_item['icon'], 22 ); ?>
				<?php if ( ! empty( $mahan_item['count'] ) ) : ?>
					<span class="mahan-bottom-bar__count"><?php echo esc_html( mahan_fa_numbers( $mahan_item['count'] ) ); ?></span>
				<?php endif; ?>
				<span><?php echo esc_html( $mahan_item['label'] ); ?></span>
			</a>
		<?php endif; ?>
	<?php endforeach; ?>
</nav>
