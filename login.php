<?php
session_start();
require_once "config.php";

$auth = new Auth();
$db_handle = new DBController();
$util = new Util();


if ( $auth->isLoggedIn() ) {
    $util->redirect("dashboard.php");
}else{
    $google_client = $auth->google_auth();
    $authUrl = $google_client->createAuthUrl();
}

if (! empty($_POST["login"])) {
    $isAuthenticated = false;
    
    $username = $_POST["user_name"];
    $password = $_POST["user_password"];
    
    $res = $auth->authenticateUser($username, $password);

    if( $res['success'] === true ) {
         $_SESSION["user_id"] = $res['user']["user_id"];
        
        // Set Auth Cookies if 'Remember Me' checked
        if (! empty($_POST["remember"])) {
            setcookie("user_login", $username, $cookie_expiration_time);
            
            $random_password = $util->getToken(16);
            setcookie("random_password", $random_password, $cookie_expiration_time);
            
            $random_selector = $util->getToken(32);
            setcookie("random_selector", $random_selector, $cookie_expiration_time);
            
            $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
            $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
            
            $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
            
            // mark existing token as expired
            $userToken = $auth->getTokenByUsername($username, 0);
            //$util->pr($userToken,1 );
            if (! empty($userToken["id"])) {
                $auth->markAsExpired($userToken["id"]);
            }
            // Insert new token
            $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
        } else {
            $util->clearAuthCookie();
        }
        $util->redirect("dashboard.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>project core</title>

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    
    <link href="<?= BASE_URL ?>css/login.css" rel="stylesheet">
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</head>
<body>
    <div class="container d-flex justify-content-center align-items-center">
        <div class="card">
            <div class="p-3 border-bottom d-flex align-items-center justify-content-center">
                <h5>Sign in</h5>
            </div>
            <div class="p-3 px-4 py-4 border-bottom">
                <form action="" method="post" id="frmLogin">
                     <div class="error-message"><?php if(isset($res['message'])) { echo $res['message']; } ?></div>

                    <input name="user_name" type="text" value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>" class="form-control mb-2" placeholder="Email/Username" />
                    <div class="form"> 
                        <input name="user_password" type="password" value="<?php if(isset($_COOKIE["user_password"])) { echo $_COOKIE["user_password"]; } ?>" class="form-control" placeholder="Password" /> <a href="#">Forgot?</a> 
                    </div>
                    <div class="custom-control custom-checkbox mb-3">
                        <input type="checkbox" name="remember" <?php if(isset($_COOKIE["user_login"])) { echo 'checked'; } ?> 
                        class="custom-control-input" id="remember-me"> 
                        <label class="custom-control-label" for="remember-me">Remember Me</label> 
                    </div>  

                    <input type="submit" name="login" value="Login" class="btn btn-danger btn-block continue" >
                </form>

                <div class="d-flex justify-content-center align-items-center mt-3 mb-3"> 
                    <span class="line"></span> <small class="px-2 line-text">OR</small> 
                    <span class="line"></span> 
                </div> 
                <a class="btn btn-danger btn-block continue facebook-button d-flex justify-content-start align-items-center"> 
                    <i class="fa fa-facebook ml-2"></i> <span class="ml-5 px-4">Continue with facebook</span> </a> 
                <a href="<?=$authUrl?>" class="btn btn-danger btn-block continue google-button d-flex justify-content-start align-items-center"> <i class="fa fa-google ml-2"></i> <span class="ml-5 px-4">Continue with Google</span> </a>

            </div>
            <div class="p-3 d-flex flex-row justify-content-center align-items-center member"> <span>Not a member? </span> <a href="<?php echo BASE_URL; ?>register.php" class="text-decoration-none ml-2">SIGNUP</a> </div>
        </div>
    </div>
</body>
</html>

