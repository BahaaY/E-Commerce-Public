<?php

class Products
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_all_products($product_type,$stock){

        $query_select="
            SELECT 
                ".TableProducts::$COLUMN_PRODUCT_ID." AS ".TableProducts::$COLUMN_PRODUCT_ID.",
                ".TableProducts::$COLUMN_TITLE." AS ".TableProducts::$COLUMN_TITLE.",
                ".TableProducts::$COLUMN_STOCK." AS ".TableProducts::$COLUMN_STOCK.",
                (
                    SELECT ".TableProductImages::$COLUMN_IMAGE."
                    FROM 
                        ".TableProductImages::$TABLE_NAME." 
                    WHERE 
                        ".TableProductImages::$COLUMN_PRODUCT_ID_FK." = ".TableProducts::$COLUMN_PRODUCT_ID." LIMIT 1
                ) AS ".TableProductImages::$COLUMN_IMAGE."
            FROM 
                ".TableProducts::$TABLE_NAME." AS ".TableProducts::$TABLE_NAME."
            WHERE 1
            ";
            if($product_type != null){
                $query_select.="
                    AND ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." = ?
                ";
            }
            if($stock != null){
                if($stock == 1){
                    $query_select.="
                        AND ".TableProducts::$COLUMN_STOCK." > 0
                    ";
                }else if($stock == 0){
                    $query_select.="
                        AND ".TableProducts::$COLUMN_STOCK." = 0
                    ";
                }
            }
            $query_select.="
            ORDER BY ".TableProducts::$COLUMN_TITLE." ASC
        ";

        $run_query_select = $this->link->prepare($query_select);
        if($product_type != null){
            $run_query_select->bindParam(1,$product_type);
        }
        if ($run_query_select->execute()) {
            return $run_query_select->fetchAll();
        } else {
            return 0;
        }

    }

    public function get_product_info($product_id){

        $query_select="
            SELECT 
                ".TableProducts::$COLUMN_TITLE." AS ".TableProducts::$COLUMN_TITLE.",
                ".TableProducts::$COLUMN_DESCRIPTION." AS ".TableProducts::$COLUMN_DESCRIPTION.",
                ".TableProducts::$COLUMN_PRICE." AS ".TableProducts::$COLUMN_PRICE.",
                ".TableProducts::$COLUMN_DISCOUNT_PRICE." AS ".TableProducts::$COLUMN_DISCOUNT_PRICE.",
                ".TableProducts::$COLUMN_STOCK." AS ".TableProducts::$COLUMN_STOCK.",
                ".TableProducts::$COLUMN_COLOR." AS ".TableProducts::$COLUMN_COLOR.",
                ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK." AS ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK.",
                ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." AS ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK.",
                ".TableProducts::$COLUMN_AVAILABILITY." AS ".TableProducts::$COLUMN_AVAILABILITY.",
                (
                    SELECT 
                        GROUP_CONCAT(".TableProductImages::$COLUMN_PRODUCT_IMAGES_ID.")
                    FROM 
                        ".TableProductImages::$TABLE_NAME." 
                    WHERE 
                        ".TableProductImages::$COLUMN_PRODUCT_ID_FK." = ".TableProducts::$COLUMN_PRODUCT_ID."
                ) AS ".TableProductImages::$COLUMN_PRODUCT_IMAGES_ID.",
                (
                    SELECT 
                        GROUP_CONCAT(".TableProductImages::$COLUMN_IMAGE.")
                    FROM 
                        ".TableProductImages::$TABLE_NAME." 
                    WHERE 
                        ".TableProductImages::$COLUMN_PRODUCT_ID_FK." = ".TableProducts::$COLUMN_PRODUCT_ID."
                ) AS ".TableProductImages::$COLUMN_IMAGE."    
            FROM 
                ".TableProducts::$TABLE_NAME." AS ".TableProducts::$TABLE_NAME."
            WHERE 
                ".TableProducts::$COLUMN_PRODUCT_ID." = ?
        ";

        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1,$product_id);
        if ($run_query_select->execute()) {
            return $run_query_select->fetch();
        } else {
            return 0;
        }

    }

    public function edit_product($product_id,$title,$description,$price,$discount_price,$stock,$color,$size,$type,$availability,$array_image){

        $query_update="
            UPDATE 
                ".TableProducts::$TABLE_NAME."
            SET
                ".TableProducts::$COLUMN_TITLE." = ?,
                ".TableProducts::$COLUMN_DESCRIPTION." = ?,
                ".TableProducts::$COLUMN_PRICE." = ?,
                ".TableProducts::$COLUMN_DISCOUNT_PRICE." = ?,
                ".TableProducts::$COLUMN_STOCK." = ?,
                ".TableProducts::$COLUMN_COLOR." = ?,
                ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK." = ?,
                ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." = ?,
                ".TableProducts::$COLUMN_AVAILABILITY." = ?
            WHERE 
                ".TableProducts::$COLUMN_PRODUCT_ID." = ?
        ";

        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1,$title);
        $run_query_update->bindParam(2,$description);
        $run_query_update->bindParam(3,$price);
        $run_query_update->bindParam(4,$discount_price);
        $run_query_update->bindParam(5,$stock);
        $run_query_update->bindParam(6,$color);
        $run_query_update->bindParam(7,$size);
        $run_query_update->bindParam(8,$type);
        $run_query_update->bindParam(9,$availability);
        $run_query_update->bindParam(10,$product_id);
        if ($run_query_update->execute()) {
            if(count($array_image) > 0){

                foreach($array_image as $image){

                    $query_insert_image="
                        INSERT 
                        INTO 
                        ".TableProductImages::$TABLE_NAME." (
                            ".TableProductImages::$COLUMN_PRODUCT_ID_FK.",
                            ".TableProductImages::$COLUMN_IMAGE."
                        ) 
                        VALUES (?,?)
                    ";
                    $run_query_insert_image = $this->link->prepare($query_insert_image);
                    $run_query_insert_image->bindParam(1,$product_id);
                    $run_query_insert_image->bindParam(2,$image);
                    $run_query_insert_image->execute();
                }
                return 1;
            }else{
                return 1;
            }
        } else {
            return 0;
        }
        
    }

    public function remove_product($product_id)
    {

        $query_delete_from_cart =
            "
                DELETE FROM 
                    " .
                        TableCart::$TABLE_NAME .
                        " 
                WHERE 
                    ".TableCart::$COLUMN_PRODUCT_ID_FK." =?
        ";
        $run_query_delete_from_cart = $this->link->prepare($query_delete_from_cart);
        $run_query_delete_from_cart->bindParam(1, $product_id);
        if ($run_query_delete_from_cart->execute()) {
            
            $query_delete_from_favourites =
                "
                    DELETE FROM 
                        " .
                            TableFavourites::$TABLE_NAME .
                            " 
                    WHERE 
                        ".TableFavourites::$COLUMN_PRODUCT_ID_FK." =?
            ";
            $run_query_delete_from_favourites = $this->link->prepare($query_delete_from_favourites);
            $run_query_delete_from_favourites->bindParam(1, $product_id);
            if ($run_query_delete_from_favourites->execute()) {

                $path_to_folder_images_product="../../uploaded_products/";
                $query_select_image="
                    SELECT 
                        ".TableProductImages::$COLUMN_IMAGE." as image
                    FROM
                        ".TableProductImages::$TABLE_NAME."
                    WHERE
                        ".TableProductImages::$COLUMN_PRODUCT_ID_FK." = ?
                ";
                $run_query_select_image=$this->link->prepare($query_select_image);
                $run_query_select_image->bindParam(1,$product_id);
                if($run_query_select_image->execute()){
                    $images_name=$run_query_select_image->fetchAll();
                }

                $query_delete_from_product_images =
                    "
                        DELETE FROM 
                            " .
                                TableProductImages::$TABLE_NAME .
                                " 
                        WHERE 
                            ".TableProductImages::$COLUMN_PRODUCT_ID_FK." =?
                ";
                $run_query_delete_from_product_images = $this->link->prepare($query_delete_from_product_images);
                $run_query_delete_from_product_images->bindParam(1, $product_id);
                if ($run_query_delete_from_product_images->execute()) {

                    if($images_name){
                        if(is_dir($path_to_folder_images_product)){
                            foreach($images_name as $image_name){
                                $image_name=$image_name['image'];
                                if(file_exists($path_to_folder_images_product.$image_name)){
                                    unlink($path_to_folder_images_product.$image_name);
                                }
                            }
                        }
                    }

                    $query_delete_from_products =
                        "
                            DELETE FROM 
                                " .
                                    TableProducts::$TABLE_NAME .
                                    " 
                            WHERE 
                                ".TableProducts::$COLUMN_PRODUCT_ID." =?
                    ";
                    $run_query_delete_from_products = $this->link->prepare($query_delete_from_products);
                    $run_query_delete_from_products->bindParam(1, $product_id);
                    if ($run_query_delete_from_products->execute()) {
                        return 1;
                    } else {
                        return 0;
                    }
                    
                }else{
                    return 0;
                }
                
            } else {
                return 0;
            }

        } else {
            return 0;
        }

    }
    
}

?>
