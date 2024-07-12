<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../classes/my_orders.php";
    require_once "../classes/notification.php";
    require_once "../classes/received_notification.php";
    require_once "../../../config/conn.php";
    require_once "../../../config/helper.php";
    
    $res=0;

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $user_id=$_SESSION[Session::$KEY_EC_USERID];
    $user_id=Helper::decrypt($user_id);

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
                            $orders=new My_orders($db_conn->get_link());
                            $class_notification=new Notification($db_conn->get_link());
                            $class_received_notification=new ReceivedNotification($db_conn->get_link());
                    
                            if($orders->cancel_order($order_id)){
                                $notification_id=2;
                                $notification_status=$class_notification->get_notification_status($notification_id);
                                if($notification_status == 1){
                                    if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
                                        $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
                                        date_default_timezone_set($time_zone);
                                    }else{
                                        date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
                                    }
                                    $dateTime = new DateTime();
                                    $created_at = $dateTime->format('Y-m-d H:i:s');
                                    $updated_at = $created_at;
                                    if($class_received_notification->insert_notification($user_id,$order_id,$notification_id,$created_at,$updated_at)){
                                        $res=1;
                                    }else{
                                        $res=0;
                                    }
                                }else{
                                    $res=1;
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

    $obj->res=$res;
    echo json_encode($obj);
?>