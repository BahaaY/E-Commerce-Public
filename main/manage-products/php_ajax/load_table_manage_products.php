<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/products.php';
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

    $data1 = array();
    $data2 = array();
    $serial_number=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['token'])){

            try{

                $product_type=$_POST['product_type'];
                $stock=$_POST['stock'];
                $token=$_POST['token'];
            
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $class_products = new Products($db_conn->get_link());
                        $dictionary = new Dictionary($db_conn->get_link());
                    
                        $all_products = $class_products->get_all_products($product_type,$stock);
                    
                        if ($all_products) {
                    
                            foreach ($all_products as $product) {
                    
                                $serial_number++;
                    
                                $product_id=$product['product_id'];
                                $title = $product["title"];
                                $image = $product["image"];
                                $stock= $product['stock'];
                                $action="
                                    <button type='button' class='btn btn-success m-1' id='btn-edit-product' title='".$dictionary->get_lang($lang,$KEY_EDIT)."' onclick='view_section_update_product(".$product_id.");'><i class='fa fa-edit'></i></button>
                                    <button type='button' class='btn btn-danger m-1' id='btn-delete-product' title='".$dictionary->get_lang($lang,$KEY_DELETE)."' data-toggle='modal' data-target='#modal-delete-product-".$product_id."'><i class='fa fa-trash'></i></button>
                                ";
                    
                                $parent_image="../../images/no_image.png";
                    
                                if($image != NULL || $image != ""){
                                    if (is_dir("../../uploaded_products")) {
                                        if (file_exists("../../uploaded_products/".$image)) {
                                            $displayed_image="
                                                <img src='../uploaded_products/".$image."' class='image'>
                                            ";
                                        }else{
                                            $displayed_image="<img src='$parent_image' class='image'>";
                                        }
                                    }else{
                                        $displayed_image="<img src='$parent_image' class='image'>";
                                    }
                                    
                                }else{
                                    $displayed_image="<img src='$parent_image' class='image'>";
                                }
                    
                                if($stock > 0){
                                    $stock="<span class='text-success'>In stock</span>";
                                }else{
                                    $stock="<span class='text-danger'>Out of stock</span>";
                                }
                    
                                $data1['serial_number'] = "<b>".$serial_number."</b>";
                                $data1['title'] = $title;
                                $data1['image'] = $displayed_image;
                                $data1['action'] = $action;
                                $data1['stock'] = $stock;
                                $data2[] = $data1;
                    
                            }
                    
                        }else{
                            $data1 = array();
                            $data2 = array();
                        }
                    }else{
                        $data1 = array();
                        $data2 = array();
                    }
                }else{
                    $data1 = array();
                    $data2 = array();
                }

            }catch(PDOException $ex){
        
                $data1 = array();
                $data2 = array();
            
            }

        }else{
            $data1 = array();
            $data2 = array();
        }

    }else{
        $data1 = array();
        $data2 = array();
    }

    echo json_encode($data2);

?>
