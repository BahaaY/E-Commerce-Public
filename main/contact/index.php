<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    require_once '../../config/variables.php';
    require_once '../../config/conn.php';
    require_once 'classes/contact_details.php';

    $class_contact_details=new ContactDetails($db_conn->get_link());

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Pages / Contact</title>
    <?php 
        require_once '../main-head.php'; 
    ?>

    <script src="js/contact.js"></script>
    <link rel="stylesheet" href="css/contact.css">
    
</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php' ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php';?>

    <?php permission_contact_page($permission,$is_active,$is_verified); ?>

    <main id="main" class="main">

        <?php 
            if(isset($_SESSION[Session::$KEY_EC_LANG])){ 
                if(($_SESSION[Session::$KEY_EC_LANG]) == "ar" ){
                    $exclamation_mark="؟";
                    $dir_required="style='text-align:left !important'";
                    $btn_text_left=$dictionary->get_lang($lang,$KEY_SEND_MESSAGE);
                    $btn_text_right="";
                }else{
                    $exclamation_mark="?";
                    $dir_required="";
                    $btn_text_right=$dictionary->get_lang($lang,$KEY_SEND_MESSAGE);
                    $btn_text_left="";
                }
            }else{
                $exclamation_mark="?";
                $dir_required="style='text-align:right !important'";
                $btn_text_left="";
                $btn_text_right=$dictionary->get_lang($lang,$KEY_SEND_MESSAGE);
            }
        ?>

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">

        <?php
            $text="Profile";
            if($permission == 1){
                $redirect="../dashboard";
            }else{
                $redirect="../products";
            }
        ?>

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_CONTACT); ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo $redirect ?>"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_PAGES); ?></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_CONTACT); ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <?php

            $address="";
            $phone_numbe="";
            $email="";

            $contact_details_info=$class_contact_details->get_contact_details();
            if($contact_details_info){
                $address=$contact_details_info['address'];
                $phone_number=$contact_details_info['phone_number'];
                $email=$contact_details_info['email'];
            }
            if($address == ""){
                $address="Pending announcement";
            }

            if($phone_number == ""){
                $phone_numbe="Pending announcement";
            }

            if($email == ""){
                $email="Pending announcement";
            }
            
        ?>

        <section class="section contact" <?php echo $dictionary->get_dir($lang); ?>>

            <div class="row gy-4">

                <div class="col-lg-12">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="info-box card mb-3">
                                <i class="bi bi-geo-alt"></i>
                                <h3><?php echo $dictionary->get_lang($lang,$KEY_ADDRESS); ?></h3>
                                <p><?php echo $address; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box card mb-3">
                                <i class="bi bi-telephone"></i>
                                <h3><?php echo $dictionary->get_lang($lang,$KEY_CALL_US); ?></h3>
                                <p><?php echo $phone_number; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box card mb-3">
                                <i class="bi bi-envelope"></i>
                                <h3><?php echo $dictionary->get_lang($lang,$KEY_EMAIL_US); ?></h3>
                                <p><?php echo $email; ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 d-none">
                            <div class="info-box card mb-3">
                                <i class="bi bi-clock"></i>
                                <h3>Open Hours</h3>
                                <p>Pending announcement</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-12 mt-0">
                    <div class="card p-4">
                      
                            <div class="row gy-4">

                                <div class="col-md-12 pb-0 mt-2">
                                    <div class="alert alert-success alert-dismissible fade show m-0" role="alert" id="alert-success-send">
                                        Enter message has been sent. Thank you!
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-12 pb-0 mt-2">
                                    <div class="alert alert-danger alert-dismissible fade show m-0" role="alert" id="alert-success-send">
                                        Error occurred.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                   
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_NAME);  ?>" >
                                        <span id="error_name" class="text-danger"></span>
                                </div>

                                <div class="col-md-6 ">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_EMAIL);  ?>" >
                                        <span id="error_email" class="text-danger"></span>
                                </div>

                                <div class="col-md-12">
                                    <input type="text" class="form-control" name="subject" id="subject" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_SUBJECT);  ?>">
                                        <span id="error_subject" class="text-danger"></span>
                                </div>

                                <div class="col-md-12">
                                    <textarea class="form-control" name="message" id="message" rows="6" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_MESSAGE);  ?>" ></textarea>
                                    <span id="error_message" class="text-danger"></span>
                                </div>

                                <div class="col-md-12 text-center">
                                    <button type="button" class="btn btn-primary" id="btn_send"><?php echo $btn_text_left; ?><i class="bi bi-send mr-2"></i><?php echo $btn_text_right; ?></button>
                                </div>

                            </div>
                        
                    </div>

                </div>

            </div>

        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php' ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
