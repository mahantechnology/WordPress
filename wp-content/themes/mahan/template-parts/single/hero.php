<?php
/**
 * The header block on a single post.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<header class="mahan-single-hero">
	<div class="mahan-container mahan-single-hero__inner">
		<?php mahan_breadcrumb(); ?>

		<?php if ( mahan_option( 'blog_show_category' ) ) : ?>
			<div class="mahan-single-hero__cats"><?php mahan_post_category_badge(); ?></div>
		<?php endif; ?>

		<h1 class="mahan-single-hero__title"><?php the_title(); ?></h1>

		<?php if ( has_excerpt() ) : ?>
			<p class="mahan-single-hero__excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
		<?php endif; ?>

		<?php mahan_post_meta(); ?>
	</div>
</header>
