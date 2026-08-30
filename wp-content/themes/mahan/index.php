<?php
/**
 * The archive fallback template.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$layout   = mahan_option( 'blog_layout', 'grid' );
$columns  = (int) mahan_option( 'blog_columns', 3 );
$position = mahan_current_sidebar_position();
?>

<div class="mahan-container mahan-layout mahan-layout--sidebar-<?php echo esc_attr( $position ); ?>">
	<main id="main" class="mahan-layout__main">
		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() && 'magazine' === $layout ) : ?>
				<?php get_template_part( 'template-parts/archive/featured' ); ?>
			<?php endif; ?>

			<div class="mahan-archive mahan-archive--<?php echo esc_attr( $layout ); ?> <?php echo 'list' === $layout ? '' : 'mahan-grid mahan-grid--' . (int) $columns; ?>"
				data-mahan-archive
				data-post-type="<?php echo esc_attr( get_post_type() ? get_post_type() : 'post' ); ?>"
				data-style="<?php echo esc_attr( $layout ); ?>">
				<?php
				while ( have_posts() ) :
					the_post();
					mahan_render_post_card( array( 'style' => $layout ) );
				endwhile;
				?>
			</div>

			<?php get_template_part( 'template-parts/archive/pagination' ); ?>

		<?php else : ?>
			<?php mahan_no_results(); ?>
		<?php endif; ?>
	</main>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
