<?php
/**
 * Shop header: big search field between the logo and the cart.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mahan-header__main mahan-header__main--shop">
	<div class="mahan-container mahan-header__inner">
		<div class="mahan-header__row mahan-header__row--brand">
			<?php mahan_site_logo(); ?>

			<div class="mahan-header__search mahan-header__search--wide">
				<form role="search" method="get" class="mahan-search mahan-search--md" action="<?php echo esc_url( home_url( '/' ) ); ?>" data-mahan-live-search>
					<label class="screen-reader-text" for="mahan-header-search"><?php esc_html_e( 'جستجوی محصول', 'mahan' ); ?></label>
					<?php mahan_icon_e( 'search', 20, 'mahan-search__icon' ); ?>
					<input
						type="search"
						id="mahan-header-search"
						name="s"
						value="<?php echo esc_attr( get_search_query() ); ?>"
						placeholder="<?php esc_attr_e( 'نام کالا، برند یا دسته‌بندی…', 'mahan' ); ?>"
						autocomplete="off"
					/>
					<?php if ( mahan_has_woocommerce() ) : ?>
						<input type="hidden" name="post_type" value="product" />
					<?php endif; ?>
					<button type="submit" class="mahan-btn mahan-btn--primary"><?php esc_html_e( 'جستجو', 'mahan' ); ?></button>
					<div class="mahan-search__results" data-mahan-search-results hidden></div>
				</form>
			</div>

			<?php Mahan_Header::actions(); ?>
		</div>
	</div>

	<div class="mahan-header__bar">
		<div class="mahan-container mahan-header__bar-inner">
			<?php if ( has_nav_menu( 'categories' ) ) : ?>
				<button type="button" class="mahan-header__cats" data-mahan-toggle="categories" aria-expanded="false">
					<?php mahan_icon_e( 'grid', 20 ); ?>
					<span><?php esc_html_e( 'دسته‌بندی کالاها', 'mahan' ); ?></span>
					<?php mahan_icon_e( 'chevron-down', 16 ); ?>
				</button>
				<div class="mahan-header__cats-panel" data-mahan-panel="categories" hidden>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'categories',
							'container'      => false,
							'menu_class'     => 'mahan-menu mahan-menu--vertical',
							'walker'         => new Mahan_Nav_Walker(),
							'fallback_cb'    => false,
						)
					);
					?>
				</div>
			<?php endif; ?>

			<nav class="mahan-header__nav" aria-label="<?php esc_attr_e( 'منوی اصلی', 'mahan' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'mahan-menu',
						'walker'         => new Mahan_Nav_Walker(),
						'fallback_cb'    => 'mahan_menu_fallback',
					)
				);
				?>
			</nav>

			<?php if ( mahan_option( 'topbar_phone' ) ) : ?>
				<a class="mahan-header__phone" href="tel:<?php echo esc_attr( mahan_en_numbers( mahan_option( 'topbar_phone' ) ) ); ?>">
					<?php mahan_icon_e( 'headphones', 20 ); ?>
					<span><?php echo esc_html( mahan_option( 'topbar_phone' ) ); ?></span>
				</a>
			<?php endif; ?>
		</div>
	</div>
</div>
