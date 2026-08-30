<?php
/**
 * The newsletter strip above the footer columns.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-footer__newsletter">
	<div class="mahan-container mahan-footer__newsletter-inner">
		<div class="mahan-footer__newsletter-copy">
			<h2><?php esc_html_e( 'از تازه‌ها باخبر شوید', 'mahan' ); ?></h2>
			<p><?php esc_html_e( 'ایمیل‌تان را بنویسید تا مطالب تازه و پیشنهادهای ویژه را برایتان بفرستیم.', 'mahan' ); ?></p>
		</div>

		<form class="mahan-newsletter__form" data-mahan-newsletter novalidate>
			<label class="screen-reader-text" for="mahan-footer-newsletter"><?php esc_html_e( 'نشانی ایمیل', 'mahan' ); ?></label>
			<input type="email" id="mahan-footer-newsletter" name="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'نشانی ایمیل شما', 'mahan' ); ?>" />
			<button type="submit" class="mahan-btn mahan-btn--contrast">
				<span class="mahan-newsletter__label"><?php esc_html_e( 'عضویت', 'mahan' ); ?></span>
				<span class="mahan-spinner" aria-hidden="true"></span>
			</button>
			<p class="mahan-newsletter__message" role="status" aria-live="polite"></p>
		</form>
	</div>
</div>
