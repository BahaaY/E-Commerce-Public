<?php
    class ReceivedNotification
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        function get_permission($user_id){
        
            $query_select_permission="
                SELECT 
                    ".TableUsers::$COLUMN_PERMISSION."
                FROM 
                    ".TableUsers::$TABLE_NAME."
                WHERE 
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
        
            $run_query_select_permission=$this->link->prepare($query_select_permission);
            $run_query_select_permission->bindParam(1,$user_id);
            if($run_query_select_permission->execute()){
                return $run_query_select_permission->fetchColumn();  // 0 for users and 1 for admin
            }else{
                return 3; //Error occurred
            }

        }

        function clear_notification($notification_id,$user_id){

            $query_update = "
                UPDATE
                    ".TableReceivedNotification::$TABLE_NAME."
                SET
                    ".TableReceivedNotification::$COLUMN_IS_ACTIVE." = 0
                WHERE
                    ".TableReceivedNotification::$COLUMN_NOTIFICATION_ID_FK." IN ($notification_id)";
                if($user_id != ""){
                    $query_update.=" AND ".TableReceivedNotification::$COLUMN_USER_ID_FK." = $user_id";
                }
            
            $run_query_update=$this->link->prepare($query_update);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
           
        }

        function clear_all_notification($notification_id,$user_id){

            $query_update = "
                    DELETE
                    FROM
                        ".TableReceivedNotification::$TABLE_NAME."
                    WHERE
                        ".TableReceivedNotification::$COLUMN_NOTIFICATION_ID_FK." IN ($notification_id)";
                    if($user_id != ""){
                        $query_update.=" AND ".TableReceivedNotification::$COLUMN_USER_ID_FK." = $user_id";
                    }
                
                $run_query_update=$this->link->prepare($query_update);
                if($run_query_update->execute()){
                    return 1;
                }else{
                    return 0;
                }
    
        }

    }

?>
