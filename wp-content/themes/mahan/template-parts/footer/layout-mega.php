<?php
/**
 * Mega footer: brand column, widget columns and a contact card side by side.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_columns = max( 1, min( 4, (int) mahan_option( 'footer_columns', 4 ) ) );
$mahan_phone   = mahan_option( 'contact_phone' );
$mahan_email   = mahan_option( 'contact_email' );
$mahan_address = mahan_option( 'contact_address' );
?>
<div class="mahan-footer__main mahan-footer__main--mega">
	<div class="mahan-container mahan-footer__mega">
		<div class="mahan-footer__about mahan-footer__about--mega">
			<?php mahan_site_logo( array( 'class' => 'mahan-logo--footer' ) ); ?>

			<?php if ( mahan_option( 'footer_about_text' ) ) : ?>
				<p class="mahan-footer__text"><?php echo esc_html( mahan_option( 'footer_about_text' ) ); ?></p>
			<?php endif; ?>

			<?php mahan_social_links( 'mahan-social--footer mahan-social--brand' ); ?>
		</div>

		<div class="mahan-footer__mega-cols mahan-footer__grid--<?php echo (int) $mahan_columns; ?>">
			<?php for ( $mahan_i = 1; $mahan_i <= $mahan_columns; $mahan_i++ ) : ?>
				<?php if ( is_active_sidebar( 'footer-' . $mahan_i ) ) : ?>
					<div class="mahan-footer__col">
						<?php dynamic_sidebar( 'footer-' . $mahan_i ); ?>
					</div>
				<?php endif; ?>
			<?php endfor; ?>
		</div>

		<?php if ( $mahan_phone || $mahan_email || $mahan_address ) : ?>
			<aside class="mahan-footer__card">
				<h3 class="mahan-footer__card-title"><?php esc_html_e( 'در تماس باشیم', 'mahan' ); ?></h3>

				<ul class="mahan-footer__card-list">
					<?php if ( $mahan_phone ) : ?>
						<li>
							<span aria-hidden="true"><?php mahan_icon_e( 'phone', 18 ); ?></span>
							<a href="tel:<?php echo esc_attr( mahan_en_numbers( $mahan_phone ) ); ?>"><?php echo esc_html( mahan_fa_numbers( $mahan_phone ) ); ?></a>
						</li>
					<?php endif; ?>

					<?php if ( $mahan_email ) : ?>
						<li>
							<span aria-hidden="true"><?php mahan_icon_e( 'mail', 18 ); ?></span>
							<a href="mailto:<?php echo esc_attr( $mahan_email ); ?>"><?php echo esc_html( $mahan_email ); ?></a>
						</li>
					<?php endif; ?>

					<?php if ( $mahan_address ) : ?>
						<li>
							<span aria-hidden="true"><?php mahan_icon_e( 'map-pin', 18 ); ?></span>
							<span><?php echo esc_html( $mahan_address ); ?></span>
						</li>
					<?php endif; ?>
				</ul>
			</aside>
		<?php endif; ?>
	</div>
</div>
