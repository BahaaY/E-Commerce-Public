<?php

    session_start();

    require_once '../config/variables.php';

    if(!isset($_GET['id'])){
        header("location:../forbidden");
    }else{
        $id=htmlspecialchars($_GET['id'], ENT_QUOTES, 'UTF-8');
    }
    if($id == ""){
        header("location:../forbidden");
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php echo WebsiteInfo::$KEY_WEBSITE_LOGO_FOR_SINGLE_PAGE ?>" rel="icon">
    <link href="<?php echo WebsiteInfo::$KEY_WEBSITE_LOGO_FOR_SINGLE_PAGE ?>" rel="apple-touch-icon">

    <title>Reset password page</title>

    <!-- Jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Font aweson -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/reset_password.css">
    <script src="js/reset_password.js"></script>

</head>

<body>

    <input type="hidden" id="id" value="<?php echo $id; ?>">

    <div class="container" id="container-parent">
        
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                        <span class='text-alert-success'>You password has been updated. You will be redirected to login page after <span id='text-alert-success-counter'>10</span>s</span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger">
                        <span id='text-alert-danger'></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Reset password</p>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-0">New password</label> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <span class="text-danger" id="error-new-password"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group" id="show_hide_new_password">
                                            <input class="form-control shadow-none" type="password" id="new-password"
                                                placeholder="Enter new password">
                                            <div class="input-group-addon input-group-addon-new-password d-flex align-items-center p-2">
                                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="m-0">Confirm new password</label> <span class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <span class="text-danger" id="error-confirm-new-password"></span>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="input-group" id="show_hide_confirm_new_password">
                                            <input class="form-control shadow-none" type="password" id="confirm-new-password"
                                                placeholder="Enter confirm password">
                                            <div class="input-group-addon input-group-addon-confirm-new-password d-flex align-items-center p-2">
                                                <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary shadow-none" id="btn_reset"
                                    name="btn_reset"><i class='bi bi-lock mr-2'></i>Reset Password</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        
    </div>
</body>

</html>
