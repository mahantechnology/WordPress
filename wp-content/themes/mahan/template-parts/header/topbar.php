<?php
/**
 * The announcement bar above the header.
 *
 * @package Mahan
 *
 * @var array $args Passed from Mahan_Header::topbar(): `text` and `phone`.
 */

defined( 'ABSPATH' ) || exit;

$mahan_text  = isset( $args['text'] ) ? $args['text'] : '';
$mahan_phone = isset( $args['phone'] ) ? $args['phone'] : '';
?>
<div class="mahan-topbar">
	<div class="mahan-container mahan-topbar__inner">
		<?php if ( $mahan_text ) : ?>
			<p class="mahan-topbar__text">
				<?php mahan_icon_e( 'truck', 18 ); ?>
				<span><?php echo esc_html( $mahan_text ); ?></span>
			</p>
		<?php endif; ?>

		<div class="mahan-topbar__side">
			<?php
			if ( has_nav_menu( 'secondary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'secondary',
						'container'      => false,
						'menu_class'     => 'mahan-topbar__menu',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			}
			?>

			<?php if ( $mahan_phone ) : ?>
				<a class="mahan-topbar__phone" href="tel:<?php echo esc_attr( mahan_en_numbers( $mahan_phone ) ); ?>">
					<?php mahan_icon_e( 'phone', 18 ); ?>
					<span><?php echo esc_html( $mahan_phone ); ?></span>
				</a>
			<?php endif; ?>

			<?php Mahan_Header::dark_toggle(); ?>
		</div>
	</div>
</div>
