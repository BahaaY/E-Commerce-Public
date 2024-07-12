<?php
    class Auth
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function check_email($email)
        {
            $get_email = "
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID." 
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE
                    ".TableUsers::$COLUMN_EMAIL." =?
            ";
            $check_email = $this->link->prepare($get_email);
            $check_email->bindParam(1, $email);
            if ($check_email->execute()) {
                $data = $check_email->rowCount();
                if ($data == 0) {
                    return 1;
                } else {
                    return 0;
                }
            }
        }

        public function is_active($email)
        {
            $get_email = "
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID." 
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE
                    ".TableUsers::$COLUMN_EMAIL." =?
                    AND ".TableUsers::$COLUMN_AVAILABILITY." = 1 
            ";
            $check_email = $this->link->prepare($get_email);
            $check_email->bindParam(1, $email);
            if ($check_email->execute()) {
                $data = $check_email->rowCount();
                if ($data == 0) {
                    return 0;
                } else {
                    return 1;
                }
            }
        }

        public function is_verified($email)
        {
            $get_email = "
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID." 
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE
                    ".TableUsers::$COLUMN_EMAIL." =?
                    AND ".TableUsers::$COLUMN_IS_VERIFIED." = 1 
            ";
            $check_email = $this->link->prepare($get_email);
            $check_email->bindParam(1, $email);
            if ($check_email->execute()) {
                $data = $check_email->rowCount();
                if ($data == 0) {
                    return 0;
                } else {
                    return 1;
                }
            }
        }

        public function get_login_available_at($email){

            $query_select = "
                SELECT 
                    ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT." 
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
            ";
            $run_query_select = $this->link->prepare($query_select);
            $run_query_select->bindParam(1, $email);
            if($run_query_select->execute()){
                $login_available_at = $run_query_select->fetchColumn();
            }else{
                $login_available_at = NULL;
            }
    
            return $login_available_at;
    
        }

        public function google_register($username, $email, $password, $registration_type, $is_verified,$country,$logged_in_at)
        {
            $query_insert = "
                INSERT 
                INTO 
                ".TableUsers::$TABLE_NAME." (
                    ".TableUsers::$COLUMN_USERNAME.",
                    ".TableUsers::$COLUMN_EMAIL.",
                    ".TableUsers::$COLUMN_PASSWORD.",
                    ".TableUsers::$COLUMN_REGISTRATION_TYPE_ID_FK.",
                    ".TableUsers::$COLUMN_IS_VERIFIED.",
                    ".TableUsers::$COLUMN_COUNTRY.",
                    ".TableUsers::$COLUMN_LOGGED_IN_AT.",
                    ".TableUsers::$COLUMN_CREATED_AT.",
                    ".TableUsers::$COLUMN_UPDATED_AT."
                    )
                VALUES (?,?,?,?,?,?,?,?,?)
            ";
            $register_user = $this->link->prepare($query_insert);
            $register_user->bindParam(1, $username);
            $register_user->bindParam(2, $email);
            $register_user->bindParam(3, $password);
            $register_user->bindParam(4, $registration_type);
            $register_user->bindParam(5, $is_verified);
            $register_user->bindParam(6, $country);
            $register_user->bindParam(7, $logged_in_at);
            $register_user->bindParam(8, $logged_in_at);
            $register_user->bindParam(9, $logged_in_at);
            if ($register_user->execute()) {
                return 1;
            } else {
                return 0;
            }
        }

        public function get_user_id($email)
        {
            $query_select = "
                SELECT
                    ".TableUsers::$COLUMN_USER_ID."
                FROM
                    ".TableUsers::$TABLE_NAME."
                WHERE
                    ".TableUsers::$COLUMN_EMAIL." = ?
            ";
            $run_query_select = $this->link->prepare($query_select);
            $run_query_select->bindParam(1, $email);
            if ($run_query_select->execute()) {
                return $run_query_select->fetchColumn();
            } else {
                return 0;
            }
        }

        function reset_login_data($email,$logged_in_at){
            $query_update = "
                UPDATE 
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_LOGIN_LIMIT." = 5,
                    ".TableUsers::$COLUMN_LOGIN_BLOCK_COUNT." = 0,
                    ".TableUsers::$COLUMN_LOGIN_BLOCKED_AT." = NULL,
                    ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT." = NULL,
                    ".TableUsers::$COLUMN_LOGGED_IN_AT." = ?
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
            ";
            $run_query_update = $this->link->prepare($query_update);
            $run_query_update->bindParam(1, $logged_in_at);
            $run_query_update->bindParam(2, $email);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

    }

?>
