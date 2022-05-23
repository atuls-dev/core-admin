<?php
session_start();


require_once "authenticate.php";

if ($isLoggedIn) {
    $util->redirect("dashboard.php");
}

if (! empty($_POST["register"])) {
    $isAuthenticated = false;
    
    $userName = $_POST["user_name"];
    $userEmail = $_POST["user_email"];
    $password = $_POST["user_password"];
    $confirmPassword = $_POST["confirm_password"];

    $error = array();
    
    $user = $auth->getUserByUsername($userName);
    if (  !empty($user) ) {
        $error[] = 'Username already taken.';
    }
    $user = $auth->getUserByEmail($userEmail);
    if (  !empty($user) ) {
        $error[] = 'Email already taken.';
    }

    if( $password !== $confirmPassword ) {
        $error[] = "Password doesn't match.";
    }


    //$util->pr($user);
    $util->pr($error);

    if ( empty($error) ) {

        $hashedPass = password_hash($password, PASSWORD_DEFAULT);

        $auth->createUser($userName, $userEmail, $hashedPass);
        
        $message = 'Registered Successfully';

        //$util->redirect("login.php");

    }

    // if (password_verify($password, $user[0]["user_password"])) {
    //     $isAuthenticated = true;
    // }
    
    // if ($isAuthenticated) {
    //     $_SESSION["user_id"] = $user[0]["user_id"];
        
    //     // Set Auth Cookies if 'Remember Me' checked
    //     if (! empty($_POST["remember"])) {
    //         setcookie("user_login", $username, $cookie_expiration_time);
            
    //         $random_password = $util->getToken(16);
    //         setcookie("random_password", $random_password, $cookie_expiration_time);
            
    //         $random_selector = $util->getToken(32);
    //         setcookie("random_selector", $random_selector, $cookie_expiration_time);
            
    //         $random_password_hash = password_hash($random_password, PASSWORD_DEFAULT);
    //         $random_selector_hash = password_hash($random_selector, PASSWORD_DEFAULT);
            
    //         $expiry_date = date("Y-m-d H:i:s", $cookie_expiration_time);
            
    //         // mark existing token as expired
    //         $userToken = $auth->getTokenByUsername($username, 0);
    //         if (! empty($userToken[0]["id"])) {
    //             $auth->markAsExpired($userToken[0]["id"]);
    //         }
    //         // Insert new token
    //         $auth->insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date);
    //     } else {
    //         $util->clearAuthCookie();
    //     }
    //     $util->redirect("dashboard.php");
    // } else {
    //     $message = "Invalid Login";
    // }
}

?>
<style>
body {
    font-family: Arial;
}

#frmLogin {
    padding: 20px 40px 40px 40px;
    background: #d7eeff;
    border: #acd4f1 1px solid;
    color: #333;
    border-radius: 2px;
    width: 300px;
}

.field-group {
    margin-top: 15px;
}

.input-field {
    padding: 12px 10px;
    width: 100%;
    border: #A3C3E7 1px solid;
    border-radius: 2px;
    margin-top: 5px
}

.form-submit-button {
    background: #3a96d6;
    border: 0;
    padding: 10px 0px;
    border-radius: 2px;
    color: #FFF;
    text-transform: uppercase;
    width: 100%;
}

.error-message {
    text-align: center;
    color: #FF0000;
}
</style>
<form action="" method="post" id="frmLogin">
    <div class="error-message"><?php if(isset($message)) { echo $message; } ?></div>
    <div class="field-group">
        <div>
            <label for="login">Username</label>
        </div>
        <div>
            <input name="user_name" type="text"
                value=""
                class="input-field">
        </div>
    </div>
    <div class="field-group">
        <div>
            <label for="email">Email</label>
        </div>
        <div>
            <input name="user_email" type="email"
                value=""
                class="input-field">
        </div>
    </div>
    <div class="field-group">
        <div>
            <label for="password">Password</label>
        </div>
        <div>
            <input name="user_password" type="password"
                value=""
                class="input-field">
        </div>
    </div>
    <div class="field-group">
        <div>
            <label for="confirm_password">Confirm Password</label>
        </div>
        <div>
            <input name="confirm_password" type="password"
                value=""
                class="input-field">
        </div>
    </div>
    <div class="field-group">
        <div>
            <input type="submit" name="register" value="Register"
                class="form-submit-button">
        </div>
    </div>
</form>