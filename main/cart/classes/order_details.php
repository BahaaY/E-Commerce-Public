<?php
    class OrderDetails
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function insert_order_details($order_id,$products_id,$quantity,$size,$color)
        {
            $array_products_id=explode(",",$products_id);
            $array_quantity=explode(",",$quantity);
            $array_color=explode(",",$color);
            $array_size=explode(",",$size);
            if(count($array_products_id) == count($array_quantity) && count($array_products_id) == count($array_color) && count($array_products_id) == count($array_size)){
                $query_insert = "
                    INSERT INTO 
                        ".TableOrderDetails::$TABLE_NAME."
                        (
                            ".TableOrderDetails::$COLUMN_ORDER_ID_FK.",
                            ".TableOrderDetails::$COLUMN_PRODUCT_ID_FK.",
                            ".TableOrderDetails::$COLUMN_QUANTITY.",
                            ".TableOrderDetails::$COLUMN_PRODUCT_SIZE_ID_FK.",
                            ".TableOrderDetails::$COLUMN_COLOR."
                        )
                        VALUES
                        (
                            :order_id,:product_id,:quantity,:size,:color
                        )
                ";
                for($i=0;$i<count($array_products_id);$i++){
                    
                    $product_id=$array_products_id[$i];
                    $qty=$array_quantity[$i];
                    $clr=$array_color[$i];
                    $size=$array_size[$i];

                    if($qty == ""){
                        $qty=NULL;
                    }
                    if($clr == ""){
                        $clr=NULL;
                    }
                    if($size == ""){
                        $size=NULL;
                    }

                    $run_query_insert = $this->link->prepare($query_insert);
                    $run_query_insert->execute(
                        array(  
                            ':order_id'=>$order_id,
                            ':product_id'=>$product_id,
                            ':quantity'=>$qty,
                            ':size'=>$size,
                            ':color'=>$clr
                        )
                    );
                }    
                return 1;
            }else{
                return 0;
            }
            
        }

    }

?>
