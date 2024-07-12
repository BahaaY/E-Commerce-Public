<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin / Orders</title>
    <?php
        require_once '../main-head.php';
        permission_orders_page($permission,$is_active,$is_verified);
    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="<?php echo $path; ?>orders/css/orders.css">
    <script src="js/orders.js"></script>

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

        <div class="pagetitle"  <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_ORDERS); ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_ADMIN); ?></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_ORDERS); ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section orders d-flex flex-column align-items-center justify-content-center" id="section_orders"  <?php echo $dictionary->get_dir($lang); ?>>
            <div class="col-md-12 p-0 m-0">
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-update-order">
                            Order has been updated.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-delete-order">
                            Order has been deleted.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger-update-order">
                            Error occurred.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                    <div class="container-fluid p-0" id="orders">
                        <div class="row justify-content-center p-0 m-0">
                            <div class="col-md-12 p-0">
                                <div class="table-responsive">
                                    <table class="sortable table bg-white m-0" id="table-orders">
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
                                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_MONETARY_UNIT);  ?>"
                                                        onkeyup="searchTable(5,this,'table-orders');"></th>
                                                <th class="p-0"><input type="text" class="form-control search"
                                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_DATE);  ?>"
                                                        onkeyup="searchTable(6,this,'table-orders');"></th>
                                                <th class="p-0"><input type="text" class="form-control search"
                                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ORDER_TYPE);  ?>"
                                                        onkeyup="searchTable(7,this,'table-orders');"></th>
                                                <th class="p-0"><input type="text" class="form-control search"
                                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_TOTAL_PRICE);  ?>"
                                                        onkeyup="searchTable(8,this,'table-orders');"></th>
                                                <th class="p-0"><input type="text" class="form-control search"
                                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_STATUS);  ?>"
                                                        onkeyup="searchTable(89,this,'table-orders');"></th>
                                                <th rowspan="2"><?php echo $dictionary->get_lang($lang,$KEY_ACTION);  ?></th>
                                                <th rowspan="2"><?php echo $dictionary->get_lang($lang,$KEY_ADD_TO_SALES);  ?></th>
                                            </tr>
                                            <tr>
                                                <th>#</th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_REF_NUMBER);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_FULLNAME);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_TRACKING_NO);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_PRICE);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_MONETARY_UNIT);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_DATE);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_ORDER_TYPE);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_TOTAL_PRICE);  ?></th>
                                                <th><?php echo $dictionary->get_lang($lang,$KEY_STATUS);  ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!---working here--->
                                            <?php require_once "php/orders_information.php";?>

                        
                                
        </section>

        <section id="section_container_print" style="display: none;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12 p-0 m-0 ">
                        <div class="form-group">
                            <button class="btn btn-primary" onclick="reload();"><i class="bi bi-arrow-left mr-2"></i><?php echo $dictionary->get_lang($lang,$KEY_BACK);  ?></button> 
                            <button class="btn btn-secondary btn-print" id="" order_id="" onclick="print();"><i class="fa fa-print mr-2"></i><?php echo $dictionary->get_lang($lang,$KEY_PRINT_INVOICE);  ?></button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 p-0 m-0 container_print_invoice" id="container_print_invoice">

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
