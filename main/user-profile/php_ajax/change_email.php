<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';
    require_once '../../../config/helper.php';

    require_once '../classes/change_email/pending_email.php';
    require_once "../classes/change_email/users.php";

    require_once '../../../mail/mail.php';
    require_once "../../../email_form/index.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;
    $user_id_hashed="";
    $type_hashed="";

    $obj = new stdClass();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['email']) && isset($_POST['token'])) {

            try{

                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $email = $_POST['email'];

                        if(isset($_SESSION[Session::$KEY_EC_USERID])){
                            $user_id=$_SESSION[Session::$KEY_EC_USERID];
                            $user_id=Helper::decrypt($user_id);
                        }else{
                            $user_id=0;
                        }
                
                        if(is_valid_email($email) == 1){
                            
                            $class_pending_email = new PendingEmail($db_conn->get_link());
                            $class_users=new Users($db_conn->get_link());
                                
                            $username=$class_users->get_username($user_id);
                
                            if ($user_id != 0) {
                
                                if($class_users->is_email_exist($email)){
                
                                    $res=3;
                
                                }else{

                                    if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
                                        $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
                                        if($time_zone != ""){
                                            date_default_timezone_set($time_zone);
                                        }else{
                                            date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
                                        }
                                    }else{
                                        date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
                                    }

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

                                        $email_verification_code=Helper::get_random_number();
                                        $dateTime = new DateTime();
                                        $current_datetime = $dateTime->format('Y-m-d H:i:s');
                                        $created_at=$current_datetime;
                
                                        if($class_pending_email->insert_pending_email($user_id,$email,$email_verification_code,$created_at)){
                                            
                                            $user_id_hashed=Helper::string_hash($user_id);
                                            $type_hashed=Helper::string_hash(Key::$KEY_PENDING_EMAIL_VERIFICATION_TYPE);
                    
                                            $email_status=1;
                                            $mail->addAddress($email);
                                            $mail->Subject = "Email verification code: ".$email_verification_code."";
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
                                        $res=4;
                                    }
                
                                }
                
                            } else {
                                $res = 0;
                            }
                
                        }else{
                            $res=2;
                        }
                    }else{
                        $res = 0;
                    }
                }else{
                    $res = 0;
                }

            }catch(PDOException $ex){

                $res = 0;
                
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
