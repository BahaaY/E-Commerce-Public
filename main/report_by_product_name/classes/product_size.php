<?php

class ProductSize
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_product_size_name($size_id)
    {
        $query_select = "
            SELECT 
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." AS ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME."
            FROM 
                ".TableProductSize::$TABLE_NAME." 
            WHERE  
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1,$size_id);
        if($run_query_select->execute()){
            return $run_query_select->fetchColumn();
        }else{
            return 0;
        }

    }
    
}

?>
