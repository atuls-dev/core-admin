<?php 
session_start();

require_once "authenticate.php";

if ($isLoggedIn) {
    $util->redirect("dashboard.php");
}
?>

<a href="login.php">Login</a>