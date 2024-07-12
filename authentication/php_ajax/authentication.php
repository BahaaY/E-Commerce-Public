<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../../config/conn.php';
    require_once '../../config/variables.php';
    require_once '../../config/helper.php';

    require_once '../classes/email_verification.php';
    require_once '../classes/two_step_email_verification.php';
    require_once '../classes/pending_email.php';
    require_once '../classes/forgot_password.php';
    require_once '../classes/users.php';

    $obj = new stdClass();

    $error = 1;
    $status=0;
    $msg_error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['verification_code']) && isset($_POST['id']) && isset($_POST['type'])) {

            try{

                $verification_code = $_POST['verification_code'];
                $encrypted_id = $_POST['id'];
                $encrypted_type = $_POST['type'];

                if($verification_code != "" && $encrypted_id != "" && $encrypted_type != ""){

                    $user_id=Helper::decrypt($encrypted_id);
                    $decrypted_type=Helper::decrypt($encrypted_type);

                    if (strlen($verification_code) == 6) {

                        $class_users = new Users($db_conn->get_link());

                        if($decrypted_type == Key::$KEY_EMAIL_VERIFICATION_TYPE){
                        
                            $class_email_verification = new EmailVerification($db_conn->get_link());
            
                            $check_verification_code = $class_email_verification->check_availability_verification_code($user_id, $verification_code);
            
                            if ($check_verification_code == 1) {
                                $email_verification_code = $class_email_verification->get_email_verification_code($user_id);
            
                                if ($verification_code == $email_verification_code) {
                                    if ($class_email_verification->update_availability_verification_code($user_id, $email_verification_code)) {
                                            
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

                                        $dateTime = new DateTime();
                                        $logged_in_date_time = $dateTime->format('Y-m-d H:i:s');

                                        $is_verified=1;

                                        if($class_users->update_user_data($user_id,$is_verified,$time_zone,$logged_in_date_time)){
                                                
                                            $user_id=Helper::string_hash($user_id);
                                            $time_zone_name_hashed=Helper::string_hash($time_zone);
                                            $_SESSION[Session::$KEY_EC_USERID] = $user_id;
                                            $_SESSION[Session::$KEY_EC_TIME_ZONE] = $time_zone_name_hashed;
                                            $_SESSION[Session::$KEY_EC_TOKEN] = Helper::generate_random_string(80,80);

                                            $error = 0;
                                            $status=0;

                                        }else{
                                            $error = 1;
                                        }
                                        
                                    } else {
                                        $error = 1;
                                    }
                                } else {
                                    $msg_error = 'Code used or expired';
                                }
                            } else {
                                $msg_error = 'Code used or expired';
                            }

                        }else if($decrypted_type == Key::$KEY_TWO_STEP_EMAIL_VERIFICATION_TYPE){
                        
                            $class_two_step_email_verification = new TwoStepEmailVerification($db_conn->get_link());
            
                            $check_verification_code = $class_two_step_email_verification->check_availability_verification_code($user_id, $verification_code);
            
                            if ($check_verification_code == 1) {
                                $email_verification_code = $class_two_step_email_verification->get_email_verification_code($user_id);
            
                                if ($verification_code == $email_verification_code) {
                                    if ($class_two_step_email_verification->update_availability_verification_code($user_id, $email_verification_code)) {
                                            
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

                                        $dateTime = new DateTime();
                                        $logged_in_date_time = $dateTime->format('Y-m-d H:i:s');

                                        $is_verified=1;

                                        if($class_users->update_user_data($user_id,$is_verified,$time_zone,$logged_in_date_time)){
                                                
                                            $user_id=Helper::string_hash($user_id);
                                            $time_zone_name_hashed=Helper::string_hash($time_zone);
                                            $_SESSION[Session::$KEY_EC_USERID] = $user_id;
                                            $_SESSION[Session::$KEY_EC_TIME_ZONE] = $time_zone_name_hashed;
                                            $_SESSION[Session::$KEY_EC_TOKEN] = Helper::generate_random_string(80,80);

                                            $error = 0;
                                            $status=0;

                                        }else{
                                            $error = 1;
                                        }
                                        
                                    } else {
                                        $error = 1;
                                    }
                                } else {
                                    $msg_error = 'Code used or expired';
                                }
                            } else {
                                $msg_error = 'Code used or expired';
                            }

                        }else if($decrypted_type == Key::$KEY_PENDING_EMAIL_VERIFICATION_TYPE){
                            
                            $class_pending_email = new PendingEmail($db_conn->get_link());
            
                            $check_pending_email_verification_code = $class_pending_email->check_availability_pending_email_verification_code($user_id, $verification_code);
            
                            if ($check_pending_email_verification_code == 1) {
                                $email_info=$class_pending_email->get_pending_email_verification_code($user_id);
                                $pending_email_verification_code = $email_info[TablePendingEmail::$COLUMN_EMAIL_VERIFICATION_CODE];
                                $pending_email_id = $email_info[TablePendingEmail::$COLUMN_PENDING_EMAIL_ID];
                                if ($verification_code == $pending_email_verification_code) {
                                    if ($class_pending_email->update_availability_pending_email_verification_code($user_id, $pending_email_verification_code)) {
                                        $new_email=$class_pending_email->get_pending_email($pending_email_id);
                                        if ($class_users->update_user_email($user_id,$new_email)) {
                                            if (isset($_COOKIE[Key::$KEY_COOKIES_EMAIL])) {
                                                unset($_COOKIE[Key::$KEY_COOKIES_EMAIL]);
                                                $new_email=Helper::string_hash($new_email);
                                                setcookie(Key::$KEY_COOKIES_EMAIL, $new_email, time() + (86400 * 30), "/"); // 86400 = 1 day
                                            }
                                            $error = 0;
                                            $status=1;
                                        } else {
                                            $error = 1;
                                        }
                                    } else {
                                        $error = 1;
                                    }
                                } else {
                                    $msg_error = 'Code used or expired';
                                }
                            } else {
                                $msg_error = 'Code used or expired';
                            }

                        }else if($decrypted_type == Key::$KEY_EMAIL_RESET_PASSWORD_TYPE){
                            
                            $class_forgot_password = new ForgotPassword($db_conn->get_link());

                            $check_verification_code = $class_forgot_password->check_availability_forgot_password_code($user_id, $verification_code);

                            if ($check_verification_code == 1) {

                                $email_forgot_password_code = $class_forgot_password->get_email_forgot_password_code($user_id);

                                if ($verification_code == $email_forgot_password_code) {
                                    if ($class_forgot_password->update_availability_forgot_password_code($user_id, $email_forgot_password_code)) {
                                        
                                        $obj->id = $encrypted_id;
                                        $error = 0;
                                        $status=2;

                                    } else {
                                        $error = 1;
                                    }
                                } else {
                                    $msg_error = 'Code used or expired';
                                }
                            } else {
                                $msg_error = 'Code used or expired';
                            }

                        }else{
                            $error = 1;
                        }

                    } else {
                        $msg_error = 'Enter a valid code';
                    }

                }else{
                    $error = 1;
                }

            }catch(PDOException $ex){
        
                $error=1;
        
            }

        }else{
            $error = 1;
        }
        
    }else{
        $error = 1;
    }

    $obj->status = $status;
    $obj->error = $error;
    $obj->msg_error = $msg_error;
    echo json_encode($obj);

?>
