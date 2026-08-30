<?php
/**
 * The element catalogue, grouped and searchable.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_catalog = Mahan_Elements_Catalog::all();
$mahan_has_el  = mahan_has_elementor();
?>

<section class="mahan-panel__section-head">
	<div>
		<h1><?php esc_html_e( 'المان‌های ماهان', 'mahan' ); ?></h1>
		<p>
			<?php
			printf(
				/* translators: %s: element count. */
				esc_html__( '%s المان اختصاصی، در دو دستهٔ «المان‌های ماهان» و «فروشگاه ماهان» داخل ویرایشگر المنتور.', 'mahan' ),
				esc_html( mahan_fa_numbers( Mahan_Elements_Catalog::count() ) )
			);
			?>
		</p>
	</div>

	<?php if ( $mahan_has_el ) : ?>
		<a class="mahan-panel__btn" href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>">
			<?php mahan_icon_e( 'pen', 16 ); ?>
			<span><?php esc_html_e( 'ویرایش برگه‌ها', 'mahan' ); ?></span>
		</a>
	<?php endif; ?>
</section>

<?php if ( ! $mahan_has_el ) : ?>
	<div class="mahan-panel__notice mahan-panel__notice--info">
		<?php mahan_icon_e( 'sparkles', 20 ); ?>
		<span>
			<?php esc_html_e( 'برای استفاده از این المان‌ها باید افزونهٔ المنتور نصب و فعال باشد.', 'mahan' ); ?>
			<?php if ( current_user_can( 'install_plugins' ) ) : ?>
				<a href="<?php echo esc_url( Mahan_Plugin_Notice::install_url( 'elementor' ) ); ?>">
					<?php esc_html_e( 'نصب المنتور', 'mahan' ); ?>
				</a>
			<?php endif; ?>
		</span>
	</div>
<?php endif; ?>

<div class="mahan-panel__filters">
	<div class="mahan-panel__search">
		<?php mahan_icon_e( 'search', 18 ); ?>
		<label class="screen-reader-text" for="mahan-element-search"><?php esc_html_e( 'جستجوی المان', 'mahan' ); ?></label>
		<input type="search" id="mahan-element-search" data-mahan-element-search placeholder="<?php esc_attr_e( 'جستجو میان المان‌ها…', 'mahan' ); ?>" />
	</div>
</div>

<?php foreach ( $mahan_catalog as $mahan_group ) : ?>
	<section class="mahan-panel__card mahan-panel__element-group" data-mahan-element-group>
		<h2>
			<span class="mahan-panel__group-icon"><?php mahan_icon_e( $mahan_group['icon'], 18 ); ?></span>
			<?php echo esc_html( $mahan_group['label'] ); ?>
			<em><?php echo esc_html( mahan_fa_numbers( count( $mahan_group['items'] ) ) ); ?></em>
		</h2>

		<div class="mahan-panel__elements">
			<?php foreach ( $mahan_group['items'] as $mahan_slug => $mahan_item ) : ?>
				<div class="mahan-panel__element" data-name="<?php echo esc_attr( $mahan_item[0] . ' ' . $mahan_item[1] . ' ' . $mahan_slug ); ?>">
					<strong><?php echo esc_html( $mahan_item[0] ); ?></strong>
					<span><?php echo esc_html( $mahan_item[1] ); ?></span>
					<code>mahan-<?php echo esc_html( $mahan_slug ); ?></code>
				</div>
			<?php endforeach; ?>
		</div>
	</section>
<?php endforeach; ?>

<p class="mahan-panel__empty" data-mahan-element-empty hidden><?php esc_html_e( 'موردی پیدا نشد.', 'mahan' ); ?></p>
