<?php

    function get_all_received_notification($conn,$notification_id,$user_id){
        $query_select="
            SELECT 
                received_notification.".TableReceivedNotification::$COLUMN_ORDER_ID_FK." AS order_id_FK,
                received_notification.".TableReceivedNotification::$COLUMN_IS_ACTIVE." AS is_active,
                received_notification.".TableReceivedNotification::$COLUMN_NOTIFICATION_ID_FK." AS notification_id_FK,
                received_notification.".TableReceivedNotification::$COLUMN_CRAETED_AT." AS datetime,
                orders.".TableOrders::$COLUMN_FULLNAME." AS fullname
            FROM 
                ".TableReceivedNotification::$TABLE_NAME." AS received_notification
            INNER JOIN ".TableOrders::$TABLE_NAME." AS orders ON received_notification.".TableReceivedNotification::$COLUMN_ORDER_ID_FK." = orders.".TableOrders::$COLUMN_ORDER_ID."
            WHERE
                ".TableReceivedNotification::$COLUMN_NOTIFICATION_ID_FK." IN ($notification_id)";
            if($user_id != ""){
                $query_select.=" AND received_notification.".TableReceivedNotification::$COLUMN_USER_ID_FK." = $user_id";
            }
            $query_select.=" ORDER BY 
                received_notification.".TableReceivedNotification::$COLUMN_CRAETED_AT." DESC,
                received_notification.".TableReceivedNotification::$COLUMN_IS_ACTIVE." DESC
        ";

        $run_query_select=$conn->prepare($query_select);
        if($run_query_select->execute()){
           return $run_query_select->fetchAll();
        }
    }

    function get_number_of_notification_active($conn,$notification_id,$user_id){
        $query_select="
            SELECT 
                ".TableReceivedNotification::$COLUMN_RECEIVED_NOTIFICATION_ID."
            FROM 
                ".TableReceivedNotification::$TABLE_NAME."
            WHERE
                ".TableReceivedNotification::$COLUMN_IS_ACTIVE." = 1
                AND ".TableReceivedNotification::$COLUMN_NOTIFICATION_ID_FK." IN ($notification_id)";
            if($user_id != ""){
                $query_select.=" AND ".TableReceivedNotification::$COLUMN_USER_ID_FK." = $user_id";
            }

        $run_query_select=$conn->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->rowCount();
        }else{
            return 0;
        }
    }

    function get_order_refernece_number($conn,$order_id){
        $query_select="
            SELECT 
                ".TableOrders::$COLUMN_REFERENCE_NUMBER."
            FROM 
                ".TableOrders::$TABLE_NAME."
            WHERE
                ".TableOrders::$COLUMN_ORDER_ID." = ?
        ";
    
        $run_query_select=$conn->prepare($query_select);
        $run_query_select->bindParam(1,$order_id);
        if($run_query_select->execute()){
            return $run_query_select->fetchColumn();
        }
    }

?>