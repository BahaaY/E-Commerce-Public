<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    $res = 0;
    $obj = new stdClass();

    require_once '../classes/delete_account/users.php';
    require_once '../classes/delete_account/orders.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $email = $_POST['email'];
                        $password = $_POST['password'];
                
                        if(isset($_SESSION[Session::$KEY_EC_USERID])){
                            $user_id = $_SESSION[Session::$KEY_EC_USERID];
                            $user_id=Helper::decrypt($user_id);
                            $class_users = new Users($db_conn->get_link());
                            $class_orders = new Orders($db_conn->get_link());
                            if($class_users->check_email_password($email,$password,$user_id)){
                                $orders_id=$class_orders->get_orders_id($user_id);
                                if($orders_id){
                                    $res = 2;
                                }else{
                                    $res = 1;
                                }
                            }else{
                                $res = 3;
                            }
                        }else{
                            $res = 0;
                        }
                    }else{
                        $res = 0;
                    }
                }else{
                    $res = 0;
                }

            }catch(PDOException $ex){

                $res = 0;
                
            }

        }else{
            $res = 0;
        }

    } else {
        $res = 0;
    }

    $obj->res = $res;
    echo json_encode($obj);

?>
