<?php

class Users
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }
    public function remove_profile_image($user_id)
    {

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
            $image_name=$run_query_select_image->fetchColumn();
            if($image_name != "" || $image_name != NULL){
                if(is_dir($path_to_folder_images_profile)){
                    if(file_exists($path_to_folder_images_profile.$image_name)){
                        unlink($path_to_folder_images_profile.$image_name);
                    }
                }
            }
        }

        $query_update="
            UPDATE 
                ".TableUsers::$TABLE_NAME."
            SET 
                ".TableUsers::$COLUMN_PROFILE_IMAGE." = NULL
            WHERE
                ".TableUsers::$COLUMN_USER_ID." = ?
        ";
        $run_query_update=$this->link->prepare($query_update);
        $run_query_update->bindParam(1,$user_id);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }
    }
}

?>
