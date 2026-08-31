<?php
/**
 * Call-to-action footer: a gradient panel above the widget columns.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_columns = max( 1, min( 5, (int) mahan_option( 'footer_columns', 3 ) ) );
$mahan_phone   = mahan_option( 'contact_phone' );
?>
<div class="mahan-footer__main mahan-footer__main--cta">
	<div class="mahan-container">
		<div class="mahan-footer__panel">
			<div class="mahan-footer__panel-copy">
				<h2><?php esc_html_e( 'آمادهٔ شروع هستید؟', 'mahan' ); ?></h2>

				<?php if ( mahan_option( 'footer_about_text' ) ) : ?>
					<p><?php echo esc_html( mahan_option( 'footer_about_text' ) ); ?></p>
				<?php endif; ?>
			</div>

			<?php if ( $mahan_phone ) : ?>
				<a class="mahan-btn mahan-btn--contrast mahan-footer__panel-btn" href="tel:<?php echo esc_attr( mahan_en_numbers( $mahan_phone ) ); ?>">
					<?php mahan_icon_e( 'phone', 18 ); ?>
					<span><?php echo esc_html( mahan_fa_numbers( $mahan_phone ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>

		<div class="mahan-footer__grid mahan-footer__grid--<?php echo (int) $mahan_columns; ?> mahan-footer__grid--cta">
			<div class="mahan-footer__about">
				<?php mahan_site_logo( array( 'class' => 'mahan-logo--footer' ) ); ?>
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
</div>
