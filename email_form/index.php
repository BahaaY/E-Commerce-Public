<?php

    function email_page($username,$verification_code,$email_status,$user_id_hashed, $type_hashed){

        $row='';

        $row.='
            <!DOCTYPE html>
            <html>
            
                <head>
                    <title></title>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
                    <style type="text/css">
                        body,
                        table,
                        td,
                        a {
                            -webkit-text-size-adjust: 100%;
                            -ms-text-size-adjust: 100%;
                        }

                        a{
                            text-decoration: none;
                            color:blue;
                        }

                        a:hover{
                            text-decoration: underline;
                        }

                        img {
                            -ms-interpolation-mode: bicubic;
                        }

                        /* RESET STYLES */
                        img {
                            border: 0;
                            height: auto;
                            line-height: 100%;
                            outline: none;
                            text-decoration: none;
                        }

                        table {
                            border-collapse: collapse !important;
                        }

                        body {
                            height: 100% !important;
                            margin: 0 !important;
                            padding: 0 !important;
                            width: 100% !important;
                        }

                        /* iOS BLUE LINKS */
                        a[x-apple-data-detectors] {
                            color: inherit !important;
                            text-decoration: none !important;
                            font-size: inherit !important;
                            font-family: inherit !important;
                            font-weight: inherit !important;
                            line-height: inherit !important;
                        }

                        /* MOBILE STYLES */
                        @media screen and (max-width:600px) {
                            h1 {
                                font-size: 32px !important;
                                line-height: 32px !important;
                            }
                        }

                        /* ANDROID CENTER FIX */
                        div[style*="margin: 16px 0;"] {
                            margin: 0 !important;
                        }
                    </style>
                </head>
                <body style="background-color: #f4f4f4; margin: 0 !important; padding: 0 !important;">
                    
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <!-- LOGO -->
                        <tr>
                            <td bgcolor="#0069d9" align="center">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                                    <tr>
                                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;"> </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#0069d9" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                                    <tr>
                                        <td bgcolor="#ffffff" align="center" valign="top" style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #111111; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                                            <h1 style="font-size: 48px; font-weight: 400; margin: 2;">';
                                            if($email_status == 0){
                                                $text="Welcome";
                                            }else if($email_status == 1){
                                                $text="Welcome back";
                                            }else if($email_status == 2){
                                                $text="Welcome back";
                                            }else{
                                                $text="Welcome";
                                            }
                                            $row.=$text.' '.$username.'!</h1> <img src=" https://img.icons8.com/clouds/100/000000/handshake.png" width="125" height="120" style="display: block; border: 0px;" />
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f4f4f4" align="center" style="padding: 0px 10px 0px 10px;">
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                                    <tr>
                                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">';
                                        if($email_status == 0){
                                            $row.='<p style="margin: 0;padding-right: 10px; padding-left: 10px;">We-re excited to have you get started. First, you need to confirm your account. Just get the code below.</p>';
                                        }else if($email_status == 1){
                                            $row.='<p style="margin: 0;padding-right: 10px; padding-left: 10px;">We-re excited to have you get back. Just get the code below to verify your new account.</p>';
                                        }else if($email_status == 2){
                                            $row.='<p style="margin: 0;padding-right: 10px; padding-left: 10px;">We-re excited to have you get back. Just get the code below to reset your account password.</p>';
                                        }else if($email_status == 3){
                                            $row.='<p style="margin: 0;padding-right: 10px; padding-left: 10px;">We-re excited to have you get back. Just get the code below to verify your identity.</p>';
                                        }
                                        $row.='</td>
                                    </tr>
                                    <tr>
                                        <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                            <p style="margin: 0;font-size: 35px;font-weight: bold;text-align: center;">'.$verification_code.'</p>
                                        </td>
                                    </tr>';

                                    $variables_path="../../../config/variables.php";
                                    if (file_exists($variables_path)) {
                                        require_once $variables_path;
                                    }else{
                                        require_once "../../config/variables.php";
                                    }
                                    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                                    $path = substr($link, 0, strpos($link, WebsiteInfo::$KEY_PATH_TO_WEBSITE)).WebsiteInfo::$KEY_PATH_TO_WEBSITE."/";
                                  
                                    if($email_status == 0){
                                        $row.='
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                                    <p style="margin: 0;padding-right: 10px; padding-left: 10px;">Or click on the following link,
                                                    <br>
                                                    <a href="'.$path.'authentication/?id='.$user_id_hashed.'&t='.$type_hashed.'">Activate your account</a></p>
                                                    <br>
                                                </td>
                                            </tr>
                                        ';
                                    }else if($email_status == 1){
                                        $row.='
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                                    <p style="margin: 0;padding-right: 10px; padding-left: 10px;">Or click on the following link,
                                                    <br>
                                                    <a href="'.$path.'authentication/?id='.$user_id_hashed.'&t='.$type_hashed.'">Verify your email</a></p>
                                                    <br>
                                                </td>
                                            </tr>
                                        ';
                                    }else if($email_status == 2){
                                        $row.='
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                                    <p style="margin: 0;padding-right: 10px; padding-left: 10px;">Or click on the following link,
                                                    <br>
                                                    <a href="'.$path.'authentication/?id='.$user_id_hashed.'&t='.$type_hashed.'">Reset your password</a></p>
                                                    <br>
                                                </td>
                                            </tr>
                                        ';
                                    }else if($email_status == 3){
                                        $row.='
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                                    <p style="margin: 0;padding-right: 10px; padding-left: 10px;">Or click on the following link,
                                                    <br>
                                                    <a href="'.$path.'authentication/?id='.$user_id_hashed.'&t='.$type_hashed.'">Verify your identity</a></p>
                                                    <br>
                                                </td>
                                            </tr>
                                        ';
                                    }
                                    $row.='
                                            <tr>
                                                <td bgcolor="#ffffff" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                                    <p style="margin: 0;padding-right: 10px; padding-left: 10px;">Please make sure you never share this code with anyone.</p>
                                                    <br>
                                                </td>
                                            </tr>
                                        ';
                                    $row.='
                                    <tr>
                                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: "Lato", Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                                            <p style="margin: 0;padding-right: 10px; padding-left: 10px;color:lightgray">
                                                Best Regards,
                                                <br>
                                                <a href="https://spacesoftwaresolutions.com">Space Software Solutions</a>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </body>
            </html>
        ';

        return $row;

    }
    
?>