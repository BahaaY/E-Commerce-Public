<?php
    class Notification
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        function get_notification_status($notification_id){

            $query_insert = "
                SELECT
                    ".TableNotification::$COLUMN_IS_ACTIVE."
                FROM
                    ".TableNotification::$TABLE_NAME."
                WHERE
                    ".TableNotification::$COLUMN_NOTIFICATION_ID." = ?
            ";
            $run_query_insert=$this->link->prepare($query_insert);
            $run_query_insert->bindParam(1,$notification_id);
            if($run_query_insert->execute()){
                return $run_query_insert->fetchColumn();
            }
           
        }

    }

?>
