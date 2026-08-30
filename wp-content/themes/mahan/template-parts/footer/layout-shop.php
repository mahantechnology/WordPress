<?php
/**
 * Shop footer: widget columns plus a help row and trust badges.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_columns = max( 1, min( 6, (int) mahan_option( 'footer_columns', 4 ) ) );

$mahan_badges = array(
	array( 'truck', __( 'ارسال سریع', 'mahan' ), __( 'به سراسر کشور', 'mahan' ) ),
	array( 'shield', __( 'ضمانت اصالت', 'mahan' ), __( 'کالای اورجینال', 'mahan' ) ),
	array( 'refresh', __( 'بازگشت کالا', 'mahan' ), __( 'تا هفت روز', 'mahan' ) ),
	array( 'headphones', __( 'پشتیبانی', 'mahan' ), __( 'همه‌روزه', 'mahan' ) ),
);
?>
<?php if ( mahan_option( 'footer_badges' ) ) : ?>
	<div class="mahan-footer__badges">
		<div class="mahan-container mahan-trust">
			<?php foreach ( $mahan_badges as $mahan_badge ) : ?>
				<div class="mahan-trust__item">
					<?php mahan_icon_e( $mahan_badge[0], 28 ); ?>
					<div class="mahan-trust__body">
						<strong><?php echo esc_html( $mahan_badge[1] ); ?></strong>
						<span><?php echo esc_html( $mahan_badge[2] ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<div class="mahan-footer__main">
	<div class="mahan-container mahan-footer__grid mahan-footer__grid--<?php echo (int) $mahan_columns; ?>">
		<div class="mahan-footer__about">
			<?php mahan_site_logo( array( 'class' => 'mahan-logo--footer' ) ); ?>

			<?php if ( mahan_option( 'footer_about_text' ) ) : ?>
				<p class="mahan-footer__text"><?php echo esc_html( mahan_option( 'footer_about_text' ) ); ?></p>
			<?php endif; ?>

			<?php if ( mahan_option( 'contact_phone' ) ) : ?>
				<a class="mahan-footer__phone" href="tel:<?php echo esc_attr( mahan_en_numbers( mahan_option( 'contact_phone' ) ) ); ?>">
					<?php mahan_icon_e( 'headphones', 22 ); ?>
					<span>
						<small><?php esc_html_e( 'پشتیبانی تلفنی', 'mahan' ); ?></small>
						<strong><?php echo esc_html( mahan_option( 'contact_phone' ) ); ?></strong>
					</span>
				</a>
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
