<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/product_images.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $error = 0;
    $obj = new stdClass();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['image_id']) && isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
            
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $image_id = $_POST['image_id'];

                        $class_product_images = new ProductImages($db_conn->get_link());
                
                        if($class_product_images->delete_product_image($image_id)){
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

    $obj->error = $error;
    echo json_encode($obj);

?>
