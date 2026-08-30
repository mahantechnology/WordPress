<?php
/**
 * Search results.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

get_header();

$position = mahan_current_sidebar_position();
?>

<div class="mahan-container mahan-layout mahan-layout--sidebar-<?php echo esc_attr( $position ); ?>">
	<main id="main" class="mahan-layout__main">
		<?php if ( have_posts() ) : ?>
			<p class="mahan-search-summary">
				<?php
				printf(
					/* translators: 1: result count, 2: search term. */
					esc_html__( '%1$s نتیجه برای «%2$s» پیدا شد.', 'mahan' ),
					esc_html( mahan_fa_numbers( number_format_i18n( $GLOBALS['wp_query']->found_posts ) ) ),
					esc_html( get_search_query() )
				);
				?>
			</p>

			<div class="mahan-archive mahan-archive--list">
				<?php
				while ( have_posts() ) :
					the_post();
					mahan_render_post_card( array( 'style' => 'list' ) );
				endwhile;
				?>
			</div>

			<?php mahan_pagination(); ?>
		<?php else : ?>
			<?php mahan_no_results(); ?>
		<?php endif; ?>
	</main>

	<?php get_sidebar(); ?>
</div>

<?php
get_footer();
