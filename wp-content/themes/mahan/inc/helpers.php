<?php
/**
 * Small helpers used across the theme.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

/**
 * Whether WooCommerce is active.
 *
 * @return bool
 */
function mahan_has_woocommerce() {
	return class_exists( 'WooCommerce' );
}

/**
 * Whether Elementor is active.
 *
 * @return bool
 */
function mahan_has_elementor() {
	return did_action( 'elementor/loaded' ) || class_exists( '\Elementor\Plugin' );
}

/**
 * Whether Elementor Pro is active.
 *
 * @return bool
 */
function mahan_has_elementor_pro() {
	return class_exists( '\ElementorPro\Plugin' );
}

/**
 * Whether the current request is the Elementor editor or its preview frame.
 *
 * @return bool
 */
function mahan_is_elementor_editor() {
	if ( ! mahan_has_elementor() ) {
		return false;
	}

	$plugin = \Elementor\Plugin::$instance;

	return ( isset( $plugin->editor ) && $plugin->editor->is_edit_mode() )
		|| ( isset( $plugin->preview ) && $plugin->preview->is_preview_mode() );
}

/**
 * Whether the page being rendered was built with Elementor.
 *
 * @param int|null $post_id Post to test. Defaults to the current post.
 * @return bool
 */
function mahan_is_built_with_elementor( $post_id = null ) {
	if ( ! mahan_has_elementor() ) {
		return false;
	}

	$post_id = $post_id ? $post_id : get_the_ID();

	if ( ! $post_id ) {
		return false;
	}

	return \Elementor\Plugin::$instance->documents->get( $post_id )
		&& \Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor();
}

/**
 * Reads a theme option.
 *
 * @param string $key     Option key.
 * @param mixed  $default Value to return when the option was never saved.
 * @return mixed
 */
function mahan_option( $key, $default = null ) {
	return Mahan_Options::get( $key, $default );
}

/**
 * Echoes an escaped theme option.
 *
 * @param string $key     Option key.
 * @param mixed  $default Fallback value.
 */
function mahan_option_e( $key, $default = null ) {
	echo esc_html( (string) Mahan_Options::get( $key, $default ) );
}

/**
 * Converts Western digits in a string to Persian digits.
 *
 * Used for prices, counters, dates and pagination so numbers read naturally
 * inside Persian copy.
 *
 * @param string|int|float $value Value to convert.
 * @return string
 */
function mahan_fa_numbers( $value ) {
	$western = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
	$persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );

	return str_replace( $western, $persian, (string) $value );
}

/**
 * Converts Persian and Arabic digits in a string back to Western digits.
 *
 * @param string $value Value to convert.
 * @return string
 */
function mahan_en_numbers( $value ) {
	$persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
	$arabic  = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
	$western = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );

	return str_replace( array_merge( $persian, $arabic ), array_merge( $western, $western ), (string) $value );
}

/**
 * Returns a human readable "x ago" string in Persian.
 *
 * @param int|null $from Timestamp to measure from. Defaults to post time.
 * @return string
 */
function mahan_time_ago( $from = null ) {
	$from = $from ? $from : get_the_time( 'U' );
	$diff = time() - (int) $from;

	if ( $diff < 0 ) {
		return mahan_fa_numbers( date_i18n( get_option( 'date_format' ), $from ) );
	}

	$units = array(
		array( YEAR_IN_SECONDS, __( 'سال', 'mahan' ) ),
		array( MONTH_IN_SECONDS, __( 'ماه', 'mahan' ) ),
		array( WEEK_IN_SECONDS, __( 'هفته', 'mahan' ) ),
		array( DAY_IN_SECONDS, __( 'روز', 'mahan' ) ),
		array( HOUR_IN_SECONDS, __( 'ساعت', 'mahan' ) ),
		array( MINUTE_IN_SECONDS, __( 'دقیقه', 'mahan' ) ),
	);

	foreach ( $units as $unit ) {
		list( $seconds, $label ) = $unit;

		if ( $diff >= $seconds ) {
			$count = (int) floor( $diff / $seconds );

			/* translators: 1: amount, 2: unit such as day or month. */
			return sprintf( __( '%1$s %2$s پیش', 'mahan' ), mahan_fa_numbers( $count ), $label );
		}
	}

	return __( 'لحظاتی پیش', 'mahan' );
}

/**
 * Estimates how long the current post takes to read.
 *
 * @param string|null $content Content to measure. Defaults to the current post.
 * @return int Minutes, never below one.
 */
function mahan_reading_time( $content = null ) {
	$content = null === $content ? get_post_field( 'post_content', get_the_ID() ) : $content;
	$words   = mahan_count_words( $content );

	return max( 1, (int) ceil( $words / 200 ) );
}

/**
 * Counts words in a way that works for Persian as well as Latin text.
 *
 * @param string $content Raw content.
 * @return int
 */
function mahan_count_words( $content ) {
	$text = wp_strip_all_tags( strip_shortcodes( (string) $content ) );
	$text = preg_replace( '/[\x{200C}\x{200B}]/u', ' ', $text );

	return count( preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY ) );
}

/**
 * Turns a hex colour into an `r, g, b` triplet for use inside `rgba()`.
 *
 * @param string $hex Colour such as #1e293b or #fff.
 * @return string
 */
function mahan_hex_to_rgb( $hex ) {
	$hex = ltrim( (string) $hex, '#' );

	if ( 3 === strlen( $hex ) ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}

	if ( 6 !== strlen( $hex ) || ! ctype_xdigit( $hex ) ) {
		return '0, 0, 0';
	}

	return implode(
		', ',
		array(
			hexdec( substr( $hex, 0, 2 ) ),
			hexdec( substr( $hex, 2, 2 ) ),
			hexdec( substr( $hex, 4, 2 ) ),
		)
	);
}

/**
 * Picks black or white text for a given background colour.
 *
 * @param string $hex Background colour.
 * @return string Hex colour with enough contrast.
 */
function mahan_contrast_color( $hex ) {
	list( $r, $g, $b ) = array_map( 'intval', explode( ',', mahan_hex_to_rgb( $hex ) ) );

	$luminance = ( 0.299 * $r + 0.587 * $g + 0.114 * $b ) / 255;

	return $luminance > 0.6 ? '#0f172a' : '#ffffff';
}

/**
 * Lightens or darkens a hex colour.
 *
 * @param string $hex     Base colour.
 * @param int    $percent Positive to lighten, negative to darken.
 * @return string
 */
function mahan_shade_color( $hex, $percent ) {
	list( $r, $g, $b ) = array_map( 'intval', explode( ',', mahan_hex_to_rgb( $hex ) ) );

	$adjust = static function ( $channel ) use ( $percent ) {
		$target = $percent > 0 ? 255 : 0;
		$ratio  = abs( $percent ) / 100;

		return (int) round( $channel + ( $target - $channel ) * $ratio );
	};

	return sprintf( '#%02x%02x%02x', $adjust( $r ), $adjust( $g ), $adjust( $b ) );
}

/**
 * Returns a sanitized SVG icon from the theme icon set.
 *
 * @param string $name  Icon name.
 * @param int    $size  Pixel size.
 * @param string $class Extra CSS classes.
 * @return string
 */
function mahan_icon( $name, $size = 24, $class = '' ) {
	$icons = mahan_icon_set();

	if ( ! isset( $icons[ $name ] ) ) {
		return '';
	}

	return sprintf(
		'<svg class="mahan-icon mahan-icon--%1$s %2$s" width="%3$d" height="%3$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%4$s</svg>',
		esc_attr( $name ),
		esc_attr( $class ),
		(int) $size,
		$icons[ $name ]
	);
}

/**
 * Echoes a theme icon.
 *
 * @param string $name  Icon name.
 * @param int    $size  Pixel size.
 * @param string $class Extra CSS classes.
 */
function mahan_icon_e( $name, $size = 24, $class = '' ) {
	echo mahan_icon( $name, $size, $class ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Markup is built from the fixed icon set below.
}

/**
 * The theme's inline icon set.
 *
 * Keeping the icons inline avoids shipping an icon font and lets them inherit
 * `currentColor`, which matters for the dark palette.
 *
 * @return array<string,string> Icon name mapped to SVG inner markup.
 */
function mahan_icon_set() {
	static $icons = null;

	if ( null !== $icons ) {
		return $icons;
	}

	$icons = array(
		'menu'        => '<path d="M4 6h16M4 12h16M4 18h16"/>',
		'close'       => '<path d="M18 6 6 18M6 6l12 12"/>',
		'search'      => '<circle cx="11" cy="11" r="7"/><path d="m20 20-3.2-3.2"/>',
		'user'        => '<path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/>',
		'cart'        => '<circle cx="9" cy="20" r="1.4"/><circle cx="18" cy="20" r="1.4"/><path d="M2 3h2.2l2.3 12.2a2 2 0 0 0 2 1.6h8.4a2 2 0 0 0 2-1.6L21 7H5"/>',
		'heart'       => '<path d="M20.3 5.6a5 5 0 0 0-7.1 0L12 6.8l-1.2-1.2a5 5 0 1 0-7.1 7.1l8.3 8.3 8.3-8.3a5 5 0 0 0 0-7.1Z"/>',
		'compare'     => '<path d="M4 7h11M4 7l3-3M4 7l3 3M20 17H9m11 0-3-3m3 3-3 3"/>',
		'chevron-down'=> '<path d="m6 9 6 6 6-6"/>',
		'chevron-up'  => '<path d="m18 15-6-6-6 6"/>',
		'chevron-left'=> '<path d="m15 18-6-6 6-6"/>',
		'chevron-right'=> '<path d="m9 18 6-6-6-6"/>',
		'arrow-left'  => '<path d="M19 12H5m7-7-7 7 7 7"/>',
		'arrow-right' => '<path d="M5 12h14m-7-7 7 7-7 7"/>',
		'arrow-up'    => '<path d="M12 19V5m-7 7 7-7 7 7"/>',
		'clock'       => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3.2 1.9"/>',
		'calendar'    => '<rect x="3" y="5" width="18" height="16" rx="2.5"/><path d="M8 3v4m8-4v4M3 10h18"/>',
		'eye'         => '<path d="M2.2 12S5.8 5.5 12 5.5 21.8 12 21.8 12 18.2 18.5 12 18.5 2.2 12 2.2 12Z"/><circle cx="12" cy="12" r="3"/>',
		'comment'     => '<path d="M21 11.5a8 8 0 0 1-11.6 7.2L3 21l2.3-6.3A8 8 0 1 1 21 11.5Z"/>',
		'tag'         => '<path d="m20.6 13.4-7.2 7.2a2 2 0 0 1-2.8 0l-7.2-7.2A2 2 0 0 1 2.8 12V4.8A2 2 0 0 1 4.8 2.8H12a2 2 0 0 1 1.4.6l7.2 7.2a2 2 0 0 1 0 2.8Z"/><circle cx="7.5" cy="7.5" r="1.3" fill="currentColor" stroke="none"/>',
		'folder'      => '<path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h3.3a2 2 0 0 1 1.6.8l1 1.4a2 2 0 0 0 1.6.8h5.5A2.5 2.5 0 0 1 21 10.5v6A2.5 2.5 0 0 1 18.5 19h-13A2.5 2.5 0 0 1 3 16.5Z"/>',
		'star'        => '<path d="m12 3.6 2.6 5.3 5.9.9-4.3 4.1 1 5.8-5.2-2.7-5.2 2.7 1-5.8L3.5 9.8l5.9-.9Z"/>',
		'check'       => '<path d="m5 13 4.2 4.2L19 7"/>',
		'check-circle'=> '<circle cx="12" cy="12" r="9"/><path d="m8.3 12.2 2.6 2.6 4.8-5"/>',
		'plus'        => '<path d="M12 5v14M5 12h14"/>',
		'minus'       => '<path d="M5 12h14"/>',
		'phone'       => '<path d="M21 16.5v2.6a2 2 0 0 1-2.2 2 19.4 19.4 0 0 1-8.5-3 19.1 19.1 0 0 1-5.9-5.9 19.4 19.4 0 0 1-3-8.6A2 2 0 0 1 3.4 3H6a2 2 0 0 1 2 1.7c.1 1 .4 2 .7 2.9a2 2 0 0 1-.5 2.1L7.1 10.9a15.5 15.5 0 0 0 5.9 5.9l1.2-1.1a2 2 0 0 1 2.1-.5c.9.3 1.9.6 2.9.7a2 2 0 0 1 1.8 2Z"/>',
		'mail'        => '<rect x="2.5" y="5" width="19" height="14" rx="2.5"/><path d="m3.5 7 7.4 5.2a2 2 0 0 0 2.2 0L20.5 7"/>',
		'map-pin'     => '<path d="M20 10.5c0 5.5-8 12-8 12s-8-6.5-8-12a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10.5" r="2.8"/>',
		'truck'       => '<path d="M2.5 7.5A1.5 1.5 0 0 1 4 6h8.5v10H2.5Z"/><path d="M12.5 9.5H17l3.5 3.5V16h-8Z"/><circle cx="6.5" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/>',
		'shield'      => '<path d="M12 2.8 4.5 6v6c0 4.6 3.2 8.2 7.5 9.2 4.3-1 7.5-4.6 7.5-9.2V6Z"/><path d="m9 12 2.2 2.2L15.5 10"/>',
		'headphones'  => '<path d="M4 15v-3a8 8 0 0 1 16 0v3"/><path d="M20 15.5a2.5 2.5 0 0 1-2.5 2.5H17v-6h.5A2.5 2.5 0 0 1 20 14.5ZM4 15.5A2.5 2.5 0 0 0 6.5 18H7v-6h-.5A2.5 2.5 0 0 0 4 14.5Z"/>',
		'gift'        => '<rect x="3" y="9" width="18" height="12" rx="2"/><path d="M3 13h18M12 9v12"/><path d="M12 9S10.8 4 8.2 4a2.2 2.2 0 0 0 0 5m3.8 0s1.2-5 3.8-5a2.2 2.2 0 0 1 0 5"/>',
		'sparkles'    => '<path d="m12 3 1.7 4.6L18.3 9l-4.6 1.7L12 15.3l-1.7-4.6L5.7 9l4.6-1.4Z"/><path d="M18.5 15.5 19.4 18l2.5.9-2.5.9-.9 2.5-.9-2.5-2.5-.9 2.5-.9Z"/>',
		'lightning'   => '<path d="M13.5 2.5 4.8 13.2a.6.6 0 0 0 .5 1H11l-.5 7.3 8.7-10.7a.6.6 0 0 0-.5-1H13Z"/>',
		'play'        => '<path d="M8 5.2v13.6L19 12Z"/>',
		'quote'       => '<path d="M9.5 6.5C6.9 7.6 5 10 5 13v4.5h6V11H8c0-1.6.6-2.9 1.5-3.5Zm9.5 0C16.4 7.6 14.5 10 14.5 13v4.5h6V11h-3c0-1.6.6-2.9 1.5-3.5Z"/>',
		'target'      => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.8"/><circle cx="12" cy="12" r="1.3" fill="currentColor" stroke="none"/>',
		'chart'       => '<path d="M4 20V4"/><path d="M4 20h16"/><path d="M8 16V11m4 5V7m4 9v-6"/>',
		'layers'      => '<path d="m12 3 9 4.6-9 4.6-9-4.6Z"/><path d="m3 12.4 9 4.6 9-4.6M3 16.9l9 4.6 9-4.6"/>',
		'code'        => '<path d="m9 8-4 4 4 4m6-8 4 4-4 4"/>',
		'pen'         => '<path d="M4 20h4L20 8a2.8 2.8 0 0 0-4-4L4 16Z"/><path d="m14.5 5.5 4 4"/>',
		'book'        => '<path d="M4 4.5A1.5 1.5 0 0 1 5.5 3H19v18H5.5A1.5 1.5 0 0 1 4 19.5Z"/><path d="M4 17.5A1.5 1.5 0 0 1 5.5 16H19"/>',
		'graduation'  => '<path d="m12 4 10 4.5-10 4.5L2 8.5Z"/><path d="M6.5 10.8V16c0 1.7 2.5 3 5.5 3s5.5-1.3 5.5-3v-5.2"/>',
		'stethoscope' => '<path d="M6 3v5a4 4 0 0 0 8 0V3"/><path d="M6 3H4.5M14 3h1.5"/><path d="M10 12v2.5a5 5 0 0 0 10 0V13"/><circle cx="20" cy="11" r="1.8"/>',
		'home'        => '<path d="m3.5 10.5 8.5-7 8.5 7"/><path d="M5.5 9.5V20h13V9.5"/><path d="M10 20v-6h4v6"/>',
		'building'    => '<rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h2m4 0h2M8 11h2m4 0h2M8 15h2m4 0h2M10 21v-3h4v3"/>',
		'utensils'    => '<path d="M6 3v8a2.5 2.5 0 0 0 5 0V3M8.5 11v10"/><path d="M17 3c-1.7 0-3 2.2-3 5s1.3 4 3 4v9"/>',
		'camera'      => '<path d="M3 8.5A2.5 2.5 0 0 1 5.5 6h1.8l1.2-2h6.9l1.2 2h2.1A2.5 2.5 0 0 1 21 8.5v9A2.5 2.5 0 0 1 18.5 20h-13A2.5 2.5 0 0 1 3 17.5Z"/><circle cx="12" cy="13" r="3.6"/>',
		'globe'       => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a15 15 0 0 1 0 18 15 15 0 0 1 0-18Z"/>',
		'sun'         => '<circle cx="12" cy="12" r="4"/><path d="M12 2.5v2M12 19.5v2M4.2 4.2l1.4 1.4M18.4 18.4l1.4 1.4M2.5 12h2M19.5 12h2M4.2 19.8l1.4-1.4M18.4 5.6l1.4-1.4"/>',
		'moon'        => '<path d="M20 14.2A8.4 8.4 0 0 1 9.8 4 8.5 8.5 0 1 0 20 14.2Z"/>',
		'filter'      => '<path d="M3 5h18l-7 8v6l-4 2v-8Z"/>',
		'grid'        => '<rect x="3.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.6"/>',
		'list'        => '<path d="M8 6h13M8 12h13M8 18h13M3.5 6h.01M3.5 12h.01M3.5 18h.01"/>',
		'refresh'     => '<path d="M20.5 12a8.5 8.5 0 1 1-2.5-6"/><path d="M20.5 4v5h-5"/>',
		'download'    => '<path d="M12 3v12m0 0 4.5-4.5M12 15l-4.5-4.5"/><path d="M4 17v2.5A1.5 1.5 0 0 0 5.5 21h13a1.5 1.5 0 0 0 1.5-1.5V17"/>',
		'external'    => '<path d="M14 4h6v6"/><path d="M20 4 11 13"/><path d="M18 14v5.5A1.5 1.5 0 0 1 16.5 21h-11A1.5 1.5 0 0 1 4 19.5v-11A1.5 1.5 0 0 1 5.5 7H11"/>',
		'whatsapp'    => '<path d="M3.5 20.5 5 16.4A8.2 8.2 0 1 1 8.2 19.5Z"/><path d="M8.9 9.1c.2 1.9 2.4 4.1 4.3 4.3l1-1 1.7.8v1.4c-2.8.6-6.9-3.5-6.3-6.3h1.4l.8 1.7Z" fill="currentColor" stroke="none"/>',
		'telegram'    => '<path d="M21 4 2.8 11.1l5 1.6L19 6.6l-8.6 8.1.3 5 2.9-3.4 4.2 3.1Z"/>',
		'instagram'   => '<rect x="3.5" y="3.5" width="17" height="17" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17" cy="7" r="1.1" fill="currentColor" stroke="none"/>',
		'linkedin'    => '<rect x="3.5" y="3.5" width="17" height="17" rx="3"/><path d="M8 10.5V17M8 7.4v.1M12 17v-3.6a2.1 2.1 0 0 1 4.2 0V17"/>',
		'twitter'     => '<path d="M4 4h3.6l5 6.7L18 4h2l-6.6 7.7L20.5 20H17l-5.3-7.1L5.6 20H3.5l7.2-8.4Z"/>',
		'youtube'     => '<rect x="2.5" y="5.5" width="19" height="13" rx="4"/><path d="m10.3 9.4 5 2.6-5 2.6Z" fill="currentColor" stroke="none"/>',
		'aparat'      => '<circle cx="12" cy="12" r="8.6"/><circle cx="12" cy="12" r="3"/><path d="M6.2 5.4 8 8m8-2.6L14.2 8M6.2 18.6 8 16m8 2.6L14.2 16"/>',
	);

	/**
	 * Filters the theme icon set so child themes can add or replace icons.
	 *
	 * @param array<string,string> $icons Icon name mapped to SVG inner markup.
	 */
	$icons = apply_filters( 'mahan_icon_set', $icons );

	return $icons;
}

/**
 * Returns the list of icon names, for use in Elementor select controls.
 *
 * @return array<string,string>
 */
function mahan_icon_choices() {
	$choices = array( '' => __( 'بدون آیکون', 'mahan' ) );

	foreach ( array_keys( mahan_icon_set() ) as $name ) {
		$choices[ $name ] = $name;
	}

	return $choices;
}

/**
 * Wraps the first N words of a heading in a highlight span.
 *
 * @param string $text  Heading text.
 * @param int    $words How many leading words to highlight.
 * @return string
 */
function mahan_highlight_words( $text, $words = 1 ) {
	$parts = preg_split( '/\s+/u', trim( (string) $text ), -1, PREG_SPLIT_NO_EMPTY );

	if ( ! $parts ) {
		return '';
	}

	$words     = max( 0, min( (int) $words, count( $parts ) ) );
	$highlight = array_slice( $parts, 0, $words );
	$rest      = array_slice( $parts, $words );

	$out = '';

	if ( $highlight ) {
		$out .= '<span class="mahan-highlight">' . esc_html( implode( ' ', $highlight ) ) . '</span>';
	}

	if ( $rest ) {
		$out .= ' ' . esc_html( implode( ' ', $rest ) );
	}

	return $out;
}

/**
 * Returns a placeholder image URL for posts with no featured image.
 *
 * @return string
 */
function mahan_placeholder_image() {
	return MAHAN_URI . 'assets/images/placeholder.svg';
}

/**
 * Builds a `srcset`-aware thumbnail, falling back to the theme placeholder.
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @param array  $attr    Extra image attributes.
 * @return string
 */
function mahan_thumbnail( $post_id, $size = 'mahan-card', $attr = array() ) {
	if ( has_post_thumbnail( $post_id ) ) {
		return get_the_post_thumbnail( $post_id, $size, $attr );
	}

	$attr = wp_parse_args(
		$attr,
		array(
			'class'   => 'mahan-thumb-placeholder',
			'alt'     => get_the_title( $post_id ),
			'loading' => 'lazy',
		)
	);

	return sprintf(
		'<img src="%1$s" class="%2$s" alt="%3$s" loading="%4$s" />',
		esc_url( mahan_placeholder_image() ),
		esc_attr( $attr['class'] ),
		esc_attr( $attr['alt'] ),
		esc_attr( $attr['loading'] )
	);
}

/**
 * Sanitizes a value against a whitelist, falling back to the first entry.
 *
 * @param string $value   Value to check.
 * @param array  $allowed Allowed values.
 * @return string
 */
function mahan_sanitize_choice( $value, array $allowed ) {
	return in_array( $value, $allowed, true ) ? $value : reset( $allowed );
}
