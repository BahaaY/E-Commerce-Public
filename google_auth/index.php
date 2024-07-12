<?php
    require_once 'auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.1.min.js"
        integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous">
    </script>

</head>

<body>

    <?php

        require_once '../config/variables.php';
        require_once '../config/conn.php';
        require_once '../config/helper.php';

        require_once 'classes/auth.php';
        
        $class_auth = new Auth($db_conn->get_link());
        
        if (isset($_SESSION['login_email']) && isset($_SESSION['access_token'])) {

            try{

                $email = $_SESSION['login_email'];

                $ip_address = $_SERVER['REMOTE_ADDR'];

                $url="https://freegeoip.app/json/{$ip_address}";

                $data = @file_get_contents($url);

                if ($data === true) {
                    // Process the data
                    $jsonData = json_decode($data, true);
                    if ($jsonData) {
                        $time_zone = $jsonData['time_zone'];
                        if($time_zone != "" || $time_zone != NULL){
                            $time_zone=$time_zone;
                        }else{
                            $time_zone = WebsiteInfo::$KEY_DEFAULT_TIME_ZONE;
                        }
                    }else{
                        $time_zone = WebsiteInfo::$KEY_DEFAULT_TIME_ZONE;
                    }
                }else{
                    $time_zone = WebsiteInfo::$KEY_DEFAULT_TIME_ZONE;
                }

                date_default_timezone_set($time_zone);
                $dateTimeLoggedInAt = new DateTime();
                $logged_in_at = $dateTimeLoggedInAt->format('Y-m-d H:i:s');

                if ($class_auth->check_email($_SESSION['login_email']) == 1) {
                    
                    $username = explode('@', $email)[0]; //split email to get username
            
                    $code_random = rand(1000000, 9999999);
                    $password = 'google_' . $code_random;
    
                    $password_hashed=Helper::string_hash($password);
            
                    $registration_type=2;
                    $is_verified=1;
    
                    $ch=curl_init();
                    curl_setopt($ch,CURLOPT_URL,"http://ip-api.com/json");
                    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                    $result=curl_exec($ch);
                    $result=json_decode($result);
                
                    if($result->status=='success'){
                        $country=$result->country;
                    }else{
                        $country="";
                    }
    
                    if ($class_auth->google_register($username, $email, $password_hashed, $registration_type,$is_verified,$country,$logged_in_at)) {
                        if($class_auth->get_user_id($email)){
                            $user_id=$class_auth->get_user_id($email);
                            $user_id=Helper::string_hash($user_id);
                            session_destroy();
                            if(session_status() !== PHP_SESSION_ACTIVE){
                                session_start();
                            }
                            $time_zone_name_hashed=Helper::string_hash($time_zone);
                            $_SESSION[Session::$KEY_EC_USERID]=$user_id;
                            $_SESSION[Session::$KEY_EC_TOKEN] = Helper::generate_random_string(80,80);
                            $_SESSION[Session::$KEY_EC_TIME_ZONE] = $time_zone_name_hashed;
                            header('location:../main');
                        }else{
                            header('location:../main');
                        }
                    } else {
                        header('location:../main');
                    }
                } else {
                    if($class_auth->is_verified($email)){
                        if($class_auth->is_active($email)){
                            $login_available_at=$class_auth->get_login_available_at($email);
                            if($login_available_at == NULL || $login_available_at == ""){
                                if($class_auth->get_user_id($email)){
                                    $user_id=$class_auth->get_user_id($email);
                                    $user_id=Helper::string_hash($user_id);
                                    if($class_auth->reset_login_data($email,$logged_in_at)){
                                        session_destroy();
                                        if(session_status() !== PHP_SESSION_ACTIVE){
                                            session_start();
                                        }
                                        $time_zone_name_hashed=Helper::string_hash($time_zone);
                                        $_SESSION[Session::$KEY_EC_USERID]=$user_id;
                                        $_SESSION[Session::$KEY_EC_TOKEN] = Helper::generate_random_string(80,80);
                                        $_SESSION[Session::$KEY_EC_TIME_ZONE] = $time_zone_name_hashed;
                                        header('location:../main');
                                    }else{
                                        header('location:../main');
                                    }
                                    
                                }else{
                                    header('location:../main');
                                }
                            }else{
                                $dateTime = new DateTime();
                                $current_datetime = $dateTime->format('Y-m-d H:i:s');
                                if(strtotime($login_available_at) > strtotime($current_datetime)){
                                    header('location:../main');
                                }else{
                                    if($class_auth->get_user_id($email)){
                                        $user_id=$class_auth->get_user_id($email);
                                        $user_id=Helper::string_hash($user_id);
                                        if($class_auth->reset_login_data($email,$logged_in_at)){
                                            session_destroy();
                                            if(session_status() !== PHP_SESSION_ACTIVE){
                                                session_start();
                                            }
                                            $time_zone_name_hashed=Helper::string_hash($time_zone);
                                            $_SESSION[Session::$KEY_EC_USERID]=$user_id;
                                            $_SESSION[Session::$KEY_EC_TOKEN] = Helper::generate_random_string(80,80);
                                            $_SESSION[Session::$KEY_EC_TIME_ZONE] = $time_zone_name_hashed;
                                            header('location:../main');
                                        }else{
                                            header('location:../main');
                                        }
                                    }else{
                                        header('location:../main');
                                    }
                                }
                            }
                        }else{
                            header('location:../main');
                        }
                    }else{
                        header('location:../main');
                    }
                }

            }catch(PDOException $ex){
    
                header('location:../main');
        
            }

        }
    
    ?>
</body>
</html>
