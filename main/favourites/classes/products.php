<?php

class Products{

    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_products_in_favourites($user_id) {
      
        $query_select="
            SELECT  
                ".TableProducts::$COLUMN_PRODUCT_ID.",
                ".TableProducts::$COLUMN_TITLE.",
                ".TableProducts::$COLUMN_DESCRIPTION.",
                ".TableProducts::$COLUMN_PRICE.",
                ".TableProducts::$COLUMN_DISCOUNT_PRICE.",
                ".TableProducts::$COLUMN_STOCK.",
                ".TableProducts::$COLUMN_COLOR.",
                ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK.",
                ".TableProducts::$COLUMN_AVAILABILITY.",
                ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK.",
                (
                    SELECT 
                        ".TableProductImages::$COLUMN_IMAGE."
                    FROM 
                        ".TableProductImages::$TABLE_NAME." 
                    WHERE 
                        ".TableProductImages::$COLUMN_PRODUCT_ID_FK." = ".TableProducts::$COLUMN_PRODUCT_ID." LIMIT 1
                ) AS ".TableProductImages::$COLUMN_IMAGE."
            FROM   
                ".TableProducts::$TABLE_NAME." p,
                ".TableFavourites::$TABLE_NAME." f
            WHERE 
                p.".TableProducts::$COLUMN_PRODUCT_ID." = f.".TableFavourites::$COLUMN_PRODUCT_ID_FK." 
                AND f.".TableFavourites::$COLUMN_USER_ID_FK." = ?
            ORDER BY ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." ASC, ".TableProducts::$COLUMN_TITLE." ASC
        ";
    
        $run_query_select=$this->link->prepare($query_select);
        $run_query_select->bindParam(1,$user_id);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }
   
    }

    public function view_product($product_id) {
      
        $query_select="
                SELECT
                    ".TableProducts::$COLUMN_PRODUCT_ID.",".TableProducts::$COLUMN_TITLE.",
                    ".TableProducts::$COLUMN_DESCRIPTION.",
                    ".TableProducts::$COLUMN_PRICE.",
                    ".TableProducts::$COLUMN_DISCOUNT_PRICE.",
                    ".TableProducts::$COLUMN_STOCK.",
                    ".TableProducts::$COLUMN_COLOR.",
                    ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK.",
                    ".TableProducts::$COLUMN_AVAILABILITY."
                FROM
                    ".TableProducts::$TABLE_NAME." WHERE ".TableProducts::$COLUMN_PRODUCT_ID." = ?
        ";
    
        $run_query_select=$this->link->prepare($query_select);
        $run_query_select->bindParam(1,$product_id);
        if($run_query_select->execute()){
            return $run_query_select->fetch();
        }else{
            return 0;
        }
   
    }

    function get_size($product_size_id){

        $query_select="
            SELECT 
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." as size_name
            FROM 
                ".TableProductSize::$TABLE_NAME." 
            WHERE 
                ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." in ($product_size_id)
        ";

        $run_query_select=$this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return 0;
        }

    }

    public function get_number_images($product_id){

        $query_get_images= "select count(*) as nbr_images from ".TableProductImages::$TABLE_NAME." where ".TableProductImages::$COLUMN_PRODUCT_ID_FK."= ?";
          
        $run_query_select=$this->link->prepare($query_get_images);
        $run_query_select->bindParam(1,$product_id);
        if($run_query_select->execute()){
            return $run_query_select->fetchColumn();
        }else{
            return 0;
        }
    }

    public function get_images_product($product_id){
      
        $query_get_images= "select ".TableProductImages::$COLUMN_IMAGE." from ".TableProductImages::$TABLE_NAME." where ".TableProductImages::$COLUMN_PRODUCT_ID_FK."= ?";
       
         $run_query_select=$this->link->prepare($query_get_images);
         $run_query_select->bindParam(1,$product_id);
         if($run_query_select->execute()){
             return $run_query_select->fetchAll();
         }else{
             return 0;
         }
     }

}





















?>