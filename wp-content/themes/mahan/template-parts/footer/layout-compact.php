<?php
/**
 * Compact footer: brand on one side, footer menu on the other.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-footer__main mahan-footer__main--compact">
	<div class="mahan-container mahan-footer__compact">
		<div class="mahan-footer__about">
			<?php mahan_site_logo( array( 'class' => 'mahan-logo--footer' ) ); ?>
			<?php if ( mahan_option( 'footer_about_text' ) ) : ?>
				<p class="mahan-footer__text"><?php echo esc_html( mahan_option( 'footer_about_text' ) ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="mahan-footer__nav" aria-label="<?php esc_attr_e( 'منوی فوتر', 'mahan' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'mahan-footer__menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>

		<?php mahan_social_links( 'mahan-social--footer' ); ?>
	</div>
</div>
