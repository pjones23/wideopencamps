<?php
require_once 'ChromePhp.php';
require_once 'WufooPHPAPIWrapper/WufooValueObjects.php';
require_once 'Registration.php';
require_once 'CampInfo.php';
require_once 'Camper.php';

class RegistrationBuilder{

  public function buildRegistration($formHash, $entry, $entryMap){
    //ChromePhp::log($entry->{'EntryId'}); // gives EntryID
    //ChromePhp::log($entryMap{'email'}); // gives field of Email
    //ChromePhp::log($entry->{$entryMap{'email'}}); // gives the email
    $email = "";
    if(isset($entry->{$entryMap{'email'}})){
      $email = $entry->{$entryMap{'email'}};
    }
    //ChromePhp::log($email);

    $dateCreated = "";
    if(isset($entry->{$entryMap{'dateCreated'}})){
      $dateCreated = $entry->{$entryMap{'dateCreated'}};
    }
    //ChromePhp::log($dateCreated);

    $athleteCount = "";
    if(isset($entry->{$entryMap{'athleteCount'}})){
      $athleteCount = $entry->{$entryMap{'athleteCount'}};
    }

    ChromePhp::log("athleteCount: ".$athleteCount);

    $registration = new Registration($email, $dateCreated);

    $paymentStatus = "";
    if(isset($entry->{$entryMap{'status'}})){
      $paymentStatus = $entry->{$entryMap{'status'}};
    }
    //ChromePhp::log($paymentStatus);

    $paymentAmount = "";
    if(isset($entry->{$entryMap{'amount'}})){
      $paymentAmount = $entry->{$entryMap{'amount'}};
    }
    //ChromePhp::log($paymentAmount);

    if(!empty($paymentStatus) && !empty($paymentAmount)){
      $payment = array('paymentStatus' => $paymentStatus, 'paymentAmount' => $paymentAmount);
      $registration->setPayment($payment);
    }

    $athleteCount = "";
    if(isset($entry->{$entryMap{'athleteCount'}})){
      $athleteCount = $entry->{$entryMap{'athleteCount'}};
    }
    //ChromePhp::log($athleteCount);

    //First Camper
    $firstCamperCampOne = "";
    if(isset($entry->{$entryMap{'firstCamperCampOne'}})){
      $firstCamperCampOne = $entry->{$entryMap{'firstCamperCampOne'}};
    }
    //ChromePhp::log($firstCamperCampOne);

    $firstCamperCampTwo = "";
    if(isset($entry->{$entryMap{'firstCamperCampTwo'}})){
      $firstCamperCampTwo = $entry->{$entryMap{'firstCamperCampTwo'}};
    }
    //ChromePhp::log($firstCamperCampTwo);
    // if no camp found, check if middle school form... if middle school form, assume middle school is camp one
    if(empty($firstCamperCampOne) && empty($firstCamperCampTwo) && isMiddleSchoolCampForm($formHash)){
      $firstCamperCampOne = getMiddleSchoolCamp();
    }

    ChromePhp::log("camp one: ".$firstCamperCampOne);
    if(!empty($firstCamperCampOne) || !empty($firstCamperCampTwo)){
      ChromePhp::log("Camper 1 present");
      $firstCamperFirstName = "";
      if(isset($entry->{$entryMap{'firstCamperFirstName'}})){
        $firstCamperFirstName = $entry->{$entryMap{'firstCamperFirstName'}};
      }
      //ChromePhp::log($firstCamperFirstName);

      $firstCamperLastName = "";
      if(isset($entry->{$entryMap{'firstCamperLastName'}})){
        $firstCamperLastName = $entry->{$entryMap{'firstCamperLastName'}};
      }
      //ChromePhp::log($firstCamperLastName);

      $firstCamperCampOneType = "";
      if(isset($entry->{$entryMap{'firstCamperCampOneType'}})){
        $firstCamperCampOneType = $entry->{$entryMap{'firstCamperCampOneType'}};
      }
      // ChromePhp::log($firstCamperCampOneType);

      $firstCamperCampTwoType = "";
      if(isset($entry->{$entryMap{'firstCamperCampTwoType'}})){
        $firstCamperCampTwoType = $entry->{$entryMap{'firstCamperCampTwoType'}};
      }
      // ChromePhp::log($firstCamperCampTwoType);

      // Create camper
      $camperOne = new Camper($firstCamperFirstName, $firstCamperLastName, $firstCamperCampOne,
        $firstCamperCampOneType, $firstCamperCampTwo, $firstCamperCampTwoType);
      // Add camper to registration
      $registration->addCamper($camperOne);
    }

    // Second Camper

    $secondCamperCampOne = "";
    if(isset($entry->{$entryMap{'secondCamperCampOne'}})){
      $secondCamperCampOne = $entry->{$entryMap{'secondCamperCampOne'}};
    }
    //ChromePhp::log($secondCamperCampOne);

    $secondCamperCampTwo = "";
    if(isset($entry->{$entryMap{'secondCamperCampTwo'}})){
      $secondCamperCampTwo = $entry->{$entryMap{'secondCamperCampTwo'}};
    }
    //ChromePhp::log($secondCamperCampTwo);

    if($athleteCount === "Two athletes" && empty($secondCamperCampOne) && empty($secondCamperCampTwo) && isMiddleSchoolCampForm($formHash)){
      $secondCamperCampOne = getMiddleSchoolCamp();
    }

    ChromePhp::log("camp two: ".$secondCamperCampOne);
    if(!empty($secondCamperCampOne) || !empty($secondCamperCampTwo)){
      ChromePhp::log("Camper 2 present");
      $secondCamperFirstName = "";
      if(isset($entry->{$entryMap{'secondCamperFirstName'}})){
        $secondCamperFirstName = $entry->{$entryMap{'secondCamperFirstName'}};
      }
      //ChromePhp::log($secondCamperFirstName);

      $secondCamperLastName = "";
      if(isset($entry->{$entryMap{'secondCamperLastName'}})){
        $secondCamperLastName = $entry->{$entryMap{'secondCamperLastName'}};
      }
      //ChromePhp::log($secondCamperLastName);

      $secondCamperCampOneType = "";
      if(isset($entry->{$entryMap{'secondCamperCampOneType'}})){
        $secondCamperCampOneType = $entry->{$entryMap{'secondCamperCampOneType'}};
      }
      //ChromePhp::log($secondCamperCampOne);

      $secondCamperCampTwoType = "";
      if(isset($entry->{$entryMap{'secondCamperCampTwoType'}})){
        $secondCamperCampTwoType = $entry->{$entryMap{'secondCamperCampTwoType'}};
      }
      //ChromePhp::log($secondCamperCampTwoType);

      // Create camper
      $camperTwo = new Camper($secondCamperFirstName, $secondCamperLastName, $secondCamperCampOne,
        $secondCamperCampOneType, $secondCamperCampTwo, $secondCamperCampTwoType);
      // Add camper to registration
      $registration->addCamper($camperTwo);
    }
    // ChromePhp::log($registration->getCampers());
    // foreach ($registration->getCampers() as $camper) {
    //   ChromePhp::log($camper->getName());
    // }
    return $registration;
  }
}

?>
