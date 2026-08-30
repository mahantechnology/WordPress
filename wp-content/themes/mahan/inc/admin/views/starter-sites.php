<?php
/**
 * The starter-site library: preview, install and roll back.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_demos    = Mahan_Demo_Library::index();
$mahan_imported = Mahan_Demo_Importer::imported_demo();
$mahan_can      = current_user_can( 'import' );

$mahan_tags = array();

foreach ( $mahan_demos as $mahan_demo ) {
	foreach ( $mahan_demo['tags'] as $mahan_tag ) {
		$mahan_tags[ sanitize_title( $mahan_tag ) ] = $mahan_tag;
	}
}
?>

<section class="mahan-panel__section-head">
	<div>
		<h1><?php esc_html_e( 'قالب‌های آماده', 'mahan' ); ?></h1>
		<p>
			<?php esc_html_e( 'هر قالب آماده برگه‌ها، منوها، ابزارک‌ها، تصاویر و رنگ‌بندی خودش را می‌سازد. محتوای همهٔ آن‌ها داخل قالب است و بدون اتصال به اینترنت نصب می‌شود.', 'mahan' ); ?>
		</p>
	</div>

	<?php if ( $mahan_imported && current_user_can( 'delete_others_pages' ) ) : ?>
		<button type="button" class="mahan-panel__btn mahan-panel__btn--danger" data-mahan-rollback>
			<?php mahan_icon_e( 'refresh', 16 ); ?>
			<span><?php esc_html_e( 'حذف محتوای درون‌ریزی‌شده', 'mahan' ); ?></span>
		</button>
	<?php endif; ?>
</section>

<?php if ( ! $mahan_can ) : ?>
	<div class="mahan-panel__notice mahan-panel__notice--error">
		<?php mahan_icon_e( 'close', 20 ); ?>
		<span><?php esc_html_e( 'برای نصب قالب آماده به دسترسی درون‌ریزی نیاز دارید.', 'mahan' ); ?></span>
	</div>
<?php endif; ?>

<div class="mahan-panel__filters">
	<div class="mahan-panel__search">
		<?php mahan_icon_e( 'search', 18 ); ?>
		<label class="screen-reader-text" for="mahan-demo-search"><?php esc_html_e( 'جستجوی قالب آماده', 'mahan' ); ?></label>
		<input type="search" id="mahan-demo-search" data-mahan-demo-search placeholder="<?php esc_attr_e( 'جستجو میان قالب‌های آماده…', 'mahan' ); ?>" />
	</div>

	<div class="mahan-panel__chips" role="group" aria-label="<?php esc_attr_e( 'فیلتر بر اساس حوزه', 'mahan' ); ?>">
		<button type="button" class="mahan-panel__chip is-active" data-mahan-demo-filter="*">
			<?php esc_html_e( 'همه', 'mahan' ); ?>
		</button>
		<?php foreach ( $mahan_tags as $mahan_slug => $mahan_label ) : ?>
			<button type="button" class="mahan-panel__chip" data-mahan-demo-filter="<?php echo esc_attr( $mahan_slug ); ?>">
				<?php echo esc_html( $mahan_label ); ?>
			</button>
		<?php endforeach; ?>
	</div>
</div>

<div class="mahan-panel__demos" data-mahan-demo-grid>
	<?php foreach ( $mahan_demos as $mahan_id => $mahan_demo ) : ?>
		<?php
		$mahan_missing   = Mahan_Demo_Library::missing_plugins( $mahan_id );
		$mahan_installed = $mahan_imported === $mahan_id;
		$mahan_slugs     = array_map( 'sanitize_title', $mahan_demo['tags'] );
		$mahan_palette   = Mahan_Options::palettes();
		$mahan_colours   = isset( $mahan_palette[ $mahan_demo['palette'] ] ) ? $mahan_palette[ $mahan_demo['palette'] ] : null;
		?>
		<article
			class="mahan-demo<?php echo $mahan_installed ? ' is-installed' : ''; ?>"
			data-terms="<?php echo esc_attr( implode( ' ', $mahan_slugs ) ); ?>"
			data-name="<?php echo esc_attr( $mahan_demo['label'] . ' ' . $mahan_demo['description'] ); ?>"
		>
			<div class="mahan-demo__preview">
				<img src="<?php echo esc_url( $mahan_demo['preview'] ); ?>" alt="<?php echo esc_attr( $mahan_demo['label'] ); ?>" loading="lazy" />

				<?php if ( $mahan_installed ) : ?>
					<span class="mahan-demo__flag"><?php mahan_icon_e( 'check', 14 ); ?><?php esc_html_e( 'نصب‌شده', 'mahan' ); ?></span>
				<?php endif; ?>

				<?php if ( $mahan_colours ) : ?>
					<span class="mahan-demo__palette" title="<?php echo esc_attr( $mahan_colours['label'] ); ?>">
						<i style="background: <?php echo esc_attr( $mahan_colours['primary'] ); ?>"></i>
						<i style="background: <?php echo esc_attr( $mahan_colours['secondary'] ); ?>"></i>
						<i style="background: <?php echo esc_attr( $mahan_colours['accent'] ); ?>"></i>
					</span>
				<?php endif; ?>
			</div>

			<div class="mahan-demo__body">
				<h2>
					<span class="mahan-demo__icon"><?php mahan_icon_e( $mahan_demo['icon'], 18 ); ?></span>
					<?php echo esc_html( $mahan_demo['label'] ); ?>
				</h2>
				<p><?php echo esc_html( $mahan_demo['description'] ); ?></p>

				<ul class="mahan-demo__tags">
					<?php foreach ( $mahan_demo['tags'] as $mahan_tag ) : ?>
						<li><?php echo esc_html( $mahan_tag ); ?></li>
					<?php endforeach; ?>
				</ul>

				<?php if ( $mahan_missing ) : ?>
					<div class="mahan-demo__requires">
						<strong><?php esc_html_e( 'نیازمند نصب:', 'mahan' ); ?></strong>
						<?php foreach ( $mahan_missing as $mahan_plugin ) : ?>
							<a href="<?php echo esc_url( Mahan_Plugin_Notice::install_url( $mahan_plugin['slug'] ) ); ?>">
								<?php echo esc_html( $mahan_plugin['name'] ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<footer class="mahan-demo__footer">
				<button
					type="button"
					class="mahan-panel__btn mahan-panel__btn--block"
					data-mahan-install="<?php echo esc_attr( $mahan_id ); ?>"
					<?php disabled( ! $mahan_can || ! empty( $mahan_missing ) ); ?>
				>
					<?php mahan_icon_e( 'download', 16 ); ?>
					<span><?php echo esc_html( $mahan_installed ? __( 'نصب دوباره', 'mahan' ) : __( 'نصب این قالب', 'mahan' ) ); ?></span>
				</button>
			</footer>
		</article>
	<?php endforeach; ?>
</div>

<p class="mahan-panel__empty" data-mahan-demo-empty hidden><?php esc_html_e( 'موردی پیدا نشد.', 'mahan' ); ?></p>

<div class="mahan-panel__modal" data-mahan-progress hidden>
	<div class="mahan-panel__modal-inner" role="dialog" aria-modal="true" aria-labelledby="mahan-progress-title">
		<h2 id="mahan-progress-title"><?php esc_html_e( 'در حال نصب قالب آماده', 'mahan' ); ?></h2>

		<ol class="mahan-panel__steps">
			<?php foreach ( Mahan_Demo_Importer::steps() as $mahan_key => $mahan_label ) : ?>
				<li data-step="<?php echo esc_attr( $mahan_key ); ?>">
					<span class="mahan-panel__step-icon" aria-hidden="true"></span>
					<span><?php echo esc_html( $mahan_label ); ?></span>
				</li>
			<?php endforeach; ?>
		</ol>

		<p class="mahan-panel__status" role="status" aria-live="polite"></p>

		<div class="mahan-panel__modal-actions" hidden>
			<a class="mahan-panel__btn" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'مشاهدهٔ سایت', 'mahan' ); ?>
			</a>
			<button type="button" class="mahan-panel__btn mahan-panel__btn--outline" data-mahan-close-progress>
				<?php esc_html_e( 'بستن', 'mahan' ); ?>
			</button>
		</div>
	</div>
</div>
