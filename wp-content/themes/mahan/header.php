<?php
/**
 * The document head and the opening markup for every page.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if ( mahan_option( 'preloader' ) ) : ?>
	<div class="mahan-preloader" data-mahan-preloader aria-hidden="true">
		<span class="mahan-preloader__spinner"></span>
	</div>
<?php endif; ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'پرش به محتوای اصلی', 'mahan' ); ?></a>

<?php if ( is_singular( 'post' ) && mahan_option( 'single_progress_bar' ) ) : ?>
	<div class="mahan-read-progress" data-mahan-read-progress aria-hidden="true"><span></span></div>
<?php endif; ?>

<div id="page" class="mahan-site">
	<?php if ( ! Mahan_Header::is_hidden() ) : ?>
		<header id="masthead" class="mahan-header<?php echo mahan_option( 'header_dark' ) ? ' mahan-header--dark' : ''; ?>" data-mahan-header>
			<?php
			/**
			 * Prints the header rows.
			 *
			 * Mahan_Header hooks the topbar and the main row onto this action.
			 */
			do_action( 'mahan_header' );
			?>
		</header>

		<?php
		/**
		 * Prints anything that sits between the header and the content.
		 *
		 * Mahan_Header hooks the page hero onto this action.
		 */
		do_action( 'mahan_after_header' );
		?>
	<?php endif; ?>

	<div id="content" class="mahan-content">
