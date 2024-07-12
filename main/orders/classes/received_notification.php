<?php
    class ReceivedNotification
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        function insert_notification($user_id,$order_id,$notification_id,$created_at,$updated_at){

            $query_insert = "
                INSERT
                INTO 
                    ".TableReceivedNotification::$TABLE_NAME."
                (
                    ".TableReceivedNotification::$COLUMN_ORDER_ID_FK.",
                    ".TableReceivedNotification::$COLUMN_NOTIFICATION_ID_FK.",
                    ".TableReceivedNotification::$COLUMN_USER_ID_FK.",
                    ".TableReceivedNotification::$COLUMN_CRAETED_AT.",
                    ".TableReceivedNotification::$COLUMN_UPDATED_AT."
                )
                VALUES
                (
                    ?,?,?,?,?
                )
            ";
            $run_query_insert=$this->link->prepare($query_insert);
            $run_query_insert->bindParam(1,$order_id);
            $run_query_insert->bindParam(2,$notification_id);
            $run_query_insert->bindParam(3,$user_id);
            $run_query_insert->bindParam(4,$created_at);
            $run_query_insert->bindParam(5,$updated_at);
            if($run_query_insert->execute()){
                return 1;
            }else{
                return 0;
            }
           
        }

    }

?>
