<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin / Report by product name</title>
    <?php 

        require_once '../main-head.php'; 
        permission_reports_page($permission,$is_active,$is_verified);

    ?>

    <!-- CSS & JS -->
    <script src="js/report.js"></script>
    
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
                    $dir_buttons_right="style='float: right'";
                    $dir_buttons_left="style='float: left'";
                }else{
                    $exclamation_mark="?";
                    $dir_required="";
                    $dir_buttons_right="";
                    $dir_buttons_left="";
                }
            }else{
                $exclamation_mark="?";
                $dir_required="style='text-align:right !important'";
                $dir_buttons_right="";
                $dir_buttons_left="";
            }
        ?>

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">
        
        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
           <h1><?php echo $dictionary->get_lang($lang,$KEY_REPORT_BY_PRODUCT_NAME); ?></h1>
           <nav>
               <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="../dashboard"><?php echo $dictionary->get_lang($lang,$KEY_HOME); ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_ADMIN); ?></li>
                    <li class="breadcrumb-item"><a href="../reports"><?php echo $dictionary->get_lang($lang,$KEY_REPORTS); ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_REPORT_BY_PRODUCT_NAME); ?></li>
               </ol>
           </nav>
        </div><!-- End Page Title -->

        <section class="section report" <?php echo $dictionary->get_dir($lang); ?>>

            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger-report" style="display:none;">
                        Error occurred.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_SELECT_PRODUCT_TYPE); ?></label>
                        <select class="form-control" id="products" data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SELECT_PRODUCT_TYPE); ?>" multiple>
                            <?php require_once 'php/get_product.php'; ?>
                        </select>
                        <span id="error_products" class="text-danger"></span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_FROM_DATE); ?> <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                <span id="error_initial_date" class="text-danger"></span>
                            </div>
                            <div class="col-md-12">
                                <input type="date" class="form-control datepicker" id="from_date" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_DATE); ?><">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="m-0"><?php echo $dictionary->get_lang($lang,$KEY_TO_DATE); ?> <span class="text-danger">*</span></label>
                            </div>
                            <div class="col-md-6 text-right" <?php echo $dir_required; ?>>
                                <span id="error_final_date" class="text-danger"></span>
                            </div>
                            <div class="col-md-12">
                                <input type="date" class="form-control datepicker" id="to_date" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_DATE); ?><" value="<?php echo date("Y-m-d"); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group pt-4">
                        <button type="button" class="btn btn-primary" id="btn_generate"><i class="fa fa-table mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_GENERATE); ?></button>
                    </div>
                </div>
            </div>

            <div class="row" id="container_buttons" style="display:none">
                <div class="col">
                    <div class="form-group text-left" <?php echo $dir_buttons_right ?>>
                        <button type="button" class="btn btn-success" id="btn_print"><i class="fa fa-print"></i></button>
                        <button type="button" class="btn btn-secondary" id="btn_export"><i class="fa fa-file-excel-o"></i></button>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group text-right" <?php echo $dir_buttons_left ?>>
                        <button type="button" class="btn btn-success" id="btn_table"><i class="fa fa-table"></i></button>
                        <button type="button" class="btn btn-primary" id="btn_diagram"><i class="fa fa-bar-chart"></i></button>
                    </div>
                </div>
            </div>

            <div class="row" id="container_table" style="display: none;">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="table_report">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_PRODUCT_NAME); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_QUANTITY); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_PRICE); ?>*1</th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_SIZE); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_COLOR); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_DATE); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_TIME); ?></th>
                                    <th><?php echo $dictionary->get_lang($lang,$KEY_TOTAL_PRICE); ?></th>
                                </tr>
                            </thead>
                            <tbody id="tbody">
                               
                            </tbody>
                            <tfoot id="tfoot">
                                <tr>
                                    <th>Total</th>
                                    <th id="nb_rows"></th>
                                    <th id="total_qty"></th>
                                    <th colspan="5"></th>
                                    <th id="sum_total" ></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row" id="container_diagram" style="display:none">
                <div class="col-md-10 offset-1">
                    <div class="table-responsive">
                        <div class="form-group" id="container_canvas">
                            <!-- <canvas id="barChart" style="height:600px"></canvas> -->
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