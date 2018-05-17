<?php
/**
 * Vogue theme.
 *
 * @since 1.0.0
 */

require_once 'ChromePhp.php';
require_once 'loadExample.php';
//ChromePhp::log($_SERVER);
 
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
//ChromePhp::log(get_the_ID());

// ---------------------------------
add_filter('initialize_page', function($arg){
    if(is_page(1657)){
		ChromePhp::log("checking balances");
		initializeExample();
		if(has_action('wp_ajax_nopriv_wcsf_ajax')) {
			// action exists so execute it
			ChromePhp::log("Action exists");
		} else {
			// action has not been registered
			ChromePhp::log("Action does not exists");
		}
	} else {
		ChromePhp::log("doing other things");
	}
});