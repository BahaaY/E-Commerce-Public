<?php

    $error=0;
    $format="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        if(session_status() !== PHP_SESSION_ACTIVE){
            session_start();
        }
        
        require_once '../classes/products.php';
        require_once '../classes/cart.php';
        require_once '../../../config/variables.php';
        require_once '../../../config/helper.php';
        require_once '../../../config/conn.php';
        require_once '../../currency.php';
        require_once '../../../lang/key.php';
        require_once '../../resources/classes/dictionary.php';

        if(isset($_POST['token']) && isset($_SESSION[Session::$KEY_EC_USERID])){

            $token=$_POST['token'];
            $user_id=$_SESSION[Session::$KEY_EC_USERID];
            $user_id=Helper::decrypt($user_id);
                    
            if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                if($token_session == $token){

                    try{
    
                        $class_products=new Products($db_conn->get_link());
                        $class_cart=new Cart($db_conn->get_link());
                        $dictionary = new Dictionary($db_conn->get_link());
                
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

                        if(isset($_SESSION[Session::$KEY_EC_LANG])) {
                            $lang=$_SESSION[Session::$KEY_EC_LANG];
                        }else {
                            $lang="en";
                        }
        
                        if($lang=='en' || $lang=='fr') {
                            $dir_button= "style='direction:ltr !important;text-align:left !important'";
                        }else if($lang=='ar'){
                            $dir_button= "style='direction:rtl !important;text-align:right !important'";
                        }else{
                            $dir_button= "style='direction:ltr !important;text-align:left !important'";
                        }
            
                        $all_products = $class_products->get_products_in_favourites($user_id);
                
                        if(count($all_products) > 0){
                        
                            foreach($all_products as $product){
                
                                $product_id=$product[TableProducts::$COLUMN_PRODUCT_ID];
                                $title=$product[TableProducts::$COLUMN_TITLE];
                                $description=$product[TableProducts::$COLUMN_DESCRIPTION];
                                $price=$product[TableProducts::$COLUMN_PRICE];
                                $discount_price=$product[TableProducts::$COLUMN_DISCOUNT_PRICE];
                                $stock=$product[TableProducts::$COLUMN_STOCK];
                                $image=$product[TableProductImages::$COLUMN_IMAGE];
                                $product_type_id_FK=$product[TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK];
                
                                if($description == "" || $description == NULL){
                                    $description="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_DESCRIPTION_AVAILABLE)."</i>";
                                }
                
                                if($currency_id == 1){
                                    $price=$price*get_currency_rate($db_conn->get_link(),2);
                                }else if($currency_id == 2){
                                    $price=$price;
                                }else if($currency_id == 3){
                                    $price=$price/get_currency_rate($db_conn->get_link(),3);
                                }
                                    
                                if($discount_price!=0){
                                    $discount_price = $price-$price*($discount_price/100);
                                    $displayed_price='<p class="item-price strike mb-2"><b class="card-price">'.$currency_symbol.''.number_format(Helper::round_price($discount_price),2).'</b> <strike>'.$currency_symbol.''.number_format(Helper::round_price($price),2).'</strike></p>';
                                }else{
                                    $displayed_price='<p class="item-price mb-2"><b class="card-price">'.$currency_symbol.''.number_format(Helper::round_price($price),2).'</b></p>';
                                }
                
                                if($stock>=1){
                
                                    if($class_cart->check_cart($user_id,$product_id)>0){
                                        $button="<button type='button' class='btn btn-primary' id='btn_cart_".$product_id."' onclick='remove_from_cart(".$product_id.",0);'><i class='bi bi-cart mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_REMOVE_FROM_CART)."</button>";
                                    }else{
                                        $button="<button type='button' class='btn btn-primary' id='btn_cart_".$product_id."' onclick='add_to_cart(".$product_id.",0);'><i class='bi bi-cart mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_ADD_TO_CART)."</button>";
                                    }
                                    
                                }else{
                                    $button="<button type='button' class='btn btn-danger' disabled ><i class='bi bi-cart mr-2'></i>Out of stock</button>";
                                }
                
                                if($image != ""){
                                    if(is_dir("../../uploaded_products")){
                                        if(file_exists("../../uploaded_products/".$image)){
                                            $displayed_image= '
                                                <img class="card-img-top" src="../uploaded_products/'.$image.'" alt="Card image product"
                                                    id="card-image-product" onclick="view_product('.$product_id.');">
                                            ';
                                        }else{
                                            $displayed_image="<img src='../../images/no_image.png' alt='Card image product' id='card-image-product' onclick='view_product(".$product_id.");'>";
                                        }
                                    }else{
                                        $displayed_image="<img src='../../images/no_image.png' alt='Card image product' id='card-image-product' onclick='view_product(".$product_id.");'>";
                                    }
                                }else{
                                    $displayed_image="<img src='../../images/no_image.png' alt='Card image product' id='card-image-product' onclick='view_product(".$product_id.");'>";
                                }
                
                                $format.='
                                    <div class="col-sm-6 col-md-4 col-lg-3" id="row_'.$product['product_id'].'" >
                                        <div class="card-product-type-name d-none">'.$product_type_id_FK.'</div>
                                        <div class="card">
                                            '.$displayed_image.'
                                            <div class="card-body pb-1">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="card-title pb-1 text-center" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'.$title.'</h5>
                                                    </div>
                                                </div>
                                                <p class="card-description mb-2" style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">'.$description.'</p>
                                                '.$displayed_price.' 
                                                <div class="row">
                                                    <div class="col p-0 text-left" '.$dir_button.'>
                                                        <div class="form-group p-1" id="container-button-'.$product_id.'">
                                                            '.$button.'
                                                        </div>
                                                    </div>
                                                    <div class="col-2 d-flex align-items-center justify-content-center">
                                                        <i class="bi bi-heart-fill text-danger" id="i-favourite-'.$product_id.'" style="margin-bottom:13px" onclick="remove_from_favourites('.$product_id.');"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                ';
                            }
                        }else{
            
                            $format='
                                <div class="col-md-12 text-center">
                                    <h5>'.$dictionary->get_lang($lang,$KEY_NO_PRODUCTS_IN_FAVORITE).'</h5>
                                </div>
                            ';
                    
                        }
            
                    }catch(PDOException $ex){
                        $error = 1;
                        $format = "";
                    }

                }else{
                    $error = 1;
                    $format = "";
                }
            }else{
                $error = 1;
                $format = "";
            }
        }else{
            $error = 1;
            $format = "";
        }

    }else{
        $error = 1;
        $format = "";
    }

    echo json_encode([
        "error"=>$error,
        "products"=>$format
    ]);

?>