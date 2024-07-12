<?php

    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once '../../config/conn.php';
    require_once '../../config/variables.php';
    require_once '../../config/helper.php';
    require_once '../currency.php';

    if(isset($_SESSION[Session::$KEY_EC_USERID])){
        $user_id=$_SESSION[Session::$KEY_EC_USERID];
        $user_id=Helper::decrypt($user_id);
    }else{
        $user_id=0;
    }

    require_once 'classes/orders.php';
    $class_orders=new Orders($db_conn->get_link());
    $order_user_info=$class_orders->get_order_user_info($user_id);
    $fullname="";
    if($order_user_info){
        $fullname=$order_user_info['fullname'];
        $username=$order_user_info['username'];
        $email=$order_user_info['email'];
        $country=$order_user_info['country'];
        $region=$order_user_info['region'];
        $address=$order_user_info['address'];
        $phone_number=$order_user_info['phone_number'];
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <title>Home / Cart</title>
    <?php
    
        require_once '../main-head.php';
        permission_cart_page($permission,$is_active,$is_verified);

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
    
    ?>

    <script src="js/cart.js"></script>

    <link rel="stylesheet" href="css/cart.css">

</head>

<body>

    <!-- ======= Header ======= -->
    <?php require_once '../header.php'; ?>

    <!-- ======= Sidebar ======= -->
    <?php require_once '../sidebar.php'; ?>

    <main id="main" class="main">

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

        <div class="pagetitle" <?php echo $dictionary->get_dir($lang); ?>>
            <h1><?php echo $dictionary->get_lang($lang,$KEY_CART);  ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../products"><?php echo $dictionary->get_lang($lang,$KEY_HOME);  ?></a></li>
                    <li class="breadcrumb-item active"><?php echo $dictionary->get_lang($lang,$KEY_CART);  ?></li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <div class="col-md-12 p-0 m-0">
            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-checkout">
                Order has been requested successfully.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
             </div>
        </div>
        <div class="col-md-12 p-0 m-0">
            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger-checkout">
                Error occurred.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
             </div>
        </div>
        <section class="section cart d-flex flex-column align-items-center justify-content-center" id="section-cart" style="width:100% !important">
        
            <div class="container-fluid p-0" <?php echo $dictionary->get_dir($lang); ?>>
                <div class="row justify-content-center m-0 p-0">
                    <div class="col-md-12 p-0">
                        <div class="table-responsive">
                            <table class="table bg-white m-0" id="table-cart">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_IMAGE);  ?></th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_PRODUCT);  ?></th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_PRICE);  ?></th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_QUANTITY);  ?></th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_SIZE);  ?></th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_COLOR);  ?></th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_STOCK);  ?></th>
                                        <th scope="col"><?php echo $dictionary->get_lang($lang,$KEY_TOTAL);  ?></th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody id="tbody">
                                    <?php

                                        require_once '../../config/conn.php';
                                        require_once '../../config/variables.php';
                                        require_once 'classes/cart.php';
                                        require_once 'classes/products.php';
                                        require_once 'classes/order_type.php';
                                        require_once 'classes/product_size.php';
                                        $class_cart=new Cart($db_conn->get_link());
                                        $class_products=new Products($db_conn->get_link());
                                        $class_order_type=new OrderType($db_conn->get_link());
                                        $class_product_size=new ProductSize($db_conn->get_link());

                                        $has_product=false;
                                        $row="";
                                        $total_price=0;
                                        $serial_number=0;
                                        $folder_path="../uploaded_products/";

                                        $all_products_in_cart = $class_cart->get_products_in_cart($user_id);

                                        if($all_products_in_cart){

                                            $has_product=true;

                                            foreach($all_products_in_cart as $product){

                                                $serial_number++;
    
                                                $cart_id=$product[TableCart::$COLUMN_CART_ID];
                                                $product_id=$product[TableProducts::$COLUMN_PRODUCT_ID];
                                                $product_title=$product[TableProducts::$COLUMN_TITLE];
                                                $price=$product[TableProducts::$COLUMN_PRICE];
                                                $stock=$product[TableProducts::$COLUMN_STOCK];
                                                $color=$product[TableProducts::$COLUMN_COLOR];
                                                $size=$product[TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK];
                                                $discount_price=$product[TableProducts::$COLUMN_DISCOUNT_PRICE];
                                                $image=$product[TableProductImages::$COLUMN_IMAGE];

                                                if($currency_id == 1){
                                                    $price=$price*get_currency_rate($db_conn->get_link(),2);
                                                }else if($currency_id == 2){
                                                    $price=$price;
                                                }else if($currency_id == 3){
                                                    $price=$price/get_currency_rate($db_conn->get_link(),3);
                                                }

                                                if($discount_price == "" || $discount_price == 0){
                                                    $total_price+=$price;
                                                }else{
                                                    $new_price = $price - $price * ($discount_price/100);
                                                    $total_price+=$new_price;
                                                }
    
                                                $row.="
                                                    <tr id='tr_$cart_id'>
                                                        <td><b>$serial_number</b></td>
                                                        <td>
                                                            <img src='$folder_path$image' width='130px' height='130px'>
                                                        </td>
                                                        <td>$product_title</td>
                                                        <td>";
                                                        if($discount_price == "" || $discount_price == 0){
                                                            $row.="".$currency_symbol."<span id='price_$cart_id'>".number_format(Helper::round_price($price),2)."</span></td>";
                                                        }else{
                                                            $new_price = $price - $price * ($discount_price/100);
                                                            $row.="".$currency_symbol."<span id='price_$cart_id'>".number_format(Helper::round_price($new_price),2)."</span> <strike>".number_format(Helper::round_price($price),2)."</strike></td>";
                                                        }
                                                        $nb_stock=$class_products->get_stock($product_id);
                                                        if($nb_stock > 0){
                                                            $value=1;
                                                        }else{
                                                            $value=0;
                                                        }
                                                        $row.="<td>
                                                            <div class='input-group text-center' id='qty_selector'>
                                                                <a class='decrement-btn' onclick='decrement_price($cart_id);'>
                                                                    <i class='fa fa-minus'></i>
                                                                </a>
                                                                <input type='text' readonly='readonly' id='qty_$cart_id' class='qty-input text-center' value='".$value."' max='".$nb_stock."'/>
                                                                <a class='increment-btn' onclick='increment_price($cart_id);'>
                                                                    <i class='fa fa-plus'></i>
                                                                </a>
                                                            </div>
                                                        </td>";
                                                        $row.="
                                                        <td>";
                                                        if($size != ""){
                                                            $array_size=$class_product_size->get_product_size($size);
                                                            if($array_size){
                                                                $check_checked=0;
                                                                foreach($array_size as $size){
                                                                    $size_id=$size['product_size_id'];
                                                                    $size_name=$size['product_size_name'];
                                                                    $check_checked++;
                                                                        if($check_checked == 1){
                                                                            $checked="checked";
                                                                        }else{
                                                                            $checked="";
                                                                        }
                                                                    $row.='
                                                                        <div class="form-check">
                                                                            <input class="form-check-input " '.$checked.' type="radio" name="size__'.$cart_id.'" product_size_id="'.$size_id.'" value="'.$size_name.'" id="'.$size_name.'__'.$cart_id.'" onclick="get_size('.$cart_id.');">
                                                                            <label class="mr-4" for="'.$size_name.'__'.$cart_id.'">'.$size_name.'</label><br>
                                                                        </div>
                                                                    ';
                                                                }
                                                            }else{
                                                                
                                                                $row.=$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE);
                                                            }
                                                        }else{

                                                            $row.=$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE);
                                                        }
                                                        
                                                        $row.="</td>
                                                        <td>";
                                                            if($color!=""){
                                                                $colors=explode(",",$color);
                                                            }else{
                                                                $colors=array();
                                                            }
                                                            
                                                            if(count($colors)>0){
                                                                $check_checked=0;
                                                                foreach($colors as $color){
                                                                    $check_checked++;
                                                                    if($check_checked == 1){
                                                                        $checked="checked";
                                                                    }else{
                                                                        $checked="";
                                                                    }
                                                                    $row.='
                                                                        <div class="form-check">
                                                                            <input class="form-check-input " '.$checked.' type="radio" name="color__'.$cart_id.'" value="'.$color.'" id="'.$color.'__'.$cart_id.'" onclick="get_color('.$cart_id.');">
                                                                            <label class="mr-4" for="'.$color.'__'.$cart_id.'">'.$color.'</label><br>
                                                                        </div>
                                                                    ';
                                                                }
                                                            }else{
                                                                $row.=$dictionary->get_lang($lang,$KEY_NO_COLOR_AVAILABLE);
                                                            }
                                                            
                                                        $row.="
                                                        </td>
                                                        <td>
                                                            ".$stock."
                                                        </td>
                                                        <td>
                                                            ".$currency_symbol."<span id='total_price_$cart_id' class='total_price'>";
                                                            if($discount_price == "" || $discount_price == 0){
                                                                $row.=number_format(Helper::round_price($price),2);
                                                            }else{
                                                                $new_price = $price - $price * ($discount_price/100);
                                                                $row.=number_format(Helper::round_price($new_price),2);
                                                            }
                                                        $row.="
                                                            </span></td>
                                                        <td><i class='fa fa-close icon-close' role='button' data-toggle='modal' data-target='#modal-remove-cart-".$cart_id."'></td>
                                                    </tr>
                                                ";

                                                //Modal remove cart
                                                $row.="
                                                    <div class='modal fade' id='modal-remove-cart-".$cart_id."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                                        <div class='modal-dialog' role='document'>
                                                            <div class='modal-content'>
                                                            <div class='modal-header' style='direction:ltr !important'>
                                                                <h5 class='modal-title' id='exampleModalLabel'>".$dictionary->get_lang($lang,$KEY_REMOVE)." ".$product_title."</h5>
                                                                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                                                <span aria-hidden='true'>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class='modal-body'>
                                                                Are you sure you want remove this item from your cart ".$exclamation_mark."
                                                            </div>
                                                            <div class='modal-footer'>
                                                                <button type='button' class='btn btn-secondary' data-dismiss='modal'><i class='fa fa-close mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_CLOSE)."</button>
                                                                <button type='button' class='btn btn-danger' id='btn_remove_cart_item_".$cart_id."' onclick='remove_cart_item($cart_id);'><i class='bi bi-trash mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_REMOVE)."</button>
                                                            </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ";
    
                                            }

                                        }else{

                                            $has_product=false;

                                            $row.="
                                                <tr>
                                                    <td colspan='10' class='text-center'>".$dictionary->get_lang($lang,$KEY_NO_PRODUCTS_IN_CART)."</td>
                                                </tr>
                                            ";

                                        }

                                        echo $row;

                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php

                    $row="";
                    if($has_product){

                        $row='
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="row" id="container-shipping">

                                            <div class="col-md-8 col-sm-8">
                                                <div class="tab-content p-2">

                                                    <div class="row mb-4">
                                                        <span class="h3 text-dark">'.$dictionary->get_lang($lang,$KEY_CHOOSE_SHIPPING_MODE).':</span>
                                                    </div>

                                                    <div class="tab-pane fade show active profile-overview" id="profile-overview">';


                                                        $orders_type=$class_order_type->get_order_type();
                                                        if($orders_type){
                                                            $is_checked=0;
                                                            foreach($orders_type as $order_type){
                                                                $is_checked++;
                                                                
                                                                $order_type_id=$order_type['order_type_id'];
                                                                $order_type_name=$order_type['order_type_name'];
                                                                $amount=$order_type['amount'];
                                                                // $currency_id=$order_type['currency_id_FK'];

                                                                $order_type_name_text="";

                                                                if($currency_id == 1){
                                                                    $amount=$amount*get_currency_rate($db_conn->get_link(),2);
                                                                }else if($currency_id == 2){
                                                                    $amount=$amount;
                                                                }else if($currency_id == 3){
                                                                    $amount=$amount/get_currency_rate($db_conn->get_link(),3);
                                                                }

                                                                // if($amount > 0){
                                                                //     if($currency_id == 1){
                                                                //         $order_type_name_text=$order_type_name . " ( +L.L.".number_format(Helper::round_price($amount),2)." )";
                                                                //     }else{
                                                                //         $order_type_name_text=$order_type_name . " ( +$".number_format(Helper::round_price($amount),2)." )";
                                                                //     }
                                                                // }
                                                                $order_type_name_text=$order_type_name . " ( +".$currency_symbol."".number_format(Helper::round_price($amount),2)." )";
                                                               
                                                                if($is_checked == 1){
                                                                    $row.='
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input" type="radio" value="'.number_format(Helper::round_price($amount),2).'" order_type_name="'.$order_type_name.'" id="'.$order_type_id.'" name="shipping_type" onchange=get_checked_input(); checked>
                                                                            <label class="form-check-label text-dark mr-4" for="'.$order_type_id.'">
                                                                                '.$order_type_name_text.'
                                                                            </label>
                                                                        </div>
                                                                    ';
                                                                }else{
                                                                    $row.='
                                                                        <div class="form-check mb-2">
                                                                            <input class="form-check-input" type="radio" value="'.number_format(Helper::round_price($amount),2).'" order_type_name="'.$order_type_name.'" id="'.$order_type_id.'" name="shipping_type" onchange=get_checked_input();>
                                                                            <label class="form-check-label text-dark mr-4" for="'.$order_type_id.'">
                                                                                '.$order_type_name_text.'
                                                                            </label>
                                                                        </div>
                                                                    ';
                                                                }
                                                                
                                                            }
                                                        }

                                                    $row.='
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-sm-4">
                                                <div class="tab-content p-2">

                                                    <div class="row">
                                                        <div class="col-8 h5">'.$dictionary->get_lang($lang,$KEY_SUBTOTAL_TTC).'</div>
                                                        <div class="col-4"><b>'.$currency_symbol.'<span id="subtotal">'.number_format(Helper::round_price($total_price),2).'</span></b></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-8 h5">'.$dictionary->get_lang($lang,$KEY_SHIPPING).'</div>
                                                        <div class="col-4"><b>'.$currency_symbol.'<b><b id="shipping">'.number_format(Helper::round_price(0),2).'</b></div>
                                                    </div>

                                                    <hr>

                                                    <div class="row">
                                                        <div class="col-8 h5">'.$dictionary->get_lang($lang,$KEY_TOTAL).'</div>
                                                        <div class="col-4"><b>'.$currency_symbol.'<span id="total-shipping">'.number_format(Helper::round_price($total_price),2).'</span></b></div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-12 text-center">
                                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modal-checkout" onclick="get_order_type_info();"><i class="bi bi-cart mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_CHECKOUT).'</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ';

                        //Modal checkout
                        $row.='
                            <div class="modal fade" id="modal-checkout" tabindex="-1" role="dialog" aria-labelledby="modal-checkout" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header" style="direction:ltr">
                                            <h5 class="modal-title" id="modal-checkout">'.$dictionary->get_lang($lang,$KEY_CHECKOUT).'</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body" style="direction: ltr !important">
                                            
                                            <div class="container-fluid row">

                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="card p-3 m-0">
                                                        <h4>Billing Address</h4>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group m-0 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text"><i class="bi bi-person"></i></div>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="fullname"
                                                                            placeholder="'.$dictionary->get_lang($lang,$KEY_FULLNAME).'" value="'.$fullname.'">
                                                                    </div>
                                                                    <label class="text-danger" id="error_fullname" '.$dir_required.'></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group m-0 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text"><i class="bi bi-person"></i></div>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="username"
                                                                            placeholder="'.$dictionary->get_lang($lang,$KEY_USERNAME).'" value="'.$username.'">
                                                                    </div>
                                                                    <label class="text-danger" id="error_username" '.$dir_required.'></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group m-0 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text"><i class="bi bi-envelope"></i></div>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="email"
                                                                            placeholder="'.$dictionary->get_lang($lang,$KEY_EMAIL).'" value="'.$email.'">
                                                                    </div>
                                                                    <label class="text-danger" id="error_email" '.$dir_required.'></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group m-0 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text"><i class="bi bi-flag"></i></div>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="country"
                                                                            placeholder="'.$dictionary->get_lang($lang,$KEY_COUNTRY).'" value="'.$country.'">
                                                                    </div>
                                                                    <label class="text-danger" id="error_country" '.$dir_required.'></label>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group m-0 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text"><i class="bi bi-flag"></i></div>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="region"
                                                                            placeholder="'.$dictionary->get_lang($lang,$KEY_REGION).'" value="'.$region.'">
                                                                    </div>
                                                                    <label class="text-danger" id="error_region" '.$dir_required.'></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group m-0 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text"><i class="bi bi-house"></i></div>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="address"
                                                                            placeholder="'.$dictionary->get_lang($lang,$KEY_ADDRESS).'" value="'.$address.'">
                                                                    </div>
                                                                    <label class="text-danger" id="error_address" '.$dir_required.'></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group m-0 p-0">
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text"><i class="bi bi-telephone"></i></div>
                                                                        </div>
                                                                        <input type="text" class="form-control" id="phone_number"
                                                                            placeholder="'.$dictionary->get_lang($lang,$KEY_PHONE_NUMBER).'" value="'.$phone_number.'">
                                                                    </div>
                                                                    <label class="text-danger" id="error_phone_number" '.$dir_required.'></label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6 col-sm-12">
                                                    <div class="card p-3 m-0">
                                                        <h4>'.$dictionary->get_lang($lang,$KEY_ORDER_DETAILS).'</h4>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                            <div class="table-responsive">
                                                                <table class="table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th class="titles" colspan="2">'.$dictionary->get_lang($lang,$KEY_PRODUCT).'</th>
                                                                            <th class="titles">'.$dictionary->get_lang($lang,$KEY_PRICE).' x1</th>
                                                                            <th class="titles">'.$dictionary->get_lang($lang,$KEY_QUANTITY).'</th>
                                                                            <th class="titles">'.$dictionary->get_lang($lang,$KEY_SIZE).'</th>
                                                                            <th class="titles">'.$dictionary->get_lang($lang,$KEY_COLOR).'</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody style="font-weight: normal">';

                                                                        require_once 'classes/cart.php';
                                                                        $class_cart=new Cart($db_conn->get_link());
                                
                                                                        $has_product=false;
                                                                        $total_price=0;
                                                                        $serial_number=0;
                                                                        $folder_path="../uploaded_products/";
                                
                                                                        $all_products_in_cart = $class_cart->get_products_in_cart($user_id);
                                
                                                                        if($all_products_in_cart){
                                
                                                                            $has_product=true;
                                                                            $string_products_id="";
                                                                            $string_carts_id="";
                                
                                                                            foreach($all_products_in_cart as $product){
                                
                                                                                $serial_number++;
                                    
                                                                                $cart_id=$product[TableCart::$COLUMN_CART_ID];
                                                                                $product_id=$product[TableProducts::$COLUMN_PRODUCT_ID];
                                                                                $product_title=$product[TableProducts::$COLUMN_TITLE];
                                                                                $price=$product[TableProducts::$COLUMN_PRICE];
                                                                                $size=$product[TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK];
                                                                                $color=$product[TableProducts::$COLUMN_COLOR];
                                                                                $discount_price=$product[TableProducts::$COLUMN_DISCOUNT_PRICE];
                                                                                $image=$product[TableProductImages::$COLUMN_IMAGE];

                                                                                if($currency_id == 1){
                                                                                    $price=$price*get_currency_rate($db_conn->get_link(),2);
                                                                                }else if($currency_id == 2){
                                                                                    $price=$price;
                                                                                }else if($currency_id == 3){
                                                                                    $price=$price/get_currency_rate($db_conn->get_link(),3);
                                                                                }
                                
                                                                                if($discount_price == "" || $discount_price == 0){
                                                                                    $total_price+=$price;
                                                                                }else{
                                                                                    $new_price = $price - $price * ($discount_price/100);
                                                                                    $total_price+=$new_price;
                                                                                }

                                                                                $string_products_id.=$product_id.",";
                                                                                $string_carts_id.=$cart_id.",";
                                    
                                                                                $row.="
                                                                                    <tr>
                                                                                        <td>
                                                                                            <img src='$folder_path"."$image' width='60px' height='60px'>
                                                                                        </td>
                                                                                        <td>$product_title</td>
                                                                                        <td>";
                                                                                        if($discount_price == "" || $discount_price == 0){
                                                                                            $row.="".$currency_symbol."<span id='price_$cart_id'>".number_format(Helper::round_price($price),2)."</span></td>";
                                                                                        }else{
                                                                                            $new_price = $price - $price * ($discount_price/100);
                                                                                            $row.="".$currency_symbol."<span id='price_$cart_id'>".number_format(Helper::round_price($new_price),2)."</span></td>";
                                                                                        }
                                                                                        if($size !=""){
                                                                                            $array_size=$class_product_size->get_product_size($size);
                                                                                            if($array_size){
                                                                                                $size=$array_size[0]['product_size_name'];
                                                                                            }else{
                                                                                                $size=$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE);
                                                                                            }
                                                                                        }else{
                                                                                            $size=$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE);
                                                                                        }
                                                                                        
                                                                                        if($color !="" || $color !=NULL){
                                                                                            $array_color=explode(",",$color);
                                                                                        }else{
                                                                                            $array_color=array();
                                                                                        }
                                                                                        if(count($array_color) > 0){
                                                                                            $color=$array_color[0];
                                                                                        }else{
                                                                                            $color=$dictionary->get_lang($lang,$KEY_NO_COLOR_AVAILABLE);
                                                                                        }
                                                                                        $row.="
                                                                                        </td>
                                                                                        <td><span id='qty_order_details_".$cart_id."'>1<span></td>
                                                                                        <td><span id='size_order_details_".$cart_id."'><span>".$size."</td>
                                                                                        <td><span id='color_order_details_".$cart_id."'><span>".$color."</td>
                                                                                    </tr>
                                                                                ";
                                                                            }
                                                                        }

                                                                        $string_products_id = substr($string_products_id, 0, -1);
                                                                        $string_carts_id = substr($string_carts_id, 0, -1);
                                                                    
                                                                        $row.='
                                                                        <tr>
                                                                            <td colspan="6" class="text-end">
                                                                                <h5 class="shipping_mode"><span id="order_type_name"></span>: '.$currency_symbol.'<span id="order_type_amount"></span></h5>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="6" class="text-end">
                                                                                <h5 class="total">'.$dictionary->get_lang($lang,$KEY_TOTAL_PRICE).': '.$currency_symbol.'<span id="total_price_order_details">'.number_format(Helper::round_price($total_price),2).'</span></h5>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" id="btn_request_order" onclick=request_order("'.$string_products_id.'","'.$string_carts_id.'");><i class="bi bi-send mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_REQUEST_ORDER).'</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_CLOSE).'</button>
                                    </div>
                                </div>
                            </div>
                        ';
                    }
                    echo $row;
                    
                ?>
                
            </div>

        </section>

    </main><!-- End #main -->

    <!-- Modal Sign Out-->
    <?php require_once '../modal-logout.php'; ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

</body>

</html>
