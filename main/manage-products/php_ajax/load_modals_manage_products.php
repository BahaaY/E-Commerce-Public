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
    $modals="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['token']) && isset($_POST['product_type']) && isset($_POST['stock'])){

            try{

                $product_type=$_POST['product_type'];
                $stock=$_POST['stock'];
                $token=$_POST['token'];
            
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $class_products = new Products($db_conn->get_link());
                        $dictionary = new Dictionary($db_conn->get_link());
                        $class_product_type = new ProductType($db_conn->get_link());
                        $class_product_size = new ProductSize($db_conn->get_link());
                    
                        $all_products = $class_products->get_all_products($product_type,$stock);
                    
                        $check_folder_path="../../uploaded_products/";
                        $folder_path="../uploaded_products/";
                    
                        if ($all_products) {
                    
                            foreach ($all_products as $product) {
                    
                                $product_id = $product[TableProducts::$COLUMN_PRODUCT_ID];
                    
                                $get_product_info=$class_products->get_product_info($product_id);
                    
                                if($get_product_info){
                    
                                    $title = $get_product_info[TableProducts::$COLUMN_TITLE];
                                    $description = $get_product_info[TableProducts::$COLUMN_DESCRIPTION];
                                    $price = $get_product_info[TableProducts::$COLUMN_PRICE];
                                    $discount_price = $get_product_info[TableProducts::$COLUMN_DISCOUNT_PRICE];
                                    $stock = $get_product_info[TableProducts::$COLUMN_STOCK];
                                    $size = $get_product_info[TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK];
                                    $colors = $get_product_info[TableProducts::$COLUMN_COLOR];
                                    $product_type_id_FK = $get_product_info[TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK];
                                    $availability = $get_product_info[TableProducts::$COLUMN_AVAILABILITY];
                                    $images_id = $get_product_info[TableProductImages::$COLUMN_PRODUCT_IMAGES_ID];
                                    $images = $get_product_info[TableProductImages::$COLUMN_IMAGE];
                    
                                    if(is_dir($check_folder_path)){
                                        $nb_image=0;
                                        foreach(explode(",",$images) as $image){
                                            $array_image_id=explode(",",$images_id);
                                            if(file_exists($check_folder_path.$image)){
                                                //Modal show image product
                                                $modals.='
                                                    <div class="modal fade modal-show-image" id="modal-show-image-'.$array_image_id[$nb_image].'" tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <img src="'.$folder_path.$image.'" class="col-md-12">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                ';
                                            }else{
                                                $modals.='
                                                    <div class="modal fade modal-show-image" id="modal-show-image-'.$array_image_id[$nb_image].'" tabindex="-1" role="dialog"
                                                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-md" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-12">
                                                                            <img src="https://www.pindula.co.zw/images/a/a7/No_Image.jpg" class="p-1" width="80px" height="80px">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            ';
                                            }
                                            $nb_image++;
                                        }
                                    }else{
                                        $modals.='
                                            <div class="modal fade modal-show-image" id="modal-show-image-'.$array_image_id[$nb_image].'" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <img src="https://www.pindula.co.zw/images/a/a7/No_Image.jpg" class="p-1" width="80px" height="80px">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                                    }
                    
                                    //Modal delete product
                                    $modals.='
                                        <div class="modal fade" id="modal-delete-product-'.$product_id.'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">'.$dictionary->get_lang($lang,$KEY_DELETE).' "'.$title.'"?</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    Are you sure you want delete this product?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close mr-2"></i>'.$dictionary->get_lang($lang,$KEY_CLOSE).'</button>
                                                    <button type="button" class="btn btn-danger" id="delete_product_'.$product_id.'" onclick="delete_product('.$product_id.');"><i class="bi bi-trash mr-2"></i>'.$dictionary->get_lang($lang,$KEY_DELETE).'</button>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    ';
                    
                                    //Modal delete image product
                                    $nb_image=0;
                                    foreach(explode(",",$images) as $image){
                                        $array_image_id=explode(",",$images_id);
                                        $modals.='
                                            <div class="modal fade modal-delete-image" id="modal-delete-image-'.$array_image_id[$nb_image].'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Delete image?</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want delete this product image?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close mr-2"></i>'.$dictionary->get_lang($lang,$KEY_CLOSE).'</button>
                                                        <button type="button" class="btn btn-danger" id="delete_product_image_'.$array_image_id[$nb_image].'" onclick="delete_product_image('.$array_image_id[$nb_image].');"><i class="bi bi-trash mr-2"></i>'.$dictionary->get_lang($lang,$KEY_DELETE).'</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        ';
                                        $nb_image++;
                                    }
                    
                                }
                                
                    
                            }
                    
                            //Modal alert delete image
                            $modals.='
                                <div class="modal fade" id="modal-alert-delete-image" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Note</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                Product must have at least one image! Please add another image to remove this one.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ';
                    
                        }else{
                            $modals="";
                        }
                    }else{
                        $modals="";
                    }
                }else{
                    $modals="";
                }

            }catch(PDOException $ex){
        
                $modals="";
            
            }
        
        }else{
            $modals="";
        }

    }else{
        $modals="";
    }

    $obj->modals=$modals;

    echo json_encode($obj);

?>
