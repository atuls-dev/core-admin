<?php 

//require_once "./config.php";
class Auth {

    function google_auth(){
        //Include Google Client Library for PHP autoload file
        require_once 'library/google-api/vendor/autoload.php';
        //Create Client Request to access Google API
        $google_client = new Google_Client([
              'verify'          => false, //https://github.com/guzzle/guzzle/issues/1935#issuecomment-629548739   
        ]);
        //$google_client->setApplicationName("PHP Google OAuth Login Example");
        $google_client->setClientId(CLIENT_ID);
        $google_client->setClientSecret(CLIENT_SECRET);
        $google_client->setRedirectUri(REDIRECT_URL);
        $google_client->setDeveloperKey(DEV_API_KEY);
        //$google_client->addScope("https://www.googleapis.com/auth/userinfo.email");
        $google_client->addScope('email');
        $google_client->addScope('profile');
        return $google_client;
    }

    function isLoggedIn(){
        
        $isLoggedIn = false;
        $db_handle = new DBController();
        $util = new Util();

        // Get Current date, time
        $current_time = time();
        $current_date = date("Y-m-d H:i:s", $current_time);

        // Set Cookie expiration for 1 month
        $cookie_expiration_time = $current_time + (30 * 24 * 60 * 60);  // for 1 month
        $cookie_expiration_time = $current_time + (2 * 60);  // for 5 min

        // Check if loggedin session and redirect if session exists
        if (! empty($_SESSION["user_id"])) {
            return true;
        }
        // Check if loggedin session exists
        else if (! empty($_COOKIE["user_login"]) && ! empty($_COOKIE["random_password"]) && ! empty($_COOKIE["random_selector"])) {
            // Initiate auth token verification diirective to false
            $isPasswordVerified = false;
            $isSelectorVerified = false;
            $isExpiryDateVerified = false;
            
            // Get token for username
            $userToken = $this->getTokenByUsername($_COOKIE["user_login"],0);
            
            // Validate random password cookie with database
            if (password_verify($_COOKIE["random_password"], $userToken["password_hash"])) {
                $isPasswordVerified = true;
            }
            
            // Validate random selector cookie with database
            if (password_verify($_COOKIE["random_selector"], $userToken["selector_hash"])) {
                $isSelectorVerified = true;
            }
            
            // check cookie expiration by date
            if($userToken["expiry_date"] >= $current_date) {
                $isExpiryDareVerified = true;
            }
            
            // Redirect if all cookie based validation retuens true
            // Else, mark the token as expired and clear cookies
            if (!empty($userToken["id"]) && $isPasswordVerified && $isSelectorVerified && $isExpiryDareVerified) {
                $isLoggedIn = true;
            } else {
                if(!empty($userToken["id"])) {
                    $this->markAsExpired($userToken["id"]);
                }
                // clear cookies
                $util->clearAuthCookie();
            }
        }
        return $isLoggedIn;
    }

    function getUserByUsername($username) {
        $db_handle = new DBController();
        $query = "Select * from users where user_name = ?";
        $result = $db_handle->runQuery($query, 's', array($username), TRUE);
        return $result;
    }

    function getUserByEmail($useremail) {
        $db_handle = new DBController();
        $query = "Select * from users where user_email = ?";
        $result = $db_handle->runQuery($query, 's', array($useremail), TRUE);
        return $result;
    }

    function getUserByGoogleId($google_id) {
        $db_handle = new DBController();
        $query = "Select * from users where google_id = ?";
        $result = $db_handle->runQuery($query, 's', array($google_id), TRUE);
        return $result;
    }
    
	function getTokenByUsername($username,$expired) {
	    $db_handle = new DBController();
	    $query = "Select * from tbl_token_auth where username = ? and is_expired = ?";
	    $result = $db_handle->runQuery($query, 'si', array($username, $expired), TRUE);
	    return $result;
    }
    
    function markAsExpired($tokenId) {
        $db_handle = new DBController();
        $query = "UPDATE tbl_token_auth SET is_expired = ? WHERE id = ?";
        $expired = 1;
        $result = $db_handle->update($query, 'ii', array($expired, $tokenId));
        return $result;
    }
    
    function insertToken($username, $random_password_hash, $random_selector_hash, $expiry_date) {
        $db_handle = new DBController();
        $query = "INSERT INTO tbl_token_auth (username, password_hash, selector_hash, expiry_date) values (?, ?, ?,?)";
        $result = $db_handle->insert($query, 'ssss', array($username, $random_password_hash, $random_selector_hash, $expiry_date));
        return $result;
    }

    function createUser($user_name, $user_email, $passwordHash) {
        $db_handle = new DBController();
        $query = "INSERT INTO users (user_name, user_email, user_password, created_at) values (?, ?, ?, ?)";
        $result = $db_handle->insert($query, 'ssss', array($user_name, $user_email, $passwordHash, CURRENT_DATETIME));
        return $result;
    }   

    function insertGoogleUser($first_name, $last_name, $user_email, $google_id) {
        $db_handle = new DBController();
        $query = "INSERT INTO users (first_name, last_name, user_email, google_id, oauth_type , created_at) values (?, ?, ?, ?, ?, ?)";

        //echo "<pre>";print_r($query); echo "</pre>"; exit;
        
        $result = $db_handle->insert($query, 'ssssss', array( $first_name, $last_name, $google_id, 'google', CURRENT_DATETIME));
        return $result;

        // $query = "INSERT INTO members (member_name, member_email, oauth_user_id, oauth_user_page, oauth_user_photo) VALUES ('" . $userData->name . "','" . $userData->email . "','" . $userData->id . "','" . $userData->link . "','" . $userData->picture . "')";
        // $result = mysql_query($query);
    }

    function updateGoogleUser($user_email, $google_id) {
        $db_handle = new DBController();
        $query = "UPDATE users SET google_id= ? , updated_at= ?  WHERE user_email= ? ";
        //$query = "INSERT INTO users (user_name, user_email, google_id, created_at) values (?, ?, ?, ?)";
        $result = $db_handle->update($query, 'sss', array( $google_id, CURRENT_DATETIME, $user_email ));
        return $result;

        //mysqli_query($this->conn,$query);
    }
    
    function update($query) {
        mysqli_query($this->conn,$query);
    }

    function authenticateUser($username, $password, $oauth_type='website' ) {

        $db_handle = new DBController();
        $query = "Select * from users where ( user_name = ? OR user_email = ? ) AND oauth_type= ?";
        $user = $db_handle->runQuery($query, 'sss', array($username, $username, $oauth_type), TRUE);

        if( !empty($user) ) {
            if (password_verify($password, $user["user_password"])) {
                return array(
                        'success' => true,
                        'user' => $user
                    );
            }else{
                return array(
                        'success' => false,
                        'message' => 'Password not matched...'
                );
            }
        }else{
            return array(
                        'success' => false,
                        'message' => 'User not found...' 
                    );
        }

       
    }

}
?>