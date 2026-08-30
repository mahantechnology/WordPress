<?php
/**
 * Related posts, matched on shared categories.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_count = max( 2, (int) mahan_option( 'single_related_count', 3 ) );
$mahan_terms = wp_get_post_categories( get_the_ID() );

$mahan_args = array(
	'post_type'           => get_post_type(),
	'posts_per_page'      => $mahan_count,
	'post__not_in'        => array( get_the_ID() ),
	'ignore_sticky_posts' => true,
	'no_found_rows'       => true,
	'orderby'             => 'rand',
);

if ( $mahan_terms ) {
	$mahan_args['category__in'] = $mahan_terms;
}

$mahan_related = new WP_Query( $mahan_args );

if ( ! $mahan_related->have_posts() ) {
	return;
}
?>
<section class="mahan-related">
	<?php
	mahan_section_title(
		array(
			'title'     => __( 'نوشته‌های مرتبط', 'mahan' ),
			'highlight' => 1,
			'align'     => 'right',
			'tag'       => 'h2',
		)
	);
	?>

	<div class="mahan-grid mahan-grid--<?php echo (int) min( 4, $mahan_count ); ?>">
		<?php
		while ( $mahan_related->have_posts() ) :
			$mahan_related->the_post();
			mahan_render_post_card(
				array(
					'show_excerpt' => false,
					'show_more'    => false,
				)
			);
		endwhile;
		?>
	</div>
</section>
<?php
wp_reset_postdata();
