<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";
    require_once '../classes/index/users.php';
    require_once '../classes/index/notification.php';

    $error= 0 ;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_SESSION[Session::$KEY_EC_USERID])){

            if(isset($_POST['two_step_verification']) && isset($_POST['states']) && isset($_POST['ids']) && isset($_POST['token'])){

                try{        
                    
                    $token=$_POST['token'];
                        
                    if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                        $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                        if($token_session == $token){
    
                            $user_id = $_SESSION[Session::$KEY_EC_USERID];
                            $user_id=Helper::decrypt($user_id);
    
                            $two_step_verification=$_POST['two_step_verification'];
                            $states=$_POST['states'];
                            $ids=$_POST['ids'];
                    
                            if(count(explode(",",$states)) != count(explode(",",$ids))){
                                $error=1;
                            }else{

                                $class_notification=new Notification($db_conn->get_link());
                                $class_users=new Users($db_conn->get_link());

                                if($states != "" && $ids != ""){
                                    if($class_notification->update_notification($ids,$states)){
                                        $error=0;
                                    }else{
                                        $error=1;
                                    }
                                }

                                if($error==0){
                                    if($two_step_verification != ""){
                                        if($class_users->update_two_step_verification($user_id,$two_step_verification)){
                                            $error=0;
                                        }else{
                                            $error=1;
                                        }
                                    }
                                }
                                
                                
                            }
                        }else{
                            $error = 1;
                        }
                    }else{
                        $error = 1;
                    }
    
                }catch(PDOException $ex){
    
                    $error=1;
                    
                }
    
            }else{
                $error = 1;
            }

        }else{
            $error = 1;
        }

    }else{
        $error = 1;
    }

    echo json_encode([
        "error"=>$error,
    ]);

?>