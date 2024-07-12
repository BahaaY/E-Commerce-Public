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
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." AS ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME.",
                ".TableProductType::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK." AS ".TableProductType::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK."
            FROM 
                ".TableProductType::$TABLE_NAME." 
            WHERE
                ".TableProductType::$COLUMN_AVAILABILITY." = 1 
            ORDER BY ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." ASC
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }

    public function get_product_size_type($product_type){
        $query_select = "
            SELECT 
                ".TableProductType::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK."
            FROM
                ".TableProductType::$TABLE_NAME." 
            WHERE
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1,$product_type);
        if($run_query_select->execute()){
            return $run_query_select->fetchColumn();
        }else{
            return 0;
        }
    }
    
}

?>
