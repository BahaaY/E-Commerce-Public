<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/product_type.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;
    $check_product_type_name=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['name']) && isset($_POST['size_type']) && isset($_POST['availability']) && isset($_POST['product_availability']) && isset($_POST['id']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $id=$_POST['id'];
                        $product_type_name=$_POST['name'];
                        $product_size_type=$_POST['size_type'];
                        $availability=$_POST['availability'];
                        $product_availability=$_POST['product_availability'];
                
                        if($product_type_name != "" && $product_size_type !="" && $availability !="" && $product_availability !="" && $id !=""){
                
                            $class_product_type=new ProductType($db_conn->get_link());
                
                            if($class_product_type->check_update_product_type_name($id,$product_type_name)){
                                $check_product_type_name=1;
                                if($class_product_type->update_product_type($id,$product_type_name,$product_size_type,$availability,$product_availability)){
                                    $res=1;
                                }else{
                                    $res=0;
                                }
                            }else{
                                $check_product_type_name=0;
                            }
                            
                        }else{
                            $res=0;
                        }
                    }else{
                        $res=0;
                    }
                }else{
                    $res=0;
                }

            }catch(PDOException $ex){

                $res=0;
                
            }

        }else{
            $res=0;
        }

    }else{
        $res=0;
    }

    echo json_encode([
        "res"=>$res,
        "check_product_type_name"=>$check_product_type_name
    ]);

?>