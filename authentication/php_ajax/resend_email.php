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

    require_once "../../email_form/index.php";
    require_once "../../mail/mail.php";
    
    $class_users = new Users($db_conn->get_link());
    $class_email_verification = new EmailVerification($db_conn->get_link());
    $class_two_step_email_verification = new TwoStepEmailVerification($db_conn->get_link());
    $class_pending_email = new PendingEmail($db_conn->get_link());
    $class_forgot_password = new ForgotPassword($db_conn->get_link());

    $error=0;
    $msg="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['id']) && isset($_POST['type'])){

            try{

                $encrypted_id = $_POST['id'];
                $encrypted_type = $_POST['type'];
                if($encrypted_id != "" && $encrypted_type != ""){

                    $user_id=Helper::decrypt($encrypted_id);
                    $decrypted_type=Helper::decrypt($encrypted_type);

                    $user_data=$class_users->get_user_data($user_id);
                    if($user_data){

                        $username=$user_data['username'];
                        $email=$user_data['email'];

                        $email_verification_code=Helper::get_random_number();

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
                        $current_datetime = $dateTime->format('Y-m-d H:i:s');
                        $created_at=$current_datetime;

                        if($decrypted_type == Key::$KEY_EMAIL_VERIFICATION_TYPE){

                            $last_email_verification_date=$class_email_verification->get_date_last_email_verification($user_id);
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

                                if($class_email_verification->insert_email_verification($user_id,$email_verification_code,$created_at)){
                            
                                    $user_id_hashed=Helper::string_hash($user_id);
                                    $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_VERIFICATION_TYPE);
                            
                                    $email_status=0;
                                    $mail->addAddress($email);
                                    $mail->Subject = "Email verification code: ".$email_verification_code."";
                                    $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                    if($mail->send()){
                            
                                        $error = 0; //success
                            
                                    }else{
                                        $error = 1;
                                    }
                            
                                }else{
                                    $error = 1;
                                }

                            }else{
                                $error=0;
                                $msg="Can not send email verification, please try again after 2 minutes";

                            }

                        }else if($decrypted_type == Key::$KEY_TWO_STEP_EMAIL_VERIFICATION_TYPE){

                            $last_email_verification_date=$class_two_step_email_verification->get_date_last_email_verification($user_id);
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

                                if($class_two_step_email_verification->insert_email_verification($user_id,$email_verification_code,$created_at)){
                            
                                    $user_id_hashed=Helper::string_hash($user_id);
                                    $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_VERIFICATION_TYPE);
                            
                                    $email_status=0;
                                    $mail->addAddress($email);
                                    $mail->Subject = "Email verification code: ".$email_verification_code."";
                                    $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                    if($mail->send()){
                            
                                        $error = 0; //success
                            
                                    }else{
                                        $error = 1;
                                    }
                            
                                }else{
                                    $error = 1;
                                }

                            }else{
                                $error=0;
                                $msg="Can not send email verification, please try again after 2 minutes";

                            }

                        }else if($decrypted_type == Key::$KEY_PENDING_EMAIL_VERIFICATION_TYPE){

                            $last_email_verification_date=$class_pending_email->get_date_last_email_verification($user_id);
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

                                $email=$class_pending_email->get_last_email($user_id);

                                if($email != ""){
                                    if($class_pending_email->insert_email_verification($user_id,$email,$email_verification_code,$created_at)){
                                
                                        $user_id_hashed=Helper::string_hash($user_id);
                                        $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_VERIFICATION_TYPE);
                                
                                        $email_status=1;
                                        $mail->addAddress($email);
                                        $mail->Subject = "Email verification code: ".$email_verification_code."";
                                        $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                        if($mail->send()){
                                
                                            $error = 0; //success
                                
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
                                $error=0;
                                $msg="Can not send email verification, please try again after 2 minutes";
                            }

                        }else if($decrypted_type == Key::$KEY_EMAIL_RESET_PASSWORD_TYPE){

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

                                if($class_forgot_password->insert_email_verification($user_id,$email_verification_code,$created_at)){
                            
                                    $user_id_hashed=Helper::string_hash($user_id);
                                    $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_VERIFICATION_TYPE);
                            
                                    $email_status=2;
                                    $mail->addAddress($email);
                                    $mail->Subject = "Email verification code: ".$email_verification_code."";
                                    $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                    if($mail->send()){
                            
                                        $error = 0; //success
                            
                                    }else{
                                        $error = 1;
                                    }
                            
                                }else{
                                    $error = 1;
                                }

                            }else{
                                $error=0;
                                $msg="Can not send email verification, please try again after 2 minutes";
                            }

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
            $error = 1;
        }
        
    }else{
        $error = 1;
    }

    echo json_encode([
        "error"=>$error,
        "msg"=>$msg
    ])
    
?>