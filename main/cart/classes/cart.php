<?php
    class Cart
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function get_products_in_cart($user_id)
        {
            $query_select = "
                SELECT 
                    ".TableCart::$COLUMN_CART_ID." AS ".TableCart::$COLUMN_CART_ID.",
                    ".TableProducts::$COLUMN_PRODUCT_ID." AS ".TableProducts::$COLUMN_PRODUCT_ID.",
                    ".TableProducts::$COLUMN_TITLE." AS ".TableProducts::$COLUMN_TITLE.",
                    ".TableProducts::$COLUMN_PRICE." AS ".TableProducts::$COLUMN_PRICE.",
                    ".TableProducts::$COLUMN_STOCK." AS ".TableProducts::$COLUMN_STOCK.",
                    ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK." AS ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK.",
                    ".TableProducts::$COLUMN_COLOR." AS ".TableProducts::$COLUMN_COLOR.",
                    ".TableProducts::$COLUMN_DISCOUNT_PRICE." AS ".TableProducts::$COLUMN_DISCOUNT_PRICE.",
                    (
                        SELECT ".TableProductImages::$COLUMN_IMAGE."
                        FROM 
                            ".TableProductImages::$TABLE_NAME." 
                        WHERE 
                            ".TableProductImages::$COLUMN_PRODUCT_ID_FK." = ".TableProducts::$COLUMN_PRODUCT_ID." LIMIT 1
                    ) AS ".TableProductImages::$COLUMN_IMAGE."
                FROM 
                    ".TableCart::$TABLE_NAME." AS ".TableCart::$TABLE_NAME."
                    JOIN ".TableProducts::$TABLE_NAME." AS ".TableProducts::$TABLE_NAME."
                    ON ".TableProducts::$COLUMN_PRODUCT_ID." = ".TableCart::$COLUMN_PRODUCT_ID_FK."
                WHERE
                    ".TableCart::$COLUMN_USER_ID_FK." = ?
                    AND ".TableProducts::$COLUMN_STOCK." > 0    
                ORDER BY ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK." ASC, ".TableProducts::$COLUMN_TITLE." ASC
            ";
            $run_query_select = $this->link->prepare($query_select);
            $run_query_select->bindParam(1, $user_id);
            if ($run_query_select->execute()) {
                return $run_query_select->fetchAll();
            } else {
                return 0;
            }
        }

        function remove_item_from_cart($cart_id){
            $query_delete = "
                DELETE
                FROM 
                    ".TableCart::$TABLE_NAME."
                WHERE
                    ".TableCart::$COLUMN_CART_ID." = ?
            ";
            $run_query_delete = $this->link->prepare($query_delete);
            $run_query_delete->bindParam(1, $cart_id);
            if ($run_query_delete->execute()) {
                return 1;
            } else {
                return 0;
            }
        }

        function remove_carts_after_request_order($carts_id,$user_id){

            $array_carts_id=explode(",",$carts_id);

            $query_delete = "
                DELETE
                FROM 
                    ".TableCart::$TABLE_NAME."
                WHERE
                    ".TableCart::$COLUMN_CART_ID." = :cart_id
                    AND ".TableCart::$COLUMN_USER_ID_FK." = :user_id
            ";
            for($i=0;$i<count($array_carts_id);$i++){
                $cart_id=$array_carts_id[$i];
                $run_query_insert = $this->link->prepare($query_delete);
                $run_query_insert->execute(
                    array(  
                        ':cart_id'=>$cart_id,
                        ':user_id'=>$user_id
                    )
                );
            }    
            return 1;
        }

    }

?>
