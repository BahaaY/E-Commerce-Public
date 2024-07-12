<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once "../../../config/variables.php";
    require_once '../../../config/conn.php';
    require_once '../classes/currency.php';

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['rate']) && isset($_POST['availability']) && isset($_POST['id']) && isset($_POST['token'])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $id=$_POST['id'];
                        $rate=$_POST['rate'];
                        $availability=$_POST['availability'];
                
                        if($rate !="" && $availability !="" && $id !=""){
                
                            $class_currency=new Currencyy($db_conn->get_link());
                
                            if($class_currency->update_currency($id,$rate,$availability)){
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