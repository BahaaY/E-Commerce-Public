<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    require_once '../../config/conn.php';
    require_once '../../config/variables.php';
    require_once '../../config/helper.php';

    try {
        $conn = new PDO("mysql:host=".DatabaseInfo::$DATABASE_HOSTNAME.";dbname=".DatabaseName::$DATABASE_NAME.";charset=utf8",DatabaseInfo::$DATABASE_USERNAME,DatabaseInfo::$DATABASE_PASSWORD);
    }catch(PDOException $ex) {
        die("Could not connect to database");
    }

    $permission="";
    $registration_type="";
    $is_active="";
    $is_verified="";
    if(isset($_SESSION[Session::$KEY_EC_USERID])){

        $user_id=$_SESSION[Session::$KEY_EC_USERID];
        $user_id=Helper::decrypt($user_id);

        $query_select="
            SELECT 
                ".TableUsers::$COLUMN_PERMISSION." AS permission,
                ".TableUsers::$COLUMN_REGISTRATION_TYPE_ID_FK." AS registration_type,
                ".TableUsers::$COLUMN_AVAILABILITY." AS availability,
                ".TableUsers::$COLUMN_IS_VERIFIED." AS is_verified
            FROM 
                ".TableUsers::$TABLE_NAME."
            WHERE 
                ".TableUsers::$COLUMN_USER_ID." = ?
        ";

        $run_query_select=$conn->prepare($query_select);
        $run_query_select->bindParam(1,$user_id);
        if($run_query_select->execute()){
            $data=$run_query_select->fetch();
            $permission=$data['permission']; // 1 => admin, 0 => user
            $registration_type=$data['registration_type']; //1 => email / 2 => gmail / 3 => facebook
            $is_active=$data['availability']; // 1 => not blocked, 0 => blocked
            $is_verified=$data['is_verified']; // 1 => verified, 0 => not verified
        }else{ //Error occurred
            $permission=3;
            $registration_type=0;
            $is_active=0;
            $is_verified=0;
        }
        
    }else{ //No user session
        $permission=2;
        $registration_type=0;
        $is_active=0;
        $is_verified=0;
    }

    //Permission for displayed buttons in sidebar

    $access_products="";
    $access_cart="";
    $access_saved="";
    $access_profile="";
    $access_login="";
    $access_register="";
    $access_forgot_password="";
    $access_contact="";
    $access_add_product="";
    $access_manage_products="";
    $access_orders="";
    $access_my_orders="";
    $access_reports="";
    $access_system_management="";

    if($permission == 1){ //Admin
        $access_products="d-none";
        $access_cart="d-none";
        $access_saved="d-none";
        $access_profile="";
        $access_login="d-none";
        $access_register="d-none";
        $access_forgot_password="d-none";
        $access_contact="d-none";
        $access_add_product="";
        $access_manage_products="";
        $access_orders="";
        $access_my_orders="d-none";
        $access_reports="";
        $access_system_management="";
    }

    if($permission == 0){  //User
        $access_products="";
        $access_cart="";
        $access_saved="";
        $access_profile="";
        $access_login="d-none";
        $access_register="d-none";
        $access_forgot_password="d-none";
        $access_contact="";
        $access_add_product="d-none";
        $access_manage_products="d-none";
        $access_orders="d-none";
        $access_my_orders="";
        $access_reports="d-none";
        $access_system_management="d-none";
    }

    if($permission == 2 || $permission == 3){  //Not logged in or error occurred
        $access_products="";
        $access_cart="d-none";
        $access_saved="d-none";
        $access_profile="d-none";
        $access_login="";
        $access_register="";
        $access_forgot_password="";
        $access_contact="";
        $access_add_product="d-none";
        $access_manage_products="d-none";
        $access_orders="d-none";
        $access_my_orders="d-none";
        $access_reports="d-none";
        $access_system_management="d-none";
    }

    //Permission for pages

    function permission_dashboard($permission,$is_active,$is_verified){
        if ($permission != 1) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_products($permission,$is_active,$is_verified){
        if ($permission == 1) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_add_product_page($permission,$is_active,$is_verified){
        if($permission == 2){
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }else if($permission == 0 || $permission == 3){
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_cart_page($permission,$is_active,$is_verified){
        if ($permission == 2 || $permission == 1) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_contact_page($permission,$is_active,$is_verified){
        if ($permission == 1) {
            //header("location:../../forbidden");
            echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_favourites_page($permission,$is_active,$is_verified){
        if($permission == 2 || $permission == 1){
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_forgot_password_page($permission,$is_active,$is_verified){
        if($permission != 2){
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_login_page($permission,$is_active,$is_verified){
        if($permission != 2){
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_manage_products_page($permission,$is_active,$is_verified){
        if ($permission == 2) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        } elseif ($permission == 0 || $permission == 3) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_orders_page($permission,$is_active,$is_verified){
        if ($permission == 2) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        } elseif ($permission == 0 || $permission == 3) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_register_page($permission,$is_active,$is_verified){
        if($permission != 2){
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_user_profile_page($permission,$is_active,$is_verified){
        if($permission == 2){
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_my_orders_page($permission,$is_active,$is_verified){
        if ($permission == 2 || $permission == 1) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_reports_page($permission,$is_active,$is_verified){
        if ($permission == 2) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        } elseif ($permission == 0 || $permission == 3) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }

    function permission_system_management_page($permission,$is_active,$is_verified){
        if ($permission == 2) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        } elseif ($permission == 0 || $permission == 3) {
            //header("location:../../forbidden");
            echo "<script>";
            echo "window.location.href='../../forbidden'";
            echo "</script>";
        }
        if($permission == 0 || $permission == 1){ //User logged in
            if($is_active == 0 || $is_verified == 0){
                if(isset($_SESSION[Session::$KEY_EC_USERID]) && isset($_SESSION[Session::$KEY_EC_TOKEN])){
                    unset($_SESSION[Session::$KEY_EC_USERID]);
                    unset($_SESSION[Session::$KEY_EC_TOKEN]);
                }
                echo "<script>";
                echo "window.location.href='../../forbidden'";
                echo "</script>";
            }
        }
    }
    
?>