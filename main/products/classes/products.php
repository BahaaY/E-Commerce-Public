<?php



class Products{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }
    public function get_products($order_by_price, $order_by_stock) {
      
        $query_select="
            SELECT
                products.".TableProducts::$COLUMN_PRODUCT_ID." AS  ".TableProducts::$COLUMN_PRODUCT_ID.",
                products.".TableProducts::$COLUMN_TITLE." AS ".TableProducts::$COLUMN_TITLE.",
                products.".TableProducts::$COLUMN_DESCRIPTION." AS ".TableProducts::$COLUMN_DESCRIPTION.",
                products.".TableProducts::$COLUMN_PRICE." AS ".TableProducts::$COLUMN_PRICE.",
                products.".TableProducts::$COLUMN_DISCOUNT_PRICE." AS ".TableProducts::$COLUMN_DISCOUNT_PRICE.",
                products.".TableProducts::$COLUMN_STOCK." AS ".TableProducts::$COLUMN_STOCK.",
                products.".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." AS ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK.",
                (
                    SELECT 
                        ".TableProductImages::$COLUMN_IMAGE."
                    FROM 
                        ".TableProductImages::$TABLE_NAME." 
                    WHERE 
                        ".TableProductImages::$COLUMN_PRODUCT_ID_FK." = ".TableProducts::$COLUMN_PRODUCT_ID." LIMIT 1
                ) AS ".TableProductImages::$COLUMN_IMAGE."
            FROM
                ".TableProducts::$TABLE_NAME." AS products
            INNER JOIN ".TableProductType::$TABLE_NAME." AS product_type ON products.".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." = product_type.".TableProductType::$COLUMN_PRODUCT_TYPE_ID."
            WHERE
                products.".TableProducts::$COLUMN_AVAILABILITY." = 1
                AND product_type.".TableProductType::$COLUMN_PRODUCT_AVAILABILITY." = 1
            ORDER BY    
            ";
            if($order_by_price != "" || $order_by_price != NULL){
                if($order_by_price == "ASC" || $order_by_price == "DESC"){
                    $order_by_price = $order_by_price;
                }else{
                    $order_by_price = "ASC";
                }
                $query_select.="
                    (products.".TableProducts::$COLUMN_PRICE."-products.".TableProducts::$COLUMN_PRICE."*products.".TableProducts::$COLUMN_DISCOUNT_PRICE."/100) ".$order_by_price."
                ";
            }else if($order_by_stock != "" || $order_by_stock != NULL){
                if($order_by_stock == "ASC" || $order_by_stock == "DESC"){
                    $order_by_stock = $order_by_stock;
                }else{
                    $order_by_stock = "ASC";
                }
                $query_select.="
                    products.".TableProducts::$COLUMN_STOCK." ".$order_by_stock."
                ";
            }else{
                $query_select.="
                    products.".TableProducts::$COLUMN_PRODUCT_ID." DESC, products.".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." ASC, ".TableProducts::$COLUMN_TITLE." ASC
                ";
            }

        $run_query_select=$this->link->prepare($query_select);
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