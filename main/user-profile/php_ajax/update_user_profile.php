<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/index/users.php';
    require_once '../classes/index/validation.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once "../../../config/helper.php";
    require_once "../../../lang/key.php";
    require_once "../../resources/classes/dictionary.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $dictionary = new Dictionary($db_conn->get_link());

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }

    $obj = new stdClass();

    $result = 0;
    $error_username="";
    $error_phone_number="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['username']) && isset($_POST['country']) && isset($_POST['region']) && isset($_POST['address']) && isset($_POST['phone_number']) && isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $required_message=$dictionary->get_lang($lang,$KEY_REQUIRED);
        
                        $user_id = $_SESSION[Session::$KEY_EC_USERID];
                        $user_id=Helper::decrypt($user_id);
                
                        $username = $_POST['username'];
                        $country = $_POST['country'];
                        $region = $_POST['region'];
                        $address = $_POST['address'];
                        $phone_number = $_POST['phone_number'];
                
                        $class_users= new Users($db_conn->get_link());
                        $class_validation = new validation($db_conn->get_link());
                
                        if(is_valid_username($user_id,$username,$class_validation) == 2){
                            $error_username=$dictionary->get_lang($lang,$KEY_USERNAME_USED);
                        }else if(is_valid_username($user_id,$username,$class_validation) == 0){
                            $error_username=$required_message;
                        }else{
                            $error_username="";
                        }
                
                        if(is_valid_phone_number($user_id,$phone_number,$class_validation) == 0){
                            $error_phone_number=$dictionary->get_lang($lang,$KEY_PHONE_NUMBER_USED);
                        }else{
                            $error_phone_number="";
                        }
                
                        if (is_valid_username($user_id,$username,$class_validation) == 1 && is_valid_phone_number($user_id,$phone_number,$class_validation) == 1) {
                
                            if ($class_users->update_user_profile($user_id, $username, $country, $region, $address, $phone_number)) {
                                $result = 1;
                            } else {
                                $result = 0;
                            }
                
                        }
                    }else {
                        $result = 0;
                    }
                }else {
                    $result = 0;
                }

            }catch(PDOException $ex){

                $result=0;
                
            }

        }else {
            $result = 0;
        }

    }else {
        $result = 0;
    }

    $obj->result = $result;
    $obj->error_username =$error_username;
    $obj->error_phone_number =$error_phone_number;

    echo json_encode($obj);

    function is_valid_username($user_id,$username,$class_validation){

        if(empty($username)){
            return 0;
        }

        if($class_validation->check_username($user_id, $username) == 0){
            return 2;
        }
    
        return 1;
            
    }

    function is_valid_phone_number($user_id,$phone_number,$class_validation){

        if($class_validation->check_phone_number($user_id, $phone_number) == 0){
            return 0;
        }
    
        return 1;
            
    }

?>
