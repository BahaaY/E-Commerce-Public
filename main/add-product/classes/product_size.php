<?php

class ProductSize
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_all_product_size($size_type)
    {
        $query_select = "
            SELECT 
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." AS ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID.",
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." AS ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME."
            FROM 
                ".TableProductSize::$TABLE_NAME." 
            WHERE 
                ".TableProductSize::$COLUMN_AVAILABILITY." = 1 
                AND ".TableProductSize::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1,$size_type);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }

    function get_product_size_id($size_name){
            
        $query_select="
            SELECT
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." as product_size_id
            FROM
                ".TableProductSize::$TABLE_NAME."
            WHERE
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." = ?
            LIMIT 1
        ";
        $run_query_select=$this->link->prepare($query_select);
        $run_query_select->bindParam(1,$size_name);
        if($run_query_select->execute()){
            if($run_query_select->rowCount() > 0){
                return $run_query_select->fetchColumn();
            }else{
                return 0;
            }
        }else{
            return 0;
        }

    }
    
}

?>
