<?php
/**
 * The comment list and form.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) {
	return;
}
?>

<section id="comments" class="mahan-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="mahan-comments__title">
			<?php mahan_icon_e( 'comment', 22 ); ?>
			<?php
			printf(
				/* translators: %s: comment count. */
				esc_html( _n( '%s دیدگاه', '%s دیدگاه', get_comments_number(), 'mahan' ) ),
				esc_html( mahan_fa_numbers( number_format_i18n( get_comments_number() ) ) )
			);
			?>
		</h2>

		<ol class="mahan-comments__list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'avatar_size' => 56,
					'short_ping'  => true,
					'callback'    => 'mahan_comment_callback',
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => mahan_icon( 'chevron-right', 18 ),
				'next_text' => mahan_icon( 'chevron-left', 18 ),
			)
		);
		?>

		<?php if ( ! comments_open() ) : ?>
			<p class="mahan-comments__closed"><?php esc_html_e( 'دیدگاه‌ها بسته شده است.', 'mahan' ); ?></p>
		<?php endif; ?>
	<?php endif; ?>

	<?php comment_form(); ?>
</section>
