<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/product_size.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;
    $check_product_size_name=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['name']) && isset($_POST['availability']) && isset($_POST['product_size_type']) && isset($_POST['id']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $id=$_POST['id'];
                        $product_size_name=$_POST['name'];
                        $availability=$_POST['availability'];
                        $product_size_type=$_POST['product_size_type'];
                
                        if($product_size_name != "" && $availability !="" && $product_size_type !="" && $id !=""){
                
                            $class_product_size=new ProductSize($db_conn->get_link());
                
                            if($class_product_size->check_update_product_size_name($id,$product_size_name)){
                                $check_product_size_name=1;
                                if($class_product_size->update_product_size($id,$product_size_name,$availability,$product_size_type)){
                                    $res=1;
                                }else{
                                    $res=0;
                                }
                            }else{
                                $check_product_size_name=0;
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
        "check_product_size_name"=>$check_product_size_name
    ]);

?>