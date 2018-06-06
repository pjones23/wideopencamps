<?php
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
    return 399;
  }

}
?>
