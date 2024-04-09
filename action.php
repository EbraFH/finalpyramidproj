<?php
require_once("php/dbClass.php");
require_once("php/userClass.php");
$db = new dbClass();
if(isset($_POST['action']) && $_POST['action'] == 'register'){
$User = new userClass($_POST['Id'],$_POST['userName'],$_POST['password'], $_POST['userPhone'],$_POST['userBirthDay'],$_POST['userAddress'],$_POST["userEmail"]);
//adding the user to the database
$db = $db->insertUsers($User);
}
if(isset($_POST['action']) && $_POST['action'] == 'login'){
    session_start();
    $Id = $_POST['userId'];
    $password = $_POST['password'];
    $User = $db-> userLogin($Id,$_POST['password']);
    if($User != null){
    $_SESSION['CurrentUser'] = ['userId'=>$User->getuserId(),'userName'=>$User->getuserName(),'userPassword'=>$User->getuserPassword(),'userPhone'=>$User->getuserPhone(),'userBirthDay'=>$User->getuserBirthDay(),'userAddress'=>$User->getuserAddress(),'userEmail'=>$User->getuserEmail(),'userRole'=>$User->getuserRole(),'userStatus'=>$User->getuserStatus()];
    if(!($_SESSION['CurrentUser']['userRole'] == 'Player')){
        $db->endTournament($_SESSION['CurrentUser']['userId']);
    }
    if($_SESSION['CurrentUser']['userRole'] == 'Player'){
        $db->expireInvitation($_SESSION['CurrentUser']['userId']);
        $db->expiredGames($_SESSION['CurrentUser']['userId'],$_SESSION['CurrentUser']['userEmail']);
    }
    if(!empty($_POST['rem'])){
        setcookie("Id",$Id,time()+(10*365*24*60*60));
        setcookie("Password",$password,time()+(10*365*24*60*60));
    }
    else{
        if(isset($_COOKIE['Id'])){
            setcookie("Id","");
        }
        if(isset($_COOKIE['Password'])){
            setcookie("Password","");
        }
    }
    
    }
    else{
        echo "Login Failed! Check your Id and Password";
    }
if(isset($_POST['action']) && $_POST['action']=='forgot'){
    $email= $_POST['email'];

    $db = $db->resetPassword($email);
    echo $db;
}

if(isset($_POST['action']) && $_POST['action']=='updateNewPassword'){
    $password= $_POST['password'];
    $email= $_POST['email'];
    $db = $db->updatePassword($password,$email);

}
}
?>