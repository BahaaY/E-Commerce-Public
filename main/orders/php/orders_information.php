<?php

if(session_status() !== PHP_SESSION_ACTIVE){
    session_start();
}

require_once '../../config/variables.php';
require_once '../../config/helper.php';
require_once '../currency.php';
require_once "classes/orders.php";
require_once "classes/order_tracking.php";
if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
    header("location:../../forbidden");
}

$format="";
$class_orders=new Orders($db_conn->get_link());
$class_order_tracking=new OrderTracking($db_conn->get_link());
$serial_number_order=0;
$serial_number_order_details=0;
$orders=$class_orders->get_orders();


    if(count($orders) > 0){
        foreach($orders as $order){
            $serial_number_order++;
            $order_tracking_name="";
            $displayed_text_color="";
            $order_id=$order["order_id"];
            $reference_number=$order["reference_number"];
            $order_full_name=$order["fullname"];
            $order_tracking_number=$order["tracking_number"];
            $order_email=$order["email"];
            $order_phone_number=$order["phone_number"];
            $order_country=$order["country"];
            $order_region=$order["region"];
            $order_address=$order["address"];
            $order_date=$order["date"];
            $order_tracking_id_FK=$order["order_tracking_id_FK"];
            $order_tracking_name=$order["order_tracking_name"];
            $displayed_text_color=$order["displayed_text_color"];
            $order_type_name=$order['order_type_name'];
            $order_delivery_amount=$order['amount'];
            $order_currency_id=$order['currency_id_FK'];
            $total_order_price=$class_orders->get_total_price($order_id);
            $total_order_price_with_delivery_on_dollar=$total_order_price+$order_delivery_amount;
            $disable_checkbox="";
            // if($order_tracking_id_FK==3){
            //     $disable_checkbox="";
            // }else{
            //     $disable_checkbox="disabled";
            // }

            if($class_orders->check_if_in_sales($order_id) > 0){
                $is_checked="checked";
            }else{
                $is_checked="";
            }
           
            $currency_symbol=get_currency_symbol($db_conn->get_link(),$order_currency_id);
            if($order_currency_id == 1){
                $total_order_price_with_delivery=$total_order_price_with_delivery_on_dollar*get_currency_rate($db_conn->get_link(),2);
            }else if($order_currency_id == 2){
                $total_order_price_with_delivery=$total_order_price_with_delivery_on_dollar;
            }else if($order_currency_id == 3){
                $total_order_price_with_delivery=$total_order_price_with_delivery_on_dollar/get_currency_rate($db_conn->get_link(),3);
            }

            $format.="
            <tr id='row_order_".$order_id."'>
                <td>".$serial_number_order."</td>
                <td>".$reference_number."</td>
                <td>".$order_full_name."</td>
                <td>".$order_tracking_number."</td>
                <td>$".number_format(Helper::round_price($total_order_price),2)."</td>
                <td>".get_currency_abbreviation($db_conn->get_link(),$order_currency_id)."</td>
                <td>".$order_date."</td>
                <td>".$order_type_name." +$".number_format(Helper::round_price($order_delivery_amount),2)."</td>
                <td>
                    $".number_format(Helper::round_price($total_order_price_with_delivery_on_dollar),2)."";
                    if($order_currency_id == 1 || $order_currency_id == 3){
                        $format.="
                            <br>
                            ".$currency_symbol."".number_format(Helper::round_price($total_order_price_with_delivery),2)."
                        ";
                    }
                $format.="
                </td>
                <td class='".$displayed_text_color."' id='status_order_".$order_id."'>".$order_tracking_name."</td>
                <td>
                    <button type='submit' class='btn btn-primary m-1' data-toggle='modal'
                        data-target='#modal-details-order-".$order_id."'><i class='bi bi-list'></i></button>";
                    if($order_tracking_id_FK == 4){
                        $format.="
                            <button type='submit' class='btn btn-danger m-1' data-toggle='modal'
                                data-target='#modal-delete-order-".$order_id."'><i class='bi bi-trash'></i></button>
                        ";
                    }else{
                        $format.="
                            <button class='btn btn-secondary m-1' data-toggle='modal'
                                data-target='#modal-ask-print-".$order_id."'><i class='fa fa-print'></i></button>
                        ";
                    }
                $format.="</td>
                <td>
                    <div class='form-check' id='container_chk_add_to_sales_".$order_id."'>
                        <input type='checkbox' ".$disable_checkbox." ".$is_checked." class='form-check-input' id='add_to_sales_".$order_id."' style='margin-top:10px;cursor:pointer;' onclick='add_to_sales(".$order_id.");'>
                    </div>
                </td>
            </tr>";
        
        }
    }else{
        $format.="<tr><td class='text-center' colspan='10'>".$dictionary->get_lang($lang,$KEY_NO_ORDERS_AVAILABLE)."</td></tr>";
    }


$format.="
</tbody>
<tfoot style='display: none;' id='tfoot-table-orders'>
<tr>
    <th colspan='10' style='font-weight:normal' class='text-center'>".$dictionary->get_lang($lang,$KEY_NO_MATCHING_RECORDS_FOUND)."</th>
</tr>
</tfoot>
</table>
</div>
</div>
</div>
</div>";
foreach($orders as $order){
    $chk_canceled="";
    $chk_completed="";
    $chk_under="";
    $total_price=0;
    $order_id=$order["order_id"];
    $order_username=$order["username"];
    $order_full_name=$order["fullname"];
    $order_tracking_number=$order["tracking_number"];
    $order_email=$order["email"];
    $order_phone_number=$order["phone_number"];
    $order_country=$order["country"];
    $order_region=$order["region"];
    $order_address=$order["address"];
    $order_date=$order["date"];
    $order_type=$order['order_type_id_FK'];
    $order_tracking_id_FK=$order["order_tracking_id_FK"];
    $format.= '
    <div class="modal fade modal-show-image" id="modal-details-order-'.$order_id.'" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="direction:ltr !important">
                <h5 class="modal-title" id="exampleModalLabel">'.$dictionary->get_lang($lang,$KEY_DETAILS).'</h5>
                <button type="button" class="close" onclick="clear_alert();" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 col-sm-12">
                        <div class="card p-2 odr1">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>'.$dictionary->get_lang($lang,$KEY_DELIVERY_DETAILS).'</h3>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fullname" class="mb-0">'.$dictionary->get_lang($lang,$KEY_FULLNAME).'</label>
                                        <input class="form-control" type="text" value="'.$order_full_name.'"
                                            id="fullname" name="fullname" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username" class="mb-0">'.$dictionary->get_lang($lang,$KEY_USERNAME).'</label>
                                        <input class="form-control" type="text" value="'.$order_username.'"
                                            id="username" name="username" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="mb-0">'.$dictionary->get_lang($lang,$KEY_EMAIL).'</label>
                                        <input class="form-control" type="email"
                                            value="'.$order_email.'"" id="email" name="email" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="mb-0">'.$dictionary->get_lang($lang,$KEY_PHONE_NUMBER).'</label>
                                        <input class="form-control" type="text" value="'.$order_phone_number.'"
                                            id="phone_number" name="phone_number" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="mb-0">'.$dictionary->get_lang($lang,$KEY_COUNTRY).'</label>
                                        <input class="form-control" type="text" value="'.$order_country.'"
                                            id="country" name="country" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="region" class="mb-0">'.$dictionary->get_lang($lang,$KEY_REGION).'</label>
                                        <input class="form-control" type="text" value="'.$order_region.'"
                                            id="region" name="region" readonly>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address" class="mb-0">'.$dictionary->get_lang($lang,$KEY_ADDRESS).'</label>
                                        <input class="form-control" type="text" value="'.$order_address.'"
                                            id="address" name="address" readonly>
                                    </div>
                                </div>';
                                if($order_type == 2){
                                    $format.='
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="tracking_number" class="mb-0">'.$dictionary->get_lang($lang,$KEY_TRACKING_NO).'</label>
                                                <input class="form-control" type="text" value="'.$order_tracking_number.'"
                                                    id="tracking_number" name="tracking_number" readonly>
                                            </div>
                                        </div>
                                    ';
                                }

                            $format.='</div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <div class="card p-2 odr1">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>'.$dictionary->get_lang($lang,$KEY_ORDER_DETAILS).'</h3>
                                    <hr>
                                </div>
                                <div class="table-responsive">
                                    <table class="table orders_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>'.$dictionary->get_lang($lang,$KEY_IMAGE).'</th>
                                                <th>'.$dictionary->get_lang($lang,$KEY_PRODUCT).'</th>
                                                <th>'.$dictionary->get_lang($lang,$KEY_PRICE).' x1</th>
                                                <th>'.$dictionary->get_lang($lang,$KEY_QUANTITY).'</th>
                                                <th>'.$dictionary->get_lang($lang,$KEY_SIZE).'</th>
                                                <th>'.$dictionary->get_lang($lang,$KEY_COLOR).'</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                     
                                        if($class_orders->get_details_order($order_id)!=0){


                                            foreach($class_orders->get_details_order($order_id) as $class_orders_details){

                                                $serial_number_order_details++;
                                                $order_details_id=$class_orders_details['order_details_id'];
                                                $image=$class_orders->get_product_image($order_details_id);
                                                $order_currency_id=$class_orders_details['currency_id_FK'];
                                                
                                                $amount=$class_orders_details['amount'];
                                                $order_type_name=$class_orders_details['order_type_name'];

                                            if($class_orders_details['discount_percentage'!=0]){

                                                    $price=$class_orders_details['price']-($class_orders_details['price'])*($class_orders_details['discount_percentage'])/100;
                                                }else{
                                                    $price=$class_orders_details['price'];
                                            }

                                            $color=$class_orders_details['color'];
                                            if($color == ""){
                                                $color="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_COLOR_AVAILABLE)."</i>";
                                            }

                                            if($class_orders_details['product_size_id_FK'] != ""){
                                                $size_name=$class_orders->get_size_name($class_orders_details['product_size_id_FK']);
                                            }else{
                                                $size_name="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE)."</i>";
                                            }

                                            $total_price=$total_price+($price*$class_orders_details['quantity']);
                                                                $format.='<tr>
                                                                <td>'.$serial_number_order_details.'</td>
                                                                <td>';
                                                                if($image != ""){
                                                                    if(is_dir("../uploaded_products")){
                                                                        if(file_exists("../uploaded_products/".$image)){
                                                                            $format.='<img src="../uploaded_products/'.$image.'" alt="Card image product" width="50px" heigth="50px">';
                                                                        }else{
                                                                            $format.="<img src='../../images/no_image.png' alt='Card image product' width='50px' heigth='50px'>";
                                                                    }
                                                                    }else{
                                                                        $format.="<img src='../../images/no_image.png' alt='Card image product' width='50px' heigth='50px'>";
                                                                }
                                                                    }
                                                                    $format.='
                                                                    </td>
                                                                    <td>
                                                                '.$class_orders_details['title'].'
                                                                </td>
                                                                <td>$'.number_format(Helper::round_price($price),2).'</td>
                                                                <td>'.$class_orders_details['quantity'].'</td>
                                                                <td>'.$size_name.'</td>
                                                                <td>'.$color.'</td>
                                                                </tr>';

                                            }

                                        }
                                        
                                        $total_price+=$amount;
                                       
                                          $format.='
                                            <tr>
                                                <td colspan="7" class="text-end">
                                                    <h5 class="total">'.$order_type_name.': $'.number_format(Helper::round_price($amount),2).'</h5>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="text-end">
                                                    <h5 class="total">'.$dictionary->get_lang($lang,$KEY_TOTAL_PRICE).': $'.number_format(Helper::round_price($total_price),2).'</h5>
                                                </td>
                                            </tr>';
                                            if($order_currency_id == 1){
                                                $total_order_price_with_delivery=$total_price*get_currency_rate($db_conn->get_link(),2);
                                            }else if($order_currency_id == 2){
                                                $total_order_price_with_delivery=$total_price;
                                            }else if($order_currency_id == 3){
                                                $total_order_price_with_delivery=$total_price/get_currency_rate($db_conn->get_link(),3);
                                            }
                                            if($order_currency_id == 1 || $order_currency_id == 3){
                                                $format.='
                                                    <tr>
                                                        <td colspan="5" class="h5">
                                                            '.$dictionary->get_lang($lang,$KEY_MONETARY_UNIT).':
                                                            '.get_currency_abbreviation($db_conn->get_link(),$order_currency_id).'
                                                        </td>
                                                        <td colspan="2" class="h5 text-end">
                                                            '.get_currency_symbol($db_conn->get_link(),$order_currency_id).''.number_format(Helper::round_price($total_order_price_with_delivery),2).'
                                                        </td>
                                                    </tr>
                                                ';
                                            }
                                        $format.='
                                        </tbody>
                                    </table>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-md-12">';
                                                    if($order_type == 1){
                                                        $format.='
                                                        <img src="img/store-pickup.png" alt="delivery"
                                                            class="col-md-12" height="130px">
                                                        ';
                                                    }else{
                                                        $format.='
                                                        <img src="img/delivery.png" alt="delivery"
                                                            class="col-md-12" height="130px">
                                                        ';
                                                    }
                                                $format.='</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                    if($order_tracking_id_FK != 4){
                                        $format.='
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="pmode" class="mb-0">'.$dictionary->get_lang($lang,$KEY_STATUS).':</label>
                                                    <select class="form-select" id="status_'.$order_id.'">
                                                        <option value="" selected disabled>'.$dictionary->get_lang($lang,$KEY_SELECT_A_STATUS).'</option>';
                                                        foreach ($class_order_tracking->get_all_order_tracking() as $order_tracking_details) {
                                                            $order_tracking_id = $order_tracking_details['order_tracking_id'];
                                                            $order_tracking_name = $order_tracking_details['order_tracking_name'];
                                                            if($order_tracking_id_FK == $order_tracking_id){
                                                                $is_selected = "selected";
                                                            }else{
                                                                $is_selected = "";
                                                            }
                                                            $format.='
                                                                <option value="'.$order_tracking_id.'" '.$is_selected.'>'.$order_tracking_name.'</option>
                                                            ';
                                                        }

                                                   $format.='
                                                   </select>
                                                </div>
                                            </div>
                                        ';
                                    }else{
                                        $format.='
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="pmode" class="mb-0">Status:</label>
                                                    <input type="text" class="form-control" value="Deleted" readonly>
                                                </div>
                                            </div>
                                    ';
                                    }
                                    $format.='</div>
                            </div>
                        </div>
                    </div>
                </div>';
                if($order_tracking_id_FK != 4){
                    $format.='
                        <div class="modal-footer" style="justify-content: center;">
                            <button type="submit" id="btn_update_order_'.$order_id.'" class="btn btn-primary mb-0" onclick=update_order('.$order_id.');><i class="bi bi-pen mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_UPDATE_ORDER).'</button> <!-- Update order status -->
                        </div>
                    ';
                }
               
                $format.='</div>
        </div>
    </div>
</div>';
$serial_number_order_details=0;

$format.='
    <div class="modal fade" id="modal-ask-print-'.$order_id.'" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="direction:ltr !important">
                        <h5 class="modal-title">'.$dictionary->get_lang($lang,$KEY_PRINT_INVOICE).'</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <button class="btn btn-primary w-100 m-1" id="btn_small_invoice_'.$order_id.'" onclick="print_small_invoice('.$order_id.');"><i class="fa fa-print mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_SMALL).'</button>
                            </div>
                            <div class="col-md-6">
                                <button class="btn btn-secondary w-100 m-1" id="btn_large_invoice_'.$order_id.'" onclick="print_large_invoice('.$order_id.');"><i class="fa fa-print mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_LARGE).'</button>
                            </div>
                            <div class="col-md-4 d-none">
                                <button class="btn btn-success w-100 m-1" id="btn_small_large_invoice_'.$order_id.'" onclick="print_small_large_invoice('.$order_id.');"><i class="fa fa-print mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_SMALL_LARGE).'</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
';

$format.='
    <div class="modal fade" id="modal-delete-order-'.$order_id.'" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="direction:ltr !important">
                        <h5 class="modal-title">'.$dictionary->get_lang($lang,$KEY_DELETE_ORDER).'</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want delete this order'.$exclamation_mark.'
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_CLOSE).'</button>
                        <button type="button" class="btn btn-danger" id="btn_delete_order_'.$order_id.'" onclick=delete_order('.$order_id.');><i class="bi bi-trash mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_DELETE_ORDER).'</button>
                    </div>
                </div>
            </div>
        </div>
';

}
echo $format;


?>