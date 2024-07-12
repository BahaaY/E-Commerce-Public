<?php

class Notification{

    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }
  
    public function get_admin_notification(){

        $query_select= "
            SELECT 
                ".TableNotification::$COLUMN_NOTIFICATION_ID." AS notification_id,
                ".TableNotification::$COLUMN_NOTIFICATION_NAME." AS notification_name,
                ".TableNotification::$COLUMN_NOTIFICATION_DESCRIPTION." AS notification_description,
                ".TableNotification::$COLUMN_IS_ACTIVE." AS is_active
            FROM 
                ".TableNotification::$TABLE_NAME."
        ";

        $run_query_select=$this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }

    }

    public function update_notification($ids,$states){

        $ids=explode(",",$ids);
        $states=explode(",",$states);

        $result=0;

        for($i=0;$i<count($ids);$i++){

            $query_update= "
                UPDATE
                    ".TableNotification::$TABLE_NAME."
                SET
                    ".TableNotification::$COLUMN_IS_ACTIVE." = ?
                WHERE
                    ".TableNotification::$COLUMN_NOTIFICATION_ID." = ?
            ";

            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$states[$i]);
            $run_query_update->bindParam(2,$ids[$i]);
            if($run_query_update->execute()){
                $result = 1;
            }else{
                $result = 0;
            }

        }

        if($result == 1){
            return 1;
        }else{
            return 0;
        }

       
    }
}

?>