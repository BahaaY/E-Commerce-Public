<?php
class Products{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_products() {
        $query_select="
            SELECT 
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." as product_type_id,
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." as product_type_name
            FROM 
                ".TableProductType::$TABLE_NAME."
        ";
        $run_query_select=$this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }

}

?>