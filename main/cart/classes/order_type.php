<?php
    class OrderType
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_order_type(){
            $query_select= "
                SELECT 
                    ".TableOrderType::$COLUMN_ORDER_TYPE_ID." AS order_type_id,
                    ".TableOrderType::$COLUMN_ORDER_TYPE_NAME." AS order_type_name,
                    ".TableOrderType::$COLUMN_AMOUNT." AS amount,
                    ".TableOrderType::$COLUMN_CURRENCY_ID_FK." AS currency_id_FK
                FROM 
                    ".TableOrderType::$TABLE_NAME."
                WHERE
                    ".TableOrderType::$COLUMN_AVAILABILITY." = 1
            ";
    
            $run_query_select=$this->link->prepare($query_select);
            if($run_query_select->execute()){
                return $run_query_select->fetchAll();
            }
        }

    }

?>
