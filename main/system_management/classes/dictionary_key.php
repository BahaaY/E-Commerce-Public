<?php

class DictionaryKey{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_all_key()
    {
        $query_select = "
            SELECT 
                ".TableDictionary::$COLUMN_DICTIONARY_ID." AS dictionary_id,
                ".TableDictionary::$COLUMN_EN." AS en,
                ".TableDictionary::$COLUMN_FR." AS fr,
                ".TableDictionary::$COLUMN_AR." AS ar
            FROM 
                ".TableDictionary::$TABLE_NAME." 
            ORDER BY ".TableDictionary::$COLUMN_EN." ASC
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }

    public function update_dictionary($dictionary_id,$en,$fr,$ar)
    {

        $query_update = "
            UPDATE
                ".TableDictionary::$TABLE_NAME."
            SET
                ".TableDictionary::$COLUMN_EN." = ?,
                ".TableDictionary::$COLUMN_FR." = ?,
                ".TableDictionary::$COLUMN_AR." = ?
            WHERE
                ".TableDictionary::$COLUMN_DICTIONARY_ID." = ?
        ";
        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1,$en);
        $run_query_update->bindParam(2,$fr);
        $run_query_update->bindParam(3,$ar);
        $run_query_update->bindParam(4,$dictionary_id);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }

    }

}

?>