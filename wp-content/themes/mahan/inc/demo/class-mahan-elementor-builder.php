<?php
/**
 * Turns a compact section spec into the `_elementor_data` JSON Elementor stores.
 *
 * The demo packs describe their pages as nested arrays of sections, columns and
 * widgets; this class expands that into the full element tree, filling in the
 * IDs and default settings Elementor expects.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

class Mahan_Elementor_Builder {

	/**
	 * Sections collected so far.
	 *
	 * @var array
	 */
	private $sections = array();

	/**
	 * Starts a new document.
	 *
	 * @return Mahan_Elementor_Builder
	 */
	public static function make() {
		return new self();
	}

	/**
	 * Adds one section.
	 *
	 * @param array $columns  Column definitions; each is a list of widgets, or an
	 *                        array with `width` and `widgets` keys.
	 * @param array $settings Section settings such as background or padding.
	 * @return Mahan_Elementor_Builder
	 */
	public function section( array $columns, array $settings = array() ) {
		$this->sections[] = array(
			'id'       => $this->id(),
			'elType'   => 'section',
			'settings' => $this->section_settings( $settings ),
			'elements' => $this->build_columns( $columns ),
			'isInner'  => false,
		);

		return $this;
	}

	/**
	 * Adds a section holding a single full-width column.
	 *
	 * @param array $widgets  Widgets to place in the column.
	 * @param array $settings Section settings.
	 * @return Mahan_Elementor_Builder
	 */
	public function row( array $widgets, array $settings = array() ) {
		return $this->section( array( $widgets ), $settings );
	}

	/**
	 * The finished element tree.
	 *
	 * @return array
	 */
	public function to_array() {
		return $this->sections;
	}

	/**
	 * The finished element tree as the JSON Elementor stores in post meta.
	 *
	 * @return string
	 */
	public function to_json() {
		return wp_json_encode( $this->sections, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
	}

	/**
	 * Describes one widget for use inside a column.
	 *
	 * @param string $type     Elementor widget type, without the `mahan-` prefix
	 *                         for the theme's own elements.
	 * @param array  $settings Widget settings.
	 * @return array
	 */
	public static function widget( $type, array $settings = array() ) {
		return array(
			'__widget' => $type,
			'settings' => $settings,
		);
	}

	/**
	 * Describes a column with an explicit width.
	 *
	 * @param int   $width    Column width as a percentage.
	 * @param array $widgets  Widgets to place inside.
	 * @param array $settings Column settings.
	 * @return array
	 */
	public static function column( $width, array $widgets, array $settings = array() ) {
		return array(
			'width'    => $width,
			'widgets'  => $widgets,
			'settings' => $settings,
		);
	}

	/**
	 * Expands the column definitions into Elementor column elements.
	 *
	 * @param array $columns Column definitions.
	 * @return array
	 */
	private function build_columns( array $columns ) {
		$count = max( 1, count( $columns ) );
		$out   = array();

		foreach ( $columns as $column ) {
			if ( isset( $column['widgets'] ) ) {
				$widgets  = $column['widgets'];
				$width    = isset( $column['width'] ) ? (float) $column['width'] : round( 100 / $count, 3 );
				$settings = isset( $column['settings'] ) ? $column['settings'] : array();
			} else {
				$widgets  = $column;
				$width    = round( 100 / $count, 3 );
				$settings = array();
			}

			$out[] = array(
				'id'       => $this->id(),
				'elType'   => 'column',
				'settings' => array_merge(
					array(
						'_column_size' => (int) round( $width ),
						'_inline_size' => $width,
					),
					$settings
				),
				'elements' => $this->build_widgets( $widgets ),
				'isInner'  => false,
			);
		}

		return $out;
	}

	/**
	 * Expands the widget definitions into Elementor widget elements.
	 *
	 * @param array $widgets Widget definitions.
	 * @return array
	 */
	private function build_widgets( array $widgets ) {
		$out = array();

		foreach ( $widgets as $widget ) {
			if ( ! isset( $widget['__widget'] ) ) {
				continue;
			}

			$type = $widget['__widget'];

			// Theme elements are registered with a `mahan-` prefix; core ones are not.
			if ( 0 !== strpos( $type, 'mahan-' ) && ! in_array( $type, self::core_widgets(), true ) ) {
				$type = 'mahan-' . $type;
			}

			$out[] = array(
				'id'         => $this->id(),
				'elType'     => 'widget',
				'widgetType' => $type,
				'settings'   => $this->expand_settings( $widget['settings'] ),
				'elements'   => array(),
			);
		}

		return $out;
	}

	/**
	 * Elementor's own widget types the packs are allowed to use directly.
	 *
	 * @return string[]
	 */
	private static function core_widgets() {
		return array( 'heading', 'text-editor', 'image', 'button', 'spacer', 'divider', 'html', 'shortcode', 'icon-list', 'video' );
	}

	/**
	 * Gives every repeater row the `_id` Elementor expects.
	 *
	 * @param array $settings Raw settings from a demo pack.
	 * @return array
	 */
	private function expand_settings( array $settings ) {
		foreach ( $settings as $key => $value ) {
			if ( ! is_array( $value ) ) {
				continue;
			}

			// A repeater is a list of associative arrays.
			if ( isset( $value[0] ) && is_array( $value[0] ) ) {
				foreach ( $value as $index => $row ) {
					if ( is_array( $row ) && ! isset( $row['_id'] ) ) {
						$value[ $index ]['_id'] = $this->id( 7 );
					}
				}

				$settings[ $key ] = $value;
			}
		}

		return $settings;
	}

	/**
	 * Section settings with the theme's defaults filled in.
	 *
	 * @param array $settings Overrides from the demo pack.
	 * @return array
	 */
	private function section_settings( array $settings ) {
		$defaults = array(
			'layout'        => 'boxed',
			'content_width' => array(
				'unit' => 'px',
				'size' => 1280,
			),
			'padding'       => array(
				'unit'     => 'px',
				'top'      => '72',
				'right'    => '0',
				'bottom'   => '72',
				'left'     => '0',
				'isLinked' => false,
			),
		);

		return array_merge( $defaults, $settings );
	}

	/**
	 * A random Elementor-style element ID.
	 *
	 * @param int $length How many hex characters.
	 * @return string
	 */
	private function id( $length = 7 ) {
		$characters = '0123456789abcdef';
		$id         = '';

		for ( $i = 0; $i < $length; $i++ ) {
			$id .= $characters[ wp_rand( 0, 15 ) ];
		}

		return $id;
	}
}

/**
 * Shorthand for describing a widget inside a demo pack.
 *
 * @param string $type     Widget type.
 * @param array  $settings Widget settings.
 * @return array
 */
function mahan_el( $type, array $settings = array() ) {
	return Mahan_Elementor_Builder::widget( $type, $settings );
}

/**
 * Shorthand for a section's padding setting.
 *
 * @param int $top    Top padding in pixels.
 * @param int $bottom Bottom padding in pixels.
 * @return array
 */
function mahan_el_padding( $top, $bottom = null ) {
	$bottom = null === $bottom ? $top : $bottom;

	return array(
		'padding' => array(
			'unit'     => 'px',
			'top'      => (string) $top,
			'right'    => '0',
			'bottom'   => (string) $bottom,
			'left'     => '0',
			'isLinked' => false,
		),
	);
}

/**
 * Shorthand for a section with a tinted background.
 *
 * @param string $color Background colour.
 * @param int    $top    Top padding in pixels.
 * @param int    $bottom Bottom padding in pixels.
 * @return array
 */
function mahan_el_bg( $color, $top = 72, $bottom = 72 ) {
	return array_merge(
		mahan_el_padding( $top, $bottom ),
		array(
			'background_background' => 'classic',
			'background_color'      => $color,
		)
	);
}
