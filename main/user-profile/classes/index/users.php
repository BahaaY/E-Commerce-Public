<?php

    class Users{

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }
    
        public function get_user_data($user_id){

            $query_select= "
                SELECT 
                    ".TableUsers::$COLUMN_USERNAME." AS username,
                    ".TableUsers::$COLUMN_EMAIL." AS email,
                    ".TableUsers::$COLUMN_COUNTRY." AS country,
                    ".TableUsers::$COLUMN_REGION." AS region,
                    ".TableUsers::$COLUMN_ADDRESS." AS address,
                    ".TableUsers::$COLUMN_PHONE_NUMBER." AS phone_number,
                    ".TableUsers::$COLUMN_TWO_STEP_VERIFICATION." AS two_step_verification,
                    ".TableUsers::$COLUMN_PROFILE_IMAGE." AS profile_image
                FROM 
                    ".TableUsers::$TABLE_NAME."
                WHERE 
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";

            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetch();
            }

        }

        public function update_user_profile($user_id, $username, $country, $region, $address, $phone_number){

            if($phone_number != ""){
                $prefix = "+961"; 

                if (substr($phone_number, 0, strlen($prefix)) === $prefix) {
                    $phone_number = $phone_number;
                } else {
                    $phone_number = "+961".$phone_number;
                }
            }

            $query_update="
                UPDATE 
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_USERNAME."=?,
                    ".TableUsers::$COLUMN_COUNTRY."= ?,
                    ".TableUsers::$COLUMN_REGION."= ?,
                    ".TableUsers::$COLUMN_ADDRESS."= ?,
                    ".TableUsers::$COLUMN_PHONE_NUMBER."=?
                WHERE "
                    .TableUsers::$COLUMN_USER_ID."=?
            ";
            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$username);
            $run_query_update->bindParam(2,$country);
            $run_query_update->bindParam(3,$region);
            $run_query_update->bindParam(4,$address);
            $run_query_update->bindParam(5,$phone_number);
            $run_query_update->bindParam(6,$user_id);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }

        }

        public function update_profile_image($user_id,$image_name){

            $path_to_folder_images_profile="../../uploaded_images_profile/";

            $query_select_image="
                SELECT 
                    ".TableUsers::$COLUMN_PROFILE_IMAGE."
                FROM
                    ".TableUsers::$TABLE_NAME."
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";
            $run_query_select_image=$this->link->prepare($query_select_image);
            $run_query_select_image->bindParam(1,$user_id);
            if($run_query_select_image->execute()){
                $old_image_name=$run_query_select_image->fetchColumn();
                if($old_image_name != "" || $old_image_name != NULL){
                    if(is_dir($path_to_folder_images_profile)){
                        if(file_exists($path_to_folder_images_profile.$old_image_name)){
                            unlink($path_to_folder_images_profile.$old_image_name);
                        }
                    }
                }
            }

            $query_update="
                UPDATE 
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_PROFILE_IMAGE." = ?
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";

            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$image_name);
            $run_query_update->bindParam(2,$user_id);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        
        }

        function update_two_step_verification($user_id,$two_step_verification){
            
            $query_update="
                UPDATE 
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_TWO_STEP_VERIFICATION." = ?
                WHERE
                    ".TableUsers::$COLUMN_USER_ID." = ?
            ";

            $run_query_update=$this->link->prepare($query_update);
            $run_query_update->bindParam(1,$two_step_verification);
            $run_query_update->bindParam(2,$user_id);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }

        }

    }

?>