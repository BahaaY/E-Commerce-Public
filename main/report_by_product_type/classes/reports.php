<?php
class Reports{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }
    public  function generate_report($product_id,$initial_date,$final_date){
        $query_select="
            SELECT 
                product_type.".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." as product_type_name,
                sum(sales.".TableSales::$COLUMN_QUANTITY.") as quantity,
                sum(quantity*(products.".TableProducts::$COLUMN_PRICE."-(products.".TableProducts::$COLUMN_PRICE."*products.".TableProducts::$COLUMN_DISCOUNT_PRICE."/100))) AS price
            FROM
                ".TableProducts::$TABLE_NAME." AS products
                INNER JOIN ".TableProductType::$TABLE_NAME." AS product_type ON products.".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK."=product_type.".TableProductType::$COLUMN_PRODUCT_TYPE_ID."
                INNER JOIN ".TableSales::$TABLE_NAME." AS sales ON products.".TableProducts::$COLUMN_PRODUCT_ID."=sales.".TableSales::$COLUMN_PRODUCT_ID_FK."
            WHERE
                product_type.".TableProductType::$COLUMN_PRODUCT_TYPE_ID." IN ($product_id)
                AND (sales.".TableSales::$COLUMN_DATE." between '$initial_date' and '$final_date')
                GROUP BY products.".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK."
        ";
        $run_query=$this->link->prepare($query_select);
        if($run_query->execute())
        {
            return $run_query->fetchAll();
        }
    }
}
?>