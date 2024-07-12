<?php

    class Order_details {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function delete_order_details($order_id){
            $query_delete="
                DELETE 
                FROM 
                    ".TableOrderDetails::$TABLE_NAME." 
                WHERE
                    ".TableOrderDetails::$COLUMN_ORDER_ID_FK." = ?
            ";
            $run_query_delete=$this->link->prepare($query_delete);
            $run_query_delete->bindParam(1,$order_id);
            if($run_query_delete->execute()){
                return 1;
            }else{
                return 0;
            }
        }

    }

    
?>