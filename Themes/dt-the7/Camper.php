<?php
require_once 'CampInfo.php';
class Camper{

  private $firstName;
  private $lastName;
  private $campOne;
  private $campOneType;
  private $campTwo;
  private $campTwoType;

  function __construct($firstName, $lastName, $campOne, $campOneType, $campTwo, $campTwoType){
    $this->firstName = $firstName;
    $this->lastName = $lastName;
    $this->campOne = $campOne;
    $this->campOneType = $campOneType;
    $this->campTwo = $campTwo;
    $this->campTwoType = $campTwoType;
  }

  public function getName(){
    return $this->firstName." ".$this->lastName;
  }

  public function getFirstName(){
    return $this->firstName;
  }

  public function getLastName(){
    return $this->lastName;
  }

  public function getCampOne(){
    return getCampFormattedForPayBalance($this->campOne);
  }

  public function getCampOneType(){
    global $middleSchoolCamps;
    if(!empty($this->campOne) && empty($this->campOneType)){
      if(in_array($this->campOne, $middleSchoolCamps)){
        return "Day Camper";
      } else {
        return "Resident Camper";
      }
    } else {
      return $this->campOneType;
    }
  }

  public function getCampTwo(){
    return getCampFormattedForPayBalance($this->campTwo);
  }

  public function getCampTwoType(){
    global $middleSchoolCamps;
    if(!empty($this->campTwo) && empty($this->campTwoType)){
      if(in_array($this->campOne, $middleSchoolCamps)){
        return "Day Camper";
      } else {
        return "Resident Camper";
      }
    } else {
      return $this->campTwoType;
    }
  }

  public function getCost(){
    $cost = 0;
    if(!empty($this->campOne)){
      $cost = getCampCost($this->campOne, $this->getCampOneType());
    }
    if(!empty($this->campTwo)){
      $cost = $cost + getCampCost($this->campTwo, $this->getCampTwoType());
    }
    return $cost;
  }

}
?>
