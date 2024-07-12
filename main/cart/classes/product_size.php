<?php
    class ProductSize
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        function get_product_size($sizes_id){

            $query_select = "
                SELECT
                    ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." AS product_size_id,
                    ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." AS product_size_name
                FROM
                    ".TableProductSize::$TABLE_NAME."
                WHERE
                    ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." IN ($sizes_id)
            ";
            $run_query_select=$this->link->prepare($query_select);
            if($run_query_select->execute()){
                return $run_query_select->fetchAll();
            }
           
        }

    }

?>
