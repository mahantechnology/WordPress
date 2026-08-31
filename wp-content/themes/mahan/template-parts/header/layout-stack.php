<?php
/**
 * Stacked header: centred brand above a full-width menu bar.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-header__main mahan-header__main--stack">
	<div class="mahan-container mahan-header__stack-top">
		<div class="mahan-header__stack-side">
			<?php Mahan_Header::dark_toggle(); ?>

			<?php if ( mahan_option( 'header_search' ) ) : ?>
				<button type="button" class="mahan-header__action" data-mahan-open="search" aria-label="<?php esc_attr_e( 'جستجو', 'mahan' ); ?>">
					<?php mahan_icon_e( 'search', 22 ); ?>
				</button>
			<?php endif; ?>
		</div>

		<?php mahan_site_logo( array( 'class' => 'mahan-logo--stack' ) ); ?>

		<div class="mahan-header__stack-side mahan-header__stack-side--end">
			<?php Mahan_Header::actions(); ?>
		</div>
	</div>

	<div class="mahan-header__bar mahan-header__bar--stack">
		<div class="mahan-container">
			<nav class="mahan-header__nav" aria-label="<?php esc_attr_e( 'منوی اصلی', 'mahan' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'mahan-menu mahan-menu--center',
						'walker'         => new Mahan_Nav_Walker(),
						'fallback_cb'    => 'mahan_menu_fallback',
					)
				);
				?>
			</nav>
		</div>
	</div>
</div>
