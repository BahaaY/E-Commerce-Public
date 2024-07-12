<!DOCTYPE html>
<html lang="en">

<head>

    <title>Home / Products</title>
    <?php 

        require_once '../main-head.php'; 
        permission_products($permission,$is_active,$is_verified);

        require_once '../../config/variables.php';
        require_once '../../config/conn.php';
        require_once 'classes/product_type.php';
        require_once '../currency.php';
    
        $class_product_type=new ProductType($db_conn->get_link());

    ?>

    <!-- CSS & JS -->
    <link rel="stylesheet" href="css/products.css">
    <script src="js/products.js"></script>

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php' ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main id="main" class="main"> 

        <input type="hidden" id="key_required" value="<?php echo $dictionary->get_lang($lang,$KEY_REQUIRED);  ?>">
        <input type="hidden" id="key_add_to_cart" value="<?php echo $dictionary->get_lang($lang,$KEY_ADD_TO_CART);  ?>">
        <input type="hidden" id="key_remove_from_cart" value="<?php echo $dictionary->get_lang($lang,$KEY_REMOVE_FROM_CART);  ?>">
        <input type="hidden" id="key_no_product_available" value="<?php echo $dictionary->get_lang($lang,$KEY_NO_PRODUCTS_AVAILABLE); ?>">

        <div class="pagetitle m-0" <?php echo $dictionary->get_dir($lang); ?>>
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-3">
                    <div class="form-group mb-2">
                        <h1><?php echo $dictionary->get_lang($lang,$KEY_PRODUCTS);  ?></h1>
                        <nav>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="../products"><?php echo $dictionary->get_lang($lang,$KEY_HOME);  ?></a></li>
                                <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_PRODUCTS);  ?></li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group mb-2 mt-1">
                        <select class="form-control" id="search_type" onchange=search_type(); data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SEARCH_BY_PRODUCT_TYPE);  ?>">
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
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group mb-2 mt-1">
                        <select class="form-control" id="search_price" onchange="search_price();" data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SEARCH_BY_PRODUCT_PRICE);  ?>">
                            <option></option>
                            <?php
                                if(isset($_SESSION[Session::$KEY_EC_CURRENCY])){
                                    $currency_abbreviation=$_SESSION[Session::$KEY_EC_CURRENCY];
                                }else{
                                    $currency_abbreviation=Currency::$KEY_EC_DEFAULT_ACTIVE_CURRENCY;
                                }
                                $currency_info=get_currency_info($db_conn->get_link(),$currency_abbreviation);
                                if($currency_info){
                                    $currency_id=$currency_info['currency_id'];
                                    $currency_symbol=$currency_info['currency_symbol'];
                                }else{
                                    $currency_id=2;
                                    $currency_symbol="$";
                                }
                                $array_price = [
                                    "1"=>"5",
                                    "5"=>"10",
                                    "10"=>"20",
                                    "20"=>"30",
                                    "30"=>"40",
                                    "40"=>"50",
                                    "50"=>"100",
                                    "100"=>"100+",
                                ];
                                foreach ($array_price as $price_from => $price_to) {
                                    if($currency_id == 1){
                                        $price_from=$price_from*get_currency_rate($db_conn->get_link(),2);
                                        $price_from=number_format(Helper::round_price($price_from),2);
                                        if($price_to != "100+"){
                                            $price_to=$price_to*get_currency_rate($db_conn->get_link(),2);
                                            $price_to=number_format(Helper::round_price($price_to),2);
                                        }else{
                                            $price_to="+";
                                        }
                                    }else if($currency_id == 2){
                                        $price_from=$price_from;
                                        $price_from=number_format(Helper::round_price($price_from),2);
                                        if($price_to != "100+"){
                                            $price_to=$price_to;
                                            $price_to=number_format(Helper::round_price($price_to),2);
                                        }else{
                                            $price_to="+";
                                        }
                                    }else if($currency_id == 3){
                                        $price_from=$price_from/get_currency_rate($db_conn->get_link(),3);
                                        $price_from=number_format(Helper::round_price($price_from),2);
                                        if($price_to != "100+"){
                                            $price_to=$price_to/get_currency_rate($db_conn->get_link(),3);
                                            $price_to=number_format(Helper::round_price($price_to),2);
                                        }else{
                                            $price_to="+";
                                        }
                                    }
                                    if($price_to != "+"){
                                        echo "<option value='".$price_from."-".$price_to."'>".$currency_symbol."".$price_from." - ".$currency_symbol."".$price_to."</option>";
                                    }else{
                                        echo "<option value='".$price_from."-".$price_to."'>".$currency_symbol."".$price_from."".$price_to."</option>";
                                    }
                                    
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group mb-3 mt-1">
                        <input type="search" id="search_text" onkeyup="search_text();" class="form-control" placeholder="<?php echo $dictionary->get_lang($lang,$KEY_TYPE_TO_START_SEARCH);  ?>" style="height:34px">
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3 d-none d-lg-block">
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group mb-2 mt-1">
                        <select class="form-control" id="order_by_price" data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SORT_BY_PRICE);  ?>">
                            <option value=""></option>
                            <option value="ASC">Lowest to Highest</option>
                            <option value="DESC">Highest to Lowest</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="form-group mb-2 mt-1">
                        <select class="form-control" id="order_by_stock" data-placeholder="<?php echo $dictionary->get_lang($lang,$KEY_SORT_BY_STOCK);  ?>">
                            <option value=""></option>
                            <option value="ASC">Lowest to Highest</option>
                            <option value="DESC">Highest to Lowest</option>
                        </select>
                    </div>
                </div>
            </div>
            
        </div><!-- End Page Title -->

        <section class="section products" <?php echo $dictionary->get_dir($lang); ?>>

            <div class="row" id="current_product">

                <div class="col-lg-12">
                    
                    <input type="hidden" id="user-permission" value="<?php echo $permission ?>">

                    <div class="row" id="container-products">

                    </div>

                </div>
                <div class="row">
                    <div aria-live="polite" aria-atomic="true" class="container-toast" id="toast-logged-in">
                            
                    </div>
                </div>
                <div class="row">
                    <div class="col-ml-md-12 text-center" id="no-products-found" style="display:none;">
                        <h5><?php echo $dictionary->get_lang($lang,$KEY_NO_MATCHING_PRODUCTS_FOUND);  ?></h5>
                    </div>
                </div>
            </div>

            <div class="row" id="loading"  style="display:none" >

                <div class="col-md-12 p-0 m-0 d-flex justify-content-center align-items-center">
                    <div class="spinner-border" role="status">
                    
                    </div>
                </div>

            </div>

            <div class="row" id="parent_product">
                    
            </div>
            
        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
