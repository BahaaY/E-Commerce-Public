<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/favourites.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }
    
    $user_id = $_SESSION[Session::$KEY_EC_USERID];
    $user_id=Helper::decrypt($user_id);

    $obj = new stdClass();
    $res = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['product_id']) && isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $product_id = $_POST['product_id'];
        
                        $class_favourites = new Favourites($db_conn->get_link());
                
                        if ($class_favourites->add_to_favourites($user_id, $product_id)) {
                            $res = 1;
                        } else {
                            $res = 0;
                        }
                    } else {
                        $res = 0;
                    }
                } else {
                    $res = 0;
                }

            }catch(PDOException $ex){
        
                $res=0;
            
            }

        } else {
            $res = 0;
        }

    }else {
        $res = 0;
    }

    $obj->res = $res;

    echo json_encode($obj);

?>
