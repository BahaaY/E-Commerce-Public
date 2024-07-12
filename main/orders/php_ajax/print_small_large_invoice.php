<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once "../../../config/variables.php";
    require_once "../classes/orders.php";
    require_once "../classes/contact_details.php";
    require_once "../../../config/conn.php";
    require_once "../../../config/helper.php";
    require_once "../invoice_templates.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    $res=0;
    $row="";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST['order_id']) && isset($_POST["token"])){

            try{

                $token=$_POST['token'];
                
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){
                        $order_id=$_POST['order_id'];
                        if($order_id != ""){
                            
                            $row.=large_invoice($order_id,$db_conn->get_link());
                            $row.="<div style='page-break-before: always'></div>";
                            $row.=small_invoice($order_id,$db_conn->get_link());
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
        "res"=>$res,
        "row"=>$row
    ]);
?>