<?php
/**
 * The off-canvas navigation shown on small screens.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_menu = has_nav_menu( 'mobile' ) ? 'mobile' : 'primary';
?>
<div class="mahan-drawer mahan-drawer--<?php echo esc_attr( mahan_option( 'mobile_menu_style', 'drawer' ) ); ?>" data-mahan-panel="drawer" hidden>
	<div class="mahan-drawer__backdrop" data-mahan-close></div>

	<div class="mahan-drawer__panel" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'منوی موبایل', 'mahan' ); ?>">
		<div class="mahan-drawer__head">
			<?php mahan_site_logo( array( 'class' => 'mahan-logo--sm' ) ); ?>
			<button type="button" class="mahan-drawer__close" data-mahan-close aria-label="<?php esc_attr_e( 'بستن منو', 'mahan' ); ?>">
				<?php mahan_icon_e( 'close', 24 ); ?>
			</button>
		</div>

		<?php if ( mahan_option( 'header_search' ) ) : ?>
			<div class="mahan-drawer__search"><?php get_search_form(); ?></div>
		<?php endif; ?>

		<nav class="mahan-drawer__nav" aria-label="<?php esc_attr_e( 'منوی موبایل', 'mahan' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => $mahan_menu,
					'container'      => false,
					'menu_class'     => 'mahan-menu mahan-menu--drawer',
					'walker'         => new Mahan_Nav_Walker(),
					'fallback_cb'    => 'mahan_menu_fallback',
				)
			);
			?>
		</nav>

		<div class="mahan-drawer__foot">
			<?php Mahan_Header::dark_toggle(); ?>
			<?php mahan_social_links( 'mahan-social--drawer' ); ?>

			<?php if ( mahan_option( 'contact_phone' ) ) : ?>
				<a class="mahan-drawer__phone" href="tel:<?php echo esc_attr( mahan_en_numbers( mahan_option( 'contact_phone' ) ) ); ?>">
					<?php mahan_icon_e( 'phone', 20 ); ?>
					<span><?php echo esc_html( mahan_option( 'contact_phone' ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
