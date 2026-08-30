<?php
/**
 * The 404 template.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<div class="mahan-container">
	<main id="main" class="mahan-404">
		<span class="mahan-404__code">۴۰۴</span>
		<h1 class="mahan-404__title"><?php esc_html_e( 'این صفحه پیدا نشد', 'mahan' ); ?></h1>
		<p class="mahan-404__text">
			<?php esc_html_e( 'شاید نشانی را اشتباه وارد کرده‌اید یا این صفحه جابه‌جا شده است. می‌توانید جستجو کنید یا به خانه برگردید.', 'mahan' ); ?>
		</p>

		<?php get_search_form(); ?>

		<div class="mahan-404__actions">
			<a class="mahan-btn mahan-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'بازگشت به خانه', 'mahan' ); ?>
			</a>
			<?php if ( mahan_has_woocommerce() && wc_get_page_id( 'shop' ) > 0 ) : ?>
				<a class="mahan-btn mahan-btn--outline" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
					<?php esc_html_e( 'رفتن به فروشگاه', 'mahan' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<?php
		$recent = new WP_Query(
			array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'no_found_rows'  => true,
			)
		);

		if ( $recent->have_posts() ) :
			?>
			<section class="mahan-404__suggestions">
				<h2><?php esc_html_e( 'شاید این‌ها به کارتان بیاید', 'mahan' ); ?></h2>
				<div class="mahan-grid mahan-grid--3">
					<?php
					while ( $recent->have_posts() ) :
						$recent->the_post();
						mahan_render_post_card(
							array(
								'show_excerpt' => false,
								'show_more'    => false,
							)
						);
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</section>
		<?php endif; ?>
	</main>
</div>

<?php
get_footer();
