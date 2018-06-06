<?php
require_once 'ChromePhp.php';
require_once 'WufooPHPAPIWrapper/WufooApiWrapper.php';
require_once 'WufooPHPAPIWrapper/WufooValueObjects.php';
require_once 'RegistrationBuilder.php';
require_once 'Registration.php';
require_once 'Camper.php';

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
		global $campFormHashes;
		global $balanceFormHashes;
		$entries = getEntries($campFormHashes);
		$registrations = getRegistrationsFromEntries($entries);
		ChromePhp::log($registrations);
		$paymentEntries = getEntries($balanceFormHashes);
		$payments = getPaymentsFromEntries($paymentEntries);
		ChromePhp::log($payments);
		$balances = createBalances($registrations, $payments);
	} catch (Exception $e){
		ChromePhp::log($e->getMessage());
	}

	echo json_encode(array('body' => wp_kses_post($_POST['data']) . "_posted", ));
	//echo json_encode(array('body' => array_values($entries, $balanceEntries)));
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

/*
ChromePhp::log($email);
ChromePhp::log($firstCamperFirstName);
ChromePhp::log($firstCamperLastName);
ChromePhp::log($firstCamperSelectedCamps);
ChromePhp::log($firstCamperCamperTypes);
ChromePhp::log($secondCamperFirstName);
ChromePhp::log($secondCamperLastName);
ChromePhp::log($secondCamperSelectedCamps);
ChromePhp::log($secondCamperCamperTypes);
*/

$entryMaps = array(
	array(
		'hash' => 'pmxpg7a0mnbsnb', // 2018-camper-application-medical-form-deposit
		'email' => 'Field200', // email field
		'dateCreated' => 'DateCreated', // date created
		'athleteCount' => 'Field212', // # of campers
		'firstCamperFirstName' => 'Field1', // first camper first name
		'firstCamperLastName' => 'Field2', // first camper last name
		'firstCamperCampOne' => 'Field99', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'firstCamperCampTwo' => 'Field100', // Late July Alabama (July 23 - 27) (empty if not selected)
		'firstCamperCampOneType' => 'Field30', // Mid July Alabama Type of Camper (empty if not selected)
		'firstCamperCampTwoType' => 'Field210', // Late July Alabama Type of Camper (empty if not selected)
		'secondCamperFirstName' => 'Field13', // second camper first name
		'secondCamperLastName' => 'Field14', // second camper last name
		'secondCamperCampOne' => 'Field224', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'secondCamperCampTwo' => 'Field225', // Late July Alabama (July 23 - 27) (empty if not selected)
		'secondCamperCampOneType' => 'Field324', // Mid July Alabama Type of Camper (empty if not selected)
		'secondCamperCampTwoType' => 'Field325', // Late July Alabama Type of Camper (empty if not selected)
		'amount' => 'PurchaseTotal', // Middle School Day Camp (June 20-23) (empty if not selected)
		'status' => 'Status' // Middle School Day Camp (June 20-23) (empty if not selected)
	),
	array(
		'hash' => 's1h1wpu1e3p7m2', // 2018-camper-application-medical-form-full-pay
		'email' => 'Field200', // email field
		'dateCreated' => 'DateCreated', // date created
		'athleteCount' => 'Field212', // # of campers
		'firstCamperFirstName' => 'Field1', // first camper first name
		'firstCamperLastName' => 'Field2', // first camper last name
		'firstCamperCampOne' => 'Field99', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'firstCamperCampTwo' => 'Field100', // Late July Alabama (July 23 - 27) (empty if not selected)
		'firstCamperCampOneType' => 'Field30', // Mid July Alabama Type of Camper (empty if not selected)
		'firstCamperCampTwoType' => 'Field210', // Late July Alabama Type of Camper (empty if not selected)
		'secondCamperFirstName' => 'Field13', // second camper first name
		'secondCamperLastName' => 'Field14', // second camper last name
		'secondCamperCampOne' => 'Field224', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'secondCamperCampTwo' => 'Field225', // Late July Alabama (July 23 - 27) (empty if not selected)
		'secondCamperCampOneType' => 'Field324', // Mid July Alabama Type of Camper (empty if not selected)
		'secondCamperCampTwoType' => 'Field325', // Late July Alabama Type of Camper (empty if not selected)
		'amount' => 'PurchaseTotal', // Middle School Day Camp (June 20-23) (empty if not selected)
		'status' => 'Status' // Middle School Day Camp (June 20-23) (empty if not selected)
	),
	array(
		'hash' => 'zx3659u01qss9c', // 2018-middle-school-camp-application-deposit
		'email' => 'Field200', // email field
		'dateCreated' => 'DateCreated', // date created
		'athleteCount' => 'Field212', // # of campers
		'firstCamperFirstName' => 'Field1', // first camper first name
		'firstCamperLastName' => 'Field2', // first camper last name
		'firstCamperCampOne' => 'Field99', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'firstCamperCampTwo' => 'Field100', // Late July Alabama (July 23 - 27) (empty if not selected)
		'firstCamperCampOneType' => 'Field30', // Mid July Alabama Type of Camper (empty if not selected)
		'firstCamperCampTwoType' => 'Field210', // Late July Alabama Type of Camper (empty if not selected)
		'secondCamperFirstName' => 'Field13', // second camper first name
		'secondCamperLastName' => 'Field14', // second camper last name
		'secondCamperCampOne' => 'Field224', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'secondCamperCampTwo' => 'Field225', // Late July Alabama (July 23 - 27) (empty if not selected)
		'secondCamperCampOneType' => 'Field324', // Mid July Alabama Type of Camper (empty if not selected)
		'secondCamperCampTwoType' => 'Field325', // Late July Alabama Type of Camper (empty if not selected)
		'amount' => 'PurchaseTotal', // Middle School Day Camp (June 20-23) (empty if not selected)
		'status' => 'Status' // Middle School Day Camp (June 20-23) (empty if not selected)
	),
	array(
		'hash' => 'z5eqt7w169o76z', // 2018-middle-school-camper-application-full-pay
		'email' => 'Field200', // email field
		'dateCreated' => 'DateCreated', // date created
		'athleteCount' => 'Field212', // # of campers
		'firstCamperFirstName' => 'Field1', // first camper first name
		'firstCamperLastName' => 'Field2', // first camper last name
		'firstCamperCampOne' => 'Field99', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'firstCamperCampTwo' => 'Field100', // Late July Alabama (July 23 - 27) (empty if not selected)
		'firstCamperCampOneType' => 'Field30', // Mid July Alabama Type of Camper (empty if not selected)
		'firstCamperCampTwoType' => 'Field210', // Late July Alabama Type of Camper (empty if not selected)
		'secondCamperFirstName' => 'Field13', // second camper first name
		'secondCamperLastName' => 'Field14', // second camper last name
		'secondCamperCampOne' => 'Field224', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'secondCamperCampTwo' => 'Field225', // Late July Alabama (July 23 - 27) (empty if not selected)
		'secondCamperCampOneType' => 'Field324', // Mid July Alabama Type of Camper (empty if not selected)
		'secondCamperCampTwoType' => 'Field325', // Late July Alabama Type of Camper (empty if not selected)
		'amount' => 'PurchaseTotal', // Middle School Day Camp (June 20-23) (empty if not selected)
		'status' => 'Status' // Middle School Day Camp (June 20-23) (empty if not selected)
	),
	array(
		'hash' => 'k16c3f9c0jwz7dm', // 2018-middle-school-camper-application-full-pay
		'firstName' => 'Field2', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'lastName' => 'Field3', // Late July Alabama (July 23 - 27) (empty if not selected)
		'campThree' => 'Field117', // Middle School Day Camp (June 20-23) (empty if not selected)
		'campOne' => 'Field114', // Mid July Alabama (July 9 - 13) (empty if not selected)
		'campTwo' => 'Field115', // Late July Alabama (July 23 - 27) (empty if not selected)
		'campThree' => 'Field117', // Middle School Day Camp (June 20-23) (empty if not selected)
		'amount' => 'PurchaseTotal', // Middle School Day Camp (June 20-23) (empty if not selected)
		'status' => 'Status' // Middle School Day Camp (June 20-23) (empty if not selected)
	)
);

$apiKey = '0QAK-VS36-I6BO-AEMY';
$subdomain = 'wideopencamps';
function getEntries($formHashes) {
	$entries = array();

	foreach ($formHashes as $formHash) {
		//ChromePhp::log($formHash[0]);
		$identifier = "system=true&Filter1=Field". $formHash[1] . "+Is_equal_to+" . $_POST['data'];
		$entry = getEntry($formHash[0], $identifier);
		if(isset($entry) && sizeof($entry) > 0){
			// $entry is an array of WufooEntry objects
			// create Registration using $registrationBuilder
			$registrationBuilder = new RegistrationBuilder;
			$wufooEntryObjects = array();
			$entryIds = array_keys($entry);
			foreach($entryIds as $entryId){
				//ChromePhp::log($entry{$entryId});
				array_push($wufooEntryObjects, $entry{$entryId});
			}
			$entries[$formHash[0]] = $wufooEntryObjects;
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

function getRegistrationsFromEntries($entries){
	// $entry is an array of WufooEntry objects
	// create Registration using $registrationBuilder
	$registrations = array();
	if(isset($entries) && sizeof($entries) > 0){
		$entryIds = array_keys($entries);
		foreach($entryIds as $entryId){
			// entry id is the form hash
			$entryMap = getEntryMap($entryId);
			$formRegistrationEntries = $entries{$entryId};
			foreach ($formRegistrationEntries as $formRegistrationEntry) {
				// create Registration object
				$registration = getRegistration($formRegistrationEntry, $entryMap);
		    foreach ($registration->getCampers() as $camper) {
					ChromePhp::log($camper->getName());
				}
				array_push($registrations, $registration);
			}
		}
	}
	return $registrations;
}

function getEntryMap($formHash){
	global $entryMaps;
	$em;
	foreach ($entryMaps as $entryMap) {
		if($entryMap{'hash'} === $formHash){
			$em = $entryMap;
		}
	}
	return $em;
}

function getRegistration($formRegistrationEntry, $entryMap){
	$registrationBuilder = new RegistrationBuilder;
	$registration = $registrationBuilder->buildRegistration($formRegistrationEntry, $entryMap);
	return $registration;
}

function getPaymentsFromEntries($paymentEntries){
	$payments = array();
	if(isset($paymentEntries) && count($paymentEntries) > 0){
		$entryIds = array_keys($paymentEntries);
		foreach ($entryIds as $entryId) {
			// entry id is the form hash
			$entryMap = getEntryMap($entryId);
			$paymentForm = $paymentEntries{$entryId};
			foreach ($paymentForm as $paymentEntry) {
				$payment = getPaymentFromEntry($paymentEntry, $entryMap);
				if(isset($payment)){
					array_push($payments, $payment);
				}
			}
		}
	}
	return $payments;
}

function getPaymentFromEntry($paymentEntry, $entryMap){
	$payment = null;

	$firstName = "";
	if(isset($paymentEntry->{$entryMap{'firstName'}})){
		$firstName = $paymentEntry->{$entryMap{'firstName'}};
	}

	$lastName = "";
	if(isset($paymentEntry->{$entryMap{'lastName'}})){
		$lastName = $paymentEntry->{$entryMap{'lastName'}};
	}

	$campOne = "";
	if(isset($paymentEntry->{$entryMap{'campOne'}})){
		$campOne = $paymentEntry->{$entryMap{'campOne'}};
	}

	$campTwo = "";
	if(isset($paymentEntry->{$entryMap{'campTwo'}})){
		$campTwo = $paymentEntry->{$entryMap{'campTwo'}};
	}

	$campThree = "";
	if(isset($paymentEntry->{$entryMap{'campThree'}})){
		$campThree = $paymentEntry->{$entryMap{'campThree'}};
	}

	$amount = "";
	if(isset($paymentEntry->{$entryMap{'amount'}})){
		$amount = $paymentEntry->{$entryMap{'amount'}};
	}

	$status = "";
	if(isset($paymentEntry->{$entryMap{'status'}})){
		$status = $paymentEntry->{$entryMap{'status'}};
	}

	$payment = array('firstName' => $firstName, 'lastName' => $lastName, 'campOne' => $campOne, 'campTwo' => $campTwo, 'campThree' => $campThree,
		'amount' => $amount, 'status' => $status);
	ChromePhp::log($payment);
	return $payment;
}

function createBalances($registrations, $payments){
	$balances = null;
	$registrationPayments = $payments; // creating copy to not modify original payments array
	foreach ($registrations as $registration) {
		$registrationCost = $registration->getCost();
		$paymentAtRegistration = $registration->getAmountPaidDuringRegistration();
		$remainingBalance = $registrationCost - $paymentAtRegistration;
		ChromePhp::log($remainingBalance);
		foreach ($registrationPayments as $payment) {
			$paid = "Paid" === $payment{'status'};
			ChromePhp::log($paid);
			if(!$paid){
				// remove from array
				$registrationPayments = removeElementFromArray($registrationPayments, $payment);
				continue;
			}
			// check if payment has matching camp
			$isPaymentApplicable = isPaymentApplicableToRegistration($registration, $payment);
			if(!$isPaymentApplicable){
				continue;
			}
			// subtract payment amount from cost
			$paidAmount = (int)$payment{'amount'};
			ChromePhp::log("Payment: ".$paidAmount);
			$remainingBalance = $remainingBalance - $paidAmount;
			$registrationPayments = removeElementFromArray($registrationPayments, $payment);
		}
		ChromePhp::log("Remaining Balance: ".$remainingBalance);
		$balance = createBalance($registration, $remainingBalance);
		ChromePhp::log($balance);
	}
	return $balances;
}

function createBalance($registration, $remainingBalance){
	$email = $registration->getEmail();
	$dateCreated = $registration->getDateCreated();
	$registrationCampers = $registration->getCampers();
	$campers = array();
	foreach($registrationCampers as $camper){
		$description = $camper->getName()." ".$camper->getCampOne()." ".$camper->getCampOneType()." ".$camper->getCampTwo()." ".$camper->getCampTwoType();
		array_push($campers, $description);
	}
	$balance = array('email' => $email, 'date' => $dateCreated, 'campers' => $campers, "remainingBalance" => $remainingBalance);
	return $balance;
}

function removeElementFromArray($array, $element){
	if (($key = array_search($element, $array)) !== false) {
		unset($array[$key]);
	}
	return $array;
}

function isPaymentApplicableToRegistration($registration, $payment){
	$registrationCamps = $registration->getCamps();
	// compare camper name and camps
	// if a payment contains at least one camp that is contains in the registration and the name matches,
	// then it is applicable
	$matchingPayment = false;
	foreach ($registrationCamps as $camp) {
		// ChromePhp::log(trim($camp));
		// ChromePhp::log(trim($payment{'campOne'}));
		// ChromePhp::log(trim($payment{'campTwo'}));
		// ChromePhp::log(trim($payment{'campThree'}));
		$matchingPayment = isSameCamp(trim($camp), trim($payment{'campOne'})) || isSameCamp(trim($camp), trim($payment{'campTwo'})) ||
			isSameCamp(trim($camp), trim($payment{'campThree'}));
		if($matchingPayment){
			break;
		}
	}

	$matchingName = false;
	foreach ($registration->getCampers() as $camper) {
		// ChromePhp::log(trim($camper->getFirstName()));
		// ChromePhp::log(trim($payment{'firstName'}));
		// ChromePhp::log(trim($camper->getFirstName()) === trim($payment{'firstName'}));
		$matchingFirstName = trim($camper->getFirstName()) === trim($payment{'firstName'});
		if($matchingFirstName){
			// ChromePhp::log(trim($camper->getLastName()));
			// ChromePhp::log(trim($payment{'lastName'}));
			// ChromePhp::log(trim($camper->getLastName()) === trim($payment{'lastName'}));
			$matchingName = trim($camper->getLastName()) === trim($payment{'lastName'});
		}
	}
	$isApplicable = $matchingPayment && $matchingName;
	return $isApplicable;
}

$midJulyCamps = array('Mid July Alabama (July 9-13)', 'Mid July Alabama (July 9 - 13)');
$lateJulyCamps = array('Late July Alabama (July 23-27)', 'Late July Alabama (July 23 - 27)');
$middleSchoolCamps = array('Middle School Day Camp (June 20-23)', 'Middle School Day Camp (June 20-23)');
function isSameCamp($campOne, $campTwo){
	global $midJulyCamps;
	global $lateJulyCamps;
	global $middleSchoolCamps;

	$sameCamp = (in_array($campOne, $midJulyCamps) && in_array($campTwo, $midJulyCamps)) ||
		(in_array($campOne, $lateJulyCamps) && in_array($campTwo, $lateJulyCamps)) ||
		(in_array($campOne, $middleSchoolCamps) && in_array($campTwo, $middleSchoolCamps));

	return $sameCamp;
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
