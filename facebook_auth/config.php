<?php

    // Start session
    if (!session_id()) {
        session_start();
    }

    // Include the autoloader provided in the SDK
    require_once 'vendor/autoload.php';

    use Facebook\Facebook;
    use Facebook\Exceptions\FacebookResponseException;
    use Facebook\Exceptions\FacebookSDKException;
    // Call Facebook API
    $fb = new Facebook([
        'app_id' => '1384325612385166',
        'app_secret' => '22073517469950ce04496bc5c53ff0cb',
        'default_graph_version' => 'v3.2',
    ]);

    // Get redirect login helper
    $helper = $fb->getRedirectLoginHelper();

    // Try to get access token
    try {
        if (isset($_SESSION['facebook_access_token'])) {
            $accessToken = $_SESSION['facebook_access_token'];
        } else {
            $accessToken = $helper->getAccessToken();
        }
    } catch (FacebookResponseException $e) {
        echo 'Graph returned an error: ' . $e->getMessage();
        exit();
    } catch (FacebookSDKException $e) {
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit();
    }

?>
