<!DOCTYPE html>
<html lang="en">

<head>

    <?php 

        require_once '../main-head.php'; 
        permission_user_profile_page($permission,$is_active,$is_verified);

    ?>

    <!-- CSS & JS | Change email-->
    <link rel="stylesheet" href="css/change_email.css">
    <link rel="stylesheet" href="css/change_password.css">
    <link rel="stylesheet" href="css/update_user_profile.css">
    <link rel="stylesheet" href="css/delete_account.css">
    <link rel="stylesheet" href="css/settings.css">
    <script src="js/change_email.js"></script>
    <script src="js/change_password.js"></script>
    <script src="js/update_user_profile.js"></script>
    <script src="js/remove_profile_image.js"></script>
    <script src="js/delete_account.js"></script>
    <script src="js/settings.js"></script>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php'; ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main id="main" class="main">
        
        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">
        <input type="hidden" id="key_enter_a_valid_email" value="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_A_VALID_EMAIL);  ?>">
        <input type="hidden" id="key_email_already_used" value="<?php echo $dictionary->get_lang($lang,$KEY_EMAIL_ALREADY_USED);  ?>">
        <input type="hidden" id="key_password_length" value="<?php echo $dictionary->get_lang($lang,$KEY_PASSWORD_LENGTH);  ?>">
        <input type="hidden" id="key_password_validation" value="<?php echo $dictionary->get_lang($lang,$KEY_PASSWORD_VALIDATION);  ?>">
        <input type="hidden" id="key_confirm_password_not_the_same" value="<?php echo $dictionary->get_lang($lang,$KEY_CONFIRM_PASSWORD_NOT_THE_SAME);  ?>">
        <input type="hidden" id="key_enter_your_current_password" value="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_YOUR_CURRENT_PASSWORD);  ?>">

        <?php
            if($permission == 1){
                $title="Settings";
            }else{
                $title="Profile";
            }
        ?>

        <title>Pages / <?php echo $title ?></title>

        <?php
            $text=$dictionary->get_lang($lang,$KEY_PROFILE);
            if($permission == 1){
                $text=$dictionary->get_lang($lang,$KEY_SETTINGS);
                $redirect="../dashboard";
            }else{
                $text=$dictionary->get_lang($lang,$KEY_PROFILE);
                $redirect="../products";
            }
        ?>

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

        <div class="pagetitle"  <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $text; ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $redirect ?>"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_PAGES); ?></li>
                    <li class="breadcrumb-item active"><?php echo $text; ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section profile"  <?php echo $dictionary->get_dir($lang); ?>>
            <div class="row">
                <?php
                    if($permission == 1){
                        $class_card_profile="col-xl-5 d-none";
                        $class_card_edit_profile="col-xl-12";
                    }else{
                        $class_card_profile="col-xl-5";
                        $class_card_edit_profile="col-xl-7";
                    }
                ?>
                <div class="<?php echo $class_card_profile; ?>">

                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                            <img src="<?php echo $profile_image; ?>" id="source_image_profile" alt="Profile" class="rounded-circle">
                            <h2 id="div_username"><?php echo $username ?></h2>

                        </div>

                        <div class="tab-content p-4">

                            <div class="tab-pane fade show active profile-overview" id="profile-overview">

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><?php echo $dictionary->get_lang($lang,$KEY_USERNAME); ?></div>
                                    <div class="col-lg-9 col-md-8" id="div_displayed_username"><?php echo $username ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><?php echo $dictionary->get_lang($lang,$KEY_COUNTRY); ?></div>
                                    <div class="col-lg-9 col-md-8" id="div_country"><?php echo $country ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><?php echo $dictionary->get_lang($lang,$KEY_REGION); ?></div>
                                    <div class="col-lg-9 col-md-8" id="div_region"><?php echo $region ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><?php echo $dictionary->get_lang($lang,$KEY_ADDRESS); ?></div>
                                    <div class="col-lg-9 col-md-8" id="div_address"><?php echo $address ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><?php echo $dictionary->get_lang($lang,$KEY_EMAIL); ?></div>
                                    <div class="col-lg-9 col-md-8" id="div_email"><?php echo $email ?></div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-3 col-md-4 label"><?php echo $dictionary->get_lang($lang,$KEY_PHONE); ?></div>
                                    <div class="col-lg-9 col-md-8" id="div_phone_number"><?php echo $phone_number ?></div>
                                </div>

                            </div>
                        </div>

                    </div>

                </div>

                <div class="<?php echo $class_card_edit_profile; ?>">

                    <?php
                                if($permission == 1){
                                    $show_active_profile="";
                                    $active_profile="";
                                    $active_settings="active";
                                    $show_active_settings="show active";
                                }else {
                                    $show_active_profile="show active";
                                    $active_profile="active";
                                    $active_settings="";
                                    $show_active_settings="";
                                }
                            ?>

                    <div class="card">
                        <div class="card-body p-4 pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered p-0">

                                <?php
                                    if($permission == 0){ //user
                                        echo '
                                            <li class="nav-item">
                                                <button class="nav-link '.$active_profile.'" data-bs-toggle="tab" data-bs-target="#profile-edit">'.$dictionary->get_lang($lang,$KEY_EDIT_PROFILE).'</button>
                                            </li>
                                        ';
                                    }
                                ?>

                                
                                <?php
                                    echo '
                                        <li class="nav-item">
                                            <button class="nav-link '.$active_settings.'" data-bs-toggle="tab"
                                                data-bs-target="#profile-settings">'.$dictionary->get_lang($lang,$KEY_SETTINGS).'</button>
                                        </li>
                                    ';
                                ?>

                                <?php
                                    if($registration_type == 1){
                                        echo '
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#profile-change-email">'.$dictionary->get_lang($lang,$KEY_CHANGE_EMAIL).'</button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#profile-change-password">'.$dictionary->get_lang($lang,$KEY_CHANGE_PASSWORD).'</button>
                                            </li>
                                        ';
                                    }
                                ?>
                                <?php if($permission == 0){ //user?>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#profile-delete-account"><?php echo $dictionary->get_lang($lang,$KEY_DELETE_ACCOUNT); ?></button>
                                    </li>
                                <?php 
                                    } 
                                ?>
                                
                            </ul>

                            <div class="tab-content">

                                <!-- Profile Edit Form -->
                                <?php
                                    if($permission == 0){ //user?>
                                        <div class="tab-pane fade <?php echo $show_active_profile; ?> pt-3" id="profile-edit">

                                            <?php require_once 'php/edit_profile.php' ?>

                                        </div>
                                    <?php                                    
                                    }
                                ?>

                                <!-- Settings Form -->
                                <div class="tab-pane fade <?php echo $show_active_settings; ?> pt-3" id="profile-settings">

                                    <?php require_once 'php/settings.php' ?>

                                </div>

                                <!-- Change Email Form -->
                                <?php
                                    if($registration_type == 1){ ?>
                                        <div class="tab-pane fade pt-3" id="profile-change-email">
                                    
                                            <?php require_once 'php/change_email.php' ?>

                                        </div>
                                    <?php
                                    }
                                ?>

                                <!-- Change Password Form -->
                                <?php
                                    if($registration_type == 1){ ?>
                                        <div class="tab-pane fade pt-3" id="profile-change-password">
                                    
                                            <?php require_once 'php/change_password.php' ?>

                                        </div>
                                    <?php
                                    }
                                ?>
                                
                                <!-- Delete account Form -->
                                <?php if($permission == 0){ //user?>
                                    <?php
                                        if($registration_type == 1){ ?>
                                            <div class="tab-pane fade pt-3" id="profile-delete-account">
                                        
                                                <?php require_once 'php/delete_account.php' ?>

                                            </div>
                                        <?php
                                        }
                                    ?>
                                <?php 
                                    } 
                                ?>
                        
                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <div class="modal fade" id="remove-profile-image" tabindex="-1" role="dialog" aria-labelledby="remove-profile-image" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="remove-profile-image">Remove profile image</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            Are you sure you want remove your profile image?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary" onclick="remove_profile_image();">Remove</button>
        </div>
        </div>
    </div>
    </div>

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
