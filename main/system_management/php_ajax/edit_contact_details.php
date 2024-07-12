<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/contact_details.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['address']) && isset($_POST['phone_number']) && isset($_POST['email']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $address=$_POST['address'];
                        $phone_number=$_POST['phone_number'];
                        $email=$_POST['email'];
                
                        //if($address != "" && $phone_number !="" && $email !=""){
                
                            $class_contact_details=new ContactDetails($db_conn->get_link());
                
                            if($class_contact_details->update_contact_details($address,$phone_number,$email)){
                                $res=1;
                            }else{
                                $res=0;
                            }
                            
                        // }else{
                        //     $res=0;
                        // }
                    }else{
                        $res=0;
                    }
                }else{
                    $res=0;
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

    echo json_encode([
        "res"=>$res
    ]);

?>