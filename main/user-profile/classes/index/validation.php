<?php


class Validation{

    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function check_username($user_id,$username){

        $get_username= "
            SELECT 
                ".TableUsers::$COLUMN_USERNAME." 
            FROM 
                ".TableUsers::$TABLE_NAME."  
            WHERE 
                ".TableUsers::$COLUMN_USER_ID." 
                NOT IN (?) AND ".TableUsers::$COLUMN_USERNAME." = ?
        ";  
        
        $check_username=$this->link->prepare($get_username);
        $check_username->bindParam(1,$user_id);
        $check_username->bindParam(2,$username);
        
        if($check_username->execute()){
            $check_if_exist=$check_username->rowCount();
            if($check_if_exist>0){
                return 0; //used
            }else{
                return 1;
            }
        }

    }

    public function check_phone_number($user_id,$phone_number){

        $prefix = "+961"; 

        if (substr($phone_number, 0, strlen($prefix)) === $prefix) {
            $phone_number = $phone_number;
        } else {
            $phone_number = "+961".$phone_number;
        }

        $query_select="
            SELECT 
                ".TableUsers::$COLUMN_PHONE_NUMBER." 
            FROM 
                ".TableUsers::$TABLE_NAME."  
            WHERE 
                ".TableUsers::$COLUMN_USER_ID." 
                NOT IN (?) AND ".TableUsers::$COLUMN_PHONE_NUMBER."=?
        ";

        $run_query_select=$this->link->prepare($query_select);
        
        $run_query_select->bindParam(1,$user_id);
        $run_query_select->bindParam(2,$phone_number);
        if($run_query_select->execute()){
            $check_if_exist=$run_query_select->rowCount();
            if($check_if_exist>0){
                return 0; //used
            }else{
                return 1;
            }
        }
    }

}


?>
