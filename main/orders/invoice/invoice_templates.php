<?php

    require_once "../../../config/variables.php";

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    function large_invoice($order_id,$conn){

        if($order_id == ""){
            die();
        }

        require_once "../../../config/variables.php";
        require_once "../classes/orders.php";
        require_once "../classes/contact_details.php";
        require_once "../classes/product_size.php";
        require_once "../../../config/conn.php";
        require_once "../../../config/helper.php";
        require_once "../../currency.php";
    
        $row="";

        date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
        $class_orders=new Orders($conn);
        $class_contact_details=new ContactDetails($conn);
        $class_product_size=new ProductSize($conn);
        $contact_details_info=$class_contact_details->get_contact_details();

        if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
            $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
            date_default_timezone_set($time_zone);
        }else{
            date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
        }

        $current_date=date("Y-m-d H:i:s");
        $date = date('d/M/Y', strtotime($current_date));
        $time = date('h:i', strtotime($current_date));
        $am_pm = date('A', strtotime($current_date));

        if($contact_details_info){
            $address=$contact_details_info['address'];
            $phone_number=$contact_details_info['phone_number'];
            $email=$contact_details_info['email'];
        }
        if($address == ""){
            $address="Pending announcement";
        }

        if($phone_number == ""){
            $phone_number="Pending announcement";
        }

        if($email == ""){
            $email="Pending announcement";
        }

        $row.='
            <style>
                .invoice_logo{
                    width:50px;
                    height:50px;
                }
                #table_invoice .tr_line{
                    border-bottom:1px solid #e4e4e4;
                }
                #table_invoice th{
                    font-family: "Open Sans", sans-serif;
                    font-weight: bold;
                    vertical-align: top;
                    text-align: left;
                    color:black;
                }
                #table_invoice td{
                    font-family: "Open Sans", sans-serif;
                    color: #747171;
                    line-height: 12px;
                    vertical-align: top;
                    padding:10px 0;
                }
                .title{
                    font-family: "Open Sans", sans-serif;
                    text-align: right;
                    font-size: 22px;
                    color:black;
                }
                .title-text{
                    color: #747171;
                    font-family: "Open Sans", sans-serif;
                    line-height: 18px;
                    vertical-align: top;
                    text-align: left;
                }
                .billing{
                    font-size: 15px;
                    font-family: "Open Sans", sans-serif;
                    color: #747171;
                    line-height: 20px;
                    vertical-align: top;
                }
                .billing-title{
                    color: black;
                    font-size: 16px;
                    font-weight: bold;
                }
                .text-bold{
                    font-weight: bold;
                }
                
                @media Print{
                    @page {
                        size: landscape;
                    }
                    
                    #invoice_footer{
                        position:fixed;
                        bottom:0px;
                        width:100% !important
                    }
                }
            </style>
        ';

        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $path = substr($link, 0, strpos($link, WebsiteInfo::$KEY_PATH_TO_WEBSITE));
        $path_to_logo=$path. WebsiteInfo::$KEY_PATH_TO_WEBSITE."/main/".WebsiteInfo::$KEY_INVOICE_LOGO;

        $row.='
            <div class="container-fluid bg-white">
                <div class="row mb-5">
                    <div class="col title text-bold text-left">
                        <img src="'.$path_to_logo.'" width="50px" height="50px">
                    </div>
                    <div class="col text-bold text-dark text-right">
                    '.$date.' '.$time.' '.$am_pm.'
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" id="table_invoice">
                                <thead>
                                    <tr class="tr_line">
                                        <th>
                                            Item
                                        </th>
                                        <th class="text-center">
                                            Price*1
                                        </th>
                                        <th class="text-center">
                                            Quantity
                                        </th>
                                        <th class="text-center">
                                            Size
                                        </th>
                                        <th class="text-center">
                                            Color
                                        </th>
                                        <th class="text-right">
                                            Subtotal
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
        ';

        if($class_orders->get_details_order($order_id)!=0){

            $total_price=0;
            $total_products_price=0;

            foreach($class_orders->get_details_order($order_id) as $class_orders_details){

                //$order_details_id=$class_orders_details['order_details_id'];
                // $image=$class_orders->get_product_image($order_details_id);

                $product_name=$class_orders_details['title'];
                $color=$class_orders_details['color'];
                $size_id=$class_orders_details['product_size_id_FK'];
                $order_type_name=$class_orders_details['order_type_name'];
                $order_currency_id=$class_orders_details['currency_id_FK'];
                $size_name=$class_product_size->get_product_size_name($size_id);
                $tracking_number=$class_orders_details['tracking_number'];
                $order_country=$class_orders_details['country'];
                $order_region=$class_orders_details['region'];
                $order_address=$class_orders_details['address'];
                $order_phone_number=$class_orders_details['phone_number'];
                $order_email=$class_orders_details['email'];
                $fullname=$class_orders_details['fullname'];

                $qty=$class_orders_details['quantity'];
                $amount=$class_orders_details['amount'];
                if($class_orders_details['discount_percentage'!=0]){
                    $price_per_one=$class_orders_details['price']-($class_orders_details['price'])*($class_orders_details['discount_percentage'])/100;
                }else{
                    $price_per_one=$class_orders_details['price'];
                }
                $price_per_one=Helper::round_price($price_per_one);
                $total_price_per_one=$price_per_one*$qty;
                $total_products_price+=$total_price_per_one;
                $total_price=$total_products_price+$amount;

                $currency_symbol=get_currency_symbol($conn,$order_currency_id);
                if($order_currency_id == 1){
                    $price_per_one=Helper::round_price($price_per_one*get_currency_rate($conn,2));
                    $total_price_per_one=Helper::round_price($total_price_per_one*get_currency_rate($conn,2));
                }else if($order_currency_id == 2){
                    $price_per_one=Helper::round_price($price_per_one);
                    $total_price_per_one=Helper::round_price($total_price_per_one);
                }else if($order_currency_id == 3){
                    $price_per_one=Helper::round_price($price_per_one*get_currency_rate($conn,3));
                    $total_price_per_one=Helper::round_price($total_price_per_one*get_currency_rate($conn,3));
                }
        
                $price_per_one=number_format($price_per_one,2);
                $total_price_per_one=number_format($total_price_per_one,2);

                $row.='
                                    <tr class="tr_line">
                                        <td style="color:red !important">
                                            '.$product_name.'
                                        </td>
                                        <td class="text-center">
                                            '.$currency_symbol.''.$price_per_one.'
                                        </td>
                                        <td class="text-center">
                                            '.$qty.'
                                        </td>
                                        <td class="text-center">
                                            '.$size_name.'
                                        </td>
                                        <td class="text-center">
                                            '.$color.'
                                        </td>
                                        <td class="text-right">
                                            '.$currency_symbol.''.$total_price_per_one.'
                                        </td>
                                    </tr>
                ';
                $total_price_per_one=0;
            }

            if ($order_currency_id == 1) {
                $total_products_price=Helper::round_price($total_products_price*get_currency_rate($conn,2));
                $amount=Helper::round_price($amount*get_currency_rate($conn,2));
                $total_price=Helper::round_price($total_price*get_currency_rate($conn,2));
            }else if($order_currency_id == 2){
                $total_products_price=Helper::round_price($total_products_price);
                $amount=Helper::round_price($amount);
                $total_price=Helper::round_price($total_price);
            } else if ($order_currency_id == 3) {
                $total_products_price=Helper::round_price($total_products_price*get_currency_rate($conn,3));
                $amount=Helper::round_price($amount*get_currency_rate($conn,3));
                $total_price=Helper::round_price($total_price*get_currency_rate($conn,3));
            }
            
            $total_products_price = number_format($total_products_price, 2);
            $amount = number_format($amount, 2);
            $total_price = number_format($total_price, 2);

            $row.='
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center text-dark text-bold">Subtotal</td>
                    <td class="text-right">'.$currency_symbol.''.$total_products_price.'</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center text-dark text-bold">'.$order_type_name.'</td>
                    <td class="text-right">'.$currency_symbol.''.$amount.'</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center text-dark text-bold">Total</td>
                    <td class="text-right">'.$currency_symbol.''.$total_price.'</td>
                </tr>
            ';
        }
        $row.='
                             </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row" id="invoice_footer">
                    <div class="col billing">
                        <span class="billing-title">
                            STORE INFORMATION
                        </span>
                        <br>
                        <span>
                            '.WebsiteInfo::$KEY_WEBSITE_NAME.'<br>
                            T: '.$phone_number.'<br>
                            E: '.$email.'<br>
                            '.$address.'<br>
                        </span>
        
                    </div>
                    <div class="col offset-2 billing">
                        <span class="billing-title">
                            SHIPPING INFORMATION
                        </span>
                        <br>
                            '.$fullname.'<br>
                            T: '.$order_phone_number.'<br>
                            E: '.$order_email.'<br>
                            '.$order_country.', '.$order_region.', '.$order_address.'<br>
                        </span>
                    </div>
                    <div class="col text-right offset-1 billing">
                        <span class="billing-title">
                            PAYMENT METHOD
                        </span>
                        <br>
                        <span>
                            '.$order_type_name.': Cash<br>';
                            if($tracking_number != "" || $tracking_number != NULL){
                                $row.='
                                Tracking Number: #'.$tracking_number.'
                                <br>
                                ';
                            }
                            
                        $row.='
                        </span>
                    </div>
                </div>
                
            </div>

        ';

        return $row;

    }
    function small_invoice($order_id,$conn){

        if($order_id == ""){
            die();
        }
    
        require_once "../../../config/variables.php";
        require_once "../classes/orders.php";
        require_once "../classes/contact_details.php";
        require_once "../classes/product_size.php";
        require_once "../../../config/conn.php";
        require_once "../../../config/helper.php";
        require_once "../../currency.php";
    
        $row="";

        date_default_timezone_set("Asia/Beirut");
        $class_orders=new Orders($conn);
        $class_contact_details=new ContactDetails($conn);
        $class_product_size=new ProductSize($conn);
        $contact_details_info=$class_contact_details->get_contact_details();

        if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
            $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
            date_default_timezone_set($time_zone);
        }else{
            date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
        }

        $current_date=date("Y-m-d H:i:s");
        $date = date('d/M/Y', strtotime($current_date));
        $time = date('h:i', strtotime($current_date));
        $am_pm = date('A', strtotime($current_date));

        if($contact_details_info){
            $address=$contact_details_info['address'];
            $phone_number=$contact_details_info['phone_number'];
            $email=$contact_details_info['email'];
        }
        if($address == ""){
            $address="Pending announcement";
        }

        if($phone_number == ""){
            $phone_number="Pending announcement";
        }

        if($email == ""){
            $email="Pending announcement";
        }

        $row.='
            <style>
                @import url("http://fonts.cdnfonts.com/css/vcr-osd-mono");
                .bill{
                    //font-family: "VCR OSD Mono";
                    //width: 365px;
                    box-shadow: 0 0 3px #aaa;
                    padding: 10px 10px;
                    box-sizing: border-box;
                    background-color:white;
                    font-size: 16px;
                    line-height: 30px;
                }
                .flex {
                    display: flex;
                }
                .justify-between {
                    justify-content: space-between;
                }
                .table_invoice{
                    border-collapse: collapse;
                    width: 100%;
                }
                .table_invoice .header{
                    border-top: 2px dashed #000;
                    border-bottom: 2px dashed #000;
                    height:30px;
                    box-shadow: none;
                }
                .table_invoice {
                    text-align: left;
                }
                .table_invoice td,.table_invoice th{
                    line-height: 30px;
                    font-size: 16px;
                }
                .table_invoice .total td:first-of-type {
                    border-top: none;
                    border-bottom: none;
                }
                .table_invoice .total td {
                    border-top: 2px dashed #000;
                    border-bottom: 2px dashed #000;
                }
                .table_invoice .net-amount td:first-of-type {
                    border-top: none;
                }
                .table_invoice .net-amount td {
                    border-top: 2px dashed #000;
                }
                .table_invoice .net-amount{
                    border-bottom: 2px dashed #000;
                }
                .text-bold{
                    font-weight: bold;
                }
                @media print {
                    .hidden-print,
                    .hidden-print * {
                        display: none !important;
                    }
                }
            </style>
        ';

        $row.='
            <div class="bill">
                <div class="brand text-bold">
                    '.WebsiteInfo::$KEY_WEBSITE_NAME.'
                </div>
                <div class="address">
                    Address- '.$address.'
                    <br> 
                    Phone No- '.$phone_number.'
                    <br> 
                    Email- '.$email.'
                </div>
                <div>RETAIL INVOICE </div>
                <div class="bill-details">
                    <div class="flex justify-between">
                        <div>DATE: '.$date.'</div>
                        <div>TIME: '.$time.' '.$am_pm.'</div>
                    </div>
                </div>
                <br>
                <table class="table_invoice">
                    <thead>
                        <tr class="header">
                            <th>
                                Items
                            </th>
                            <th>
                                Price*1
                            </th>
                            <th>
                                Qty
                            </th>
                            <th>
                                Total
                            </th>
                        </tr>
                    </thead>
                    <tbody>
        ';
        if($class_orders->get_details_order($order_id)!=0){

            $total_price=0;
            $total_products_price=0;

            foreach($class_orders->get_details_order($order_id) as $class_orders_details){

                $order_details_id=$class_orders_details['order_details_id'];
                $image=$class_orders->get_product_image($order_details_id);
                $product_name=$class_orders_details['title'];
                $color=$class_orders_details['color'];
                $size_id=$class_orders_details['product_size_id_FK'];
                $order_type_name=$class_orders_details['order_type_name'];
                $tracking_number=$class_orders_details['tracking_number'];
                $order_currency_id=$class_orders_details['currency_id_FK'];
                $size_name=$class_product_size->get_product_size_name($size_id);

                $qty=$class_orders_details['quantity'];
                $amount=$class_orders_details['amount'];
                if($class_orders_details['discount_percentage'!=0]){
                    $price_per_one=$class_orders_details['price']-($class_orders_details['price'])*($class_orders_details['discount_percentage'])/100;
                }else{
                    $price_per_one=$class_orders_details['price'];
                }
                $price_per_one=Helper::round_price($price_per_one);
                $total_price_per_one=$price_per_one*$qty;
                $total_products_price+=$total_price_per_one;
                $total_price=$total_products_price+$amount;

                $currency_symbol=get_currency_symbol($conn,$order_currency_id);
                if($order_currency_id == 1){
                    $price_per_one=Helper::round_price($price_per_one*get_currency_rate($conn,2));
                    $total_price_per_one=Helper::round_price($total_price_per_one*get_currency_rate($conn,2));
                }else if($order_currency_id == 2){
                    $price_per_one=Helper::round_price($price_per_one);
                    $total_price_per_one=Helper::round_price($total_price_per_one);
                }else if($order_currency_id == 3){
                    $price_per_one=Helper::round_price($price_per_one*get_currency_rate($conn,3));
                    $total_price_per_one=Helper::round_price($total_price_per_one*get_currency_rate($conn,3));
                }
        
                $price_per_one=number_format($price_per_one,2);
                $total_price_per_one=number_format($total_price_per_one,2);

                $row.='
                    <tr>
                        <td>'.$product_name.'</td>
                        <td>'.$currency_symbol.''.$price_per_one.'</td>
                        <td>'.$qty.'</td>
                        <td>'.$currency_symbol.''.$total_price_per_one.'</td>
                    </tr>
                ';
                $total_price_per_one=0;
            }

            if ($order_currency_id == 1) {
                $total_products_price=Helper::round_price($total_products_price*get_currency_rate($conn,2));
                $amount=Helper::round_price($amount*get_currency_rate($conn,2));
                $total_price=Helper::round_price($total_price*get_currency_rate($conn,2));
            }else if($order_currency_id == 2){
                $total_products_price=Helper::round_price($total_products_price);
                $amount=Helper::round_price($amount);
                $total_price=Helper::round_price($total_price);
            } else if ($order_currency_id == 3) {
                $total_products_price=Helper::round_price($total_products_price*get_currency_rate($conn,3));
                $amount=Helper::round_price($amount*get_currency_rate($conn,3));
                $total_price=Helper::round_price($total_price*get_currency_rate($conn,3));
            }
            
            $total_products_price = number_format($total_products_price, 2);
            $amount = number_format($amount, 2);
            $total_price = number_format($total_price, 2);

            $row.='
                        <tr class="total">
                            <td>Total</td>
                            <td></td>
                            <td></td>
                            <td>'.$currency_symbol.''.$total_products_price.'</td>
                        </tr>
                        <tr>
                            <td>'.$order_type_name.'</td>
                            <td></td>
                            <td></td>
                            <td>'.$currency_symbol.''.$amount.'</td>
                        </tr>
                        <tr class="net-amount">
                            <td>Net Amnt</td>
                            <td></td>
                            <td></td>
                            <td>'.$currency_symbol.''.$total_price.'</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                Payment Method: '.$order_type_name.'-Cash<br>';
                if($tracking_number != "" || $tracking_number != NULL){
                    $row.='
                        Tracking Number: #'.$tracking_number.'
                        <br>
                    ';
                }
                
                $row.='
                Thank you for shipping with us,
                <br>
                POWERED BY SSS.
                </div>
            ';
        }

        return $row;

    }
?>