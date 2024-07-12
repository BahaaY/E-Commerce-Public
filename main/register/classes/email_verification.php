<?php

    class EmailVerification {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function insert_email_verification($user_id,$email_verification_code,$created_at){
            $query_insert="
                INSERT INTO 
                    ".TableEmailVerification::$TABLE_NAME."
                (
                    ".TableEmailVerification::$COLUMN_USER_ID_FK.",
                    ".TableEmailVerification::$COLUMN_EMAIL_VERIFICATION_CODE.",
                    ".TableEmailVerification::$COLUMN_CREATED_AT.",
                    ".TableEmailVerification::$COLUMN_UPDATED_AT."
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

    }

    
?>