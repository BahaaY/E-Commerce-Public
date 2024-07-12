<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    $res = 0;
    $obj = new stdClass();

    require_once '../classes/delete_account/users.php';
    require_once '../classes/delete_account/cart.php';
    require_once '../classes/delete_account/favourites.php';
    require_once '../classes/delete_account/order_details.php';
    require_once '../classes/delete_account/orders.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $class_users = new Users($db_conn->get_link());
    $class_orders = new Orders($db_conn->get_link());
    $class_order_details = new Order_details($db_conn->get_link());
    $class_cart = new Cart($db_conn->get_link());
    $class_favourites = new Favourites($db_conn->get_link());

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_SESSION[Session::$KEY_EC_USERID])){

            try{

                $user_id = $_SESSION[Session::$KEY_EC_USERID];
                $user_id=Helper::decrypt($user_id);

                if($class_cart->delete_cart($user_id)){
                    if($class_favourites->delete_favourites($user_id)){
                        $orders_id=$class_orders->get_orders_id($user_id);
                        $check_delete_orders=true;
                        $check_delete_order_details=true;
                        if($orders_id){
                            foreach($orders_id as $order_id){
                                if($class_order_details->delete_order_details($order_id)){
                                    $check_delete_order_details=true;
                                }else{
                                    $check_delete_order_details=false;
                                }
                            }
                            if($check_delete_order_details){
                                if($class_orders->delete_orders($user_id)){
                                    $check_delete_orders=true;
                                }else{
                                    $check_delete_orders=false;
                                }
                            }else{
                                $check_delete_orders=false;
                            }
                        }
                        if($check_delete_orders){
                            if($class_users->delete_user($user_id)){
                                unset($_SESSION[Session::$KEY_EC_USERID]);
                                session_destroy();
                                if (isset($_COOKIE[Key::$KEY_COOKIES_EMAIL])) {
                                    unset($_COOKIE[Key::$KEY_COOKIES_EMAIL]); 
                                    setcookie(Key::$KEY_COOKIES_EMAIL, '', -1, '/'); 
                                }
                                if (isset($_COOKIE[Key::$KEY_COOKIES_PASSWORD])) {
                                    unset($_COOKIE[Key::$KEY_COOKIES_PASSWORD]); 
                                    setcookie(Key::$KEY_COOKIES_PASSWORD, '', -1, '/'); 
                                }
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

                $res = 0;
                
            }

        }else{
            $res=0;
        }

    } else {
        $res = 0;
    }

    $obj->res = $res;
    echo json_encode($obj);

?>
