<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin / System Management</title>
    <?php
        require_once '../main-head.php';
        permission_system_management_page($permission,$is_active,$is_verified);
    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/system_management.css">
    <script src="js/contact_details.js"></script>
    <script src="js/currency.js"></script>
    <script src="js/dictionary.js"></script>
    <script src="js/order_type.js"></script>
    <script src="js/product_size.js"></script>
    <script src="js/product_type.js"></script>

    <?php
        require_once '../../config/conn.php';
        require_once 'classes/product_type.php';
        require_once 'classes/product_size.php';
        require_once 'classes/order_type.php';
        require_once 'classes/contact_details.php';
        require_once '../currency.php';
        $class_product_type=new ProductType($db_conn->get_link());
        $class_product_size=new ProductSize($db_conn->get_link());
        $class_order_type=new OrderType($db_conn->get_link());
        $class_contact_details=new ContactDetails($db_conn->get_link());
    ?>
    
</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php'; ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main class="main" id="main">

        <?php 
            if(isset($_SESSION[Session::$KEY_EC_LANG])){ 
                if(($_SESSION[Session::$KEY_EC_LANG]) == "ar" ){
                    $dir_required="style='text-align:left !important'";
                }else{
                    $dir_required="";
                }
            }else{
                $dir_required="style='text-align:right !important'";
            }
        ?>

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-4">
                    <div class="form-group mb-2">
                        <h1><?php echo $dictionary->get_lang($lang,$KEY_SYSTEM_MANAGEMENT); ?></h1>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                                <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_ADMIN); ?></li>
                            <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_SYSTEM_MANAGEMENT); ?></li>
                        </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-5"></div>
                <div class="col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group mb-3 mt-1">
                        <input type="search" id="search_for_card" onkeyup="search_for_card();" class="form-control" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_TYPE_TO_START_SEARCH);  ?>" style="height:34px">
                    </div>
                </div>
            </div>
        </div><!-- End Page Title -->

        <section class="section system-management d-flex flex-column align-items-center justify-content-center" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="container-fluid p-0">
                <div class="row p-0 m-0">
                    <div class="col-md-12 p-0 m-0">
                        <div id="accordion">
                            
                            <?php
                                require_once 'php/section_product_type.php';
                            ?>

                            <?php
                                require_once 'php/section_product_size.php';
                            ?>

                            <?php
                                require_once 'php/section_order_type.php';
                            ?>

                            <?php
                                require_once 'php/section_currency.php';
                            ?>
    
                            <?php
                                require_once 'php/section_contact_details.php';
                            ?>

                            <?php
                                require_once 'php/section_dictionary.php';
                            ?>

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
