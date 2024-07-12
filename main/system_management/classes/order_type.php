<?php

class OrderType{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_all_order_type()
    {
        $query_select = "
            SELECT 
                ".TableOrderType::$COLUMN_ORDER_TYPE_ID." AS order_type_id,
                ".TableOrderType::$COLUMN_ORDER_TYPE_NAME." AS order_type_name,
                ".TableOrderType::$COLUMN_AMOUNT." AS amount,
                ".TableOrderType::$COLUMN_AVAILABILITY." AS availability
            FROM 
                ".TableOrderType::$TABLE_NAME." 
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }

    public function update_order_type($order_type_id,$name,$amount,$availability)
    {

        $query_update = "
            UPDATE
                ".TableOrderType::$TABLE_NAME."
            SET
                ".TableOrderType::$COLUMN_ORDER_TYPE_NAME." = ?,
                ".TableOrderType::$COLUMN_AMOUNT." = ?,
                ".TableOrderType::$COLUMN_AVAILABILITY." = ?
            WHERE
                ".TableOrderType::$COLUMN_ORDER_TYPE_ID." = ?
        ";
        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1,$name);
        $run_query_update->bindParam(2,$amount);
        $run_query_update->bindParam(3,$availability);
        $run_query_update->bindParam(4,$order_type_id);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function delete_order_type($order_type_id)
    {
        
        $query_delete = "
            DELETE
            FROM
                ".TableOrderType::$TABLE_NAME."
            WHERE
                ".TableOrderType::$COLUMN_ORDER_TYPE_ID." = ?
        ";
        $run_query_delete = $this->link->prepare($query_delete);
        $run_query_delete->bindParam(1,$order_type_id);
        if($run_query_delete->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function insert_order_type($name,$amount,$availability)
    {

        $query_insert = "
            INSERT INTO
                ".TableOrderType::$TABLE_NAME."
            (
                ".TableOrderType::$COLUMN_ORDER_TYPE_NAME.",
                ".TableOrderType::$COLUMN_AMOUNT.",
                ".TableOrderType::$COLUMN_AVAILABILITY."
            )
            VALUES
            (
                ?,?,?
            )
        ";
        $run_query_insert = $this->link->prepare($query_insert);
        $run_query_insert->bindParam(1,$name);
        $run_query_insert->bindParam(2,$amount);
        $run_query_insert->bindParam(3,$availability);
        if($run_query_insert->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function get_last_insert_order_type_id()
    {
        $query_select = "
            SELECT 
                ".TableOrderType::$COLUMN_ORDER_TYPE_ID."
            FROM 
                ".TableOrderType::$TABLE_NAME." 
            ORDER BY
                ".TableOrderType::$COLUMN_ORDER_TYPE_ID." DESC
            LIMIT 1
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchColumn();
        }else{
            return 0;
        }

    }

    function check_update_order_type_name($order_type_id,$order_type_name){
        $query_select = "
            SELECT 
                ".TableOrderType::$COLUMN_ORDER_TYPE_ID."
            FROM 
                ".TableOrderType::$TABLE_NAME." 
            WHERE
                ".TableOrderType::$COLUMN_ORDER_TYPE_NAME." = ?
                AND ".TableOrderType::$COLUMN_ORDER_TYPE_ID." != ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1,$order_type_name);
        $run_query_select->bindParam(2,$order_type_id);
        if($run_query_select->execute()){
            $nb_rows=$run_query_select->rowCount();
            if($nb_rows == 0){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }
    }

}

?>