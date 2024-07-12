<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    $res = 0;
    $obj = new stdClass();

    require_once '../classes/change_password/users.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once '../../../config/helper.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $current_password = $_POST['current_password'];
                        $new_password = $_POST['new_password'];
                
                        if (is_valid_new_password($new_password) == 1) {
                
                            $class_users = new Users($db_conn->get_link());
                            $user_id = $_SESSION[Session::$KEY_EC_USERID];
                            $user_id=Helper::decrypt($user_id);
                
                            if($class_users->check_current_password($user_id,$current_password)){
                                $hash_password = Helper::string_hash($new_password);
                                if ($class_users->change_password($user_id, $hash_password)) {
                                    $res = 1;
                                    if (isset($_COOKIE[Key::$KEY_COOKIES_PASSWORD])) {
                                        unset($_COOKIE[Key::$KEY_COOKIES_PASSWORD]); 
                                        setcookie(Key::$KEY_COOKIES_PASSWORD, $new_password, time() + (86400 * 30), "/"); // 86400 = 1 day
                                    }
                                } else {
                                    $res = 0;
                                }
                            }else{
                                $res = 2;
                            }
                            
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

                $res = 0;
                
            }

        } else {
            $res = 0;
        }

    } else {
        $res = 0;
    }  

    $obj->res = $res;
    echo json_encode($obj);

    function is_valid_new_password($new_password)
    {
        $space = preg_match('/^[^ ].* .*[^ ]$/', $new_password);
        $uppercase = preg_match('@[A-Z]@', $new_password);
        $lowercase = preg_match('@[a-z]@', $new_password);
        $number = preg_match('@[0-9]@', $new_password);
        $special_char = preg_match('@[\W]@', $new_password);

        if (strlen(trim($new_password)) == 0) {
            return 0;
        }

        if (strlen(trim($new_password)) < 6) {
            return 3;
        }

        if ($space && !$uppercase && !$lowercase && !$number && !$special_char) {
            return 2;
        }

        return 1;
    }

?>
