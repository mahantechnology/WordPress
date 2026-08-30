<?php
/**
 * Centred header: logo on its own row above a centred menu.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-header__main mahan-header__main--centered">
	<div class="mahan-container mahan-header__inner">
		<div class="mahan-header__row mahan-header__row--brand">
			<span class="mahan-header__spacer"></span>
			<?php mahan_site_logo( array( 'class' => 'mahan-logo--lg' ) ); ?>
			<?php Mahan_Header::actions(); ?>
		</div>

		<nav class="mahan-header__nav mahan-header__nav--centered" aria-label="<?php esc_attr_e( 'منوی اصلی', 'mahan' ); ?>">
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
	</div>
</div>
