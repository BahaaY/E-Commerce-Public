<?php

    class Users {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function reset_user_password($user_id,$new_password){

            require_once '../../config/helper.php';

            $password_hashed=Helper::string_hash($new_password);

            $query_update="
                UPDATE 
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_PASSWORD." = ?,
                    ".TableUsers::$COLUMN_LOGIN_LIMIT." = 5,
                    ".TableUsers::$COLUMN_LOGIN_BLOCK_COUNT." = 0,
                    ".TableUsers::$COLUMN_LOGIN_BLOCKED_AT." = NULL,
                    ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT." = NULL
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ? 
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$password_hashed);
            $run_query_update->bindParam(2,$user_id);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

    }

    
?>