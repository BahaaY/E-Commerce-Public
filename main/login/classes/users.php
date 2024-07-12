<?php

    class Users {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_user_info($email){
            $query_select="
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID.",
                    ".TableUsers::$COLUMN_USERNAME.",
                    ".TableUsers::$COLUMN_PERMISSION.",
                    ".TableUsers::$COLUMN_TWO_STEP_VERIFICATION.",
                    ".TableUsers::$COLUMN_LOGIN_BLOCK_COUNT.",
                    ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT."
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE
                    ".TableUsers::$COLUMN_EMAIL." = ? 
                LIMIT 1
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$email);
            if($run_query_select->execute()){
                return $run_query_select->fetch();
            }
        }

    }

    
?>