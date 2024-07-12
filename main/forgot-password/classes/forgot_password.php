<?php

    class ForgotPassword {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function insert_forgot_password($user_id,$email_verification_code,$created_at){
            $query_insert="
                INSERT INTO 
                    ".TableForgotPassword::$TABLE_NAME."(
                    ".TableForgotPassword::$COLUMN_USER_ID_FK.",
                    ".TableForgotPassword::$COLUMN_EMAIL_VERIFICATION_CODE.",
                    ".TableForgotPassword::$COLUMN_CREATED_AT.",
                    ".TableForgotPassword::$COLUMN_UPDATED_AT."
                ) 
                values(?,?,?,?)
            ";
            $run_query_insert=$this->link->prepare($query_insert);
            $run_query_insert->bindParam(1,$user_id);
            $run_query_insert->bindParam(2,$email_verification_code);
            $run_query_insert->bindParam(3,$created_at);
            $run_query_insert->bindParam(4,$created_at);
            if($run_query_insert->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        public function get_date_last_email_verification($user_id){

            $query_select="
                SELECT
                    ".TableForgotPassword::$COLUMN_CREATED_AT."
                FROM
                    ".TableForgotPassword::$TABLE_NAME."
                WHERE
                    ".TableForgotPassword::$COLUMN_USER_ID_FK." = ?
                    AND ".TableForgotPassword::$COLUMN_AVAILABILITY." = 1
                ORDER BY
                    ".TableForgotPassword::$COLUMN_CREATED_AT." DESC
                LIMIT 1
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            if($run_query_select->execute()){
                if($run_query_select->rowCount() > 0){
                    return $run_query_select->fetchColumn();
                }else{
                    return 0;
                }
                
            }else{
                return 0;
            }

        }

    }

    
?>