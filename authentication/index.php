<?php

    session_start();

    require_once '../config/variables.php';

    $id="";
    $type="";
    if(!isset($_GET['id']) || !isset($_GET['t'])){
        header("location:../forbidden");
    }else{
        $id=htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
        $type=htmlspecialchars($_GET['t'], ENT_QUOTES, 'UTF-8');
    }
    if($id == "" || $type == ""){
        header("location:../forbidden");
    }

    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $path = substr($link, 0, strpos($link, WebsiteInfo::$KEY_PATH_TO_WEBSITE));
    $path=$path. WebsiteInfo::$KEY_PATH_TO_WEBSITE."/main/";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo WebsiteInfo::$KEY_WEBSITE_LOGO_FOR_SINGLE_PAGE ?>" rel="icon">
    <link href="<?php echo WebsiteInfo::$KEY_WEBSITE_LOGO_FOR_SINGLE_PAGE ?>" rel="apple-touch-icon">

    <title>Verification code page</title>

    <!-- Jquery -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script src="<?php echo $path; ?>resources/libs/jquery-3.6.1/jquery.js"></script>

    <!-- Bootstrap -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->
    <link rel="stylesheet" href="<?php echo $path; ?>resources/libs/bootstrap/bootstrap.min.css">
    <script src="<?php echo $path; ?>resources/libs/bootstrap/poper.min.js"></script>
    <script src="<?php echo $path; ?>resources/libs/bootstrap/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/authentication.css">
    <script src="js/authentication.js"></script>

</head>

<body>

    <input type="hidden" id="id" value="<?php echo $id ?>">
    <input type="hidden" id="type" value="<?php echo $type ?>">

    <div class="container" id="wrapper">
        <div id="dialog">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                        <span class='text-alert-success-account-verification'>You account has been verified. You will be redirected to main page after <span id='text-alert-success-counter-account-verification'>10</span>s</span>
                        <span class='text-alert-success-update-email'>You email has been verified. You will be redirected to profile page after <span id='text-alert-success-counter-update-email'>10</span>s</span>
                        <span class='text-alert-success-resend-email'>Verification code has been resent to your account.</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert" id="alert-danger">
                            
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <h3>Please enter the 6-digit verification code we sent via Email:</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span>(we want to make sure it's you before we proceed with any further steps)</span>
                </div>
            </div>
            <div class="row" id="form">
                <div class="col-md-12">
                    <input class="m-1" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="digit1">
                    <input class="m-1" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="digit2">
                    <input class="m-1" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="digit3">
                    <input class="m-1" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="digit4">
                    <input class="m-1" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="digit5">
                    <input class="m-1" type="text" maxLength="1" size="1" min="0" max="9" pattern="[0-9]{1}" id="digit6">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="btn btn-primary mb-3 shadow-none" id="btn_verify">Verify</button>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    Didn't receive the code?
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <button class="text-primary" role="button" id="btn_resend_email">Send code again</button>
                </div>
            </div>

        </div>
    </div>
</body>

</html>
