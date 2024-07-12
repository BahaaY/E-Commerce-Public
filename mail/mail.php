<?php

    // Import PHPMailer classes into the global namespace
    // These must be at the top of your script, not inside a function
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;

    // Load Composer's autoloader
    require 'mailer/autoload.php';
    $variables_path="../../../config/variables.php";
    if (file_exists($variables_path)) {
        require_once $variables_path;
    }else{
        require_once "../../config/variables.php";
    }

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer();

    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
    $mail->isSMTP();                                            // Send using SMTP
    $mail->Host       = EmailInfo::$KEY_HOST;                    // Set the SMTP server to send through
    $mail->SMTPAuth   = EmailInfo::$KEY_SMTP_AUTH;                                   // Enable SMTP authentication
    $mail->Username   = EmailInfo::$KEY_EMAIL;                     // SMTP username
    $mail->Password   = EmailInfo::$KEY_EMAIL_PASSWORD;                               // SMTP password
    $mail->SMTPSecure = EmailInfo::$KEY_SMTP_SECURE;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = EmailInfo::$KEY_PORT;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
    $mail->setFrom(EmailInfo::$KEY_EMAIL, EmailInfo::$KEY_EMAIL_TITLE);
    $mail->AddReplyTo(EmailInfo::$KEY_EMAIL, EmailInfo::$KEY_EMAIL_TITLE);

    // Content
    $mail->isHTML(true);  
    $mail->CharSet = "UTF-8";

?>