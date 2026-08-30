<?php
/**
 * The sidebar, printed only when the current view asks for one.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

if ( ! mahan_has_sidebar() ) {
	return;
}
?>
<aside id="secondary" class="mahan-sidebar mahan-layout__sidebar"<?php echo mahan_option( 'sticky_sidebar' ) ? ' data-mahan-sticky' : ''; ?>>
	<div class="mahan-sidebar__inner">
		<?php dynamic_sidebar( mahan_current_sidebar_id() ); ?>
	</div>
</aside>
