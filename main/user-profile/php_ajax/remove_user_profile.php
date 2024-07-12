<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    $error = 0;
    $obj = new stdClass();
    
    require_once '../classes/remove_profile_image/users.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $user_id = $_SESSION[Session::$KEY_EC_USERID];
    $user_id=Helper::decrypt($user_id);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $class_users = new Users($db_conn->get_link());
        
                        if($class_users->remove_profile_image($user_id)){
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

                $error = 1;
                
            }

        }else{
            $error=1;
        }

    }else{
        $error=1;
    }

    $obj->error = $error;
    echo json_encode($obj);

?>
