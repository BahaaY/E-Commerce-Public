<?php
    require_once '../../config/helper.php';
?>

<header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
        <a href="../index.php" class="logo d-flex align-items-center">
            <img class="d-none d-md-block" src="<?php echo WebsiteInfo::$KEY_WEBSITE_LOGO; ?>" alt="">
            <span class="d-none d-md-block"><?php echo WebsiteInfo::$KEY_WEBSITE_NAME; ?></span>
        </a>
        <i class="bi bi-list toggle-sidebar-btn p-0 m-0"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <?php
            if($permission != 1){
            ?>

                <li class="nav-item dropdown pr-2" style="margin-right:9px">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <span class="dropdown-toggle ps-2"><?php echo $dictionary->get_lang($lang,$KEY_CURRENCY);?></span>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile pt-0 mt-0">

                        <?php
                            $all_active_currency=get_all_active_currency($db_conn->get_link());
                            if(count($all_active_currency)){
                                $nb_currency=0;
                                foreach ($all_active_currency as $currency_info) {
                                    $nb_currency++;
                                    $currency_abbreviation=$currency_info['currency_abbreviation'];
                                    if(isset($_SESSION[Session::$KEY_EC_CURRENCY])) {
                                        $active_currency=$_SESSION[Session::$KEY_EC_CURRENCY];
                                    }else{
                                        $active_currency=Currency::$KEY_EC_DEFAULT_ACTIVE_CURRENCY;
                                    }
                                    if($active_currency==$currency_abbreviation){
                                        $active_currency_style="background-color:lightblue";
                                    }else{
                                        $active_currency_style="";
                                    }
                                    echo '
                                        <li>
                                            <a class="dropdown-item d-flex align-items-center" style="'.$active_currency_style.'" href="?currency='.$currency_abbreviation.'">
                                                <span>'.$currency_abbreviation.'</span>
                                            </a>
                                        </li>
                                    ';
                                    if($nb_currency != count($all_active_currency)){
                                        echo '
                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>
                                        ';
                                    }
                                }
                            }
                        ?>

                    </ul>
                </li>

            <?php
            }
            ?>

            <li class="nav-item dropdown pr-2" style="margin-right:13px">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <span class="dropdown-toggle ps-2"><?php echo $dictionary->get_lang($lang,$KEY_LANGUAGES);  ?></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile pt-0 mt-0">

                    <?php
                        if(isset($_SESSION[Session::$KEY_EC_LANG])) {
                            $lang=$_SESSION[Session::$KEY_EC_LANG];
                        }else {
                            $lang="en";
                        }
                        if($lang == "en"){
                            $active_en="background-color:lightblue";
                        }else{
                            $active_en="";
                        }

                        if($lang == "fr"){
                            $active_fr="background-color:lightblue";
                        }else{
                            $active_fr="";
                        }

                        if($lang == "ar"){
                            $active_ar="background-color:lightblue";
                        }else{
                            $active_ar="";
                        }
                    ?>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" style="<?php echo $active_en ?>" href="?lang=en">
                            <span><?php echo $dictionary->get_lang($lang,$KEY_ENGLISH);  ?></span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" style="<?php echo $active_fr ?>" href="?lang=fr">
                            <span><?php echo $dictionary->get_lang($lang,$KEY_FRENCH);  ?></span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" style="<?php echo $active_ar ?>" href="?lang=ar">
                            <span><?php echo $dictionary->get_lang($lang,$KEY_ARABIC);  ?></span>
                        </a>
                    </li>

                </ul>
            </li>

            <?php

                $row="";

                if($permission == 1){
                    $notification_id="1,2";
                    $user_id="";
                }else{
                    $notification_id="3";
                    if(isset($_SESSION[Session::$KEY_EC_USERID])){
                        $user_id=$_SESSION[Session::$KEY_EC_USERID];
                        $user_id=Helper::decrypt($user_id);
                    }else{
                        $user_id="";
                    }
                }

                    $number_of_notification_active=get_number_of_notification_active($db_conn->get_link(),$notification_id,$user_id);
                    if($number_of_notification_active == 0){
                        $hide_notification_number="d-none";
                    }else{
                        $hide_notification_number="";
                    }
                    $received_notification=get_all_received_notification($db_conn->get_link(),$notification_id,$user_id);
                    if(count($received_notification) > 0){
                        $hr='
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                        ';
                        $dropdown_footer='
                            <li class="dropdown-footer">
                                <a role="button" id="btn_show_all_notifications" onclick="btn_show_all_notifications();">'.$dictionary->get_lang($lang,$KEY_SHOW_ALL_NOTIFICATIONS).'</a>
                            </li>
                        ';
                        $btn_clear='
                            <a role="button" onclick="open_modal_ask_clear_all_notifications();"><span class="badge rounded-pill bg-primary p-2 ms-2">'.$dictionary->get_lang($lang,$KEY_CLEAR_ALL_NOTIFICATIONS).'</span></a>
                        ';
                    }else{
                        $hr='';
                        $dropdown_footer='';
                        $btn_clear='';
                    }

                    $row.='
                        <li class="nav-item dropdown '.$access_profile.'">
                            <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" id="icon_open_notification">
                                <i class="bi bi-bell"></i>
                                <span class="badge bg-primary badge-number '.$hide_notification_number.'" id="notification_number">'.$number_of_notification_active.'</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications p-0" id="dropdown_menu_notification">
                                <li class="dropdown-header">
                                    '.$dictionary->get_lang($lang,$KEY_YOU_HAVE).' <span id="text_notification_number">'.$number_of_notification_active.'</span> '.$dictionary->get_lang($lang,$KEY_NEW_NOTIFICATIONS).'
                                    '.$btn_clear.'
                                </li>
                                '.$hr.'
                               
                    ';
                    if($received_notification){
                        
                        foreach($received_notification as $notification){
                            $order_id=$notification['order_id_FK'];
                            $is_active_notification=$notification['is_active'];
                            $notification_id=$notification['notification_id_FK'];
                            $created_datetime=$notification['datetime'];
                            $fullname=$notification['fullname'];
                            $created_datetime=new DateTime($created_datetime);

                            $order_reference_number=get_order_refernece_number($db_conn->get_link(),$order_id);
                            if($order_reference_number == "" || $order_reference_number == "undefined"){
                                $order_reference_number="";
                            }

                            $dateTime = new DateTime();
                            $current_datetime = $dateTime->format('Y-m-d H:i:s');
                            $current_datetime=new DateTime($current_datetime);
                            $interval = $created_datetime->diff($current_datetime);
                            $totalDays = $interval->days;
                            $remainingHours = $interval->h;
                            if ($remainingHours > 24) {
                                $totalDays++;
                                $remainingHours -= 24;
                            }
                            $remainingMinutes = $interval->i;
                            $remainingSeconds = $interval->s;
                            if($current_datetime > $created_datetime){

                                if($totalDays == 1){
                                    $days_text="day";
                                }else{
                                    $days_text="days";
                                }
                                if($remainingHours == 1){
                                    $hours_text="hour";
                                }else{
                                    $hours_text="hours";
                                }
                                if($remainingHours == 1){
                                    $minutes_text="minute";
                                }else{
                                    $minutes_text="minutes";
                                }
                
                                if ($totalDays > 0) {
                                    if($remainingHours > 0){
                                        $formattedDate = $totalDays . " ".$days_text." and ". $remainingHours . " ".$hours_text."";
                                    }else{
                                        $formattedDate = $totalDays . " ".$days_text." and ". $remainingMinutes . " ".$minutes_text."";
                                    }
                                }else{
                                    if($remainingHours > 0){
                                        if($remainingMinutes > 0){
                                            $formattedDate = $remainingHours . " ".$hours_text." and ". $remainingMinutes . " ".$minutes_text."";
                                        }else{
                                            $formattedDate = $remainingHours . " ".$hours_text."";
                                        }
                                    }else{
                                        if($remainingMinutes > 0){
                                            $formattedDate = $remainingMinutes . " ".$minutes_text."";
                                        }else{
                                            $formattedDate = $remainingSeconds . " seconds";
                                        }
                                        
                                    }
                                }

                            }else{
                                $formattedDate="0 second";
                            }

                            if($notification_id == 1){
                                $icon_color="text-primary";
                                $title="<b>".$fullname."</b> requested an order";
                                $subject="We wanted to inform you that a new order with Ref. Number ".$order_reference_number." has been placed on our orders section.";
                            }else if($notification_id == 2){
                                $icon_color="text-danger";
                                $title="<b>".$fullname."</b> cancelled an order";
                                $subject="We wanted to inform you that a order with Ref. Number ".$order_reference_number." has been canceled.";
                            }else if($notification_id == 3){
                                $icon_color="text-success";
                                $title="Status order updated";
                                $subject="We wanted to inform you that a order status with Ref. Number ".$order_reference_number." has been updated.";
                            }

                            if($is_active_notification == 1){
                                $bg_color="bg-light";
                            }else{
                                $bg_color="";
                            }
                            
                            $row.='
                                <li class="notification-item '.$bg_color.'" id="li_container_notification_text" onclick=copy_reference_number("'.$order_reference_number.'");>
                                    <i class="bi bi-bell '.$icon_color.'"></i>
                                    <div style="cursor:pointer">
                                        <h4>'.$title.'</h4>
                                        <p>'.$subject.'</p>
                                        <p>'.$formattedDate.' ago.</p>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                            ';
                        }
                    }
                    //$row.=$dropdown_footer;
                    $row.='
                            </ul>
                        </li>
                    ';
                

                echo $row;

            ?>

            <li class="nav-item dropdown pr-4 <?php echo $access_profile ?>">

                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <?php
                        // if($permission == 0){
                        //     echo '
                        //         <img src="'.$profile_image.'" id="header_image_profile" alt="Profile" class="rounded-circle">
                        //     ';
                        // }
                        echo '
                            <img src="'.$profile_image.'" id="header_image_profile" alt="Profile" class="rounded-circle">
                        ';
                    ?>
                    <span class="d-none d-md-block dropdown-toggle ps-2" id="div_header_username"><?php echo $username ?></span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6 id="div_header_menu_username"><?php echo $username ?></h6>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <?php
                        $text="";
                        $icon="";
                        if($permission == 1){
                            $text=$dictionary->get_lang($lang,$KEY_SETTINGS);
                            $icon="bi bi-gear";
                        }else{
                            $text=$dictionary->get_lang($lang,$KEY_MY_PROFILE);
                            $icon="bi bi-person";
                        }
                    ?>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="../user-profile/">
                            <i class="<?php echo $icon; ?>"></i>
                            <span><?php echo $text; ?></span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <?php if($permission == 0){ ?>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="../contact/">
                            <i class="bi bi-envelope"></i>
                            <span><?php echo $dictionary->get_lang($lang,$KEY_CONTACT); ?></span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    
                    <?php } ?>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" role="button" data-toggle="modal"
                            data-target="#modal-sign-out">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>
                                <?php echo $dictionary->get_lang($lang,$KEY_SIGN_OUT); ?>
                            </span>
                        </a>
                    </li>

                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header><!-- End Header -->

<div aria-live="polite" aria-atomic="true" class="container_toast_copy_reference_number" id="toast_copy_reference_number">
    <div class="toast toast_copy_reference_number" style="position: fixed; bottom: 20px; right: 20px;z-index:100">
        <div class="toast-header">
            <strong class="mr-auto" id="title_toast_copy_reference_number">

            </strong>
        </div>
        <div class="toast-body" id="body_toast_copy_reference_number">
                        
        </div>
    </div>
</div>

<div class="modal fade" id="modal-ask-clear-all-notifications" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Clear Notifications</h5>
                <button type="button" class="close" onclick="close_modal_ask_clear_all_notifications();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want clear all your notifications?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="close_modal_ask_clear_all_notifications();"><i class='fa fa-close mr-2'></i>Close</button>
                <form method="post">
                    <button type="submit" class="btn btn-danger" onclick="clear_all_notifications();" id="btn_clear_all_notifications"><i class='bi bi-trash mr-2'></i>Clear</button>
                </form>
            </div>
        </div>
    </div>
</div>

