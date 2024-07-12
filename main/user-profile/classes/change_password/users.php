<?php

class Users
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
        require_once '../../../config/helper.php';
    }

    public function check_current_password($user_id,$current_password)
    {
        $query_select ="
            SELECT 
                ".TableUsers::$COLUMN_PASSWORD." 
            FROM 
                ".TableUsers::$TABLE_NAME." 
            WHERE 
                ".TableUsers::$COLUMN_USER_ID."  = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1, $user_id);
        if ($run_query_select->execute()) {
            $hashed_password=$run_query_select->fetchColumn();
            if(Helper::string_verify($current_password,$hashed_password)){
                return 1;
            }else{
                return 0;
            }
        } else {
            return 0;
        }
    }
    
    public function change_password($user_id, $new_password)
    {
        $query_change_password =
            "
            Update 
                " .
                    TableUsers::$TABLE_NAME .
                    " 
            set 
                " .
                    TableUsers::$COLUMN_PASSWORD .
                    " = ?
        WHERE ".TableUsers::$COLUMN_USER_ID." = ?";
        $update_password = $this->link->prepare($query_change_password);
        $update_password->bindParam(1, $new_password);
        $update_password->bindParam(2, $user_id);
        if ($update_password->execute()) {
            return 1;
        } else {
            return 0;
        }
    }
}

?>
