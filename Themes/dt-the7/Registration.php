<?php
class Registration{

  private $email;
  private $dateCreated;
  private $campers = array();
  private $payment;

  function __construct($email, $dateCreated){
    $this->email = $email;
    $this->dateCreated = $dateCreated;
  }

  public function getEmail(){
    return $this->email;
  }

  public function getDateCreated(){
    return $this->dateCreated;
  }

  public function addCamper($camper){
    array_push($this->campers, $camper);
  }

  public function getCampers(){
    return $this->campers;
  }

  public function getCamps(){
    $camps = array();
    foreach ($this->campers as $camper) {
      $camp = $camper->getCampOne();
      if(!empty($camp) && !in_array($camp, $camps)){
        array_push($camps, $camp);
      }
      $camp = $camper->getCampTwo();
      if(!empty($camp) && !in_array($camp, $camps)){
        array_push($camps, $camp);
      }
    }
    return $camps;
  }

  public function getCost(){
    // return total cost from campers
    $totalCost = 0;
    foreach ($this->campers as $camper) {
      $totalCost = $camper->getCost();
    }
    return $totalCost;
  }

  public function setPayment($payment){
    $this->payment = $payment;
  }

  public function getAmountPaidDuringRegistration(){
    $amount = 0;
    $paid = "Paid" === $this->payment{'paymentStatus'};
    if($paid){
      $amount = $this->payment{'paymentAmount'};
    }
    return $amount;
  }

}
?>
