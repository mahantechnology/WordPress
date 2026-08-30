<?php
/**
 * The author box under a single post.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_author_id  = (int) get_the_author_meta( 'ID' );
$mahan_author_bio = get_the_author_meta( 'description' );
?>
<aside class="mahan-author-box">
	<?php echo get_avatar( $mahan_author_id, 96, '', '', array( 'class' => 'mahan-author-box__avatar' ) ); ?>

	<div class="mahan-author-box__body">
		<span class="mahan-author-box__label"><?php esc_html_e( 'نویسنده', 'mahan' ); ?></span>
		<h3 class="mahan-author-box__name">
			<a href="<?php echo esc_url( get_author_posts_url( $mahan_author_id ) ); ?>"><?php the_author(); ?></a>
		</h3>

		<?php if ( $mahan_author_bio ) : ?>
			<p class="mahan-author-box__bio"><?php echo esc_html( $mahan_author_bio ); ?></p>
		<?php endif; ?>

		<span class="mahan-author-box__count">
			<?php
			printf(
				/* translators: %s: post count. */
				esc_html__( '%s نوشته منتشر کرده است', 'mahan' ),
				esc_html( mahan_fa_numbers( number_format_i18n( count_user_posts( $mahan_author_id, 'post' ) ) ) )
			);
			?>
		</span>
	</div>
</aside>
