<?php
require_once 'ChromePhp.php';
require_once 'WufooPHPAPIWrapper/WufooApiWrapper.php';
require_once 'WufooPHPAPIWrapper/WufooValueObjects.php';
require_once 'RegistrationBuilder.php';
require_once 'Registration.php';

/**
 * The construct method. This is where we hook all of our scripts outlined below.
 */
//public function __construct() {
function initializeExample() {
	// Enqueue our ajax with WordPress
	add_action('wp_enqueue_scripts', 'add_javascript');
	// Hook our Ajax script with the action we specified in the ajax request
	add_action('wp_ajax_getBalances', 'getBalances');
	add_action('wp_ajax_nopriv_getBalances', 'getBalances');
}

/**
 * Enqueue our JavaScriptssssss
 */
function add_javascript() {
	//ChromePhp::log($_REQUEST);
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
	//ChromePhp::log($_POST['data']);
	//ChromePhp::log($_POST['submission']);
	//ChromePhp::log($_POST['nonce']);
	// Check that we requested this from the input field and the nonce is valid. Prevents malicious users.
	if ( ! isset( $_POST['submission'] ) && ! $_POST['submission'] && ! wp_verify_nonce( $_POST['nonce'], 'action_search_email_nonce' ) )
		return;

	try{
		//$forms = getForms(null);
		//ChromePhp::log($forms);
		global $campFormHashes;
		global $balanceFormHashes;
		$entries = getEntries($campFormHashes);
		//$balanceEntries = getEntries($balanceFormHashes);

		//ChromePhp::log(print_r($entries));
	} catch (Exception $e){
		ChromePhp::log($e->getMessage());
	}

	//echo json_encode(array('body' => wp_kses_post($_POST['data']) . "_posted", ));
	echo json_encode(array('body' => array_values($entries, $balanceEntries)));
	//echo json_encode(array('body' => array_values($forms)));
	// This funciton is REQUIRED within WordPress or else you'll get 'parse' errors
	// because there's a zero at the end of your JSON
	wp_die();
}

$campFormHashes = array(
	array(
		'pmxpg7a0mnbsnb', // 2018-camper-application-medical-form-deposit
		'200', // email field
		'212' // # of campers
	),
	array(
		's1h1wpu1e3p7m2', // 2018-camper-application-medical-form-full-pay
		'200', // email field
		'212', // # of campers
	),
	array(
		'zx3659u01qss9c', // 2018-middle-school-camp-application-deposit
		'200', // email field
		'212' // # of campers
	),
	array(
		'z5eqt7w169o76z', // 2018-middle-school-camper-application-full-pay
		'200', // email field
		'212' // # of campers
	),
);

$balanceFormHashes = array(
	array(
		'k16c3f9c0jwz7dm', // wide-open-balance
		'12'// email field
	)
);

$apiKey = '0QAK-VS36-I6BO-AEMY';
$subdomain = 'wideopencamps';
function getEntries($formHashes) {
	$entries = array();

	foreach ($formHashes as $formHash) {
		ChromePhp::log($formHash[0]);
		$identifier = "system=true&Filter1=Field". $formHash[1] . "+Is_equal_to+" . $_POST['data'];
		$entry = getEntry($formHash[0], $identifier);
		if(isset($entry) && sizeof($entry) > 0){
			// $entry is an array of WufooEntry objects
			// create Registration using $registrationBuilder
			$registrationBuilder = new RegistrationBuilder;
			$entryIds = array_keys($entry);
			foreach($entryIds as $entryId){
				ChromePhp::log($entry{$entryId});
				//ChromePhp::log($entryId);
			}
			//$registrationBuilder->buildRegistration($entry['Field200'], $entry['Field1'], $entry['Field2'],
			//array($entry['Field99'], $entry['Field100']), array($entry['Field30'], $entry['Field210']), $entry['Field13'], $entry['Field14'],
		  //array($entry['Field224'], $entry['Field225']), array($entry['Field324'], $entry['Field325']));
			//ChromePhp::log($entry);
			//ChromePhp::log($entry{146});
			array_push($entries, $entry);
		}
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
