<!DOCTYPE html>
<html lang="en">

<head>

    <title>Home / My Orders</title>
    <?php
        require_once '../main-head.php';
        permission_my_orders_page($permission,$is_active,$is_verified);
    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/my_orders.css">
    <script src="js/my_orders.js"></script>
    
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

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_MY_ORDERS); ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../products"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_MY_ORDERS); ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section my_orders d-flex flex-column align-items-center justify-content-center" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="alert alert-success alert-dismissible fade show col-12" role="alert"
                id="alert-success-cancel-order">
                Order has been canceled.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="alert alert-danger alert-dismissible fade show col-12" role="alert"
                id="alert-danger-cancel-order">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="container-fluid p-0" id="my_orders">
                <div class="row justify-content-center p-0 m-0">
                    <div class="col-md-12 p-0">
                        <div class="table-responsive">
                            <table class="table bg-white m-0" id="table-orders">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="p-0"><input type="text" class="form-control search" placeholder="#"
                                                onkeyup="searchTable(0,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_REF_NUMBER);  ?>"
                                                onkeyup="searchTable(1,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_FULLNAME);  ?>"
                                                onkeyup="searchTable(2,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_TRACKING_NO);  ?>"
                                                onkeyup="searchTable(3,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_PRICE);  ?>"
                                                onkeyup="searchTable(4,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_DATE);  ?>"
                                                onkeyup="searchTable(5,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ORDER_TYPE);  ?>"
                                                onkeyup="searchTable(6,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_TOTAL_PRICE);  ?>"
                                                onkeyup="searchTable(7,this,'table-orders');"></th>
                                        <th class="p-0"><input type="text" class="form-control search"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_STATUS);  ?>"
                                                onkeyup="searchTable(8,this,'table-orders');"></th>
                                        <th rowspan="2"><?php echo $dictionary->get_lang($lang,$KEY_ACTION);  ?></th>
                                    </tr>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_REF_NUMBER);  ?></th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_FULLNAME);  ?></th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_TRACKING_NO);  ?></th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_PRICE);  ?></th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_DATE);  ?></th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_ORDER_TYPE);  ?></th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_TOTAL_PRICE);  ?></th>
                                        <th><?php echo $dictionary->get_lang($lang,$KEY_STATUS);  ?></th>
                                    </tr>
                                </thead>
                                <tbody id="tbOrder">
                                    <!---working here--->
                                    <?php require_once 'php/my_orders.php'; ?>

        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
