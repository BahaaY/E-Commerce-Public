<?php

    require_once '../../../mail/mail.php';
    require_once '../../../config/variables.php';
    $obj = new stdClass();
    $res=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && $_POST['message']) {

            try{

                $message = $_POST['message'];
                $name = $_POST['name'];
                $email = $_POST['email'];
                $subject = $_POST['subject'];

                try{

                    // $body="From: $name, $email<br><br>$message";

                    // $mail->addAddress(EmailInfo::$KEY_EMAIL);
                    // $mail->Subject = $subject;
                    // $mail-> Body= $body;
                    // $mail->setFrom(EmailInfo::$KEY_EMAIL, EmailInfo::$KEY_EMAIL_TITLE);
                    // if($mail->send()){
                    //     $res=1;
                    // }else{
                    //     $res=0;
                    // }

                    $toEmail = EmailInfo::$KEY_EMAIL;
                    $mailHeaders = "From: " . $name . "<". $email .">";
                    if(mail($toEmail, $subject, $message, $mailHeaders)) {
                        $res=1;
                    } else {
                        $res=0;
                    }

                }catch(Exception $ex){
                    $res=0;
                }

            }catch(PDOException $ex){
        
                $res=0;
            
            }

        } else {
            $res = 0;
        }
        
    } else {
        $res = 0;
    }
    $obj->res = $res;
    echo json_encode($obj);

?>
