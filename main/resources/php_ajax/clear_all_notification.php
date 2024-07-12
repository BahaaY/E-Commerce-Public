<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    $error=0;
    require_once '../classes/received_notification.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['token'])){

            try{

                $token=$_POST['token'];
            
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $user_id=$_SESSION[Session::$KEY_EC_USERID];
                        $user_id=Helper::decrypt($user_id);
                        
                        $class_received_notification=new ReceivedNotification($db_conn->get_link());
                    
                        $permission=$class_received_notification->get_permission($user_id);
                        if($permission == 1){
                            $notification_id="1,2";
                            $user_id="";
                        }else{
                            $notification_id="3";
                            $user_id=$_SESSION[Session::$KEY_EC_USERID];
                            $user_id=Helper::decrypt($user_id);
                        }
                    
                        if($class_received_notification->clear_all_notification($notification_id,$user_id)){
                            $error=0;
                        }else{
                            $error=1;
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

    echo json_encode([
        "error" => $error
    ]);

?>