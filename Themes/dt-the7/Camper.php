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
    return $this->campOne;
  }

  public function getCampOneType(){
    return $this->campOneType;
  }

  public function getCampTwo(){
    return $this->campTwo;
  }

  public function getCampTwoType(){
    return $this->campTwoType;
  }

  public function getCost(){
    $cost = 0;
    if(!empty($this->campOne) && !empty($this->campOneType)){
      $cost = getCampCost($this->campOne, $this->campOneType);
    }
    if(!empty($this->campTwo) && !empty($this->campTwoType)){
      $cost = $cost + getCampCost($this->campTwo, $this->campTwoType);
    }
    return $cost;
  }

}
?>
