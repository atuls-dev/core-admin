<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	// Defining constant
	define("BASE_URL", "http://localhost/project_core/");
	define("SITE_URL", "http://localhost/project_core/");

	
	define("CURRENT_DATETIME", date('Y-m-s h:i:s') );



    require "class/DBController.php";
    require "class/Auth.php";
    require "class/Util.php";


    //google auth api key: atul.devops@gmail.com
    define("CLIENT_ID", "xxxxxxxxxx-xxxxxxxxxxx.apps.googleusercontent.com");
	define("CLIENT_SECRET", "xxxxxxxxxxxxxxxxxxxxxx");
	define("REDIRECT_URL", "http://localhost/project_core/google_login.php");
	define("DEV_API_KEY", "xxxxxxxxxxxxxxxxxxxxxxx");


?>
