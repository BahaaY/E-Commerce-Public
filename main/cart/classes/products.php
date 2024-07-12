<?php
    class Products
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function update_stock($product_id,$quantity)
        {

            $array_products_id=explode(",",$product_id);
            $array_qty=explode(",",$quantity);

            $query_update="
                UPDATE
                    ".TableProducts::$TABLE_NAME." 
                SET
                    ".TableProducts::$COLUMN_STOCK." = :qty
                WHERE
                    ".TableProducts::$COLUMN_PRODUCT_ID." = :product_id
            ";

            for($i=0;$i<count($array_products_id);$i++){
                
                $product_id=$array_products_id[$i];
                $qty=$array_qty[$i];

                $stock=$this->get_stock($product_id);
                $updated_stock=$stock-$qty;

                $run_query_update = $this->link->prepare($query_update);
                if($run_query_update->execute(array(
                    "qty"=>$updated_stock,
                    ":product_id"=>$product_id
                ))){
                    $check_update=1;
                }else{
                    $check_update=0;
                }
            }

            return $check_update;
           
        }

        public function check_stock($product_id,$quantity){

            $array_products_id=explode(",",$product_id);
            $array_quantity=explode(",",$quantity);

            $query_select="
                SELECT
                    ".TableProducts::$COLUMN_STOCK." AS stock
                FROM
                    ".TableProducts::$TABLE_NAME."
                WHERE
                    ".TableProducts::$COLUMN_PRODUCT_ID." = :product_id
            ";

            for($i=0;$i<count($array_products_id);$i++){
                $product_id=$array_products_id[$i];
                $run_query_select = $this->link->prepare($query_select);
                if($run_query_select->execute(array(
                    ":product_id"=>$product_id
                ))){
                    $stock= $run_query_select->fetchColumn();
                    if($stock >= $array_quantity[$i]){
                        $check_stock=1;
                    }else{
                        $check_stock=0;
                    }
                }else{
                    return 0;
                }
            }

            return $check_stock;

        }

        public function get_stock($product_id){

            $query_select="
                SELECT
                    ".TableProducts::$COLUMN_STOCK." AS stock
                FROM
                    ".TableProducts::$TABLE_NAME."
                WHERE
                    ".TableProducts::$COLUMN_PRODUCT_ID." = :product_id
            ";
            $run_query_select = $this->link->prepare($query_select);
            if($run_query_select->execute(array(
                ":product_id"=>$product_id
            ))){
                return $run_query_select->fetchColumn();
            }else{
                return 0;
            }

        }

    }

?>
