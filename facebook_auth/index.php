<?php

    // Include required libraries
    use Facebook\Exceptions\FacebookResponseException;
    use Facebook\Exceptions\FacebookSDKException;

    // Include configuration file
    require_once 'config.php';

    if(isset($accessToken)){
        if(isset($_SESSION['facebook_access_token'])){
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        }else{
            // Put short-lived access token in session
            $_SESSION['facebook_access_token'] = (string) $accessToken;
            
            // OAuth 2.0 client handler helps to manage access tokens
            $oAuth2Client = $fb->getOAuth2Client();
            
            // Exchanges a short-lived access token for a long-lived one
            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
            $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;
            
            // Set default access token to be used in script
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
        }
        
        // Redirect the user back to the same page if url has "code" parameter in query string
        if(isset($_GET['code'])){
            header('Location: ./');
        }
        
        // Getting user's profile info from Facebook
        try {
            $graphResponse = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,picture');
            $fbUser = $graphResponse->getGraphUser();
        } catch(FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            session_destroy();
            // Redirect user back to app login page
            header("Location: ../login");
            exit;
        } catch(FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        
        // Getting user's profile data
        $fbUserData = array();
        $fbUserData['oauth_uid']  = !empty($fbUser['id'])?$fbUser['id']:'';
        $fbUserData['first_name'] = !empty($fbUser['first_name'])?$fbUser['first_name']:'';
        $fbUserData['last_name']  = !empty($fbUser['last_name'])?$fbUser['last_name']:'';
        $fbUserData['email']      = !empty($fbUser['email'])?$fbUser['email']:'';
        $fbUserData['gender']     = !empty($fbUser['gender'])?$fbUser['gender']:'';
        $fbUserData['picture']    = !empty($fbUser['picture']['url'])?$fbUser['picture']['url']:'';
        $fbUserData['link']       = !empty($fbUser['link'])?$fbUser['link']:'';
        
        // Get logout url
        $logoutURL = $helper->getLogoutUrl($accessToken, "http://localhost/Projects/E-commerce/facebook_auth/".'logout.php');
        
        // Render Facebook profile data
        if(!empty($fbUserData)){
            $output  = '<h2>Facebook Profile Details</h2>';
            $output .= '<div class="ac-data">';
            $output .= '<p><b>Facebook ID:</b> '.$fbUserData['oauth_uid'].'</p>';
            $output .= '<p><b>Name:</b> '.$fbUserData['first_name'].' '.$fbUserData['last_name'].'</p>';
            $output .= '<p><b>Email:</b> '.$fbUserData['email'].'</p>';
            $output .= '<p><b>Gender:</b> '.$fbUserData['gender'].'</p>';
            $output .= '<p><b>Logged in with:</b> Facebook</p>';
            $output .= '<p><b>Profile Link:</b> <a href="'.$fbUserData['link'].'" target="_blank">Click to visit Facebook page</a></p>';
            $output .= '<p><b>Logout from <a href="'.$logoutURL.'">Facebook</a></p>';
            $output .= '</div>';
        }else{
            $output = '<h3 style="color:red">Some problem occurred, please try again.</h3>';
        }
    }else{
        // Get login url
        $permissions = ['email']; // Optional permissions
        $loginURL = $helper->getLoginUrl("http://localhost/Projects/E-commerce/facebook_auth/",$permissions);

        // Render Facebook login button
        //$output = '<a href="'.htmlspecialchars($loginURL).'">Login</a>';

        header("location:".$helper->getLoginUrl("http://localhost/Projects/E-commerce/facebook_auth/",$permissions));
    }
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Login with Facebook using PHP by CodexWorld</title>
<meta charset="utf-8">
</head>
<body>
<div class="container">
    <div class="fb-box">
        <!-- Display login button / Facebook profile information -->
        <?php echo $output; ?>
    </div>
</div>
</body>
</html>