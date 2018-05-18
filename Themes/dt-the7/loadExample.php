<?php
require_once 'ChromePhp.php';


//class WCSF_Example {
  
  /**
	 * The construct method. This is where we hook all of our scripts outlined below.
	 */
	//public function __construct() {
  	function initializeExample(){
  		// Enqueue our ajax with WordPress
		add_action( 'wp_enqueue_scripts', 'add_javascript');
		//add_javascript();
		// Hook our Ajax script with the action we specified in the ajax request
		ChromePhp::log("Adding Ajax actions");
		//add_action( 'wp_ajax_getBalances', array( $this, 'getBalances' ) );
		//add_action( 'wp_ajax_nopriv_getBalances', array( $this, 'getBalances' ) );
		add_action( 'wp_ajax_getBalances', 'getBalances');
		add_action( 'wp_ajax_nopriv_getBalances', 'getBalances');
  	}
  
  /**
		 * Enqueue our JavaScriptssssss
		 */
  function add_javascript() {
	// Enqueue our jQuery. You never know if an install is loading it!
	wp_enqueue_script( 'jquery' );
	// Call our script that contains the Ajaxy goodness.
	wp_enqueue_script( 'ajax_getBalances', get_template_directory_uri() . '/js/script.js');
	// Although used for translation, this function allows us to load arbitrary JS built with PHP into the head of our WP theme
	// Without modifying the themes header.php :) #magix
	// We only need the admin ajax file in WordPress, so that's all we'll do.
	wp_localize_script( 'ajax_getBalances', 'balancesAjax', array(
	  'ajaxurl' => admin_url( 'admin-ajax.php' ),
	) );
  }
  
  /**
		 * The actual script to process the Ajax request.
		 * This function is called when we pass the "action" key through $_POST and WordPress will map that to the proper add_action().
		 * We'll process and then return the data in JSON form. Make sure you sanitize yo data! Safty first.
		 */
  function getBalances() {
  	ChromePhp::log("processing ajax");
	ChromePhp::log($_POST['data']);
	// Check that we requested this from the input field and the nonce is valid. Prevents malicious users.
	//if ( ! isset( $_POST['submission'] ) && ! $_POST['submission'] && ! wp_verify_nonce( $_POST['nonce'], 'wcsf-ajax' ) )
	  //return;
	echo json_encode( array(
	  'body' => wp_kses_post( $_POST['data'] ),
	) );
	// This funciton is REQUIRED within WordPress or else you'll get 'parse' errors 
	// because there's a zero at the end of your JSON
	wp_die(); 
  }
  
  /**
		 * Add our input/button at the end of the_content() function
		 */
  function add_button() {
	echo '<form id="wcsf-example">';
	echo '<input type="text" class="wcsf-text-field" placeholder="Add your text" value="">';
	echo '<input type="submit" class="wcsf-submit-field" value="Add yo text!" />';
	echo '<input type="hidden" name="wcsf-submitted" class="wcsf-submitted" value="true" />';
	wp_nonce_field( 'wcsf-ajax', 'wcsf-nonce' ); // Adds our nonce and creates a unique key automatically! #moremagix
	echo '</form>';
  }
  
//}

// Initiate our class.
//function initializeExample(){
	//$wcsf_example = New WCSF_Example();
//}
?>