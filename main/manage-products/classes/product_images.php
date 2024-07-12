<?php

class ProductImages
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function delete_product_image($image_id)
    {

        $path_to_folder_images_product="../../uploaded_products/";

        $query_select_image="
            SELECT 
                ".TableProductImages::$COLUMN_IMAGE."
            FROM
                ".TableProductImages::$TABLE_NAME."
            WHERE
                ".TableProductImages::$COLUMN_PRODUCT_IMAGES_ID." = ?
        ";
        $run_query_select_image=$this->link->prepare($query_select_image);
        $run_query_select_image->bindParam(1,$image_id);
        if($run_query_select_image->execute()){
            $image_name=$run_query_select_image->fetchColumn();
            if($image_name != "" || $image_name != NULL){
                if(is_dir($path_to_folder_images_product)){
                    if(file_exists($path_to_folder_images_product.$image_name)){
                        unlink($path_to_folder_images_product.$image_name);
                    }
                }
            }
        }

        $query_delete = "
            DELETE
            FROM  
                ".TableProductImages::$TABLE_NAME."
            WHERE 
                ".TableProductImages::$COLUMN_PRODUCT_IMAGES_ID." = ?
        ";
        $run_query_delete = $this->link->prepare($query_delete);
        $run_query_delete->bindParam(1,$image_id);
        if($run_query_delete->execute()){
            return 1;
        }else{
            return 0;
        }

    }
    
}

?>
