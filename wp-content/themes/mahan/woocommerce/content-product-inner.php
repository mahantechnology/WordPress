<?php
/**
 * One product card without its own list wrapper.
 *
 * The product carousel element needs the card markup inside its own slide
 * element, so this template runs the loop hooks without the surrounding <li>.
 *
 * @package Mahan
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

do_action( 'woocommerce_before_shop_loop_item' );
do_action( 'woocommerce_before_shop_loop_item_title' );
do_action( 'woocommerce_shop_loop_item_title' );
do_action( 'woocommerce_after_shop_loop_item_title' );
do_action( 'woocommerce_after_shop_loop_item' );
