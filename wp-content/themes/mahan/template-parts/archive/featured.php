<?php
/**
 * The magazine-style featured strip at the top of the blog archive.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_featured = new WP_Query(
	array(
		'post_type'      => 'post',
		'posts_per_page' => 4,
		'no_found_rows'  => true,
		'post__in'       => get_option( 'sticky_posts' ) ? get_option( 'sticky_posts' ) : null,
		'orderby'        => 'date',
	)
);

if ( ! $mahan_featured->have_posts() ) {
	$mahan_featured = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 4,
			'no_found_rows'  => true,
		)
	);
}

if ( ! $mahan_featured->have_posts() ) {
	return;
}

$mahan_index = 0;
?>
<section class="mahan-featured">
	<?php
	while ( $mahan_featured->have_posts() ) :
		$mahan_featured->the_post();
		++$mahan_index;
		?>
		<article <?php post_class( 1 === $mahan_index ? 'mahan-featured__lead' : 'mahan-featured__item' ); ?>>
			<a class="mahan-featured__link" href="<?php the_permalink(); ?>">
				<span class="mahan-featured__media">
					<?php echo mahan_thumbnail( get_the_ID(), 1 === $mahan_index ? 'mahan-wide' : 'mahan-card' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core image markup. ?>
				</span>
				<span class="mahan-featured__body">
					<?php mahan_post_category_badge(); ?>
					<span class="mahan-featured__title"><?php the_title(); ?></span>
					<span class="mahan-featured__date"><?php echo esc_html( mahan_time_ago() ); ?></span>
				</span>
			</a>
		</article>
	<?php endwhile; ?>
</section>
<?php
wp_reset_postdata();
