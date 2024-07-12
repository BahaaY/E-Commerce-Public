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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['id']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $id=$_POST['id'];

                        if($id !=""){
                
                            $class_product_type=new ProductType($db_conn->get_link());
                
                            if($class_product_type->delete_product_type($id)){
                                $res=1;
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
        "res"=>$res
    ]);

?>