<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/products.php'; 
    require_once '../classes/cart.php';
    require_once '../classes/favourites.php'; 
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once '../../../config/helper.php';
    require_once '../../../lang/key.php';
    require_once '../../currency.php';
    require_once '../../resources/classes/dictionary.php';

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }

    if($lang=='en' || $lang=='fr') {
        $dir_button= "style='direction:ltr !important;text-align:left !important'";
        $dir_heart="text-right";
    }else if($lang=='ar'){
        $dir_button= "style='direction:rtl !important;text-align:right !important'";
        $dir_heart="text-left";
    }else{
        $dir_button= "style='direction:ltr !important;text-align:left !important'";
        $dir_heart="text-right";
    }

    $obj=new stdClass();
    $res=0;

    if(isset($_SESSION[Session::$KEY_EC_USERID])){
        $user_id=$_SESSION[Session::$KEY_EC_USERID];
        $user_id=Helper::decrypt($user_id);
    }else{
        $user_id=0;
    }

    $is_disabled="";
    $res=1;

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

    if(isset($_POST['permission'])){
        $permission = $_POST['permission'];
        if($permission == 1){
            $is_disabled="d-none";
        }else{
            $is_disabled="";
        }
    }

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['product_id'])){

            try{

                $product_id=$_POST['product_id'];

                $class_products=new Products($db_conn->get_link());
                $class_cart = new Cart($db_conn->get_link());
                $class_favourites = new Favourites($db_conn->get_link());
                $dictionary = new Dictionary($db_conn->get_link());

                if($product=$class_products->view_product($product_id)){

                    $title=$product[TableProducts::$COLUMN_TITLE];
                    $description=$product[TableProducts::$COLUMN_DESCRIPTION];
                    $price=$product[TableProducts::$COLUMN_PRICE];
                    $discount_price=$product[TableProducts::$COLUMN_DISCOUNT_PRICE];
                    $stock=$product[TableProducts::$COLUMN_STOCK];
                    $color=$product[TableProducts::$COLUMN_COLOR];
                    $size_id=$product[TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK];

                    if($description == "" || $description == NULL){
                        $description="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_DESCRIPTION_AVAILABLE)."</i>";
                    }

                    if($size_id != "" || $size_id != NULL){
                        $array_size=$class_products->get_size($size_id);
                    }else{
                        $array_size=array();
                    }
                    
                    if($color != "" || $color != NULL){
                        $array_color=explode(',',$color);
                    }else{
                        $array_color=array();
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
                        $displayed_price='<p class="strike m-0 p-0"><b>'.$currency_symbol.''.number_format(Helper::round_price($discount_price),2).'</b> <strike>'.$currency_symbol.''.number_format(Helper::round_price($price),2).'</strike> <small>*'.$dictionary->get_lang($lang,$KEY_INCLUDED_TAX).'</small></p>';
                    }else{
                        $displayed_price='<p class="p-0 m-0"><b>'.$currency_symbol.''.number_format(Helper::round_price($price),2).'</b> <small>*'.$dictionary->get_lang($lang,$KEY_INCLUDED_TAX).'</small></p>';
                    }

                    if($user_id == 0){
                        $heart_click="show_toast();";
                        $button_click="show_toast();";
                    }else{
                        $heart_click="action_heart($product_id,1);";
                        if($class_cart->check_cart($user_id,$product_id)>0){
                            $button_click="remove_from_cart($product_id,1);";
                        }else{
                            $button_click="add_to_cart($product_id,1);";
                        }
                    }

                    if($stock>=1){

                        if($class_cart->check_cart($user_id,$product_id)>0){
                            $button="<button type='button' class='btn btn-primary' id='btn_cart_info_".$product_id."' onclick='".$button_click."'><i class='bi bi-cart mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_REMOVE_FROM_CART)."</button>";
                        }else{
                            $button="<button type='button' class='btn btn-primary' id='btn_cart_info_".$product_id."' onclick='".$button_click."'><i class='bi bi-cart mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_ADD_TO_CART)."</button>";
                        }
                        
                    }else{
                        $button="<button type='button' class='btn btn-danger' disabled ><i class='bi bi-cart mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_OUT_OF_STOCK)."</button>";
                    }

                    if($class_favourites->check_favourite($user_id,$product_id)>0){
                        $heart="bi bi-heart-fill text-danger";
                    }else{
                        $heart="bi bi-heart"; 
                    }

                    if($lang == "ar"){
                        $dir_arrow="right";
                    }else{
                        $dir_arrow="left";
                    }

                    $format='
                        <div class="container">
                            <div class="row">
                                <div class="col-md-12">
                                    <a role="button" onclick="back();" class="btn btn-primary"><i class="bi bi-arrow-'.$dir_arrow.'"></i> '.$dictionary->get_lang($lang,$KEY_BACK).'</a>
                                </div>
                            </div>
                            <div class="product-content product-wrap clearfix product-deatil pb-3 pt-3 mt-2 mb-0">
                                <div class="row">
                                    <div class="col-md-5 p-0 m-0">
                                        <div class="product-image">
                                            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                                                <div class="carousel-indicators">
                    ';

                    $nbr_image=$class_products->get_number_images($product_id);

                    if($nbr_image > 0){

                        for($i=0;$i<$nbr_image;$i++){
                            if($i==0){
                                $format.='<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$i.'" class="active" aria-current="true" aria-label="Slide '.$i.'"></button>';
                            }else{
                                $format.='<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$i.'" aria-label="Slide '.$i.'"></button>';
                            }
                        }
                    
                    }

                    $format.='
                        </div>
                            <div class="carousel-inner mb-3">
                    ';
                
                    if($product_image=$class_products->get_images_product($product_id)){

                        $count=0;
                        foreach($product_image as $val){

                            $image=$val['image'];

                            if($count==0){
                                $attr="active";
                            }else{
                                $attr="";
                            }
            
                            if($image != ""){
                                if(is_dir("../../uploaded_products")){
                                    if(file_exists("../../uploaded_products/".$image)){
                                        $format.= '
                                                    <div class="carousel-item '.$attr.'">
                                                <img src="../uploaded_products/'.$image.'" class="d-block w-100" alt="Card image product" id="card-view-image-product">
                                            </div>
                                        ';
                                    }else{
                                        $format.= '
                                                    <div class="carousel-item '.$attr.'">
                                                <img src="../../images/no_image.png" class="d-block w-100" alt="Card image product" id="card-view-image-product">
                                            </div>
                                        ';
                                    }
                                }else{
                                    $format.= '
                                                    <div class="carousel-item '.$attr.'">
                                                <img src="../../images/no_image.png" class="d-block w-100" alt="Card image product" id="card-view-image-product">
                                            </div>
                                        ';
                                }
                            }else{
                                $format.= '
                                                <div class="carousel-item '.$attr.'">
                                            <img src="../../images/no_image.png" class="d-block w-100" alt="Card image product" id="card-view-image-product">
                                        </div>
                                    ';
                            }
                        
                            $count=$count+1;
                        }

                    }else{
                        $format.= '
                                        <div class="carousel-item active">
                                    <img src="../../images/no_image.png" class="d-block w-100" alt="Card image product" id="card-view-image-product">
                                </div>
                            ';
                    }

                    $format.= ' 
                                    </div>';
                                    if($nbr_image > 1){
                                        $format.='
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                        ';
                                    }
                                    
                                $format.= ' 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-7 pl-4">
                            <h2 class="name">
                                '.$title.'
                            </h2>
                            <hr/>
                            <h3 class="price-container">
                                '.$displayed_price.'
                            </h3>
                            <div class="certified mt-0 ml-1 d-none">
                                <ul>
                                    <li>
                                        <a href="javascript:void(0);">Delivery time<span>7 Working Days</span></a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);">Certified<span>Quality Assured</span></a>
                                    </li>
                                </ul>
                            </div>
                            <hr/>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0 h6 font-weight-bold">'.$dictionary->get_lang($lang,$KEY_PRODUCT_DESCRIPTION).'</label>
                                        <p>'.$description.'</p>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group m-0 p-0 mb-2">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="mb-0 h6 font-weight-bold">'.$dictionary->get_lang($lang,$KEY_AVAILABLE_COLORS).'</label>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-start">';
                                            if(count($array_color) > 0){
                                                foreach($array_color as $key_color){
                                                    $format.='
                                                        <div class="col-md-2 p-0 m-0"><dd class="circle" title="'.$key_color.'" style="background-color:'.$key_color.'"></dd></div>     
                                                    ';
                                                }
                                            }else{
                                                $format.='
                                                    <div class="col-md-12 p-0 m-0" style="opacity:0.7"><i>'.$dictionary->get_lang($lang,$KEY_NO_COLOR_AVAILABLE).'</i></div>
                                                ';
                                            }
                                        $format.='
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="mb-0 h6 font-weight-bold">'.$dictionary->get_lang($lang,$KEY_AVAILABLE_SIZES).'</label>
                                            </div>
                                            <div class="col-md-12 d-flex justify-content-start">';
                                            if(count($array_size) > 0){
                                                foreach($array_size as $key_size){
                                                    $format.='
                                                        <div class="col-md-2 p-0 m-0">'.ucfirst($key_size['size_name']).'</div>
                                                    ';
                                                }
                                            }else{
                                                $format.='
                                                    <div class="col-md-12 p-0 m-0" style="opacity:0.7"><i>'.$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE).'</i></div>
                                                ';
                                            }
                                        
                                            $format.='
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="mb-0 h6 font-weight-bold">'.$dictionary->get_lang($lang,$KEY_STOCK).'</label>
                                        <p>'.$stock.'</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row '.$is_disabled.'">
                                <div class="col p-0 text-left" '.$dir_button.'>
                                    <div class="form-group p-1" id="container-button-view-'.$product_id.'">
                                        '.$button.'
                                    </div>
                                </div>
                                <div class="col-2 p-0 m-0 '.$dir_heart.' pt-2">
                                    <i class="'.$heart.'" id="i-favourite-view-'.$product_id.'" onclick="'.$heart_click.'"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                    ';

                }

                /* Toast not logged in */
                $format.='
                    <div aria-live="polite" aria-atomic="true" class="container-toast mt-4" id="toast-logged-in">
                        <div class="toast toast-logged-in">
                            <div class="toast-header">
                                <strong class="mr-auto">Note</strong>
                            </div>
                            <div class="toast-body">
                                You must be logged in.
                            </div>
                        </div>
                    </div>
                ';

            }catch(PDOException $ex){
        
                $format = "";
                $res = 0;
            
            }

        }else{
            $format = "";
            $res = 0;
        }

    }else{
        $format = "";
        $res = 0;
    }

    $obj->format=$format;
    $obj->res=$res;
    echo json_encode($obj);

?>