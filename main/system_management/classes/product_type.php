<?php

class ProductType{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    function get_all_product_type(){
        $query_select = "
            SELECT 
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." AS product_type_id,
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." AS product_type_name,
                ".TableProductType::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK." AS product_size_type_id_FK,
                ".TableProductType::$COLUMN_AVAILABILITY." AS availability,
                ".TableProductType::$COLUMN_PRODUCT_AVAILABILITY." AS product_availability
            FROM 
                ".TableProductType::$TABLE_NAME."
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }
    }

    function get_all_product_size_type(){
        $query_select = "
            SELECT 
                ".TableProductSizeType::$COLUMN_PRODUCT_SIZE_TYPE_ID." AS product_size_type_id,
                ".TableProductSizeType::$COLUMN_PRODUCT_SIZE_TYPE_NAME." AS product_size_type_name
            FROM 
                ".TableProductSizeType::$TABLE_NAME."
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }
    }

    function get_all_product_size_type_for_product_size_field(){
        $query_select = "
            SELECT 
                ".TableProductSizeType::$COLUMN_PRODUCT_SIZE_TYPE_ID." AS product_size_type_id,
                ".TableProductSizeType::$COLUMN_PRODUCT_SIZE_TYPE_NAME." AS product_size_type_name
            FROM 
                ".TableProductSizeType::$TABLE_NAME."
            WHERE ".TableProductSizeType::$COLUMN_PRODUCT_SIZE_TYPE_ID." NOT IN (3)
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }
    }

    public function update_product_type($product_type_id,$name,$product_size_type,$availability,$product_availability)
    {
        
        $query_update = "
            UPDATE
                ".TableProductType::$TABLE_NAME."
            SET
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." = ?,
                ".TableProductType::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK." = ?,
                ".TableProductType::$COLUMN_AVAILABILITY." = ?,
                ".TableProductType::$COLUMN_PRODUCT_AVAILABILITY." = ?
            WHERE
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." = ?
        ";
        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1,$name);
        $run_query_update->bindParam(2,$product_size_type);
        $run_query_update->bindParam(3,$availability);
        $run_query_update->bindParam(4,$product_availability);
        $run_query_update->bindParam(5,$product_type_id);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function delete_product_type($product_type_id)
    {
        
        $query_delete = "
            DELETE
            FROM
                ".TableProductType::$TABLE_NAME."
            WHERE
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." = ?
        ";
        $run_query_delete = $this->link->prepare($query_delete);
        $run_query_delete->bindParam(1,$product_type_id);
        if($run_query_delete->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function insert_product_type($name,$size_type,$availability,$product_availability)
    {

        $query_insert = "
            INSERT INTO
                ".TableProductType::$TABLE_NAME."
            (
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME.",
                ".TableProductType::$COLUMN_PRODUCT_SIZE_TYPE_ID_FK.",
                ".TableProductType::$COLUMN_AVAILABILITY.",
                ".TableProductType::$COLUMN_PRODUCT_AVAILABILITY."
            )
            VALUES
            (
                ?,?,?,?
            )
        ";
        $run_query_insert = $this->link->prepare($query_insert);
        $run_query_insert->bindParam(1,$name);
        $run_query_insert->bindParam(2,$size_type);
        $run_query_insert->bindParam(3,$availability);
        $run_query_insert->bindParam(4,$product_availability);
        if($run_query_insert->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    public function get_last_insert_product_type_id()
    {
        $query_select = "
            SELECT 
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID."
            FROM 
                ".TableProductType::$TABLE_NAME." 
            ORDER BY
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." DESC
            LIMIT 1
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchColumn();
        }else{
            return 0;
        }

    }

    function check_update_product_type_name($product_type_id,$product_type_name){
        $query_select = "
            SELECT 
                ".TableProductType::$COLUMN_PRODUCT_TYPE_ID."
            FROM 
                ".TableProductType::$TABLE_NAME." 
            WHERE
                ".TableProductType::$COLUMN_PRODUCT_TYPE_NAME." = ?
                AND ".TableProductType::$COLUMN_PRODUCT_TYPE_ID." != ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1,$product_type_name);
        $run_query_select->bindParam(2,$product_type_id);
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