<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    require_once '../../config/variables.php';
    require_once '../../config/conn.php';
    require_once 'classes/product_type.php';

    $class_product_type=new ProductType($db_conn->get_link());

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Admin / Add Product</title>
    <?php 

        require_once '../main-head.php'; 
        permission_add_product_page($permission,$is_active,$is_verified);

    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/add_product.css">
    <link rel="stylesheet" type="text/css" href="css/import_excel_products.css">
    <script src="js/add_product.js"></script>
    <script src="js/import_excel_products.js"></script>

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
        <input type="hidden" id="key_select_a_type" value="<?php echo $dictionary->get_lang($lang,$KEY_SELECT_A_TYPE);  ?>">
        <input type="hidden" id="key_file_is_empty" value="<?php echo $dictionary->get_lang($lang,$KEY_FILE_IS_EMPTY);  ?>">
        <input type="hidden" id="key_no_products_found" value="<?php echo $dictionary->get_lang($lang,$KEY_NO_PRODUCTS_FOUND);  ?>">

        <div class="pagetitle"  <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_ADD_PRODUCT);  ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../dashboard"><?php echo $dictionary->get_lang($lang,$KEY_HOME);  ?></a></li>
                    <li class="breadcrumb-item"><?php echo $dictionary->get_lang($lang,$KEY_ADMIN);  ?></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_ADD_PRODUCT);  ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section add-product "  <?php echo $dictionary->get_dir($lang); ?>>

            <div class="row">
                <div class="col-xl-12">

                    <div class="card p-2 mb-3">
                        
                        <div class="tab-content p-2">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    Product has been added.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    Error occurred
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                            <div class="row">
                                <div class="col-md-12">
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
                                                <input type="text" class="form-control" id="title" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_TITLE);  ?>">
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
                                                        <div class="input-group-text"><i class="bi bi-currency-dollar"></i></div>
                                                    </div>
                                                    <input type='currency' class="form-control input-number" onchange="get_discount_result();" id="price" <?php echo $dictionary->get_dir($lang); ?> placeholder='<?php echo $dictionary->get_lang($lang,$KEY_ENTER_PRICE);  ?>' />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group" style="direction: ltr !important">
                                        <label class="mb-0"><?php echo $dictionary->get_lang($lang,$KEY_DISCOUNT_PERCENTAGE);  ?></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="bi bi-percent"></i></div>
                                            </div>
                                            <input type="text" class="form-control input-number" onchange="get_discount_result();" id="discount_price" <?php echo $dictionary->get_dir($lang); ?> placeholder="<?php echo $dictionary->get_lang($lang,$KEY_ENTER_DISCOUNT_PERCENTAGE);  ?>">
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
                                                    <button id="decrement" onclick="decrement();"><span class="span-minus-btn">&minus;</span></button>
                                                    <input type="number" value="1" id="stock" onchange="check_stock();"/>
                                                    <button id="increment" onclick="increment();"><span class="span-plus-btn">&plus;</span></button>
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
                                                <label class="mb-0 text-danger d-none">*</label>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <input type="text" id="color" class="form-control">
                                            </div>
                                            <div class="col-md-9 d-flex align-items-center justify-content-around" id="container-rounder-color">
                                                
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
                                                <select class="form-control" id="type" onchange="get_product_size();" data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SELECT_A_TYPE);  ?>">
                                                    <option></option>
                                                    <?php
                                                        $row_option="";
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
                                            <div class="col-md-12" id="container_product_size">
                                                <div class="row ml-0 mr-0 mt-1">
                                                    <?php echo $dictionary->get_lang($lang,$KEY_SELECT_A_TYPE);  ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
                                                <input class="form-control" type="file" id="image" multiple accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="container-images">
                                    
                                </div>
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary m-0 mt-3" id="btn_add_product"><i class="bi bi-plus mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_ADD_PRODUCT);?></button>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            <div class="row">
                <div class="col-xl-12">
                    <div class="card p-2">
                        <div class="tab-content p-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success">
                                        Products has been added.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger">
                                        Error occurred
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-file m-1">
                                            <input type="file" class="custom-file-input shadow-none" name="excel_file" id="excel_file" accept=".xlsm">
                                            <label class="custom-file-label shadow-none" for="customFile" id="file_text" style="padding-right:75px"><?php echo $dictionary->get_lang($lang,$KEY_CHOOSE_EXCEL_FILE);  ?></label>
                                        </div>
                                        <div class="text-danger m-1" id="error_excel_file"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button class="btn btn-secondary shadow-none m-1" id="btn_import"><i class="bi bi-filetype-xls mr-2 ml-2"></i><?php echo $dictionary->get_lang($lang,$KEY_IMPORT);  ?></button>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group pt-2">
                                        <span id="number_of_products" nb_products=""></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 ml-1">
                                    <div class="row" id="container_products">

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
