<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../../../config/conn.php";
    require_once "../../../config/helper.php";
    require_once "../../currency.php";

    require_once "../classes/cart.php";
    require_once "../classes/orders.php";
    require_once "../classes/order_details.php";
    require_once "../classes/products.php";
    require_once "../classes/notification.php";
    require_once "../classes/received_notification.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if(isset($_SESSION[Session::$KEY_EC_CURRENCY])){
        $currency_abbreviation=$_SESSION[Session::$KEY_EC_CURRENCY];
    }else{
        $currency_abbreviation=Currency::$KEY_EC_DEFAULT_ACTIVE_CURRENCY;
    }

    $currency_info=get_currency_info($db_conn->get_link(),$currency_abbreviation);
    if($currency_info){
        $currency_id=$currency_info['currency_id'];
    }else{
        $currency_id=2;
    }
    
    $user_id=$_SESSION[Session::$KEY_EC_USERID];
    $user_id=Helper::decrypt($user_id);
   
    $obj=new stdClass();

    $error=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['fullname']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['address'])
            && isset($_POST['country']) && isset($_POST['region']) && isset($_POST['phone_number']) && isset($_POST['products_id'])
            && isset($_POST['carts_id']) && isset($_POST['quantity']) && isset($_POST['color']) && isset($_POST['size']) && isset($_POST['order_type']) && isset($_POST['token'])){

            try{

                $fullname=$_POST['fullname'];
                $username=$_POST['username'];
                $email=$_POST['email'];
                $address=$_POST['address'];
                $country=$_POST['country'];
                $region=$_POST['region'];
                $phone_number=$_POST['phone_number'];
                $products_id=$_POST['products_id'];
                $carts_id=$_POST['carts_id'];
                $quantity=$_POST['quantity'];
                $color=$_POST['color'];
                $size=$_POST['size'];
                $order_type=$_POST['order_type'];
                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){

                        if($fullname != "" && $username != "" && $email != "" && $address != "" && $country != "" && $region != "" 
                            && $phone_number != "" && $products_id != "" && $carts_id != "" && $quantity != "" && $order_type != ""){

                            $class_cart=new Cart($db_conn->get_link());
                            $class_orders=new Orders($db_conn->get_link());
                            $class_order_details=new OrderDetails($db_conn->get_link());
                            $class_products=new Products($db_conn->get_link());
                            $class_notification=new Notification($db_conn->get_link());
                            $class_received_notification=new ReceivedNotification($db_conn->get_link());

                            if($order_type == 1){
                                $tracking_number="";
                            }else{
                                $tracking_number=chr(rand(65, 90)).chr(rand(65, 90))."-".rand(1000000000,9999999999);
                            }
                                
                            $date=date("Y-m-d");
                                
                            $check_stock=$class_products->check_stock($products_id,$quantity);
                            if($check_stock){
                                if($class_products->update_stock($products_id,$quantity)){
                                    $reference_number=rand(10000000,99999999);
                                    $order_tracking_id_FK = 1;
                                    $check_insert_order=$class_orders->insert_order($reference_number,$user_id,$fullname,$username,$email,$address,$country,$region,$phone_number,$tracking_number,$date,$order_type,$order_tracking_id_FK,$currency_id);
                                
                                    if($check_insert_order > 0){
                                        $order_id=$check_insert_order;
                                        if($class_order_details->insert_order_details($order_id,$products_id,$quantity,$size,$color)){
                                            if($class_cart->remove_carts_after_request_order($carts_id,$user_id)){
                                                $notification_id=1;
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
                                                        $error = 0;
                                                    }else{
                                                        $error = 1;
                                                    }
                                                }else{
                                                    $error = 0;
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
                                }else{
                                    $error = 1;
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
                }else{
                    $error = 1;
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

    $obj->error=$error;
    echo json_encode($obj);

?>