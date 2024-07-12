<?php

    class Users {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function update_user_data($user_id,$is_verified,$time_zone,$logged_in_date_time){
            $query_update="
                UPDATE
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_IS_VERIFIED." = ?,
                    ".TableUsers::$COLUMN_LOGGED_IN_AT." = ?,
                    ".TableUsers::$COLUMN_TIME_ZONE." = ?
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ? 
            ";
            $run_query_update = $this->link->prepare($query_update);
            $run_query_update->bindParam(1, $is_verified);
            $run_query_update->bindParam(2, $logged_in_date_time);
            $run_query_update->bindParam(3, $time_zone);
            $run_query_update->bindParam(4, $user_id);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        public function update_user_email($user_id,$new_email){
            $query_update="
                UPDATE 
                    ".TableUsers::$TABLE_NAME." 
                SET 
                    ".TableUsers::$COLUMN_EMAIL." = ?,
                    ".TableUsers::$COLUMN_USERNAME." = ?
                WHERE 
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $username=explode("@",$new_email)[0];
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$new_email);
            $run_query_update->bindParam(2,$username);
            $run_query_update->bindParam(3,$user_id);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        public function get_user_data($user_id){
            $query_select="
                SELECT 
                    ".TableUsers::$COLUMN_EMAIL." AS email,
                    ".TableUsers::$COLUMN_USERNAME." AS username
                FROM 
                    ".TableUsers::$TABLE_NAME."
                WHERE 
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetch();
            }else{
                return 0;
            }
        }

    }

    
?>