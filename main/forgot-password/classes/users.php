<?php
    class Users
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_user_id($email)
        {
            $get_query = "
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID." 
                FROM 
                    ".TableUsers::$TABLE_NAME."  
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
                    AND ".TableUsers::$COLUMN_REGISTRATION_TYPE_ID_FK." = 1
                    AND ".TableUsers::$COLUMN_IS_VERIFIED." = 1
            ";
            $get_user_id = $this->link->prepare($get_query);
            $get_user_id->bindParam(1, $email);
            if ($get_user_id->execute()) {
                return $get_user_id->fetchColumn();
            } else {
                return 0;
            }
        }

        public function is_verified($email)
        {
            $get_query = "
                SELECT 
                    ".TableUsers::$COLUMN_USER_ID." 
                FROM 
                    ".TableUsers::$TABLE_NAME."  
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
                    AND ".TableUsers::$COLUMN_AVAILABILITY." = 1
            ";
            $get_user_id = $this->link->prepare($get_query);
            $get_user_id->bindParam(1, $email);
            if ($get_user_id->execute()) {
                $check=$get_user_id->rowCount();
                if($check == 1){
                    return 1;
                }else{
                    return 0;
                }
            } else {
                return 0;
            }
        }

        function check_login_limit($email){

            $query_select = "
                SELECT 
                    ".TableUsers::$COLUMN_LOGIN_LIMIT." 
                FROM 
                    ".TableUsers::$TABLE_NAME." 
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
            ";
            $run_query_select = $this->link->prepare($query_select);
            $run_query_select->bindParam(1, $email);
            if($run_query_select->execute()){
                $login_limit=$run_query_select->fetchColumn();
                if($login_limit > 0){
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
