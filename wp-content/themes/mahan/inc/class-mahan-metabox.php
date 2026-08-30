<?php
/**
 * Per-post layout settings.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Metabox {

	/**
	 * Post types the metabox is offered on.
	 *
	 * @var string[]
	 */
	private $screens = array( 'post', 'page', 'product', 'mahan_portfolio', 'mahan_service' );

	/**
	 * Hooks the metabox.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'register' ) );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
		add_action( 'wp_head', array( $this, 'inline_styles' ) );
		add_filter( 'body_class', array( $this, 'body_class' ) );
	}

	/**
	 * The fields the metabox renders, keyed by meta key.
	 *
	 * @return array
	 */
	private function fields() {
		return array(
			'_mahan_layout'        => array(
				'label'   => __( 'چیدمان صفحه', 'mahan' ),
				'type'    => 'select',
				'default' => 'default',
				'options' => array(
					'default'    => __( 'پیش‌فرض قالب', 'mahan' ),
					'boxed'      => __( 'باکس‌بندی‌شده', 'mahan' ),
					'full'       => __( 'تمام‌عرض', 'mahan' ),
					'narrow'     => __( 'باریک (مطالعه)', 'mahan' ),
					'blank'      => __( 'بوم خالی (بدون هدر و فوتر)', 'mahan' ),
				),
			),
			'_mahan_sidebar'       => array(
				'label'   => __( 'ستون کناری', 'mahan' ),
				'type'    => 'select',
				'default' => 'default',
				'options' => array(
					'default' => __( 'پیش‌فرض قالب', 'mahan' ),
					'right'   => __( 'راست', 'mahan' ),
					'left'    => __( 'چپ', 'mahan' ),
					'none'    => __( 'بدون ستون کناری', 'mahan' ),
				),
			),
			'_mahan_hide_title'    => array(
				'label'   => __( 'پنهان کردن عنوان صفحه', 'mahan' ),
				'type'    => 'checkbox',
				'default' => '',
			),
			'_mahan_hide_header'   => array(
				'label'   => __( 'پنهان کردن هدر', 'mahan' ),
				'type'    => 'checkbox',
				'default' => '',
			),
			'_mahan_hide_footer'   => array(
				'label'   => __( 'پنهان کردن فوتر', 'mahan' ),
				'type'    => 'checkbox',
				'default' => '',
			),
			'_mahan_transparent'   => array(
				'label'   => __( 'هدر شیشه‌ای روی محتوا', 'mahan' ),
				'type'    => 'checkbox',
				'default' => '',
			),
			'_mahan_hero_title'    => array(
				'label'   => __( 'عنوان جایگزین سربرگ', 'mahan' ),
				'type'    => 'text',
				'default' => '',
			),
			'_mahan_hero_subtitle' => array(
				'label'   => __( 'زیرعنوان سربرگ', 'mahan' ),
				'type'    => 'textarea',
				'default' => '',
			),
			'_mahan_page_color'    => array(
				'label'   => __( 'رنگ اصلی اختصاصی این صفحه', 'mahan' ),
				'type'    => 'color',
				'default' => '',
			),
		);
	}

	/**
	 * Adds the metabox to the supported screens.
	 */
	public function register() {
		foreach ( $this->screens as $screen ) {
			if ( ! post_type_exists( $screen ) ) {
				continue;
			}

			add_meta_box(
				'mahan-page-settings',
				__( 'تنظیمات قالب ماهان', 'mahan' ),
				array( $this, 'render' ),
				$screen,
				'side',
				'default'
			);
		}
	}

	/**
	 * Renders the metabox.
	 *
	 * @param WP_Post $post Post being edited.
	 */
	public function render( $post ) {
		wp_nonce_field( 'mahan_metabox', 'mahan_metabox_nonce' );

		echo '<div class="mahan-metabox">';

		foreach ( $this->fields() as $key => $field ) {
			$value = get_post_meta( $post->ID, $key, true );
			$value = '' === $value ? $field['default'] : $value;
			$id    = 'mahan-field-' . ltrim( $key, '_' );

			echo '<p class="mahan-metabox__field">';

			switch ( $field['type'] ) {
				case 'select':
					printf( '<label for="%1$s"><strong>%2$s</strong></label>', esc_attr( $id ), esc_html( $field['label'] ) );
					printf( '<select id="%1$s" name="%1$s" class="widefat">', esc_attr( $id ) );

					foreach ( $field['options'] as $option_value => $option_label ) {
						printf(
							'<option value="%1$s" %2$s>%3$s</option>',
							esc_attr( $option_value ),
							selected( $value, $option_value, false ),
							esc_html( $option_label )
						);
					}

					echo '</select>';
					break;

				case 'checkbox':
					printf(
						'<label for="%1$s"><input type="checkbox" id="%1$s" name="%1$s" value="1" %2$s /> %3$s</label>',
						esc_attr( $id ),
						checked( $value, '1', false ),
						esc_html( $field['label'] )
					);
					break;

				case 'textarea':
					printf( '<label for="%1$s"><strong>%2$s</strong></label>', esc_attr( $id ), esc_html( $field['label'] ) );
					printf(
						'<textarea id="%1$s" name="%1$s" rows="3" class="widefat">%2$s</textarea>',
						esc_attr( $id ),
						esc_textarea( $value )
					);
					break;

				case 'color':
					printf( '<label for="%1$s"><strong>%2$s</strong></label>', esc_attr( $id ), esc_html( $field['label'] ) );
					printf(
						'<input type="color" id="%1$s" name="%1$s" value="%2$s" />',
						esc_attr( $id ),
						esc_attr( $value ? $value : '#4f46e5' )
					);
					printf(
						' <label class="mahan-metabox__reset"><input type="checkbox" name="%1$s_clear" value="1" /> %2$s</label>',
						esc_attr( $id ),
						esc_html__( 'بدون رنگ اختصاصی', 'mahan' )
					);
					break;

				default:
					printf( '<label for="%1$s"><strong>%2$s</strong></label>', esc_attr( $id ), esc_html( $field['label'] ) );
					printf(
						'<input type="text" id="%1$s" name="%1$s" value="%2$s" class="widefat" />',
						esc_attr( $id ),
						esc_attr( $value )
					);
			}

			echo '</p>';
		}

		echo '</div>';
	}

	/**
	 * Stores the metabox values.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save( $post_id, $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( empty( $_POST['mahan_metabox_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['mahan_metabox_nonce'] ) ), 'mahan_metabox' ) ) {
			return;
		}

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		foreach ( $this->fields() as $key => $field ) {
			$name = 'mahan-field-' . ltrim( $key, '_' );

			if ( 'checkbox' === $field['type'] ) {
				update_post_meta( $post_id, $key, isset( $_POST[ $name ] ) ? '1' : '' );
				continue;
			}

			if ( 'color' === $field['type'] ) {
				if ( ! empty( $_POST[ $name . '_clear' ] ) ) {
					delete_post_meta( $post_id, $key );
					continue;
				}

				$color = isset( $_POST[ $name ] ) ? sanitize_hex_color( wp_unslash( $_POST[ $name ] ) ) : '';
				update_post_meta( $post_id, $key, $color );
				continue;
			}

			if ( ! isset( $_POST[ $name ] ) ) {
				continue;
			}

			$value = wp_unslash( $_POST[ $name ] );

			if ( 'select' === $field['type'] ) {
				$value = array_key_exists( $value, $field['options'] ) ? $value : $field['default'];
			} elseif ( 'textarea' === $field['type'] ) {
				$value = sanitize_textarea_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}

			update_post_meta( $post_id, $key, $value );
		}
	}

	/**
	 * Applies a per-page accent colour.
	 */
	public function inline_styles() {
		if ( ! is_singular() ) {
			return;
		}

		$color = get_post_meta( get_the_ID(), '_mahan_page_color', true );

		if ( ! $color ) {
			return;
		}

		printf(
			'<style id="mahan-page-color">:root{--mahan-primary:%1$s;--mahan-primary-rgb:%2$s;--mahan-primary-dark:%3$s;--mahan-primary-light:%4$s;--mahan-primary-contrast:%5$s;}</style>',
			esc_attr( $color ),
			esc_attr( mahan_hex_to_rgb( $color ) ),
			esc_attr( mahan_shade_color( $color, -18 ) ),
			esc_attr( mahan_shade_color( $color, 82 ) ),
			esc_attr( mahan_contrast_color( $color ) )
		);
	}

	/**
	 * Adds the per-page layout class.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		if ( ! is_singular() ) {
			return $classes;
		}

		$layout = get_post_meta( get_the_ID(), '_mahan_layout', true );

		if ( $layout && 'default' !== $layout ) {
			$classes[] = 'mahan-layout-' . sanitize_html_class( $layout );
		}

		if ( get_post_meta( get_the_ID(), '_mahan_transparent', true ) ) {
			$classes[] = 'mahan-transparent-header';
		}

		return $classes;
	}
}
