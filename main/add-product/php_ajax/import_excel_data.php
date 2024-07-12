<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    require_once '../classes/products.php';
    require_once '../../../config/conn.php';
    require_once '../../../config/variables.php';

    $error=1;

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        try{

            if(isset($_POST['number_of_products']) && isset($_POST['array_title']) && isset($_POST['array_description']) && isset($_POST['array_price']) && isset($_POST['array_discount_percentage']) 
            && isset($_POST['array_stock']) && isset($_POST['array_color']) && isset($_POST['array_size']) && isset($_POST['array_product_type']) && isset($_POST['token'])){

                $number_of_products=$_POST['number_of_products'];
                $array_title=$_POST['array_title'];
                $array_description=$_POST['array_description'];
                $array_price=$_POST['array_price'];
                $array_discount_percentage=$_POST['array_discount_percentage'];
                $array_stock=$_POST['array_stock'];
                $array_color=$_POST['array_color'];
                $array_size=$_POST['array_size'];
                $array_product_type=$_POST['array_product_type'];
                $token=$_POST['token'];
                    
                if(isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    $token_session=$_SESSION[Session::$KEY_EC_TOKEN];
                    if($token_session == $token){

                        if($number_of_products != "" && $array_title != "" && $array_description != "" && $array_price != "" && $array_discount_percentage != ""
                        && $array_stock != "" && $array_product_type != ""){
        
                            if($number_of_products == count(explode(",",$array_title))){
        
                                $class_products = new Products($db_conn->get_link());
                                if($class_products->insert_products_excel($number_of_products,$array_title,$array_description,$array_price,$array_discount_percentage,$array_stock,$array_color,$array_size,$array_product_type)){
                                    $error = 0;
                                }else{
                                    $error = 1;
                                }
                                
                            }else{
                                $error = 1;
                            }
        
                        }else{
                            $error = 1;
                        }

                    }else{
                        $error = 1;
                    }

                }else{
                    $error = 1;
                }

            }else{
                $error = 1;
            }

        }catch(PDOException $ex){
            $error=1;
        }

    }else{
        $error = 1;
    }

    echo json_encode([
        "error"=>$error
    ]);

?>