<?php
/**
 * Archive pagination, in whichever style the options ask for.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_style = mahan_option( 'blog_pagination', 'numbers' );

if ( 'numbers' === $mahan_style ) {
	mahan_pagination();
	return;
}

if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
	return;
}

$mahan_term     = get_queried_object();
$mahan_term_id  = $mahan_term instanceof WP_Term ? $mahan_term->term_id : 0;
$mahan_taxonomy = $mahan_term instanceof WP_Term ? $mahan_term->taxonomy : '';
?>
<div class="mahan-loadmore"
	data-mahan-loadmore="<?php echo esc_attr( $mahan_style ); ?>"
	data-page="<?php echo esc_attr( max( 1, get_query_var( 'paged' ) ) ); ?>"
	data-max="<?php echo esc_attr( $GLOBALS['wp_query']->max_num_pages ); ?>"
	data-per-page="<?php echo esc_attr( get_query_var( 'posts_per_page' ) ); ?>"
	data-post-type="<?php echo esc_attr( get_post_type() ? get_post_type() : 'post' ); ?>"
	data-style="<?php echo esc_attr( mahan_option( 'blog_layout', 'grid' ) ); ?>"
	data-term="<?php echo esc_attr( $mahan_term_id ); ?>"
	data-taxonomy="<?php echo esc_attr( $mahan_taxonomy ); ?>">
	<button type="button" class="mahan-btn mahan-btn--outline mahan-btn--lg" data-mahan-loadmore-button>
		<span class="mahan-loadmore__label"><?php esc_html_e( 'نمایش بیشتر', 'mahan' ); ?></span>
		<span class="mahan-spinner" aria-hidden="true"></span>
	</button>
</div>
