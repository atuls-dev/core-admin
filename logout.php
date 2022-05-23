<?php
session_start();

require "config.php";
$auth = new Auth();
$util = new Util();
//Clear Session
$_SESSION["user_id"] = "";

if( isset($_SESSION['access_token']) ) {

	$google_client = $auth->google_auth();
	unset($_SESSION['access_token']);
	$google_client->revokeToken();
	
}

session_destroy();

// clear cookies
$util->clearAuthCookie();

header("Location: ./");
?>