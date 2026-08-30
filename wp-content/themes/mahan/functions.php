<?php
/**
 * Mahan theme bootstrap.
 *
 * Everything the theme does is registered from inc/class-mahan-theme.php; this
 * file only defines the constants that class needs and hands control over.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

define( 'MAHAN_VERSION', '1.0' );
define( 'MAHAN_DIR', trailingslashit( get_template_directory() ) );
define( 'MAHAN_URI', trailingslashit( get_template_directory_uri() ) );
define( 'MAHAN_INC', MAHAN_DIR . 'inc/' );

require_once MAHAN_INC . 'class-mahan-theme.php';

Mahan_Theme::instance();
