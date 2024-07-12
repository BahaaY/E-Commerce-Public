<?php

class Currencyy{

    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function update_currency($currency_id,$rate,$availability)
    {

        $query_update = "
            UPDATE
                ".TableCurrency::$TABLE_NAME."
            SET
                ".TableCurrency::$COLUMN_CURRENCY_RATE." = ?,
                ".TableCurrency::$COLUMN_AVAILABILITY." = ?
            WHERE
                ".TableCurrency::$COLUMN_CURRENCY_ID." = ?
        ";
        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1,$rate);
        $run_query_update->bindParam(2,$availability);
        $run_query_update->bindParam(3,$currency_id);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }

    }

}

?>