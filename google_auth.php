<?php
session_start();
require_once "config.php";


$auth = new Auth();
//$db_handle = new DBController();
$util = new Util();


//Google API PHP Library includes
// require_once 'Google/Client.php';
// require_once 'Google/Service/Oauth2.php';

 
// //Create Client Request to access Google API
// $google_client = new Google_Client([
//       'verify'          => false, //https://github.com/guzzle/guzzle/issues/1935#issuecomment-629548739   
// ]);
// //$google_client->setApplicationName("PHP Google OAuth Login Example");
// $google_client->setClientId(CLIENT_ID);
// $google_client->setClientSecret(CLIENT_SECRET);
// $google_client->setRedirectUri(REDIRECT_URL);
// $google_client->setDeveloperKey(DEV_API_KEY);
// //$google_client->addScope("https://www.googleapis.com/auth/userinfo.email");
// $google_client->addScope('email');
// $google_client->addScope('profile');

$google_client = $auth->google_auth();

//Send Client Request
$objOAuthService = new Google_Service_Oauth2($google_client);

// $userData = $objOAuthService->userinfo->get();

// echo "<pre>";print_r($userData); echo "</pre>"; exit;

//Logout
if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
  $google_client->revokeToken();
  header('Location: ' . filter_var(REDIRECT_URL, FILTER_SANITIZE_URL)); //redirect user back to page
}

//Authenticate code from Google OAuth Flow
//Add Access Token to Session
if (isset($_GET['code'])) {
    $google_client->authenticate($_GET['code']);

    if ( $_SESSION['access_token'] = $google_client->getAccessToken() ) {
        $userData = $objOAuthService->userinfo->get();

        //echo "<pre>";print_r($userData); echo "</pre>"; exit;
      
        if(!empty($userData)) {
              //$objDBController = new DBController();
            $existing_member = $auth->getUserByEmail($userData->email);
            if(empty($existing_member)) {
                $insert_id = $auth->insertGoogleUser($userData->givenName, $userData->familyName, $userData->email, $userData->id );

                $_SESSION["user_id"] = $insert_id;

            }else{
                $auth->updateGoogleuser($userData->email, $userData->id);
                $_SESSION["user_id"] = $existing_member['user_id'];
            }
            $util->redirect("dashboard.php");
        }else{
            $_SESSION['error'][] = 'Access token expired!';
        }
    }else{
        $_SESSION['error'][] = 'Authentication failed.';
    }
    $util->redirect("login.php");
  //header('Location: ' . filter_var(REDIRECT_URL, FILTER_SANITIZE_URL));
} else {
      $authUrl = $google_client->createAuthUrl();
}

//Set Access Token to make Request
// if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
//   $google_client->setAccessToken($_SESSION['access_token']);
// }

//Get User Data from Google Plus
//If New, Insert to Database


?>
