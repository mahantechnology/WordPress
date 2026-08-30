<?php
/**
 * Minimal header: logo and a burger, nothing else.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-header__main mahan-header__main--minimal">
	<div class="mahan-container mahan-header__inner">
		<?php mahan_site_logo(); ?>

		<nav class="mahan-header__nav mahan-header__nav--compact" aria-label="<?php esc_attr_e( 'منوی اصلی', 'mahan' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'mahan-menu',
					'depth'          => 2,
					'walker'         => new Mahan_Nav_Walker(),
					'fallback_cb'    => 'mahan_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="mahan-header__actions mahan-header__actions--minimal">
			<?php Mahan_Header::dark_toggle(); ?>
			<button type="button" class="mahan-header__action mahan-header__burger" data-mahan-open="drawer" aria-label="<?php esc_attr_e( 'منو', 'mahan' ); ?>" aria-expanded="false">
				<?php mahan_icon_e( 'menu', 24 ); ?>
			</button>
		</div>
	</div>
</div>
