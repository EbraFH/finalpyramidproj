<?php
class userClass{
  protected $userId;
  protected $userName;
  protected $userPassword;
  protected $userPhone;
  protected $userBirthDay;
  protected $userAddress;
  protected $userEmail;
  protected $userRole;
  protected $userStatus;
  //constructor
  public  function __construct($userId, $userName,$userPassword,$userPhone, $userBirthDay,$userAddress, $userEmail,$userRole="Player",$userStatus="Active"){
    $this->userId = $userId;
    $this->userName = $userName;
    $this->userPassword = $userPassword;
    $this->userPhone = $userPhone;
    $this->userBirthDay = $userBirthDay;
    $this->userAddress = $userAddress;
    $this->userEmail = $userEmail;
    $this->userRole = $userRole;
    $this->userStatus = $userStatus;
  }
  //getters and setters
  //userId get/set
  public function getuserId(){
    return $this->userId;
  }
  public function setuserId($Id){
    $this->userId = $Id;
  }
  //userName get/set
  public function getuserName(){
    return $this->userName;
  }
  public function setuserName($Name){
    $this->userName = $Name;
  }
  //password get/set
  public function getuserPassword(){
    return $this->userPassword;
  }
  public function setuserPassowrd($Password){
   $this-> userPassword = $Password;
  }
   //phone number get/set
  public function getuserPhone(){
    return $this->userPhone;
  }
  public function setuserPhone($phoneNumber){
    $this->userPhone = $phoneNumber;
  }
  //BirthDay get/set
  public function getuserBirthDay(){
    return $this-> userBirthDay;
  }
   public function setuserBirthDay($BirthDay){
     $this-> userBirthDay = $BirthDay;
  }
   //Address get/set
  public function getuserAddress(){
    return $this->userAddress;
  }
  public function setuserAddress($address){
    $this->userAddress = $address;
  }
  //email get/set
  public function getuserEmail(){
    return $this->userEmail;
  }
  public function setuserEmail($email)
  {
  $this->userEmail = $email;
  }
  //userRole get/set
  public function getuserRole(){
    return $this->userRole;
  }
  public function setuserRole($Role){
    $this->userRole = $Role;
  }
  //userStatus get/set
  public function getuserStatus(){
    return $this->userStatus;
  }
  public function setuserStatus($Status){
     $this->userStatus = $Status;
  }
}
?>