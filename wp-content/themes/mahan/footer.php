<?php
/**
 * Closes the page and prints the footer.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
	</div><!-- #content -->

	<?php if ( ! Mahan_Footer::is_hidden() ) : ?>
		<footer id="colophon" class="mahan-footer<?php echo mahan_option( 'footer_dark' ) ? ' mahan-footer--dark' : ''; ?>">
			<?php
			/**
			 * Prints the footer rows.
			 *
			 * Mahan_Footer hooks the newsletter, widget columns and copyright here.
			 */
			do_action( 'mahan_footer' );
			?>
		</footer>
	<?php endif; ?>
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>
