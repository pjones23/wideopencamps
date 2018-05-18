<?php
/**
 * Vogue theme.
 *
 * @since 1.0.0
 */

require_once 'ChromePhp.php';
require_once 'loadExample.php';
 
// File Security Check
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since 1.0.0
 */
if ( ! isset( $content_width ) ) {
	$content_width = 1200; /* pixels */
}

/**
 * Initialize theme.
 *
 * @since 1.0.0
 */
require( trailingslashit( get_template_directory() ) . 'inc/init.php' );

ChromePhp::log("checking balances");
initializeExample();
if(has_action('wp_ajax_getBalances')) {
	// action exists so execute it
	ChromePhp::log("Admin Action exists");
} else {
	// action has not been registered
	ChromePhp::log("Admin Action does not exists");
}
if(has_action('wp_ajax_nopriv_getBalances')) {
	// action exists so execute it
	ChromePhp::log("Public Action exists");
} else {
	// action has not been registered
	ChromePhp::log("Public Action does not exists");
}