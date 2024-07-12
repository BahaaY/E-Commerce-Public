<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once '../../../config/helper.php';

    require_once '../classes/forgot_password.php';
    require_once '../classes/users.php';

    require_once '../../../mail/mail.php';
    require_once "../../../email_form/index.php";

    $res=0;
    $user_id_hashed="";
    $type_hashed="";

    $obj = new stdClass();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['email'])) {

            try{

                $email = $_POST['email'];

                if(is_valid_email($email) == 1){
                    
                    $class_users = new Users($db_conn->get_link());
                    $class_forgot_password = new ForgotPassword($db_conn->get_link());

                    $user_id=$class_users->get_user_id($email);

                    if ($user_id) {

                        if($class_users->is_verified($email)){

                            if($class_users->check_login_limit($email)){

                                $username = explode('@', $email)[0];
                                $user_id = $user_id;

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

                                $last_email_verification_date=$class_forgot_password->get_date_last_email_verification($user_id);
                                if($last_email_verification_date != 0){

                                    $last_email_verification_date = new DateTime($last_email_verification_date);
                                    $dateTimeLastEmailVerification = new DateTime();
                                    $current_datetime_last_email_verification = $dateTimeLastEmailVerification->format('Y-m-d H:i:s');
                                    $current_datetime_last_email_verification=new DateTime($current_datetime_last_email_verification);
                                    
                                    $diff = $last_email_verification_date->diff($current_datetime_last_email_verification);
                                    $diff_in_minutes=$diff->i;
        
                                }else{
                                    $diff_in_minutes = 3;
                                }

                                if($diff_in_minutes >= 2){
                                    
                                    $dateTime = new DateTime();
                                    $current_datetime = $dateTime->format('Y-m-d H:i:s');
                                    $created_at=$current_datetime;

                                    $email_verification_code=Helper::get_random_number();

                                    if($class_forgot_password->insert_forgot_password($user_id,$email_verification_code,$created_at)){
                                        
                                        $user_id_hashed=Helper::string_hash($user_id);
                                        $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_RESET_PASSWORD_TYPE);

                                        $email_status=2;
                                        $mail->addAddress($email);
                                        $mail->Subject = "Reset password code: ".$email_verification_code."";
                                        $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                        if($mail->send()){

                                            $res=1;

                                        }else{
                                            $res = 0;
                                        }

                                    }else{
                                        $res = 0;
                                    }
                                    
                                }else{
                                    $res=6;
                                }

                            }else {
                                $res = 5;
                            }

                        }else {
                            $res = 4;
                        }

                    } else {
                        $res = 2;
                    }

                }else{
                    $res=3;
                }

            }catch(PDOException $ex){
        
                $res=0;
            
            }

        }else{
            $res = 0;
        }

    }else{
        $res = 0;
    }
    
    $obj->res = $res;
    $obj->id=$user_id_hashed;
    $obj->type=$type_hashed;

    function is_valid_email($email){

        if(empty($email)){
            return 0;
        }
    
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return 2;
        }
    
        return 1;
            
    }

    echo json_encode($obj);

?>
