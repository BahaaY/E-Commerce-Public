<?php

    class TwoStepEmailVerification {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_email_verification_code($user_id){
            $query_select="
                SELECT 
                    ".TableTwoStepEmailVerification::$COLUMN_EMAIL_VERIFICATION_CODE." 
                FROM 
                    ".TableTwoStepEmailVerification::$TABLE_NAME." 
                WHERE 
                    ".TableTwoStepEmailVerification::$COLUMN_USER_ID_FK." = ? 
                    AND ".TableTwoStepEmailVerification::$COLUMN_AVAILABILITY." = 1 
                    AND ".TableTwoStepEmailVerification::$COLUMN_TWO_STEP_EMAIL_VERIFICATION_ID." = (
                        SELECT 
                            MAX(".TableTwoStepEmailVerification::$COLUMN_TWO_STEP_EMAIL_VERIFICATION_ID.")
                        FROM
                            ".TableTwoStepEmailVerification::$TABLE_NAME."
                        WHERE 
                            ".TableTwoStepEmailVerification::$COLUMN_USER_ID_FK." = ? 
                    )
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            $run_query_select->bindParam(2,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetchColumn();
            }
        }

        public function check_availability_verification_code($user_id,$email_verification_code){
            $query_select="
                SELECT 
                    ".TableTwoStepEmailVerification::$COLUMN_AVAILABILITY." 
                FROM 
                    ".TableTwoStepEmailVerification::$TABLE_NAME." 
                WHERE 
                    ".TableTwoStepEmailVerification::$COLUMN_USER_ID_FK." = ? 
                    AND ".TableTwoStepEmailVerification::$COLUMN_EMAIL_VERIFICATION_CODE." = ?
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            $run_query_select->bindParam(2,$email_verification_code);
            if($run_query_select->execute()){
                return $run_query_select->fetchColumn();
            }
        }

        public function update_availability_verification_code($user_id,$email_verification_code){
            $query_update="
                UPDATE 
                    ".TableTwoStepEmailVerification::$TABLE_NAME." 
                SET 
                    ".TableTwoStepEmailVerification::$COLUMN_AVAILABILITY." = 0 
                WHERE 
                    ".TableTwoStepEmailVerification::$COLUMN_USER_ID_FK." = ? 
                AND ".TableTwoStepEmailVerification::$COLUMN_EMAIL_VERIFICATION_CODE." = ?
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$user_id);
            $run_query_update->bindParam(2,$email_verification_code);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        public function insert_email_verification($user_id,$email_verification_code,$created_at){
            $query_insert="
                INSERT INTO 
                    ".TableTwoStepEmailVerification::$TABLE_NAME."
                (
                    ".TableTwoStepEmailVerification::$COLUMN_USER_ID_FK.",
                    ".TableTwoStepEmailVerification::$COLUMN_EMAIL_VERIFICATION_CODE.",
                    ".TableTwoStepEmailVerification::$COLUMN_CREATED_AT.",
                    ".TableTwoStepEmailVerification::$COLUMN_UPDATED_AT."
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
                    ".TableTwoStepEmailVerification::$COLUMN_CREATED_AT."
                FROM
                    ".TableTwoStepEmailVerification::$TABLE_NAME."
                WHERE
                    ".TableTwoStepEmailVerification::$COLUMN_USER_ID_FK." = ?
                    AND ".TableTwoStepEmailVerification::$COLUMN_AVAILABILITY." = 1
                ORDER BY
                    ".TableTwoStepEmailVerification::$COLUMN_CREATED_AT." DESC
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