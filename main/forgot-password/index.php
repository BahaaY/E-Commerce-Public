<!DOCTYPE html>
<html lang="en">

<head>

    <title>Pages / Forgot password</title>
    <?php 

        require_once '../main-head.php'; 
        permission_forgot_password_page($permission,$is_active,$is_verified);

    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/forgot_password.css">
    <script src="js/forgot_password.js"></script>

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
        <input type="hidden" id="key_email_not_exist" value="<?php echo $dictionary->get_lang($lang,$KEY_EMAIL_NOT_EXIST);  ?>">
        <input type="hidden" id="key_account_blocked" value="<?php echo $dictionary->get_lang($lang,$KEY_YOU_ARE_BLOCKED);  ?>">
        <input type="hidden" id="key_error_login_limit" value="<?php echo $dictionary->get_lang($lang,$KEY_ERROR_LOGIN_LIMIT);  ?>">
        <input type="hidden" id="key_error_occurred" value="<?php echo $dictionary->get_lang($lang,$KEY_ERROR_OCCURRED);  ?>">

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_FORGOT_PASSWORD); ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../products"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_PAGES); ?></li>
                    <li class="breadcrumb-item"><a href="../login"><?php echo $dictionary->get_lang($lang,$KEY_LOGIN); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_FORGOT_PASSWORD); ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section forgot-password d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4"><?php echo $dictionary->get_lang($lang,$KEY_RESET_YOUR_PASSWORD); ?></h5>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                                            <span class='text-alert-success'>Reset password code has been sent to your account. You will be redirected to verification page after <span id='text-alert-success-counter'>10</span>s</span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger">
                                            <span id="text-danger"></span>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
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
                                                    <input type="email" class="form-control" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_EMAIL);  ?>" id="email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 text-center">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-primary" id="btn_send"
                                                name="btn_send"><i class='bi bi-send mr-2'></i><?php echo $dictionary->get_lang($lang,$KEY_SEND); ?></button>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 small">
                                        <a href="../login" class="link-primary"><?php echo $dictionary->get_lang($lang,$KEY_BACK_TO_LOGIN_PAGE); ?></a>
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
