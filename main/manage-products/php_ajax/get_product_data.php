<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/products.php';
    require_once '../classes/product_type.php';
    require_once '../classes/product_size.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once '../../resources/classes/dictionary.php';
    require_once '../../../lang/key.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }

    $obj=new stdClass();
    $array_data= array();
    $error=0;

    $class_products = new Products($db_conn->get_link());
    $class_product_type = new ProductType($db_conn->get_link());
    $class_product_size = new ProductSize($db_conn->get_link());
    $dictionary = new Dictionary($db_conn->get_link());

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['product_id']) && isset($_POST['token'])){

            try{
                
                $token=$_POST['token'];
            
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $product_id=$_POST['product_id'];

                        $error=0;

                        $get_product_info=$class_products->get_product_info($product_id);

                        if($get_product_info){

                            $title = $get_product_info[TableProducts::$COLUMN_TITLE];
                            $description = $get_product_info[TableProducts::$COLUMN_DESCRIPTION];
                            $price = $get_product_info[TableProducts::$COLUMN_PRICE];
                            $discount_price = $get_product_info[TableProducts::$COLUMN_DISCOUNT_PRICE];
                            $stock = $get_product_info[TableProducts::$COLUMN_STOCK];
                            $size = $get_product_info[TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK];
                            $color = $get_product_info[TableProducts::$COLUMN_COLOR];
                            $product_type_id_FK = $get_product_info[TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK];
                            $availability = $get_product_info[TableProducts::$COLUMN_AVAILABILITY];
                            $images_id = $get_product_info[TableProductImages::$COLUMN_PRODUCT_IMAGES_ID];
                            $images = $get_product_info[TableProductImages::$COLUMN_IMAGE];

                            $displayed_availability="";
                            $displayed_color="";
                            $displayed_type="";
                            $displayed_size="";
                            $displayed_images="";

                            for($i=1; $i>=0; $i--){

                                if($i == 1){
                                    $availability_name="Show";
                                } else if($i == 0){
                                    $availability_name="Hide";
                                }
                            
                                if($i == $availability){
                                    $is_selected="selected";
                                } else{
                                    $is_selected="";
                                }

                                $displayed_availability.='<option value="'.$i.'" '.$is_selected.'>'.$availability_name.'</option>';

                            }

                            $all_product_type=$class_product_type->get_all_product_type();

                            $product_size_type_id_FK="";
                            if($all_product_type){
                                foreach($all_product_type as $product_type){
                                    $product_type_id=$product_type[TableProductType::$COLUMN_PRODUCT_TYPE_ID];
                                    $product_type_name=$product_type[TableProductType::$COLUMN_PRODUCT_TYPE_NAME];
                                    if($product_type_id_FK == $product_type_id){
                                        $is_selected="selected";
                                        $product_size_type_id_FK=$product_type[TableProductType::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK];
                                    }else{
                                        $is_selected="";
                                    }
                                    $displayed_type.='<option value="'.$product_type_id.'" '.$is_selected.'>'.$product_type_name.'</option>';
                                }
                            }

                            $array_color=explode(',',$color);
                            foreach($array_color as $key_color){
                                $displayed_color.='
                                    <label class="rounder-color" id="'.trim($key_color).'" title="'.trim($key_color).'" value="'.trim($key_color).'" style="background-color:'.$key_color.'"></label>     
                                ';
                            }

                            $all_product_size=$class_product_size->get_product_size($product_size_type_id_FK);

                            if($all_product_size){
                                foreach($all_product_size as $product_size){
                                    $product_size_id=$product_size[TableProductSize::$COLUMN_PRODUCT_SIZE_ID];
                                    $product_size_name=$product_size[TableProductSize::$COLUMN_PRODUCT_SIZE_NAME];

                                    $is_checked = "";
                                    foreach (explode(",", $size) as $sizee) {

                                    
                                        if ($sizee == $product_size_id) {
                                            $is_checked = "checked";
                                            break;
                                        }
                                    }
                                    
                                    $displayed_size.='
                                        <label class="col p-0 m-0 pt-2">
                                            <div class="form-check">
                                                <input class="form-check-input " '.$is_checked.' type="checkbox" value="'.$product_size_id.'" id="'.$product_size_name.'">
                                                <label class="form-check-label mr-4" for="'.$product_size_name.'">
                                                    '.$product_size_name.'
                                                </label>
                                            </div>
                                        </label>
                                    ';
                                
                                }
                            }else{
                                $displayed_size.='
                                    <span class="text-danger m-0 p-0 mt-1">
                                        Size not allowed
                                    </span>
                                ';
                            }

                            $nb_image=0;
                            if($images){

                                if(is_dir("../../uploaded_products")){
                                    foreach(explode(",",$images) as $image){
                                        $array_image_id=explode(",",$images_id);
                                        if(file_exists("../../uploaded_products/".$image)){
                                            $displayed_images.='
                                                <div class="col-md-1" id="col-'.$array_image_id[$nb_image].'" style="width: 80px; !important">
                                                    <div class="form-group row text-center">
                                                        <img src="../uploaded_products/'.$image.'" id="image_'.$array_image_id[$nb_image].'" class="img p-1" style="width:80px;height:80px;cursor:pointer;" data-toggle="modal" data-target="#modal-show-image-'.$array_image_id[$nb_image].'">';
                                                        if(count(explode(",",$images)) > 1){
                                                            $displayed_images.='
                                                                <i class="fa fa-close" id="icon-remove-image" data-toggle="modal" data-target="#modal-delete-image-'.$array_image_id[$nb_image].'"></i>
                                                            ';
                                                        }else{
                                                            $displayed_images.='    
                                                                <i class="fa fa-close" id="icon-remove-image" data-toggle="modal" data-target="#modal-alert-delete-image"></i>
                                                            ';
                                                        }
                                                    $displayed_images.=' 
                                                    </div>
                                                </div>
                                            ';
                                        }else{
                                            $displayed_images.='
                                                <div class="col-md-1" style="width: 80px; !important">
                                                    <div class="form-group row text-center">
                                                        <img src="../../images/no_image.png" class="p-1" style="width:80px;height:80px;">
                                                    </div>
                                                </div>
                                            ';
                                            
                                        }
                                        $nb_image++;
                                    }
                                    
                                }else{
                                    $displayed_images.='
                                        <div class="col-md-1" style="width: 80px; !important">
                                            <div class="form-group row text-center">
                                                <img src="../../images/no_image.png" class="p-1">
                                            </div>
                                        </div>
                                    ';
                                }

                            }

                            $displayed_button='
                                <button class="btn btn-primary" id="btn_update_product_'.$product_id.'" onclick="edit_product('.$product_id.');"><i class="bi bi-pen mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_SAVE_CHANGES).'</button>
                            ';

                            $array_data["title"]=$title;
                            $array_data["description"]=$description;
                            $array_data["availability"]=$displayed_availability;
                            $array_data["price"]=$price;
                            $array_data["discount_price"]=$discount_price;
                            $array_data["stock"]=$stock;
                            $array_data["color"]=$displayed_color;
                            $array_data["size"]=$displayed_size;
                            $array_data["type"]=$displayed_type;
                            $array_data["image"]=$displayed_images;
                            $array_data['product_size_type']=$product_size_type_id_FK;
                            $array_data['button']=$displayed_button;

                        }else{
                            $error=1;
                        }
                    }else{
                        $error=1;
                    }
                }else{
                    $error=1;
                }

            }catch(PDOException $ex){
        
                $error=1;
            
            }

        }else{
            $error=1;
        }

    }else{
        $error=1;
    }

    $obj->error=$error;
    $obj->array_data=$array_data;
    echo json_encode($obj);

?>
