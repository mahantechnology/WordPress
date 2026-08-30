<?php
/**
 * Footer with an about column plus the registered widget columns.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_columns = max( 1, min( 6, (int) mahan_option( 'footer_columns', 4 ) ) );
?>
<div class="mahan-footer__main">
	<div class="mahan-container mahan-footer__grid mahan-footer__grid--<?php echo (int) $mahan_columns; ?>">
		<div class="mahan-footer__about">
			<?php mahan_site_logo( array( 'class' => 'mahan-logo--footer' ) ); ?>

			<?php if ( mahan_option( 'footer_about_text' ) ) : ?>
				<p class="mahan-footer__text"><?php echo esc_html( mahan_option( 'footer_about_text' ) ); ?></p>
			<?php endif; ?>

			<?php mahan_social_links( 'mahan-social--footer' ); ?>
		</div>

		<?php for ( $mahan_i = 1; $mahan_i <= $mahan_columns; $mahan_i++ ) : ?>
			<?php if ( is_active_sidebar( 'footer-' . $mahan_i ) ) : ?>
				<div class="mahan-footer__col">
					<?php dynamic_sidebar( 'footer-' . $mahan_i ); ?>
				</div>
			<?php endif; ?>
		<?php endfor; ?>
	</div>
</div>
