<?php
class Sales{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function insert_sales($order_id,$product_id,$quantity,$size,$color,$date,$time){
        $query_insert="
            INSERT INTO
                ".TableSales::$TABLE_NAME."
            (
                ".TableSales::$COLUMN_ORDER_ID_FK.",
                ".TableSales::$COLUMN_PRODUCT_ID_FK.",
                ".TableSales::$COLUMN_QUANTITY.",
                ".TableSales::$COLUMN_PRODUCT_SIZE_ID_FK.",
                ".TableSales::$COLUMN_COLOR.",
                ".TableSales::$COLUMN_DATE.",
                ".TableSales::$COLUMN_TIME."
            )
            VALUES
            (
                ?,?,?,?,?,?,?
            )
        ";
        $run_query_insert=$this->link->prepare($query_insert);
        $run_query_insert->bindParam(1,$order_id);
        $run_query_insert->bindParam(2,$product_id);
        $run_query_insert->bindParam(3,$quantity);
        $run_query_insert->bindParam(4,$size);
        $run_query_insert->bindParam(5,$color);
        $run_query_insert->bindParam(6,$date);
        $run_query_insert->bindParam(7,$time);
        if($run_query_insert->execute()){
            return 1;
        }else{
            return 0;
        }
    }

    public function delete_sales($order_id){
        $query_delete="
            DELETE FROM
                ".TableSales::$TABLE_NAME."
            WHERE
                ".TableSales::$COLUMN_ORDER_ID_FK." = ?
        ";
        $run_query_delete=$this->link->prepare($query_delete);
        $run_query_delete->bindParam(1,$order_id);
        if($run_query_delete->execute()){
            return 1;
        }else{
            return 0;
        }
    }

}

?>