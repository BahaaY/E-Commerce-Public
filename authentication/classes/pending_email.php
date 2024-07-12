<?php

    class PendingEmail {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_pending_email_verification_code($user_id){
            $query_select="
                SELECT 
                    ".TablePendingEmail::$COLUMN_EMAIL_VERIFICATION_CODE.",
                    ".TablePendingEmail::$COLUMN_PENDING_EMAIL_ID." 
                FROM 
                    ".TablePendingEmail::$TABLE_NAME." 
                WHERE 
                    ".TablePendingEmail::$COLUMN_USER_ID_FK." = ? 
                    AND ".TablePendingEmail::$COLUMN_AVAILABILITY." = 1 
                    AND ".TablePendingEmail::$COLUMN_PENDING_EMAIL_ID." = (
                        SELECT 
                            MAX(".TablePendingEmail::$COLUMN_PENDING_EMAIL_ID.")
                        FROM
                            ".TablePendingEmail::$TABLE_NAME."
                        WHERE 
                            ".TablePendingEmail::$COLUMN_USER_ID_FK." = ? 
                    )
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            $run_query_select->bindParam(2,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetch();
            }
        }

        public function check_availability_pending_email_verification_code($user_id,$pending_email_verification_code){
            $query_select="
                SELECT 
                    ".TablePendingEmail::$COLUMN_AVAILABILITY." 
                FROM 
                    ".TablePendingEmail::$TABLE_NAME." 
                WHERE 
                    ".TablePendingEmail::$COLUMN_USER_ID_FK." = ? 
                    AND ".TablePendingEmail::$COLUMN_EMAIL_VERIFICATION_CODE." = ?
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            $run_query_select->bindParam(2,$pending_email_verification_code);
            if($run_query_select->execute()){
                return $run_query_select->fetchColumn();
            }
        }

        public function update_availability_pending_email_verification_code($user_id,$pending_email_verification_code){
            $query_update="
                UPDATE 
                    ".TablePendingEmail::$TABLE_NAME." 
                SET 
                    ".TablePendingEmail::$COLUMN_AVAILABILITY." = 0 
                WHERE 
                    ".TablePendingEmail::$COLUMN_USER_ID_FK." = ? 
                AND ".TablePendingEmail::$COLUMN_EMAIL_VERIFICATION_CODE." = ?
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$user_id);
            $run_query_update->bindParam(2,$pending_email_verification_code);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        public function get_pending_email($pending_email_id){
            $query_select="
                SELECT 
                    ".TablePendingEmail::$COLUMN_EMAIL." 
                FROM 
                    ".TablePendingEmail::$TABLE_NAME." 
                WHERE 
                    ".TablePendingEmail::$COLUMN_PENDING_EMAIL_ID." = ? 
                    
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$pending_email_id);
            if($run_query_select->execute()){
                return $run_query_select->fetchColumn();
            }
        }

        public function get_last_email($user_id){
            $query_select="
                SELECT 
                    ".TablePendingEmail::$COLUMN_EMAIL." 
                FROM 
                    ".TablePendingEmail::$TABLE_NAME." 
                WHERE 
                    ".TablePendingEmail::$COLUMN_USER_ID_FK." = ? 
                ORDER BY ".TablePendingEmail::$COLUMN_PENDING_EMAIL_ID." DESC
                LIMIT 1 
            ";
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetchColumn();
            }
        }

        public function insert_email_verification($user_id,$email,$email_verification_code,$created_at){
            $query_insert="
                INSERT INTO 
                    ".TablePendingEmail::$TABLE_NAME."
                (
                    ".TablePendingEmail::$COLUMN_USER_ID_FK.",
                    ".TablePendingEmail::$COLUMN_EMAIL.",
                    ".TablePendingEmail::$COLUMN_EMAIL_VERIFICATION_CODE.",
                    ".TablePendingEmail::$COLUMN_CREATED_AT.",
                    ".TablePendingEmail::$COLUMN_UPDATED_AT."
                ) 
                values(?,?,?,?,?)
            ";
            $run_query_insert=$this->link->prepare($query_insert);
            $run_query_insert->bindParam(1,$user_id);
            $run_query_insert->bindParam(2,$email);
            $run_query_insert->bindParam(3,$email_verification_code);
            $run_query_insert->bindParam(4,$created_at);
            $run_query_insert->bindParam(5,$created_at);
            if($run_query_insert->execute()){
                return 1;
            }else{
                return 0;
            }
        }

        public function get_date_last_email_verification($user_id){

            $query_select="
                SELECT
                    ".TablePendingEmail::$COLUMN_CREATED_AT."
                FROM
                    ".TablePendingEmail::$TABLE_NAME."
                WHERE
                    ".TablePendingEmail::$COLUMN_USER_ID_FK." = ?
                    AND ".TablePendingEmail::$COLUMN_AVAILABILITY." = 1
                ORDER BY
                    ".TablePendingEmail::$COLUMN_CREATED_AT." DESC
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