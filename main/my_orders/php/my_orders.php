<?php

    if(session_status() !== PHP_SESSION_ACTIVE){
        session_start();
    }
    require_once '../../config/variables.php';
    require_once '../../config/helper.php';
    require_once "classes/my_orders.php";
    require_once "classes/order_tracking.php";

    if (!isset($_SESSION[Session::$KEY_EC_USERID])) {
        header("location:../../forbidden");
    }

    $user_id=$_SESSION[Session::$KEY_EC_USERID];
    $user_id=Helper::decrypt($user_id);

    $format="";
    $orders=new My_orders($db_conn->get_link());
    $class_order_tracking=new OrderTracking($db_conn->get_link());
    $serial_number_order=0;
    $serial_number_order_details=0;
    if($orders->get_orders($user_id)!=0){

        if($orders->get_orders($user_id)){
            foreach($orders->get_orders($user_id) as $order){
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
                $currency_id=$order['currency_id_FK'];
                $total_order_price=$orders->get_total_price($order_id);
                $total_order_price_with_delivery=$total_order_price+$order_delivery_amount;

                $currency_symbol=get_currency_symbol($db_conn->get_link(),$currency_id);
                if($currency_id == 1){
                    $total_order_price=$total_order_price*get_currency_rate($db_conn->get_link(),2);
                    $order_delivery_amount=$order_delivery_amount*get_currency_rate($db_conn->get_link(),2);
                    $total_order_price_with_delivery=$total_order_price_with_delivery*get_currency_rate($db_conn->get_link(),2);
                }else if($currency_id == 2){
                    $total_order_price=$total_order_price;
                    $order_delivery_amount=$order_delivery_amount;
                    $total_order_price_with_delivery=$total_order_price_with_delivery;
                }else if($currency_id == 3){
                    $total_order_price=$total_order_price/get_currency_rate($db_conn->get_link(),3);
                    $order_delivery_amount=$order_delivery_amount/get_currency_rate($db_conn->get_link(),3);
                    $total_order_price_with_delivery=$total_order_price_with_delivery/get_currency_rate($db_conn->get_link(),3);
                }
            
                $format.="<tr id='row_order_".$order_id."'>
                <td>".$serial_number_order."</td>
                <td>".$reference_number."</td>
                <td>".$order_full_name."</td>
                <td>".$order_tracking_number."</td>
                <td>".$currency_symbol."".number_format(Helper::round_price($total_order_price),2)."</td>
                <td>".$order_date."</td>
                <td>".$order_type_name." +".$currency_symbol."".number_format(Helper::round_price($order_delivery_amount),2)."</td>
                <td>".$currency_symbol."".number_format(Helper::round_price($total_order_price_with_delivery),2)."</td>
                <td class='".$displayed_text_color."' id='status_order_".$order_id."'>".$order_tracking_name."</td>
                <td>
                    <button type='submit' class='btn btn-primary' data-toggle='modal'
                        data-target='#modal-details-order-".$order_id."'><i class='bi bi-list'></i></button>
                </td>
                </tr>";
            
            }
        }else{
            $format.="<tr><td class='text-center' colspan='9'>".$dictionary->get_lang($lang,$KEY_NO_ORDERS_AVAILABLE)."</td></tr>";
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
    foreach($orders->get_orders($user_id) as $order){
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
        $order_tracking_name=$order["order_tracking_name"];
        if($order_tracking_id_FK=="1"){
            $active="";
            $btn_text="<i class='bi bi-pen mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_CANCEL_ORDER)."";
            $btn_color="btn-danger";
        }else if($order_tracking_id_FK=="2"){
            $active="disabled";
            $btn_text="<i class='bi bi-pen mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_ORDER_CANCELED)."";
            $btn_color="btn-danger";
        }else if($order_tracking_id_FK=="3"){
            $active="disabled";
            $btn_text="<i class='bi bi-pen mr-2 ml-2'></i>".$dictionary->get_lang($lang,$KEY_ORDER_COMPLETED)."";
            $btn_color="btn-success";
        }
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
                        <div class="col-md-12">
                            <div class="alert alert-success alert-dismissible fade show" role="alert" id="alert-success-cancel-order-'.$order_id.'">
                                Order has been canceled.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-danger-cancel-order">
                                Error occurred.
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
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

                                $format.='
                                </div>
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
                                        
                                            if($orders->get_details_order($order_id)!=0){


                            foreach($orders->get_details_order($order_id) as $orders_details){

                                $serial_number_order_details++;
                                $order_details_id=$orders_details['order_details_id'];
                                $order_currency_id=$orders_details['currency_id_FK'];
                                $image=$orders->get_product_image($order_details_id);

                                $amount=$orders_details['amount'];
                                $order_type_name=$orders_details['order_type_name'];

                                $order_currency_symbol=get_currency_symbol($db_conn->get_link(),$order_currency_id);

                            if($orders_details['discount_percentage'!=0]){

                                    $price=$orders_details['price']-($orders_details['price'])*($orders_details['discount_percentage'])/100;
                                }else{
                                    $price=$orders_details['price'];
                            }

                            if($order_currency_id == 1){
                                $amount=$amount*get_currency_rate($db_conn->get_link(),2);
                                $price=$price*get_currency_rate($db_conn->get_link(),2);
                            }else if($order_currency_id == 2){
                                $amount=$amount;
                                $price=$price;
                            }else if($order_currency_id == 3){
                                $amount=$amount/get_currency_rate($db_conn->get_link(),3);
                                $price=$price/get_currency_rate($db_conn->get_link(),3);
                            }

                            $color=$orders_details['color'];
                            if($color == ""){
                                $color="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_COLOR_AVAILABLE)."</i>";
                            }

                            if($orders_details['product_size_id_FK'] != ""){
                                $size_name=$orders->get_size_name($orders_details['product_size_id_FK']);
                            }else{
                                $size_name="<i style='opacity:0.7'>".$dictionary->get_lang($lang,$KEY_NO_SIZE_AVAILABLE)."</i>";
                            }
                            
                            $total_price=$total_price+($price*$orders_details['quantity']);
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
                                                '.$orders_details['title'].'
                                                </td>
                                                <td>'.$order_currency_symbol.''.number_format(Helper::round_price($price),2).'</td>
                                                <td>'.$orders_details['quantity'].'</td>
                                                <td>'.$size_name.'</td>
                                                <td>'.$color.'</td>
                                                </tr>';

                            }

                                            }
                                            
                                            $total_price+=$amount;
                                        
                                            $format.='
                                                <tr>
                                                    <td colspan="7" class="text-end">
                                                        <h5 class="total">'.$order_type_name.': '.$order_currency_symbol.''.number_format(Helper::round_price($amount),2).'</h5>
                                                    </td>
                                                </tr>
                                                    <tr>
                                                    <td colspan="7" class="text-end">
                                                        <h5 class="total">'.$dictionary->get_lang($lang,$KEY_TOTAL_PRICE).': '.$order_currency_symbol.''.number_format(Helper::round_price($total_price),2).'</h5>
                                                    </td>
                                                </tr>
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
                                        </div>
                                        <div class="col-md-12 mt-2">
                                            <div class="form-group">
                                                <label class="mb-0">'.$dictionary->get_lang($lang,$KEY_STATUS).'</label>
                                                <input type="text" class="form-control" value="'.$order_tracking_name.'" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="justify-content: center;">
                    <button type="submit" id="btn_cancel_order" class="btn '.$btn_color.' mb-0 '.$active.'" data-toggle="modal" data-target="#modal-cancel-order-'.$order_id.'">'.$btn_text.'</button>
                    </div>
                </div>
            </div>
        </div>
    </div>';
    $serial_number_order_details=0;
    if($order_tracking_id_FK == 1){
        $format.='
            <div class="modal fade" id="modal-cancel-order-'.$order_id.'" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="direction:ltr !important">
                            <h5 class="modal-title">'.$dictionary->get_lang($lang,$KEY_CANCEL_ORDER).'</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want cancel this order'.$exclamation_mark.'
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-close mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_CLOSE).'</button>
                            <button type="button" class="btn btn-danger" id="btn_cancel_order_'.$order_id.'" onclick=cancel_order('.$order_id.');><i class="bi bi-trash mr-2 ml-2"></i>'.$dictionary->get_lang($lang,$KEY_CANCEL_ORDER).'</button>
                        </div>
                    </div>
                </div>
            </div>
        ';
    }


    }

    }
    echo $format;

?>