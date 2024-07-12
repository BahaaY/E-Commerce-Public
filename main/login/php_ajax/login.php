<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../../../config/conn.php";
    require_once "../../../config/helper.php";
    require_once "../../../lang/key.php";
    require_once "../../resources/classes/dictionary.php";

    require_once "../classes/auth.php";
    require_once "../classes/users.php";
    require_once "../classes/email_verification.php";
    require_once "../classes/two_step_email_verification.php";
    
    require_once "../../../email_form/index.php";
    require_once "../../../mail/mail.php";
    
    $dictionary = new Dictionary($db_conn->get_link());

    if(isset($_SESSION[Session::$KEY_EC_LANG])) {
        $lang=$_SESSION[Session::$KEY_EC_LANG];
    }else {
        $lang="en";
    }
   
    $obj=new stdClass();

    $success=0;
    $error=1;
    $msg="";
    $error_email="";
    $error_password="";
    $user_id_hashed="";
    $type_hashed="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['email']) && isset($_POST['password']) && isset($_POST['remember'])){

            try{

                $required_message=$dictionary->get_lang($lang,$KEY_REQUIRED);

                $email=$_POST['email'];
                $password=$_POST['password'];
                $remember=$_POST['remember'];

                if(is_valid_email($email) == 2){
                    $error_email=$dictionary->get_lang($lang,$KEY_ENTER_A_VALID_EMAIL);
                }else if(is_valid_email($email) == 0){
                    $error_email=$required_message;
                }else{
                    $error_email="";
                }
                
                if(is_valid_password($password) == 0){
                    $is_valid_password=$required_message;
                }else{
                    $is_valid_password="";
                }

                if(is_valid_email($email) == 1 && is_valid_password($password) == 1){

                    $class_auth=new Auth($db_conn->get_link());
                    $class_users=new Users($db_conn->get_link());
                    $class_email_verification=new EmailVerification($db_conn->get_link());
                    $class_two_step_email_verification=new TwoStepEmailVerification($db_conn->get_link());

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
                    
                    $user_info=$class_users->get_user_info($email);
                    $user_id=$user_info['user_id'];
                    $username=$user_info['username'];
                    $permission=$user_info['permission'];
                    $two_step_verification=$user_info['two_step_verification'];
                    $login_block_count=$user_info['login_block_count'];
                    $login_available_at=$user_info['login_available_at'];

                    $login_available_at_dateTime=new DateTime($login_available_at);

                    $dateTimeLoginAvailableAt = new DateTime();
                    $current_datetime_login_available_at = $dateTimeLoginAvailableAt->format('Y-m-d H:i:s');
                    $current_datetime_login_available_at=new DateTime($current_datetime_login_available_at);
                    $interval_login_available_at = $login_available_at_dateTime->diff($current_datetime_login_available_at);
                    $totalDays = $interval_login_available_at->days;
                    $remainingHours = $interval_login_available_at->h;
                    if ($remainingHours > 24) {
                        $totalDays++;
                        $remainingHours -= 24;
                    }
                    $remainingMinutes = $interval_login_available_at->i;
                    $remainingSeconds = $interval_login_available_at->s;
                    if($current_datetime_login_available_at > $login_available_at){

                        if($totalDays == 1){
                            $days_text="day";
                        }else{
                            $days_text="days";
                        }
                        if($remainingHours == 1){
                            $hours_text="hour";
                        }else{
                            $hours_text="hours";
                        }
                        if($remainingHours == 1){
                            $minutes_text="minute";
                        }else{
                            $minutes_text="minutes";
                        }

                        if ($totalDays > 0) {
                            if($remainingHours > 0){
                                $formattedDate = $totalDays . " ".$days_text." and ". $remainingHours . " ".$hours_text."";
                            }else{
                                $formattedDate = $totalDays . " ".$days_text." and ". $remainingMinutes . " ".$minutes_text."";
                            }
                        }else{
                            if($remainingHours > 0){
                                if($remainingMinutes > 0){
                                    $formattedDate = $remainingHours . " ".$hours_text." and ". $remainingMinutes . " ".$minutes_text."";
                                }else{
                                    $formattedDate = $remainingHours . " ".$hours_text."";
                                }
                            }else{
                                if($remainingMinutes > 0){
                                    $formattedDate = $remainingMinutes . " ".$minutes_text."";
                                }else{
                                    $formattedDate = $remainingSeconds . " seconds";
                                }
                                
                            }
                        }

                    }else{
                        $formattedDate="0 second";
                    }
                    
                    $check_email_login=$class_auth->email_login($email,$password,$time_zone);
                    if($check_email_login == 1){ //Success

                        if($two_step_verification == 1){ //Send two step email verification

                            if (isset($_SESSION[Session::$KEY_EC_USERID])) {
                                unset($_SESSION[Session::$KEY_EC_USERID]);
                            }
                            if (isset($_SESSION[Session::$KEY_EC_TOKEN])) {
                                unset($_SESSION[Session::$KEY_EC_TOKEN]);
                            }
                            if (isset($_SESSION[Session::$KEY_EC_TIME_ZONE])) {
                                unset($_SESSION[Session::$KEY_EC_TIME_ZONE]);
                            }

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

                                $dateTime = new DateTime();
                                $current_datetime = $dateTime->format('Y-m-d H:i:s');
                                $created_at=$current_datetime;

                                $email_verification_code=Helper::get_random_number();
                                if($class_two_step_email_verification->insert_two_step_email_verification($user_id,$email_verification_code,$created_at)){
                                        
                                    $user_id_hashed=Helper::string_hash($user_id);
                                    $type_hashed=Helper::string_hash(Key::$KEY_TWO_STEP_EMAIL_VERIFICATION_TYPE);

                                    $email_status=3;
                                    $mail->addAddress($email);
                                    $mail->Subject = "Email verification code: ".$email_verification_code."";
                                    $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                    if($mail->send()){

                                        $success=2;
                                        $error = 0;

                                    }else{
                                        $error=1;
                                    }

                                }else{
                                    $error=1;
                                }

                            }else{
                                $error = 0;
                                $msg="Can not send email verification, please try again after 2 minutes";
                            }

                        }else{ // Login
                            $error = 0;
                            $success=1;
                        }

                        if($remember == 1){
                            $email=Helper::string_hash($email);
                            $password=Helper::string_hash($password);
                            setcookie(Key::$KEY_COOKIES_EMAIL, $email, time() + (86400 * 30), "/"); // 86400 = 1 day
                            setcookie(Key::$KEY_COOKIES_PASSWORD, $password, time() + (86400 * 30), "/"); // 86400 = 1 day
                        }else{
                            if (isset($_COOKIE[Key::$KEY_COOKIES_EMAIL])) {
                                unset($_COOKIE[Key::$KEY_COOKIES_EMAIL]); 
                                setcookie(Key::$KEY_COOKIES_EMAIL, '', -1, '/'); 
                            }
                            if (isset($_COOKIE[Key::$KEY_COOKIES_PASSWORD])) {
                                unset($_COOKIE[Key::$KEY_COOKIES_PASSWORD]); 
                                setcookie(Key::$KEY_COOKIES_PASSWORD, '', -1, '/'); 
                            }
                        }
                        
                    }else if($check_email_login == 2){ //Email or password incorrect

                        if($permission == 0){

                            if($class_auth->check_login_limit($email) == 1){ // login_limit > 0

                                $dateTime = new DateTime();
                                $current_datetime = $dateTime->format('Y-m-d H:i:s');
                                if($login_available_at == NULL || $login_available_at == ""){
                                    if($class_auth->decrement_login_limit($email)){
            
                                        $error = 0;
                                        $msg=$dictionary->get_lang($lang,$KEY_EMAIL_OR_PASSWORD_INCORRECT);
            
                                    }else{
                                        $error = 1;
                                    }
                                }else{
                                    if(strtotime($login_available_at) > strtotime($current_datetime)){
                                        $error = 0;
                                        $msg=$dictionary->get_lang($lang,$KEY_ERROR_LOGIN_LIMIT). " ".$formattedDate;
                                    }else{
                                        $class_auth->decrement_login_limit($email);
                                        $error = 0;
                                        $msg=$dictionary->get_lang($lang,$KEY_EMAIL_OR_PASSWORD_INCORRECT);
                                    }
                                }

                            }else{  // login_limit == 0

                                $class_auth->reset_login_limit($email);
                                $dateTime = new DateTime();
                                $current_datetime = $dateTime->format('Y-m-d H:i:s');
                                if($login_available_at == NULL || $login_available_at == ""){
                                    if($class_auth->increment_login_block($email)){
                                        $error = 0;
                                        $msg=$dictionary->get_lang($lang,$KEY_ERROR_LOGIN_LIMIT). " ".$formattedDate;
                                    }else{
                                        $error = 1;
                                    }
                                }else{ // login_available_at not null
                                    if(strtotime($login_available_at) > strtotime($current_datetime)){
                                        $error = 0;
                                        $msg=$dictionary->get_lang($lang,$KEY_ERROR_LOGIN_LIMIT). " ".$formattedDate;
                                    }else{
                                        if($login_block_count == 0){
                                            if($class_auth->reset_login_limit($email)){
                                                $error = 0;
                                                $msg=$dictionary->get_lang($lang,$KEY_EMAIL_OR_PASSWORD_INCORRECT);
                                            }
                                        }else{
                                            if($class_auth->increment_login_block($email)){
                                                $error = 0;
                                                $msg=$dictionary->get_lang($lang,$KEY_ERROR_LOGIN_LIMIT). " ".$formattedDate;
                                            }else{
                                                $error = 1;
                                            }
                                        }
                                    }
                                }
                                
                            }

                        }else{
                            $error = 0;
                            $msg=$dictionary->get_lang($lang,$KEY_EMAIL_OR_PASSWORD_INCORRECT);
                        }

                    }else if($check_email_login == 3){ //Not verified

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

                            $dateTime = new DateTime();
                            $current_datetime = $dateTime->format('Y-m-d H:i:s');
                            $created_at=$current_datetime;

                            if($permission == 0){

                                if(strtotime($login_available_at) > strtotime($current_datetime) || $class_auth->check_login_limit($email) == 0){
    
                                    $error = 0;
                                    $msg=$dictionary->get_lang($lang,$KEY_ERROR_LOGIN_LIMIT). " ".$formattedDate;
    
                                }else{

                                    $email_verification_code=Helper::get_random_number();
                                    if($class_email_verification->insert_email_verification($user_id,$email_verification_code,$created_at)){
                                            
                                        $user_id_hashed=Helper::string_hash($user_id);
                                        $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_VERIFICATION_TYPE);
    
                                        $email_status=0;
                                        $mail->addAddress($email);
                                        $mail->Subject = "Email verification code: ".$email_verification_code."";
                                        $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                        if($mail->send()){
    
                                            $success=2;
                                            $error = 0;
    
                                        }else{
                                            $error=1;
                                        }
    
                                    }else{
                                        $error=1;
                                    }
    
                                }
    
                            }else{
    
                                $email_verification_code=Helper::get_random_number();
                
                                if($class_email_verification->insert_email_verification($user_id,$email_verification_code,$created_at)){
                                    
                                    $user_id_hashed=Helper::string_hash($user_id);
                                    $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_VERIFICATION_TYPE);
    
                                    $email_status=0;
                                    $mail->addAddress($email);
                                    $mail->Subject = "Email verification code: ".$email_verification_code."";
                                    $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                    if($mail->send()){
    
                                        $success=2;
                                        $error = 0;
    
                                    }else{
                                        $error=1;
                                    }
    
                                }else{
                                    $error=1;
                                }
    
                            }

                        }else{
                            $error = 0;
                            $msg="Can not send email verification, please try again after 2 minutes";
                        }
                        
                    }else if($check_email_login == 4){ //Blocked
                        $error = 0;
                        $msg=$dictionary->get_lang($lang,$KEY_YOU_ARE_BLOCKED);
                    }else if($check_email_login == 5){ //Reached login limit
                        $error = 0;
                        $msg=$dictionary->get_lang($lang,$KEY_ERROR_LOGIN_LIMIT). " ".$formattedDate;
                    }else{
                        $error = 0;
                        $msg=$dictionary->get_lang($lang,$KEY_ERROR_OCCURRED);
                    }

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

    $obj->success=$success;
    $obj->error=$error;
    $obj->msg=$msg;
    $obj->error_email=$error_email;
    $obj->error_password=$error_password;
    $obj->id=$user_id_hashed;
    $obj->type=$type_hashed;
    echo json_encode($obj);

    function is_valid_email($email){

        if(empty($email)){
            return 0;
        }
    
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return 2;
        }
    
        return 1;
            
    }

    function is_valid_password($password){
    
        if(strlen(trim($password)) == 0 ){
            return 0;
        }
    
        return 1;
    
    }

?>