<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../../../config/conn.php";
    require_once "../../../config/helper.php";
    require_once "../classes/users.php";

    $error=0;

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['id']) && isset($_POST['login_limit']) && isset($_POST['token'])){

            try{

                $user_id=$_POST['id'];
                $user_id=Helper::decrypt($user_id);
                $token=$_POST['token'];
                $login_limit=$_POST['login_limit'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        if($login_limit != "" && $login_limit > 0){
                            $class_users=new Users($db_conn->get_link());
                            if($class_users->update_user($user_id,$login_limit)){
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
        "error"=>$error
    ])
?>