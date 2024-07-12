<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin / Manage Products</title>
    <?php
    
    require_once '../main-head.php';
    permission_manage_products_page($permission,$is_active,$is_verified);
    
    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/manage_products.css">
    <script src="js/manage_products.js"></script>

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

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">

        <div class="pagetitle"  <?php echo $dictionary->get_dir($lang); ?>>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group mb-2">
                        <h1><?php echo $dictionary->get_lang($lang,$KEY_MANAGE_PRODUCTS);  ?></h1>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../dashboard"><?php echo $dictionary->get_lang($lang,$KEY_HOME);  ?></a></li>
                                <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_ADMIN);  ?></li>
                                <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_MANAGE_PRODUCTS);  ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group mb-2 mt-1">
                        <select class="form-control" id="search_by_category" onchange="search();" data-placeholder="Search by category">
                                <option></option>
                                <?php
                                    require_once '../../config/conn.php';
                                    require_once 'classes/product_type.php';
                                    $row_option="";
                                    $class_product_type=new ProductType($db_conn->get_link());
                                    foreach($class_product_type->get_all_product_type() as $product_type){
                                        $product_type_id=$product_type[TableProductType::$COLUMN_PRODUCT_TYPE_ID];
                                        $product_type_name=$product_type[TableProductType::$COLUMN_PRODUCT_TYPE_NAME];
                                        $row_option.="
                                            <option value='".$product_type_id."'>".$product_type_name."</option>
                                        ";
                                    }
                                    echo $row_option;
                                ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group mb-2 mt-1">
                        <select class="form-control" id="search_by_stock" onchange="search();" data-placeholder="Search by stock">
                            <option></option>
                            <option value="1">In stock</option>
                            <option value="0">Out of stock</option>
                        </select>
                    </div>
                </div>
            </div>
        </div><!-- End Page Title -->

        <section class="section manage-product" id="container-table"  <?php echo $dictionary->get_dir($lang); ?>>

            <div class="row">

                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-update-product">
                        Product has been updated.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-delete-product">
                        Product has been deleted.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-delete-image">
                        Image has been deleted
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger-product">
                        Error occurred
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                </div>

                <div class="col-md-12 ">
                    <div class="table-responsive">
                        <table class="table" id="table_manage_products">
                            <thead class="thead-light">
                                <th>#</th>
                                <th><?php echo $dictionary->get_lang($lang,$KEY_PRODUCT_TITLE);  ?></th>
                                <th><?php echo $dictionary->get_lang($lang,$KEY_PRODUCT_IMAGE);  ?></th>
                                <th><?php echo $dictionary->get_lang($lang,$KEY_ACTION);  ?></th>
                                <th></th>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </section>

        <!-- Edit product -->
        <section class="row" id="container-update-product" style="display: none;">

            <div class="container" <?php echo $dictionary->get_dir($lang); ?>>

                <?php
                    if($lang == "ar"){
                        $dir_arrow="right";
                    }else{
                        $dir_arrow="left";
                    }
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <a role="button" onclick="back();" class="btn btn-primary"><i class="bi bi-arrow-<?php echo $dir_arrow ?>"></i>
                        <?php echo $dictionary->get_lang($lang,$KEY_BACK);  ?></a>
                    </div>
                </div>
                <div class="product-content product-wrap clearfix product-deatil pb-3 pt-3 mt-2 mb-0">

                    <div class="row">

                        <div class="col-md-9">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_TITLE);  ?></label>
                                        <label class="mb-0 text-danger">*</label>
                                    </div>
                                    <div class="col text-right" <?php echo $dir_required; ?>>
                                        <label class="mb-0 text-danger" id="error_title"></label>
                                    </div>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control" id="title"
                                            placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_TITLE);  ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_AVAILABILITY);  ?></label>
                                    </div>
                                    <div class="col-md-12">
                                        <select class="form-control" id="availability"
                                            data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SELECT_AVAILABILITY);  ?>">
                                            
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_DESCRIPTION);  ?></label>
                                <textarea class="form-control" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_DESCRIPTION);  ?>" id="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_PRICE);  ?></label>
                                        <label class="mb-0 text-danger">*</label>
                                    </div>
                                    <div class="col text-right" <?php echo $dir_required; ?>>
                                        <label class="mb-0 text-danger" id="error_price"></label>
                                    </div>
                                    <div class="col-md-12" style="direction: ltr !important">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="bi bi-currency-dollar"></i>
                                                </div>
                                            </div>
                                            <input type="currency" class="form-control input-number" id="price"
                                                placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PRICE);  ?>" onchange="get_discount_result();" <?php echo $dictionary->get_dir($lang); ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5" style="direction: ltr !important">
                            <div class="form-group">
                                <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_DISCOUNT_PERCENTAGE);  ?></label>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="bi bi-percent"></i></div>
                                    </div>
                                    <input type="text" class="form-control input-number" id="discount_price" onchange="get_discount_result();"
                                        placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_DISCOUNT_PERCENTAGE);  ?>" <?php echo $dictionary->get_dir($lang); ?>>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group" style="margin-top:25px">
                                <label id="price_result"></label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_STOCK);  ?></label>
                                <div class="row">
                                    <div class="col-md-12">
                                        <span class="input-wrapper">
                                            <button id="decrement" onclick="decrement();"><span
                                                    class="span-minus-btn">&minus;</span></button>
                                            <input type="number" id="stock" onkeyup="check_stock()"; />
                                            <button id="increment" onclick="increment();"><span
                                                    class="span-plus-btn">&plus;</span></button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_COLOR);  ?></label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" id="color" class="form-control">
                                    </div>
                                    <div class="col-md-9 d-flex align-items-center justify-content-around"
                                        id="container-rounder-color">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_TYPE);  ?></label>
                                        <label class="mb-0 text-danger">*</label>
                                    </div>
                                    <div class="col text-right" <?php echo $dir_required; ?>>
                                        <label class="mb-0 text-danger" id="error_type"></label>
                                    </div>
                                    <div class="col-md-12">
                                        <select class="form-control" onchange="get_product_size();" id="type"
                                            data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SELECT_A_TYPE);  ?>">

                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_SIZE);  ?></label>
                                    </div>
                                    <div class="col text-right" <?php echo $dir_required; ?>>
                                        <label class="mb-0 text-danger" id="error_size"></label>
                                    </div>
                                    <input type="hidden" id="product_size_type">
                                    <div class="col-md-12">
                                        <div class="row ml-0 mr-0" id="container_product_size">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_IMAGE);  ?></label>
                                        <label class="mb-0 text-danger">*</label>
                                    </div>
                                    <div class="col text-right" <?php echo $dir_required; ?>>
                                        <label class="mb-0 text-danger" id="error_image"></label>
                                    </div>
                                    <div class="col-md-12">
                                        <input class="form-control" type="file" id="image" multiple
                                            accept="image/*" onchange="upload_image();">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="row" id="container-images">

                            </div>
                        </div>
                        <div class="col-md-12 text-center mt-2" id="container-button-update">
                            
                        </div>
                    </div>
                </div>

            </div>

        </section>

        <section class="row" id="loading"  style="display:none" >

            <div class="col-md-12 p-0 m-0 d-flex justify-content-center align-items-center">
                <div class="spinner-border" role="status">
                    
                </div>
            </div>

        </section>

        <!-- Container modal show image and delete product -->
        <section class="row" id="container-modals">

        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
