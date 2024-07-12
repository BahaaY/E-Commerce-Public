<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../../../config/conn.php";
    require_once "../../../lang/key.php";
    require_once "../../resources/classes/dictionary.php";
    require_once '../classes/product_size.php';
    require_once '../classes/product_type.php';

    $dictionary=new Dictionary($db_conn->get_link());

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }
   
    $obj=new stdClass();

    $error=0;
    $size_type=3;
    $res="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['type']) && isset($_POST['token'])){

            try{

                $type=$_POST['type'];
                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){

                        if($type != ""){

                            $class_product_type=new ProductType($db_conn->get_link());
                            $class_product_size=new ProductSize($db_conn->get_link());
                
                            $size_type=$class_product_type->get_product_size_type($type);
                
                            if($size_type == 1 || $size_type == 2){
                
                                $all_product_size=$class_product_size->get_all_product_size($size_type);
                
                                if($all_product_size){
                                    $res.='
                                        <div class="row ml-0 mr-0">
                                    ';
                                    foreach($all_product_size as $product_size){
                                        $product_size_id=$product_size[TableProductSize::$COLUMN_PRODUCT_SIZE_ID];
                                        $product_size_name=$product_size[TableProductSize::$COLUMN_PRODUCT_SIZE_NAME];
                                        
                                        $res.= '
                                            <label class="col p-0 m-0 pt-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="'.$product_size_id.'" id="'.$product_size_name.'">
                                                    <label class="form-check-label mr-4" for="'.$product_size_name.'">
                                                        '.$product_size_name.'
                                                    </label>
                                                </div>
                                            </label>
                                        ';
                                    
                                    }
                                    $res.='
                                        </div>
                                    ';
                                }else{
                                    $error=1;
                                }
                
                            }else if($size_type == 3){
                                $res.= '
                                    <div class="row ml-0 mr-0 mt-1 text-danger">
                                        Size not allowed
                                    </div>
                                ';
                            }else{
                                $res="";
                            }
                
                        }else{
                            $res.= '
                                <div class="row ml-0 mr-0 mt-1">
                                    Select product type
                                </div>
                            ';
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
    $obj->size_type=$size_type;
    $obj->res=$res;
    echo json_encode($obj);

?>