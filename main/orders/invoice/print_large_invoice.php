<?php

    require_once "../../../config/variables.php";
    require_once "../../../config/helper.php";

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../../forbidden");
    }

    if(isset($_GET['order_id'])){

        $order_id=$_GET['order_id'];
        $order_id=htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8');
        $order_id=Helper::decrypt($order_id);
        if($order_id == ""){
            die("<span style='color: red;'>Order not found.</span>");
        }

        require_once "../../resources/libs/tcpdf/tcpdf.php";
        require_once "../../../config/conn.php";
        require_once "../classes/orders.php";
        require_once "../classes/contact_details.php";
        require_once "../classes/product_size.php";
        require_once "../../currency.php";

        $conn=$db_conn->get_link();

        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $path = substr($link, 0, strpos($link, WebsiteInfo::$KEY_PATH_TO_WEBSITE));
        $path_to_logo=$path. WebsiteInfo::$KEY_PATH_TO_WEBSITE."/main/".WebsiteInfo::$KEY_INVOICE_LOGO;

        $class_orders=new Orders($conn);
        $class_contact_details=new ContactDetails($conn);
        $class_product_size=new ProductSize($conn);
        $contact_details_info=$class_contact_details->get_contact_details();

        date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);

        if(isset($_SESSION[Session::$KEY_EC_TIME_ZONE])){
            $time_zone=Helper::decrypt($_SESSION[Session::$KEY_EC_TIME_ZONE]);
            date_default_timezone_set($time_zone);
        }else{
            date_default_timezone_set(WebsiteInfo::$KEY_DEFAULT_TIME_ZONE);
        }
        
        $website_name=WebsiteInfo::$KEY_WEBSITE_NAME;

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

        $pdf = new TCPDF("L", "mm", "A4", true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $lg = [];
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'ltr';
        $lg['a_meta_language'] = 'en';
        $lg['w_page'] = 'page';
        $pdf->setLanguageArray($lg);
        $pdf->SetFont('', '', '10');
        $pdf->SetAutoPageBreak(true, 0);

        $pdf->AddPage();
        
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $path = substr($link, 0, strpos($link, WebsiteInfo::$KEY_PATH_TO_WEBSITE));
        $path_to_logo=$path. WebsiteInfo::$KEY_PATH_TO_WEBSITE."/main/".WebsiteInfo::$KEY_INVOICE_LOGO;
        
        $image_file = $path_to_logo;
        
        $pdf->Image($image_file, 5, 5 , 12,'', 'PNG', '', 'T', false, 0, 'L', false, false, 0, false, false, false);
      
        $date=$date.' '.$time.' '.$am_pm;
        $pdf->Cell(0, 20, $date, 0, 1, 'R', 0, '', 0, false, 'T', 'T');

        $table =<<<EOO
            <style>
                .table_invoice{
                    border-collapse: collapse;
                    width: 100%;
                    line-height:22px;
                }
                .table_invoice th{
                    font-size: 12px;
                }
                #table_invoice .tr_line{
                    border-bottom:1px solid #e4e4e4 !important;
                }
                .billing-title{
                    color: black;
                    font-size: 13px;
                    font-weight: bold;
                }
                .text-bold{
                    font-weight: bold;
                }
                .text-center{
                    text-align: center;
                }
                .text-right{
                    text-align: right;
                }
                .text-left{
                    text-align: left;
                }
                .text-red{
                    color:red;
                }
            </style>
        EOO;

        $table .=<<<EOO
            <table class="table_invoice">
                    <tr>
                        <th class="text-bold" style="border-bottom:1px solid #e4e4e4;">Item</th>
                        <th class="text-bold text-center" style="border-bottom:1px solid #e4e4e4;">Price*1</th>
                        <th class="text-bold text-center" style="border-bottom:1px solid #e4e4e4;">Quantity</th>
                        <th class="text-bold text-center" style="border-bottom:1px solid #e4e4e4;">Size</th>
                        <th class="text-bold text-center" style="border-bottom:1px solid #e4e4e4;">Color</th>
                        <th class="text-bold text-right" style="border-bottom:1px solid #e4e4e4;">Subtotal</th>
                    </tr>
                <tbody>
        EOO;

        if($class_orders->get_details_order($order_id)!=0){

            $total_price=0;
            $total_products_price=0;

            foreach($class_orders->get_details_order($order_id) as $class_orders_details){

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
                $order_fullname=$class_orders_details['fullname'];

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

                $table .= <<<EOO
                        <tr>
                            <td class="text-red" style="border-bottom:1px solid #e4e4e4;">$product_name</td>
                            <td class="text-center" style="border-bottom:1px solid #e4e4e4;">$currency_symbol$price_per_one</td>
                            <td class="text-center" style="border-bottom:1px solid #e4e4e4;">$qty</td>
                            <td class="text-center" style="border-bottom:1px solid #e4e4e4;">$size_name</td>
                            <td class="text-center" style="border-bottom:1px solid #e4e4e4;">$color</td>
                            <td class="text-right" style="border-bottom:1px solid #e4e4e4;">$currency_symbol$total_price_per_one</td>
                        </tr>
                EOO;
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
            
            $website_name=WebsiteInfo::$KEY_WEBSITE_NAME;
            
            $table .= <<<EOO
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center text-dark text-bold">Subtotal</td>
                            <td class="text-right">$currency_symbol$total_products_price</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center text-dark text-bold">$order_type_name</td>
                            <td class="text-right">$currency_symbol$amount</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-center text-dark text-bold">Total</td>
                            <td class="text-right">$currency_symbol$total_price</td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            EOO;

            $table .= <<<EOO
                <table>
                    <tbody>
                        <tr>
                            <td colspan="2"><span class="billing-title">STORE INFORMATION</span></td>
                            <td colspan="2"><span class="billing-title">SHIPPING INFORMATION</span></td>
                            <td colspan="2" class="text-right"><span class="billing-title">PAYMENT METHOD</span></td>
                        </tr>
                        <tr>
                            <td colspan="2">$website_name</td>
                            <td colspan="2">$order_fullname</td>
                            <td colspan="2" class="text-right">$order_type_name: Cash</td>
                        </tr>
                        <tr>
                            <td colspan="2">T: $phone_number</td>
                            <td colspan="2">T: $order_phone_number</td>
                            
                EOO;
                if($tracking_number != "" || $tracking_number != NULL){
                    $table.= <<<EOO
                        <td colspan="2" class="text-right">Tracking Number: #$tracking_number</td>
                    EOO;
                }else{
                    $table.= <<<EOO
                        <td colspan="2" class="text-right"></td>
                    EOO;
                }    
                $table.= <<<EOO
                        </tr>
                        <tr>
                            <td colspan="2">E: $email</td>
                            <td colspan="2">E: $order_email</td>
                        </tr>
                        <tr>
                            <td colspan="2">$address</td>
                            <td colspan="2">$order_country, $order_region, $order_address</td>
                        </tr>
                    </tbody>
                </table>
            EOO;

        }

        $pdf->WriteHTML($table, true, false, false, 0);
        $pdf->Output('Large Invoice.pdf', 'I');

    }

?>
