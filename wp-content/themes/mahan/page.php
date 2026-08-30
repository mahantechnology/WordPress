<?php
/**
 * Single page.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$position = mahan_current_sidebar_position();
?>

<div class="mahan-container mahan-layout mahan-layout--sidebar-<?php echo esc_attr( $position ); ?>">
	<main id="main" class="mahan-layout__main">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( 'mahan-page' ); ?>>
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
			</article>

			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
		?>
	</main>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
