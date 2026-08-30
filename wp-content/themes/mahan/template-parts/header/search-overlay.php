<?php
/**
 * The full-screen search panel opened from the header.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-search-overlay" data-mahan-panel="search" hidden>
	<div class="mahan-search-overlay__backdrop" data-mahan-close></div>

	<div class="mahan-search-overlay__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'جستجو', 'mahan' ); ?>">
		<button type="button" class="mahan-search-overlay__close" data-mahan-close aria-label="<?php esc_attr_e( 'بستن', 'mahan' ); ?>">
			<?php mahan_icon_e( 'close', 26 ); ?>
		</button>

		<form role="search" method="get" class="mahan-search mahan-search--lg" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-mahan-live-search>
			<label class="screen-reader-text" for="mahan-overlay-search"><?php esc_html_e( 'جستجو', 'mahan' ); ?></label>
			<?php mahan_icon_e( 'search', 24, 'mahan-search__icon' ); ?>
			<input
				type="search"
				id="mahan-overlay-search"
				name="s"
				placeholder="<?php esc_attr_e( 'دنبال چه چیزی می‌گردید؟', 'mahan' ); ?>"
				autocomplete="off"
				data-mahan-search-focus
			/>
			<button type="submit" class="mahan-btn mahan-btn--primary"><?php esc_html_e( 'جستجو', 'mahan' ); ?></button>
			<div class="mahan-search__results" data-mahan-search-results hidden></div>
		</form>

		<?php
		$mahan_terms = get_terms(
			array(
				'taxonomy'   => 'post_tag',
				'orderby'    => 'count',
				'order'      => 'DESC',
				'number'     => 8,
				'hide_empty' => true,
			)
		);

		if ( $mahan_terms && ! is_wp_error( $mahan_terms ) ) :
			?>
			<div class="mahan-search-overlay__suggestions">
				<span><?php esc_html_e( 'جستجوهای پرتکرار:', 'mahan' ); ?></span>
				<?php foreach ( $mahan_terms as $mahan_term ) : ?>
					<a href="<?php echo esc_url( get_term_link( $mahan_term ) ); ?>"><?php echo esc_html( $mahan_term->name ); ?></a>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
