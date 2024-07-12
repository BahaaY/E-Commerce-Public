<?php

class Products
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function insert_product($title,$description,$price,$discount_price,$stock,$color,$size,$type,$array_image)
    {
        $query_insert_product = "
            INSERT 
            INTO 
            ".TableProducts::$TABLE_NAME." (
                ".TableProducts::$COLUMN_TITLE.",
                ".TableProducts::$COLUMN_DESCRIPTION.",
                ".TableProducts::$COLUMN_PRICE.",
                ".TableProducts::$COLUMN_DISCOUNT_PRICE.",
                ".TableProducts::$COLUMN_STOCK.",
                ".TableProducts::$COLUMN_COLOR.",
                ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK.",
                ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK."
            ) 
            VALUES (?,?,?,?,?,?,?,?)
        ";
        $run_query_insert_product = $this->link->prepare($query_insert_product);
        $run_query_insert_product->bindParam(1,$title);
        $run_query_insert_product->bindParam(2,$description);
        $run_query_insert_product->bindParam(3,$price);
        $run_query_insert_product->bindParam(4,$discount_price);
        $run_query_insert_product->bindParam(5,$stock);
        $run_query_insert_product->bindParam(6,$color);
        $run_query_insert_product->bindParam(7,$size);
        $run_query_insert_product->bindParam(8,$type);
        if($run_query_insert_product->execute()){
            if(count($array_image) > 0){
                $product_id=$this->link->lastInsertId();

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
        }else{
            return 0;
        }

    }
    
    public function insert_products_excel($number_of_products,$array_title,$array_description,$array_price,$array_discount_percentage,$array_stock,$array_color,$array_size,$array_product_type){

        $array_title=explode(",",$array_title);
        $array_description=explode(",",$array_description);
        $array_price=explode(",",$array_price);
        $array_discount_percentage=explode(",",$array_discount_percentage);
        $array_stock=explode(",",$array_stock);
        $array_color=explode(",",$array_color);
        $array_size=explode(",",$array_size);
        $array_product_type=explode(",",$array_product_type);
        $query_insert="
            INSERT INTO 
                ".TableProducts::$TABLE_NAME."
            (
                ".TableProducts::$COLUMN_TITLE.",
                ".TableProducts::$COLUMN_DESCRIPTION.",
                ".TableProducts::$COLUMN_PRICE.",
                ".TableProducts::$COLUMN_DISCOUNT_PRICE.",
                ".TableProducts::$COLUMN_STOCK.",
                ".TableProducts::$COLUMN_COLOR.",
                ".TableProducts::$COLUMN_PRODUCT_SIZE_ID_FK.",
                ".TableProducts::$COLUMN_PRODUCT_TYPE_ID_FK."
            ) 
            VALUES
                (?,?,?,?,?,?,?,?)
        ";
        $is_inserted=false;
        for($i=0;$i<$number_of_products;$i++){
            $title=filter_var(trim($array_title[$i]), FILTER_SANITIZE_STRING);
            $description=filter_var(trim($array_description[$i]), FILTER_SANITIZE_STRING);
            $price=filter_var(trim($array_price[$i]), FILTER_SANITIZE_STRING);
            $discount_percentage=filter_var(trim($array_discount_percentage[$i]), FILTER_SANITIZE_STRING);
            $stock=filter_var(trim($array_stock[$i]), FILTER_SANITIZE_STRING);
            $color=filter_var(str_replace('-',',',trim($array_color[$i])), FILTER_SANITIZE_STRING);
            $size=filter_var(str_replace('-',',',trim($array_size[$i])), FILTER_SANITIZE_STRING);
            $product_type=filter_var(trim($array_product_type[$i]), FILTER_SANITIZE_STRING);
            $run_query=$this->link->prepare($query_insert);
            $run_query->bindParam(1,$title);
            $run_query->bindParam(2,$description);
            $run_query->bindParam(3,$price);
            $run_query->bindParam(4,$discount_percentage);
            $run_query->bindParam(5,$stock);
            $run_query->bindParam(6,$color);
            $run_query->bindParam(7,$size);
            $run_query->bindParam(8,$product_type);
            if($run_query->execute()){
                $is_inserted=true;
            }else{
                $is_inserted=false;
            }
        }

        if($is_inserted){
            return 1;
        }else{
            return 0;
        }
        
    }
    
}

?>
