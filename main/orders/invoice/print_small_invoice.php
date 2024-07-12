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

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80,300), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setMargins(3,3,3,false);
        $lg = [];
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'ltr';
        $lg['a_meta_language'] = 'en';
        $lg['w_page'] = 'page';
        $pdf->setLanguageArray($lg);
        $pdf->SetFont('', '', '10');

        $pdf->AddPage();

        $table =<<<EOO
            <style>
                .text-bold{
                    font-weight: bold;
                }
                .text-center{
                    text-align: center;
                }
                .text-end {
                    float:right;
                    text-align: right;
                }
                .table_invoice{
                    border-collapse: collapse;
                    width: 100%;
                    line-height:17px;
                }
                .table_invoice  td {
                    border-top: 1px dashed #000;
                }
                .table_invoice .net-amount td,
                .table_invoice .net-amount th{
                    border-bottom: 1px dashed #000;
                }
            </style>
        EOO;

        $table .=<<<EOO
            <table class="table_invoice">
               
                    <tr>
                        <th colspan="4" class="text-bold text-center">$website_name</th>
                    </tr>
                    <tr>
                        <th colspan="4">Address- $address</th>
                    </tr>
                    <tr>
                        <th colspan="4">Phone Nb- $phone_number</th>
                    </tr>
                    <tr>
                        <th colspan="4">Email- $email</th>
                    </tr>
                    <tr>
                        <th colspan="4">RETAIL INVOICE</th>
                    </tr>
                    <tr>
                        <th colspan="2">DATE: $date</th>
                        <th colspan="2" style="text-align:right"> TIME:$time $am_pm</th>
                    </tr>
                    <tr>
                        <th colspan="4">
                        </th>
                    </tr>
                    <tr>
                        <td class="text-bold">Items</td>
                        <td class="text-bold">Price*1</td>
                        <td class="text-bold">Qty</td>
                        <td class="text-bold text-end">Total</td>
                    </tr>
                
                <tbody>
        EOO;

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
                $currency_symbol_lb=$currency_symbol;
                $currency_symbol_usd=$currency_symbol;
                if($order_currency_id == 1){
                    $price_per_one=Helper::round_price($price_per_one*get_currency_rate($conn,2));
                    $total_price_per_one=Helper::round_price($total_price_per_one*get_currency_rate($conn,2));
                    $currency_symbol_lb="";
                    $currency_symbol_usd="";
                }else if($order_currency_id == 2){
                    $price_per_one=Helper::round_price($price_per_one);
                    $total_price_per_one=Helper::round_price($total_price_per_one);
                    $currency_symbol_lb="";
                }else if($order_currency_id == 3){
                    $price_per_one=Helper::round_price($price_per_one*get_currency_rate($conn,3));
                    $total_price_per_one=Helper::round_price($total_price_per_one*get_currency_rate($conn,3));
                    $currency_symbol_lb="";
                }
        
                $price_per_one=number_format($price_per_one,2);
                $total_price_per_one=number_format($total_price_per_one,2);

                $table .= <<<EOO
                    <tr>
                        <td>$product_name</td>
                        <td>$currency_symbol_lb$currency_symbol_usd$price_per_one</td>
                        <td>$qty</td>
                        <td class="text-end">$currency_symbol_lb$currency_symbol_usd$total_price_per_one</td>
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

            $table .= <<<EOO
                    <tr>
                        <th>Total</th>
                        <td></td>
                        <td colspan="2" class="text-end">$currency_symbol$total_products_price</td>
                    </tr>
                    <tr>
                        <th>$order_type_name</th>
                        <td></td>
                        <td colspan="2" class="text-end">$currency_symbol$amount</td>
                    </tr>
                    <tr class="net-amount">
                        <th>Net Amnt</th>
                        <td></td>
                        <td colspan="2" class="text-end">$currency_symbol$total_price</td>
                    </tr>
                    <tr>
                        <th colspan="4">
                        </th>
                    </tr>
                    <tr>
                        <th colspan="4">Payment Method: $order_type_name-Cash</th>
                    </tr> 
            EOO;

            if($tracking_number != "" || $tracking_number != NULL){
                $table .= <<<EOO
                    <tr>
                        <th colspan="4">Tracking Number: #$tracking_number</th>
                    </tr>
                EOO;
            }

            $table .= <<<EOO
                    <tr>
                        <th colspan="4">Thank you for shipping with us,</th>
                    </tr>
                    <tr>
                        <th colspan="4">POWERED BY SSS.</th>
                    </tr>
                    </tbody>
                </table>
            EOO;
        }

        // Calculate the height of the content using GetStringHeight
        $contentHeight = $pdf->getStringHeight(80, $table);

        // Calculate the actual height based on content
        $autoHeight = $contentHeight; // Adjust the padding as needed

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, array(80,$autoHeight/3.2), true, 'UTF-8', false);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setMargins(3,3,3,false);
        $lg = [];
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'ltr';
        $lg['a_meta_language'] = 'en';
        $lg['w_page'] = 'page';
        $pdf->setLanguageArray($lg);
        $pdf->SetFont('', '', '10');

        $pdf->AddPage();

        $pdf->WriteHTML($table, true, false, false, 0);
        $pdf->Output('Small Invoice.pdf', 'I');

    }

?>
