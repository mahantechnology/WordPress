<?php
/**
 * Split header: brand row on top, menu row underneath.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-header__main mahan-header__main--split">
	<div class="mahan-container mahan-header__inner">
		<div class="mahan-header__row mahan-header__row--brand">
			<?php mahan_site_logo(); ?>

			<?php if ( mahan_option( 'header_search' ) ) : ?>
				<div class="mahan-header__search">
					<?php get_search_form(); ?>
				</div>
			<?php endif; ?>

			<?php Mahan_Header::actions(); ?>
		</div>
	</div>

	<div class="mahan-header__bar">
		<div class="mahan-container">
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
		</div>
	</div>
</div>
