<?php
/**
 * The search form.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_search_id = 'mahan-search-' . wp_unique_id();
?>
<form role="search" method="get" class="mahan-search mahan-search--md" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $mahan_search_id ); ?>">
		<?php esc_html_e( 'جستجو در سایت', 'mahan' ); ?>
	</label>
	<?php mahan_icon_e( 'search', 20, 'mahan-search__icon' ); ?>
	<input
		type="search"
		id="<?php echo esc_attr( $mahan_search_id ); ?>"
		name="s"
		value="<?php echo esc_attr( get_search_query() ); ?>"
		placeholder="<?php esc_attr_e( 'دنبال چه چیزی می‌گردید؟', 'mahan' ); ?>"
	/>
	<button type="submit" class="mahan-btn mahan-btn--primary">
		<?php esc_html_e( 'جستجو', 'mahan' ); ?>
	</button>
</form>
