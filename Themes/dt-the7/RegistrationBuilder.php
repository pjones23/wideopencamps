<?php
require_once 'ChromePhp.php';
require_once 'WufooPHPAPIWrapper/WufooValueObjects.php';

class RegistrationBuilder{

  // public function buildRegistration($email, $firstCamperFirstName, $firstCamperLastName,
  // $firstCamperSelectedCamps, $firstCamperCamperTypes, $secondCamperFirstName, $secondCamperLastName,
  // $secondCamperSelectedCamps, $secondCamperCamperTypes){
  //   ChromePhp::log($email);
  //   ChromePhp::log($firstCamperFirstName);
  //   ChromePhp::log($firstCamperLastName);
  //   ChromePhp::log($firstCamperSelectedCamps);
  //   ChromePhp::log($firstCamperCamperTypes);
  //   ChromePhp::log($secondCamperFirstName);
  //   ChromePhp::log($secondCamperLastName);
  //   ChromePhp::log($secondCamperSelectedCamps);
  //   ChromePhp::log($secondCamperCamperTypes);
  // }

  public function buildRegistration($entry, $entryMap){
    ChromePhp::log("buildRegistration");
    //ChromePhp::log($entry->{'EntryId'});
    ChromePhp::log($entryMap{'email'});
    ChromePhp::log($entry->{$entryMap{'email'}});
  }
}

?>
