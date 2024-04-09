<?php
session_start();
if(!isset($_SESSION['CurrentUser'])){
    $_SESSION['message'] = "Login to Access Dashboard";
    header("Location: ../index.php");
    exit(0);
}  
?>