<?php

class ProductType
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_all_product_type()
    {
        $query_select = "
            SELECT 
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." AS ".TableProductType::$COLUMN_PRODUCT_TYPE_ID.",
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." AS ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME."
            FROM 
                ".TableProductType::$TABLE_NAME." 
            WHERE
                ".TableProductType::$COLUMN_AVAILABILITY." = 1 
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }
    
}

?>
