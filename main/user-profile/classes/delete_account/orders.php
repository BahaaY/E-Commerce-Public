<?php

    class orders {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function delete_orders($user_id){
            
            $query_delete="
                DELETE 
                FROM 
                    ".TableOrders::$TABLE_NAME." 
                WHERE
                    ".TableOrders::$COLUMN_USER_ID_FK." = ?
            ";
            $run_query_delete=$this->link->prepare($query_delete);
            $run_query_delete->bindParam(1,$user_id);
            if($run_query_delete->execute()){
                return 1;
            }else{
                return 0;
            }
        }
        
        public function get_orders_id($user_id){
            $query_select="
                SELECT
                    ".TableOrders::$COLUMN_ORDER_ID." AS order_id
                FROM 
                    ".TableOrders::$TABLE_NAME." 
                WHERE
                    ".TableOrders::$COLUMN_USER_ID_FK." = ?
                    AND ".TableOrders::$COLUMN_STATUS." NOT IN (2,3,4)
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetchAll();
            }
        }

    }

    
?>