<?php

    class Users {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
            require_once '../../../config/helper.php';
        }

        public function delete_user($user_id){
            $query_delete="
                DELETE 
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_delete=$this->link->prepare($query_delete);
            $run_query_delete->bindParam(1,$user_id);
            if($run_query_delete->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        public function check_email_password($email, $password, $user_id)
        {

            $query_select = "
                SELECT
                    ".TableUsers::$COLUMN_PASSWORD." AS password
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
                    AND ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_select = $this->link->prepare($query_select);
            $run_query_select->bindParam(1, $email);
            $run_query_select->bindParam(2, $user_id);
            $run_query_select->execute();
            $user = $run_query_select->fetch();

            if ($user) {

                $hashed_password = $user['password'];

                if (Helper::string_verify($password, $hashed_password)) {
                    return 1;
                }else{
                    return 0;
                }

            }else{
                return 0;
            }
        }

    }

    
?>