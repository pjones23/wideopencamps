<?php
class Registration{

  private $email;
  private $campers = array();

  function __construct($email){
    $this->email = $email;

  }

  public function addCamper($camper){
    ChromePhp::log("Adding camper");
    //ChromePhp::log($camper);
    array_push($this->campers, $camper);
  }

  public function getCampers(){
    return $this->campers;
  }

  public function getCost(){
    // return total cost from campers
  }

}
?>
