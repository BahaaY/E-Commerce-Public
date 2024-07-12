<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../classes/orders.php";
    require_once "../classes/sales.php";
    require_once "../../../config/conn.php";
    require_once "../../../config/variables.php";
    require_once "../../../config/helper.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $obj=new stdClass();
    $res=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST["order_id"]) && isset($_POST["is_checked"]) && isset($_POST["token"])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $is_checked=$_POST['is_checked'];
                        $order_id=$_POST['order_id'];
                        $orders=new Orders($db_conn->get_link());
                        $class_sales=new Sales($db_conn->get_link());
                
                        if($is_checked == 1){
                            if($orders->get_details_order($order_id)){
                
                                if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
                                    $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
                                    date_default_timezone_set($time_zone);
                                }else{
                                    date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
                                }
                                $dateTime = new DateTime();
                                $date = $dateTime->format('Y-m-d');
                                $time = $dateTime->format('H:i:s');
                    
                                foreach($orders->get_details_order($order_id) as $order_info){
                                    $product_id=$order_info['product_id_FK'];
                                    $quantity=$order_info['quantity'];
                                    $size=$order_info['product_size_id_FK'];
                                    $color=$order_info['color'];
                                    $check_insert=true;
                                    if($class_sales->insert_sales($order_id,$product_id,$quantity,$size,$color,$date,$time)){
                                        $check_insert=true;
                                    }else{
                                        $check_insert=false;
                                    }
                                }
                    
                                if($check_insert){
                                    $res=1;
                                }else{
                                    $res=0;
                                }
                    
                            }else{
                                $res=0;
                            }
                        }else{
                            if($class_sales->delete_sales($order_id)){
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