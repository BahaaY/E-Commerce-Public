<?php

class ProductSize{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_all_product_size()
    {
        $query_select = "
            SELECT 
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." AS product_size_id,
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." AS product_size_name,
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK." AS product_size_type_id_FK,
                ".TableProductSize::$COLUMN_AVAILABILITY." AS availability
            FROM 
                ".TableProductSize::$TABLE_NAME."
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }

    public function update_product_size($product_size_id,$name,$availability,$product_size_type)
    {
        
        $query_update = "
            UPDATE
                ".TableProductSize::$TABLE_NAME."
            SET
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." = ?,
                ".TableProductSize::$COLUMN_AVAILABILITY." = ?,
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK." = ?
            WHERE
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." = ?
        ";
        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1,$name);
        $run_query_update->bindParam(2,$availability);
        $run_query_update->bindParam(3,$product_size_type);
        $run_query_update->bindParam(4,$product_size_id);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function delete_product_size($product_size_id)
    {
        
        $query_delete = "
            DELETE
            FROM
                ".TableProductSize::$TABLE_NAME."
            WHERE
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." = ?
        ";
        $run_query_delete = $this->link->prepare($query_delete);
        $run_query_delete->bindParam(1,$product_size_id);
        if($run_query_delete->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function insert_product_size($name,$availability,$product_size_type)
    {

        $query_insert = "
            INSERT INTO
                ".TableProductSize::$TABLE_NAME."
            (
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME.",
                ".TableProductSize::$COLUMN_AVAILABILITY.",
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK."
            )
            VALUES
            (
                ?,?,?
            )
        ";
        $run_query_insert = $this->link->prepare($query_insert);
        $run_query_insert->bindParam(1,$name);
        $run_query_insert->bindParam(2,$availability);
        $run_query_insert->bindParam(3,$product_size_type);
        if($run_query_insert->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function get_last_insert_product_size_id()
    {
        $query_select = "
            SELECT 
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID."
            FROM 
                ".TableProductSize::$TABLE_NAME." 
            ORDER BY
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." DESC
            LIMIT 1
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchColumn();
        }else{
            return 0;
        }

    }

    function check_update_product_size_name($product_size_id,$product_size_name){
        $query_select = "
            SELECT 
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID."
            FROM 
                ".TableProductSize::$TABLE_NAME." 
            WHERE
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." = ?
                AND ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." != ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1,$product_size_name);
        $run_query_select->bindParam(2,$product_size_id);
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