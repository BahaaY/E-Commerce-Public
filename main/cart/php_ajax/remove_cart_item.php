<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../../../config/conn.php";
    require_once "../classes/cart.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }
   
    $obj=new stdClass();

    $error=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['cart_id']) && isset($_POST['token'])){

            try{

                $cart_id=$_POST['cart_id'];
                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
        
                        if($cart_id != ""){
        
                            try{
                
                                $class_cart=new Cart($db_conn->get_link());
                
                                $remove_cart=$class_cart->remove_item_from_cart($cart_id);
                
                                if($remove_cart){
                                    $error=0;
                                }else{
                                    $error=1;
                                }
                
                            }catch(PDOException $ex){
                
                                $error=1;
                
                            }
                            
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

    $obj->error=$error;
    echo json_encode($obj);

?>