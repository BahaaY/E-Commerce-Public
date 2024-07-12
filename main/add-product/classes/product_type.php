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

    function get_product_type_id($type_name){
            
        $query_select="
            SELECT
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." as product_type_id
            FROM
                ".TableProductType::$TABLE_NAME."
            WHERE
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." = ?
            LIMIT 1
        ";
        $run_query_select=$this->link->prepare($query_select);
        $run_query_select->bindParam(1,$type_name);
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
