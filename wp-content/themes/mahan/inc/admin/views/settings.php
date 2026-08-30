<?php
/**
 * The settings screen: every option group as a tab, rendered from the schema.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

$mahan_schema = Mahan_Schema::all();

foreach ( $mahan_schema as $mahan_key => $mahan_group ) {
	if ( 'woocommerce' === ( isset( $mahan_group['requires'] ) ? $mahan_group['requires'] : '' ) && ! mahan_has_woocommerce() ) {
		unset( $mahan_schema[ $mahan_key ] );
	}
}

$mahan_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : '';
$mahan_tab = isset( $mahan_schema[ $mahan_tab ] ) ? $mahan_tab : (string) array_key_first( $mahan_schema );
$mahan_now = $mahan_schema[ $mahan_tab ];
?>

<section class="mahan-panel__section-head">
	<div>
		<h1><?php esc_html_e( 'تنظیمات قالب', 'mahan' ); ?></h1>
		<p><?php esc_html_e( 'همین گزینه‌ها در «نمایش ← سفارشی‌سازی» هم با پیش‌نمایش زنده در دسترس هستند.', 'mahan' ); ?></p>
	</div>

	<div class="mahan-panel__head-actions">
		<a class="mahan-panel__ghost-btn" href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=mahan_export_settings' ), 'mahan_export_settings' ) ); ?>">
			<?php mahan_icon_e( 'download', 16 ); ?>
			<span><?php esc_html_e( 'برون‌بری تنظیمات', 'mahan' ); ?></span>
		</a>

		<button type="button" class="mahan-panel__ghost-btn" data-mahan-toggle-import>
			<?php mahan_icon_e( 'refresh', 16 ); ?>
			<span><?php esc_html_e( 'درون‌ریزی', 'mahan' ); ?></span>
		</button>
	</div>
</section>

<form
	class="mahan-panel__import"
	data-mahan-import-form
	method="post"
	enctype="multipart/form-data"
	action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>"
	hidden
>
	<?php wp_nonce_field( 'mahan_import_settings' ); ?>
	<input type="hidden" name="action" value="mahan_import_settings" />
	<label for="mahan-settings-file"><?php esc_html_e( 'فایل تنظیمات (JSON)', 'mahan' ); ?></label>
	<input type="file" id="mahan-settings-file" name="mahan_settings_file" accept="application/json,.json" required />
	<button type="submit" class="mahan-panel__btn"><?php esc_html_e( 'درون‌ریزی', 'mahan' ); ?></button>
</form>

<div class="mahan-panel__settings">
	<nav class="mahan-panel__side" aria-label="<?php esc_attr_e( 'گروه‌های تنظیمات', 'mahan' ); ?>">
		<?php foreach ( $mahan_schema as $mahan_key => $mahan_group ) : ?>
			<a
				class="mahan-panel__side-link<?php echo $mahan_key === $mahan_tab ? ' is-active' : ''; ?>"
				href="<?php echo esc_url( Mahan_Admin::url( 'settings', array( 'tab' => $mahan_key ) ) ); ?>"
			>
				<?php mahan_icon_e( $mahan_group['icon'], 18 ); ?>
				<span><?php echo esc_html( $mahan_group['label'] ); ?></span>
				<em><?php echo esc_html( mahan_fa_numbers( count( $mahan_group['fields'] ) ) ); ?></em>
			</a>
		<?php endforeach; ?>
	</nav>

	<form class="mahan-panel__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
		<?php wp_nonce_field( 'mahan_save_settings' ); ?>
		<input type="hidden" name="action" value="mahan_save_settings" />
		<input type="hidden" name="mahan_group" value="<?php echo esc_attr( $mahan_tab ); ?>" />

		<header class="mahan-panel__form-head">
			<h2><?php echo esc_html( $mahan_now['label'] ); ?></h2>
			<?php if ( ! empty( $mahan_now['description'] ) ) : ?>
				<p><?php echo esc_html( $mahan_now['description'] ); ?></p>
			<?php endif; ?>
		</header>

		<div class="mahan-panel__fields">
			<?php foreach ( $mahan_now['fields'] as $mahan_name => $mahan_field ) : ?>
				<?php
				$mahan_value = mahan_option( $mahan_name );
				$mahan_id    = 'mahan-field-' . $mahan_name;
				$mahan_class = 'mahan-panel__field mahan-panel__field--' . $mahan_field['type'];
				?>
				<div class="<?php echo esc_attr( $mahan_class ); ?>">
					<?php if ( 'checkbox' === $mahan_field['type'] ) : ?>
						<label class="mahan-panel__switch" for="<?php echo esc_attr( $mahan_id ); ?>">
							<input
								type="checkbox"
								id="<?php echo esc_attr( $mahan_id ); ?>"
								name="mahan[<?php echo esc_attr( $mahan_name ); ?>]"
								value="1"
								<?php checked( (bool) $mahan_value ); ?>
							/>
							<span class="mahan-panel__switch-track" aria-hidden="true"><i></i></span>
							<span class="mahan-panel__switch-text">
								<strong><?php echo esc_html( $mahan_field['label'] ); ?></strong>
								<?php if ( ! empty( $mahan_field['description'] ) ) : ?>
									<em><?php echo esc_html( $mahan_field['description'] ); ?></em>
								<?php endif; ?>
							</span>
						</label>

					<?php else : ?>
						<label class="mahan-panel__label" for="<?php echo esc_attr( $mahan_id ); ?>">
							<?php echo esc_html( $mahan_field['label'] ); ?>
							<?php if ( ! empty( $mahan_field['unit'] ) ) : ?>
								<span class="mahan-panel__unit"><?php echo esc_html( $mahan_field['unit'] ); ?></span>
							<?php endif; ?>
						</label>

						<?php if ( 'select' === $mahan_field['type'] ) : ?>
							<select id="<?php echo esc_attr( $mahan_id ); ?>" name="mahan[<?php echo esc_attr( $mahan_name ); ?>]">
								<?php foreach ( $mahan_field['choices'] as $mahan_option_value => $mahan_option_label ) : ?>
									<option value="<?php echo esc_attr( $mahan_option_value ); ?>" <?php selected( (string) $mahan_value, (string) $mahan_option_value ); ?>>
										<?php echo esc_html( $mahan_option_label ); ?>
									</option>
								<?php endforeach; ?>
							</select>

							<?php if ( ! empty( $mahan_field['swatches'] ) ) : ?>
								<div class="mahan-panel__swatches" data-mahan-palette-swatches>
									<?php foreach ( Mahan_Options::palettes() as $mahan_pk => $mahan_pal ) : ?>
										<button
											type="button"
											class="mahan-panel__swatch<?php echo $mahan_value === $mahan_pk ? ' is-active' : ''; ?>"
											data-mahan-palette="<?php echo esc_attr( $mahan_pk ); ?>"
											title="<?php echo esc_attr( $mahan_pal['label'] ); ?>"
										>
											<i style="background: <?php echo esc_attr( $mahan_pal['primary'] ); ?>"></i>
											<i style="background: <?php echo esc_attr( $mahan_pal['secondary'] ); ?>"></i>
											<i style="background: <?php echo esc_attr( $mahan_pal['accent'] ); ?>"></i>
											<span><?php echo esc_html( $mahan_pal['label'] ); ?></span>
										</button>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

						<?php elseif ( 'color' === $mahan_field['type'] ) : ?>
							<div class="mahan-panel__color">
								<input
									type="color"
									id="<?php echo esc_attr( $mahan_id ); ?>"
									name="mahan[<?php echo esc_attr( $mahan_name ); ?>]"
									value="<?php echo esc_attr( $mahan_value ); ?>"
									data-mahan-color
								/>
								<output for="<?php echo esc_attr( $mahan_id ); ?>"><?php echo esc_html( $mahan_value ); ?></output>
							</div>

						<?php elseif ( 'textarea' === $mahan_field['type'] ) : ?>
							<textarea
								id="<?php echo esc_attr( $mahan_id ); ?>"
								name="mahan[<?php echo esc_attr( $mahan_name ); ?>]"
								rows="3"
							><?php echo esc_textarea( $mahan_value ); ?></textarea>

						<?php elseif ( 'number' === $mahan_field['type'] ) : ?>
							<input
								type="number"
								id="<?php echo esc_attr( $mahan_id ); ?>"
								name="mahan[<?php echo esc_attr( $mahan_name ); ?>]"
								value="<?php echo esc_attr( $mahan_value ); ?>"
								min="<?php echo esc_attr( isset( $mahan_field['min'] ) ? $mahan_field['min'] : '' ); ?>"
								max="<?php echo esc_attr( isset( $mahan_field['max'] ) ? $mahan_field['max'] : '' ); ?>"
								step="<?php echo esc_attr( isset( $mahan_field['step'] ) ? $mahan_field['step'] : 1 ); ?>"
							/>

						<?php else : ?>
							<input
								type="<?php echo 'url' === $mahan_field['type'] ? 'url' : 'text'; ?>"
								id="<?php echo esc_attr( $mahan_id ); ?>"
								name="mahan[<?php echo esc_attr( $mahan_name ); ?>]"
								value="<?php echo esc_attr( $mahan_value ); ?>"
								<?php echo 'url' === $mahan_field['type'] ? 'placeholder="https://"' : ''; ?>
							/>
						<?php endif; ?>

						<?php if ( ! empty( $mahan_field['description'] ) ) : ?>
							<p class="mahan-panel__hint"><?php echo esc_html( $mahan_field['description'] ); ?></p>
						<?php endif; ?>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<footer class="mahan-panel__form-foot">
			<button type="submit" class="mahan-panel__btn">
				<?php mahan_icon_e( 'check', 16 ); ?>
				<span><?php esc_html_e( 'ذخیرهٔ تغییرات', 'mahan' ); ?></span>
			</button>

			<a
				class="mahan-panel__ghost-btn mahan-panel__ghost-btn--danger"
				href="<?php echo esc_url( wp_nonce_url( admin_url( 'admin-post.php?action=mahan_reset_settings' ), 'mahan_reset_settings' ) ); ?>"
				data-mahan-confirm="<?php esc_attr_e( 'همهٔ تنظیمات قالب به حالت پیش‌فرض برمی‌گردد. ادامه می‌دهید؟', 'mahan' ); ?>"
			>
				<?php esc_html_e( 'بازگشت به پیش‌فرض', 'mahan' ); ?>
			</a>
		</footer>
	</form>
</div>
