<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/order_type.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;
    $check_order_type_name=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['name']) && isset($_POST['amount']) && isset($_POST['availability']) && isset($_POST['id']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $id=$_POST['id'];
                        $order_type_name=$_POST['name'];
                        $amount=$_POST['amount'];
                        $availability=$_POST['availability'];
                
                        if($order_type_name != "" && $amount !="" && $availability !="" && $id !=""){
                
                            $class_order_type=new OrderType($db_conn->get_link());
                
                            if($class_order_type->check_update_order_type_name($id,$order_type_name)){
                                $check_order_type_name=1;
                                if($class_order_type->update_order_type($id,$order_type_name,$amount,$availability)){
                                    $res=1;
                                }else{
                                    $res=0;
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
        "check_order_type_name"=>$check_order_type_name
    ]);

?>