<?php
/**
 * Customizer panels, built from the shared option schema.
 *
 * Every control writes into the single `mahan_settings` theme mod through the
 * option-array syntax, which keeps a configuration exportable in one piece.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Customizer {

	/**
	 * Hooks the customizer registration.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register' ) );
		add_action( 'customize_preview_init', array( $this, 'preview_script' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'controls_assets' ) );
	}

	/**
	 * Builds a section and its controls for every group in the schema.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 */
	public function register( $wp_customize ) {
		$wp_customize->get_setting( 'blogname' )->transport        = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$wp_customize->add_panel(
			'mahan',
			array(
				'title'       => __( 'تنظیمات قالب ماهان', 'mahan' ),
				'description' => __( 'همین تنظیمات در «قالب ماهان ← تنظیمات» هم در دسترس است.', 'mahan' ),
				'priority'    => 10,
			)
		);

		$priority = 10;

		foreach ( Mahan_Schema::all() as $group_key => $group ) {
			if ( 'woocommerce' === ( isset( $group['requires'] ) ? $group['requires'] : '' ) && ! mahan_has_woocommerce() ) {
				continue;
			}

			$wp_customize->add_section(
				'mahan_' . $group_key,
				array(
					'title'       => $group['label'],
					'panel'       => 'mahan',
					'description' => isset( $group['description'] ) ? $group['description'] : '',
					'priority'    => $priority,
				)
			);

			$priority += 10;

			foreach ( $group['fields'] as $key => $field ) {
				$this->add_control( $wp_customize, 'mahan_' . $group_key, $key, $field );
			}
		}
	}

	/**
	 * Registers one setting plus its control.
	 *
	 * @param WP_Customize_Manager $wp_customize Customizer manager.
	 * @param string               $section      Section ID.
	 * @param string               $key          Option key.
	 * @param array                $field        Field definition from the schema.
	 */
	private function add_control( $wp_customize, $section, $key, array $field ) {
		$defaults = Mahan_Options::defaults();

		$wp_customize->add_setting(
			Mahan_Options::KEY . '[' . $key . ']',
			array(
				'type'              => 'theme_mod',
				'default'           => isset( $defaults[ $key ] ) ? $defaults[ $key ] : '',
				'transport'         => isset( $field['transport'] ) ? $field['transport'] : 'refresh',
				'capability'        => 'edit_theme_options',
				'sanitize_callback' => static function ( $value ) use ( $key ) {
					return Mahan_Options::sanitize( $key, $value );
				},
			)
		);

		$args = array(
			'label'       => $field['label'],
			'section'     => $section,
			'settings'    => Mahan_Options::KEY . '[' . $key . ']',
			'description' => isset( $field['description'] ) ? $field['description'] : '',
		);

		if ( 'color' === $field['type'] ) {
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'mahan_' . $key, $args ) );

			return;
		}

		$args['type'] = $field['type'];

		if ( isset( $field['choices'] ) ) {
			$args['choices'] = $field['choices'];
		}

		if ( 'number' === $field['type'] ) {
			$args['input_attrs'] = array(
				'min'  => isset( $field['min'] ) ? $field['min'] : '',
				'max'  => isset( $field['max'] ) ? $field['max'] : '',
				'step' => isset( $field['step'] ) ? $field['step'] : 1,
			);
		}

		$wp_customize->add_control( 'mahan_' . $key, $args );
	}

	/**
	 * Loads the live-preview script.
	 */
	public function preview_script() {
		wp_enqueue_script(
			'mahan-customizer-preview',
			MAHAN_URI . 'assets/js/customizer-preview.js',
			array( 'customize-preview' ),
			MAHAN_VERSION,
			true
		);
	}

	/**
	 * A link from the customizer back to the theme panel.
	 */
	public function controls_assets() {
		wp_add_inline_style(
			'customize-controls',
			'#sub-accordion-panel-mahan .customize-control-title{font-weight:600;}'
		);
	}
}
