<?php

    class Users {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_username($user_id){
            $query_select="
                SELECT 
                    ".TableUsers::$COLUMN_USERNAME."
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ? 
                LIMIT 1
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetchColumn();
            }
        }

        public function is_email_exist($email){
            $query_select="
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID."
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE
                    ".TableUsers::$COLUMN_EMAIL." = ?
                LIMIT 1
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$email);
            if($run_query_select->execute()){
                return $run_query_select->fetchColumn();
            }
        }

    }

    
?>