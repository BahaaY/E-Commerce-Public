<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../classes/orders.php";
    require_once "../../../config/conn.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_SESSION[Session::$KEY_EC_USERID])){

            try{

                $obj=new stdClass();
                if(isset($_POST["order_id"]) && isset($_POST["token"])){
                    $token=$_POST['token'];
                    
                    if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                        $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                        if($token_session == $token){
                            $order_id=$_POST['order_id'];
                            $class_orders=new orders($db_conn->get_link());
                    
                            if($class_orders->delete_order($order_id)){
                                $res=1;
                            }else{
                                $res=0;
                            }
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

    $obj->res=$res;
    echo json_encode($obj);
?>