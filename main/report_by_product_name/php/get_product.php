<?php

if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

require_once 'classes/products.php';
require_once "../../config/conn.php";
require_once "../../config/variables.php";

if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
    header("location:../../forbidden");
}

$products=new Products($db_conn->get_link());
$format=$products->get_products();
if($products->get_products())
{
    foreach($products->get_products() as $product){
        $format.= "<option value=".$product['product_type_id'].">".$product['product_type_name']."</option>";
    }
}
echo $format;




?>