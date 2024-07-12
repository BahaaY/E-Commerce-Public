<?php

    function get_all_active_currency($conn){
        $query_select="
            SELECT 
                ".TableCurrency::$COLUMN_CURRENCY_ABBREVIATION." AS currency_abbreviation
            FROM
                ".TableCurrency::$TABLE_NAME."
            WHERE
                ".TableCurrency::$COLUMN_AVAILABILITY." = 1
        ";

        $run_query_select=$conn->prepare($query_select);
        if($run_query_select->execute()){
           return $run_query_select->fetchAll();
        }else{
            return [];
        }
    }

    function get_all_currency($conn){
        $query_select="
            SELECT 
                ".TableCurrency::$COLUMN_CURRENCY_ID." AS currency_id,
                ".TableCurrency::$COLUMN_CURRENCY_ABBREVIATION." AS currency_abbreviation,
                ".TableCurrency::$COLUMN_CURRENCY_RATE." AS currency_rate,
                ".TableCurrency::$COLUMN_AVAILABILITY." AS availability
            FROM
                ".TableCurrency::$TABLE_NAME."
        ";

        $run_query_select=$conn->prepare($query_select);
        if($run_query_select->execute()){
           return $run_query_select->fetchAll();
        }else{
            return [];
        }
    }

    function get_currency_info($conn,$currency_abbreviation){
        $query_select="
            SELECT 
                ".TableCurrency::$COLUMN_CURRENCY_ID." AS currency_id,
                ".TableCurrency::$COLUMN_CURRENCY_SYMBOL." AS currency_symbol
            FROM
                ".TableCurrency::$TABLE_NAME."
            WHERE
                ".TableCurrency::$COLUMN_CURRENCY_ABBREVIATION." LIKE :currency_abbreviation
        ";

        $run_query_select=$conn->prepare($query_select);
        $run_query_select->bindValue(':currency_abbreviation','%'.$currency_abbreviation.'%');
        if($run_query_select->execute()){
           return $run_query_select->fetch();
        }else{
            return [];
        }
    }

    function get_currency_rate($conn,$currency_id){
        $query_select="
            SELECT
                ".TableCurrency::$COLUMN_CURRENCY_RATE." AS currency_rate
            FROM
                ".TableCurrency::$TABLE_NAME."
            WHERE
                ".TableCurrency::$COLUMN_CURRENCY_ID." = ?
        ";

        $run_query_select=$conn->prepare($query_select);
        $run_query_select->bindParam(1,$currency_id);
        if($run_query_select->execute()){
           return $run_query_select->fetchColumn();
        }else{
            return 1500;
        }
    }

    function get_currency_symbol($conn,$currency_id){
        $query_select="
            SELECT
                ".TableCurrency::$COLUMN_CURRENCY_SYMBOL." AS currency_symbol
            FROM
                ".TableCurrency::$TABLE_NAME."
            WHERE
                ".TableCurrency::$COLUMN_CURRENCY_ID." = ?
        ";

        $run_query_select=$conn->prepare($query_select);
        $run_query_select->bindParam(1,$currency_id);
        if($run_query_select->execute()){
           return $run_query_select->fetchColumn();
        }else{
            return "$";
        }
    }

    function get_currency_abbreviation($conn,$currency_id){
        $query_select="
            SELECT
                ".TableCurrency::$COLUMN_CURRENCY_ABBREVIATION." AS currency_abbreviation
            FROM
                ".TableCurrency::$TABLE_NAME."
            WHERE
                ".TableCurrency::$COLUMN_CURRENCY_ID." = ?
        ";

        $run_query_select=$conn->prepare($query_select);
        $run_query_select->bindParam(1,$currency_id);
        if($run_query_select->execute()){
           return $run_query_select->fetchColumn();
        }else{
            return "USD";
        }
    }

?>