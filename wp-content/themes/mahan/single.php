<?php
/**
 * Single post.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$mahan_layout   = mahan_option( 'single_layout', 'sidebar' );
$mahan_position = 'sidebar' === $mahan_layout ? mahan_current_sidebar_position() : 'none';
?>

<?php get_template_part( 'template-parts/single/hero' ); ?>

<div class="mahan-container mahan-layout mahan-layout--sidebar-<?php echo esc_attr( $mahan_position ); ?> mahan-single mahan-single--<?php echo esc_attr( $mahan_layout ); ?>">
	<main id="main" class="mahan-layout__main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'mahan-post' ); ?>>
				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="mahan-post__thumb">
						<?php the_post_thumbnail( 'mahan-hero', array( 'loading' => 'eager' ) ); ?>
						<?php if ( get_the_post_thumbnail_caption() ) : ?>
							<figcaption><?php echo esc_html( get_the_post_thumbnail_caption() ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endif; ?>

				<div class="mahan-entry">
					<?php
					the_content();

					wp_link_pages(
						array(
							'before' => '<div class="mahan-page-links">' . esc_html__( 'صفحه‌ها:', 'mahan' ),
							'after'  => '</div>',
						)
					);
					?>
				</div>

				<footer class="mahan-post__footer">
					<?php mahan_post_tags(); ?>
					<?php
					if ( mahan_option( 'single_share' ) ) {
						mahan_share_buttons();
					}
					?>
				</footer>
			</article>

			<?php
			if ( mahan_option( 'single_author_box' ) ) {
				get_template_part( 'template-parts/single/author' );
			}

			if ( mahan_option( 'single_prev_next' ) ) {
				get_template_part( 'template-parts/single/prev-next' );
			}

			if ( mahan_option( 'single_related' ) ) {
				get_template_part( 'template-parts/single/related' );
			}

			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</main>

	<?php
	if ( 'none' !== $mahan_position ) {
		get_sidebar();
	}
	?>
</div>

<?php
get_footer();
