<?php
/**
 * Previous and next post links.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_prev = get_previous_post();
$mahan_next = get_next_post();

if ( ! $mahan_prev && ! $mahan_next ) {
	return;
}
?>
<nav class="mahan-post-nav" aria-label="<?php esc_attr_e( 'پیمایش نوشته‌ها', 'mahan' ); ?>">
	<?php if ( $mahan_prev ) : ?>
		<a class="mahan-post-nav__item mahan-post-nav__item--prev" href="<?php echo esc_url( get_permalink( $mahan_prev ) ); ?>">
			<?php mahan_icon_e( 'chevron-right', 20 ); ?>
			<span>
				<small><?php esc_html_e( 'نوشتهٔ قبلی', 'mahan' ); ?></small>
				<strong><?php echo esc_html( get_the_title( $mahan_prev ) ); ?></strong>
			</span>
		</a>
	<?php endif; ?>

	<?php if ( $mahan_next ) : ?>
		<a class="mahan-post-nav__item mahan-post-nav__item--next" href="<?php echo esc_url( get_permalink( $mahan_next ) ); ?>">
			<span>
				<small><?php esc_html_e( 'نوشتهٔ بعدی', 'mahan' ); ?></small>
				<strong><?php echo esc_html( get_the_title( $mahan_next ) ); ?></strong>
			</span>
			<?php mahan_icon_e( 'chevron-left', 20 ); ?>
		</a>
	<?php endif; ?>
</nav>
