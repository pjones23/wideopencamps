<?php
require_once 'ChromePhp.php';
require_once 'WufooPHPAPIWrapper/WufooApiWrapper.php';

/**
 * The construct method. This is where we hook all of our scripts outlined below.
 */
//public function __construct() {
function initializeExample() {
	// Enqueue our ajax with WordPress
	add_action('wp_enqueue_scripts', 'add_javascript');
	//add_javascript();
	// Hook our Ajax script with the action we specified in the ajax request
	ChromePhp::log("Adding Ajax actions");
	//add_action( 'wp_ajax_getBalances', array( $this, 'getBalances' ) );
	//add_action( 'wp_ajax_nopriv_getBalances', array( $this, 'getBalances' ) );
	add_action('wp_ajax_getBalances', 'getBalances');
	add_action('wp_ajax_nopriv_getBalances', 'getBalances');
}

/**
 * Enqueue our JavaScriptssssss
 */
function add_javascript() {
	ChromePhp::log($_REQUEST);
	// Enqueue our jQuery. You never know if an install is loading it!
	wp_enqueue_script('jquery');
	// Call our script that contains the Ajaxy goodness.
	wp_enqueue_script('ajax_getBalances', get_template_directory_uri() . '/js/script.js');
	// Although used for translation, this function allows us to load arbitrary JS built with PHP into the head of our WP theme
	// Without modifying the themes header.php :) #magix
	// We only need the admin ajax file in WordPress, so that's all we'll do.

	wp_localize_script('ajax_getBalances', 'balancesAjax', array('ajaxurl' => admin_url('admin-ajax.php'), 'requestData' => $_REQUEST));
}

/**
 * The actual script to process the Ajax request.
 * This function is called when we pass the "action" key through $_POST and WordPress will map that to the proper add_action().
 * We'll process and then return the data in JSON form. Make sure you sanitize yo data! Safty first.
 */
function getBalances() {
	ChromePhp::log("processing ajax");
	ChromePhp::log($_POST['data']);
	ChromePhp::log($_POST['submission']);
	ChromePhp::log($_POST['nonce']);
	// Check that we requested this from the input field and the nonce is valid. Prevents malicious users.
	if ( ! isset( $_POST['submission'] ) && ! $_POST['submission'] && ! wp_verify_nonce( $_POST['nonce'], 'action_search_email_nonce' ) )
		return;


	$identifier = "system=true&Filter1=Field200+Is_equal_to+" . $_POST['data'];
	ChromePhp::log($identifier);
	try{
		//$forms = getForms(null);
		//ChromePhp::log($forms);
		$entries = getEntries($identifier);
		//ChromePhp::log(print_r($entries));
	} catch (Exception $e){
		ChromePhp::log($e->getMessage());
	}

	//echo json_encode(array('body' => wp_kses_post($_POST['data']) . "_posted", ));
	echo json_encode(array('body' => array_values($entries)));
	//echo json_encode(array('body' => array_values($forms)));
	// This funciton is REQUIRED within WordPress or else you'll get 'parse' errors
	// because there's a zero at the end of your JSON
	wp_die();
}

$formHashes = array(
	'pmxpg7a0mnbsnb', //2018-camper-application-medical-form-deposit
	's1h1wpu1e3p7m2', //2018-camper-application-medical-form-full-pay
	'zx3659u01qss9c', //2018-middle-school-camp-application-deposit
	'z5eqt7w169o76z' //2018-middle-school-camper-application-full-pay
);
$apiKey = '0QAK-VS36-I6BO-AEMY';
$subdomain = 'wideopencamps';
function getEntries($identifier = null) {
	global $formHashes;

	$entries = array();

	foreach ($formHashes as $formHash) {
		ChromePhp::log($formHash);
		$entry = getEntry($formHash, $identifier);
		ChromePhp::log($entry);
		array_push($entries, $entry);
	}

	return $entries;
}

function getEntry($formHash, $identifier = null) {
	global $apiKey;
	global $subdomain;

	$wrapper = new WufooApiWrapper($apiKey, $subdomain);
	return $wrapper->getEntries($formHash, 'forms', $identifier);
}

/**
 * Add our input/button at the end of the_content() function
 */
function add_button() {
	echo '<form id="wcsf-example">';
	echo '<input type="text" class="wcsf-text-field" placeholder="Add your text" value="">';
	echo '<input type="submit" class="wcsf-submit-field" value="Add yo text!" />';
	echo '<input type="hidden" name="wcsf-submitted" class="wcsf-submitted" value="true" />';
	wp_nonce_field('wcsf-ajax', 'wcsf-nonce');
	// Adds our nonce and creates a unique key automatically! #moremagix
	echo '</form>';
}

?>
