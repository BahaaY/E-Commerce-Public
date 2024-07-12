<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/dictionary_key.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;
    $check_product_size_name=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['english']) && isset($_POST['french']) && isset($_POST['arabic']) && isset($_POST['id']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $id=$_POST['id'];
                        $english=$_POST['english'];
                        $french=$_POST['french'];
                        $arabic=$_POST['arabic'];
                
                        if($english != "" && $french !="" && $arabic !="" && $id !=""){
                
                            $class_dictionary=new DictionaryKey($db_conn->get_link());
                
                            if($class_dictionary->update_dictionary($id,$english,$french,$arabic)){
                                $res=1;
                            }else{
                                $res=0;
                            }
                            
                        }else{
                            $res=0;
                        }
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