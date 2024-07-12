<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../../../config/conn.php';
    require_once "../../../config/variables.php";
    require_once "../../../config/helper.php";

    require_once '../classes/auth.php';
    require_once '../classes/validation.php';
    require_once '../classes/email_verification.php';

    require_once "../../../email_form/index.php";
    require_once "../../../mail/mail.php";
    
    $res = 0;
    $user_id_hashed="";
    $type_hashed="";
    $obj = new stdClass();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['email']) && isset($_POST['password']) && isset($_POST['country'])) {

            try{

                $country = $_POST['country'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                if (is_valid_email($email) == 1 && is_valid_password($password) == 1 && is_valid_country($country) == 1) {

                    $class_auth = new Auth($db_conn->get_link());
                    $class_validation = new validation($db_conn->get_link());
                    $class_email_verification = new EmailVerification($db_conn->get_link());

                    if ($class_validation->check_email($email)) {

                        $password_hashed = Helper::string_hash($password);
                        $username = explode('@', $email)[0]; //split email to get username
                        $registration_type = 1;

                        $user_id = $class_auth->register($username, $email, $password_hashed, $registration_type,$country);

                        if ($user_id > 0) {

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

                            $dateTime = new DateTime();
                            $current_datetime = $dateTime->format('Y-m-d H:i:s');
                            $created_at=$current_datetime;

                            $email_verification_code=Helper::get_random_number();

                            if($class_email_verification->insert_email_verification($user_id,$email_verification_code,$created_at)){
                                
                                $user_id_hashed=Helper::string_hash($user_id);
                                $type_hashed=Helper::string_hash(Key::$KEY_EMAIL_VERIFICATION_TYPE);

                                $email_status=0;
                                $mail->addAddress($email);
                                $mail->Subject = "Email verification code: ".$email_verification_code."";
                                $mail-> Body= email_page($username,$email_verification_code,$email_status,$user_id_hashed,$type_hashed);
                                if($mail->send()){

                                    $res = 1; //success

                                }else{
                                    $res = 0;
                                }

                            }else{
                                $res = 0;
                            }

                        } else {
                            $res = 0;
                        }
                    } else {
                        $res = 2; //email used
                    }
                }

            }catch(PDOException $ex){

                $res=0;
                
            }

        }else{
            $res=0;
        }
        
    }else{
        $res=0;
    }

    $obj->res = $res;
    $obj->id=$user_id_hashed;
    $obj->type=$type_hashed;
    echo json_encode($obj);

    function is_valid_country($country){

        if (empty($country)) {
            return 0;
        }

        return 1;

    }

    function is_valid_password($password)
    {
        $space = preg_match('/^[^ ].* .*[^ ]$/', $password);
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number = preg_match('@[0-9]@', $password);
        $special_char = preg_match('@[\W]@', $password);

        if (strlen(trim($password)) == 0) {
            return 0;
        }

        if (strlen(trim($password)) < 6) {
            return 3;
        }

        if ($space && !$uppercase && !$lowercase && !$number && !$special_char) {
            return 2;
        }

        return 1;
    }

    function is_valid_email($email)
    {
        if (empty($email)) {
            return 0;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return 2;
        }

        return 1;
    }

?>
