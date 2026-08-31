<?php
/**
 * Minimal footer: one quiet row with the brand, the menu and the socials.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-footer__main mahan-footer__main--minimal">
	<div class="mahan-container mahan-footer__minimal">
		<?php mahan_site_logo( array( 'class' => 'mahan-logo--footer' ) ); ?>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="mahan-footer__nav" aria-label="<?php esc_attr_e( 'منوی فوتر', 'mahan' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'mahan-footer__menu',
						'depth'          => 1,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<?php mahan_social_links( 'mahan-social--footer' ); ?>
	</div>
</div>
