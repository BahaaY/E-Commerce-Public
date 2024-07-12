<?php

    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }

    require_once '../config/conn.php';
    require_once '../config/variables.php';
    require_once '../config/helper.php';

    try {
        $conn = new PDO("mysql:host=".DatabaseInfo::$DATABASE_HOSTNAME.";dbname=".DatabaseName::$DATABASE_NAME.";charset=utf8",DatabaseInfo::$DATABASE_USERNAME,DatabaseInfo::$DATABASE_PASSWORD);
    }catch(PDOException $ex) {
        die("Could not connect to database");
    }

    //Permission
    $permission="";
    if(isset($_SESSION[Session::$KEY_EC_USERID])){

        $user_id=$_SESSION[Session::$KEY_EC_USERID];
        $user_id=Helper::decrypt($user_id);

        $query_select_permission="
            SELECT 
                ".TableUsers::$COLUMN_PERMISSION."
            FROM 
                ".TableUsers::$TABLE_NAME."
            WHERE 
                ".TableUsers::$COLUMN_USER_ID." = ?
        ";

        $run_query_select_permission=$conn->prepare($query_select_permission);
        $run_query_select_permission->bindParam(1,$user_id);
        if($run_query_select_permission->execute()){
            $permission=$run_query_select_permission->fetchColumn();  // 0 for users and 1 for admin
        }else{
            $permission=3; //Error occurred
        }
    }else{
        $permission=2; //No user session
    }
    if($permission == 1){
        header("location:dashboard");
    }else{  
        header("location:products");
    }
    

?>