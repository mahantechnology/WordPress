<?php
/**
 * Gradient header: a brand-coloured band with light text and a call to action.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-header__main mahan-header__main--gradient">
	<div class="mahan-container mahan-header__inner">
		<div class="mahan-header__start">
			<?php mahan_site_logo(); ?>
		</div>

		<nav class="mahan-header__nav" aria-label="<?php esc_attr_e( 'منوی اصلی', 'mahan' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'menu_class'     => 'mahan-menu',
					'walker'         => new Mahan_Nav_Walker(),
					'fallback_cb'    => 'mahan_menu_fallback',
				)
			);
			?>
		</nav>

		<?php Mahan_Header::actions(); ?>
	</div>
</div>
