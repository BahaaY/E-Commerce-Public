<!DOCTYPE html>
<html lang="en">

<head>

    <title>Pages / Login</title>
    <?php 

        require_once '../main-head.php'; 
        permission_login_page($permission,$is_active,$is_verified);

    ?>

    <?php
        require_once '../../config/variables.php';
        $is_checked="";
        $email="";
        $password="";
        if(isset($_COOKIE[Key::$KEY_COOKIES_EMAIL]) && isset($_COOKIE[Key::$KEY_COOKIES_PASSWORD])) {
            $is_checked="checked";
            $email=$_COOKIE[Key::$KEY_COOKIES_EMAIL];
            $password=$_COOKIE[Key::$KEY_COOKIES_PASSWORD];
            $email=Helper::decrypt($email);
            $password=Helper::decrypt($password);
        }

    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/login.css">
    <script src="js/login.js"></script>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php' ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main class="main" id="main">
        
        <?php 
            if(isset($_SESSION[Session::$KEY_EC_LANG])){ 
                if(($_SESSION[Session::$KEY_EC_LANG]) == "ar" ){
                    $exclamation_mark="؟";
                    $dir_required="style='text-align:left !important'";
                }else{
                    $exclamation_mark="?";
                    $dir_required="";
                }
            }else{
                $exclamation_mark="?";
                $dir_required="style='text-align:right !important'";
            }
        ?>

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">
        <input type="hidden" id="key_enter_a_valid_email" value="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_A_VALID_EMAIL);  ?>">

        <div class="pagetitle"  <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_LOGIN); ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../products"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_PAGES); ?></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_LOGIN); ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section login d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card mb-3">

                            <div class="card-body">

                                <div>
                                    <h5 class="card-title text-center pb-0 fs-4"><?php echo $dictionary->get_lang($lang,$KEY_SIGN_IN_TO_YOUR_ACCOUNT); ?></h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert"
                                            id="alert-success">
                                            <span class='text-alert-success'>Verification code has been sent to your
                                                account. You will be redirected to verification page after <span
                                                    id='text-alert-success-counter'>10</span>s</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-2">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert"
                                            id="alert-danger">
                                            <span id="text-danger"></span>
                                            <button type="button" class="close" data-dismiss="alert"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row p-2 d-none">
                                    <div class="col-md-6 col-sm-6 text-center">
                                        <div class="form-group">
                                            <button class="btn google-btn social-btn btn-danger" type="button">
                                                <span><i class="fa fa-google-plus"></i>&nbsp; <?php echo $dictionary->get_lang($lang,$KEY_SIGN_IN_WITH_GOOGLE); ?>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 text-center">
                                        <div class="form-group">
                                            <button class="btn facebook-btn social-btn btn-primary" type="button" id="btn_facebook">
                                                <span>
                                                    <i class="fa fa-facebook"></i>&nbsp; Sign In with
                                                    Facebook
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row" <?php echo $dictionary->get_dir($lang); ?>>
                                                <div class="col-md-6">
                                                    <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_EMAIL); ?></label> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                                    <span class="text-danger" id="error-email"></span>
                                                </div>
                                                <div class="col-md-12">
                                                    <input type="email" class="form-control" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_EMAIL);  ?>" id="email" value="<?php echo $email; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row" <?php echo $dictionary->get_dir($lang); ?>>
                                                <div class="col-md-6">
                                                    <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_PASSWORD); ?></label> <span class="text-danger">*</span></label>
                                                </div>
                                                <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                                    <span class="text-danger" id="error-password"></span>
                                                </div>
                                                <div class="col-md-12" style="direction: ltr !important">
                                                    <div class="input-group" id="show_hide_password">
                                                        <input class="form-control" type="password" id="password"
                                                            placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PASSWORD);  ?>" value="<?php echo $password; ?>" <?php echo $dictionary->get_dir($lang); ?>>
                                                        <div
                                                            class="input-group-addon input-group-addon-password d-flex align-items-center p-2">
                                                            <i class="fa fa-eye-slash" aria-hidden="true"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" id="btn_login"
                                                name="btn_login"><i class='bi bi-box-arrow-in-right mr-2'></i><?php echo $dictionary->get_lang($lang,$KEY_SIGN_IN); ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="chk_remember_me" <?php echo $is_checked; ?>>
                                            <label class="form-check-label small" for="chk_remember_me">
                                                <?php echo $dictionary->get_lang($lang,$KEY_REMEMBER_ME); ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col small text-right">  
                                        <a role="button" class="link-primary" id="btn_google"><?php echo $dictionary->get_lang($lang,$KEY_SIGN_IN_WITH_GOOGLE); ?></a>
                                    </div>
                                </div>

                                <div class="row" >
                                    <div class="col text-left small">
                                        <?php echo $dictionary->get_lang($lang,$KEY_NOT_A_MEMBER); ?><?php echo $exclamation_mark ?> <a href="../register" class="link-primary"><?php echo $dictionary->get_lang($lang,$KEY_CREATE_ACCOUNT); ?></a>
                                    </div>
                                    <div class="col text-right small">
                                        <a href="../forgot-password" class="link-primary"><?php echo $dictionary->get_lang($lang,$KEY_FORGOT_PASSWORD); ?><?php echo $exclamation_mark ?></a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
