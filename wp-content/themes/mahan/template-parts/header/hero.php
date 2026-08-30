<?php
/**
 * The page hero: title plus breadcrumb.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_subtitle = is_singular() ? get_post_meta( get_the_ID(), '_mahan_hero_subtitle', true ) : '';

if ( ! $mahan_subtitle && ( is_category() || is_tag() || is_tax() ) ) {
	$mahan_subtitle = wp_strip_all_tags( term_description() );
}
?>
<section class="mahan-page-hero">
	<span class="mahan-page-hero__glow" role="presentation"></span>
	<div class="mahan-container mahan-page-hero__inner">
		<?php mahan_breadcrumb(); ?>

		<h1 class="mahan-page-hero__title"><?php echo esc_html( Mahan_Header::hero_title() ); ?></h1>

		<?php if ( $mahan_subtitle ) : ?>
			<p class="mahan-page-hero__subtitle"><?php echo esc_html( wp_trim_words( $mahan_subtitle, 34, '…' ) ); ?></p>
		<?php endif; ?>
	</div>
</section>
