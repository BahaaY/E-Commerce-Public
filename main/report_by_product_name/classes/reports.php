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
                S.".TableSales::$COLUMN_SALES_ID.",
                P.".TableProducts::$COLUMN_TITLE.",
                S.".TableSales::$COLUMN_QUANTITY.",
                S.".TableSales::$COLUMN_PRODUCT_SIZE_ID_FK.",
                S.".TableSales::$COLUMN_COLOR.",
                S.".TableSales::$COLUMN_DATE.",
                S.".TableSales::$COLUMN_TIME.",
                (P.".TableProducts::$COLUMN_PRICE."-P.".TableProducts::$COLUMN_PRICE."*".TableProducts::$COLUMN_DISCOUNT_PRICE."/100) As Fprice 
            FROM 
                ".TableProducts::$TABLE_NAME." P,
                ".TableSales::$TABLE_NAME." S 
            WHERE 
                S.".TableSales::$COLUMN_PRODUCT_ID_FK."=P.".TableProducts::$COLUMN_PRODUCT_ID." 
                AND (S.".TableSales::$COLUMN_DATE." between ('$initial_date') AND ('$final_date') )  
                AND P.".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." in (".$product_id.") 
            order by S.".TableSales::$COLUMN_DATE." DESC
        ";
        $run_query=$this->link->prepare($query_select);
        if($run_query->execute())
        {
            return $run_query->fetchAll();
        }
    }
}
?>