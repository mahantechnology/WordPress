<?php
/**
 * The copyright row at the very bottom.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-footer__bottom">
	<div class="mahan-container mahan-footer__bottom-inner">
		<p class="mahan-footer__copyright"><?php echo wp_kses_post( Mahan_Footer::copyright() ); ?></p>

		<?php if ( has_nav_menu( 'footer_help' ) ) : ?>
			<nav class="mahan-footer__legal" aria-label="<?php esc_attr_e( 'پیوندهای راهنما', 'mahan' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer_help',
						'container'      => false,
						'menu_class'     => 'mahan-footer__legal-menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php endif; ?>
	</div>
</div>
