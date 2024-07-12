<?php

    class Users {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_all_users(){
            $query_select="
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID." AS user_id,
                    ".TableUsers::$COLUMN_USERNAME." AS username,
                    ".TableUsers::$COLUMN_EMAIL." AS email,
                    ".TableUsers::$COLUMN_AVAILABILITY." AS availability,
                    ".TableUsers::$COLUMN_LOGIN_LIMIT." AS login_limit
                FROM 
                    ".TableUsers::$TABLE_NAME."
                WHERE
                    ".TableUsers::$COLUMN_PERMISSION." NOT IN (1)
                ORDER BY
                    username ASC
            ";
            $run_query_select=$this->link->prepare($query_select);
            if($run_query_select->execute()){
                return $run_query_select->fetchAll();
            }
        }

        function block_user($userid){
            $query_update="
                UPDATE
                    ".TableUsers::$TABLE_NAME."
                SET
                    ".TableUsers::$COLUMN_AVAILABILITY." = 0
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$userid);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        function unblock_user($userid){
            $query_update="
                UPDATE
                    ".TableUsers::$TABLE_NAME."
                SET
                    ".TableUsers::$COLUMN_AVAILABILITY." = 1
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$userid);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        function update_user($userid,$login_limit){
            $query_update="
                UPDATE
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_LOGIN_LIMIT." = ?
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$login_limit);
            $run_query_update->bindParam(2,$userid);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        function reset_login_limit($userid){
            $query_update="
                UPDATE
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_LOGIN_LIMIT." = 5,
                    ".TableUsers::$COLUMN_LOGIN_BLOCK_COUNT." = 0,
                    ".TableUsers::$COLUMN_LOGIN_BLOCKED_AT." = NULL,
                    ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT." = NULL
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$userid);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

    }

    
?>