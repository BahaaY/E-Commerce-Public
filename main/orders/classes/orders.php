<?php
class Orders{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }
public function get_total_price ($order_id){


$query_get_total="
    SELECT 
    CEIL(sum((products.".TableProducts::$COLUMN_PRICE."-products.".TableProducts::$COLUMN_PRICE."*(products.".TableProducts::$COLUMN_DISCOUNT_PRICE."/100))*order_details.".TableOrderDetails::$COLUMN_QUANTITY.")) as Total 
    FROM 
                    ".TableOrderDetails::$TABLE_NAME." AS order_details,".TableProducts::$TABLE_NAME." AS products 
                    where order_details.".TableOrderDetails::$COLUMN_PRODUCT_ID_FK."=products.".TableProducts::$COLUMN_PRODUCT_ID." 
                    and
                    order_details.".TableOrderDetails::$COLUMN_ORDER_ID_FK."=?
                    GROUP BY
                    order_details.".TableOrderDetails::$COLUMN_ORDER_ID_FK."";
$run_query=$this->link->prepare($query_get_total);
                    $run_query->bindParam(1,$order_id);
                    if($run_query->execute()){
                        return $run_query->fetchColumn();
                    }else{
                        return 0;
                    }

}

function get_size_name($size_id){
    $query_select="
        SELECT
            ".TableProductSize::$COLUMN_PRODUCT_SIZE_NAME." AS product_size_name
        FROM 
            ".TableProductSize::$TABLE_NAME."
        WHERE
            ".TableProductSize::$COLUMN_PRODUCT_SIZE_ID." = ?
    ";
    $run_query_select = $this->link->prepare($query_select);
    $run_query_select->bindParam(1,$size_id);
    if ($run_query_select->execute()) {
        return $run_query_select->fetchColumn();
    }
}

    public function update_status_order($order_id,$status){


  $query_update="UPDATE ".TableOrders::$TABLE_NAME." SET ".TableOrders::$COLUMN_ORDER_TRACKING_ID_FK."=? WHERE ".TableOrders::$COLUMN_ORDER_ID."=?";
                
   $run_query=$this->link->prepare($query_update);
                $run_query->bindParam(1,$status);
                $run_query->bindParam(2,$order_id);
                if($run_query->execute()){
                    return 1;

                }else{

                    return 0;
                }


}
    public function get_orders(){
    $query_get_orders="select o.".TableOrders::$COLUMN_ORDER_ID.",
                            o.".TableOrders::$COLUMN_REFERENCE_NUMBER.",
                            o.".TableOrders::$COLUMN_FULLNAME.",
                            o.".TableOrders::$COLUMN_USERNAME.",
                            o.".TableOrders::$COLUMN_TRACKING_NUMBER.",
                            o.".TableOrders::$COLUMN_EMAIL.",
                            o.".TableOrders::$COLUMN_PHONE_NUMBER.",
                            o.".TableOrders::$COLUMN_COUNTRY.",
                            o.".TableOrders::$COLUMN_REGION.",
                            o.".TableOrders::$COLUMN_ADDRESS.",
                            o.".TableOrders::$COLUMN_DATE.",
                            o.".TableOrders::$COLUMN_ORDER_TRACKING_ID_FK.",
                            order_tracking.".TableOrderTracking::$COLUMN_ORDER_TRACKING_NAME.",
                            order_tracking.".TableOrderTracking::$COLUMN_DISPLAYED_TEXT_COLOR.",
                            o.".TableOrders::$COLUMN_CURRENCY_ID_FK.",
                            o.".TableOrders::$COLUMN_ORDER_TYPE_ID_FK.",
                            ot.".TableOrderType::$COLUMN_ORDER_TYPE_NAME.",
                            ot.".TableOrderType::$COLUMN_AMOUNT."
                            FROM "
                               .TableOrders::$TABLE_NAME." o
                               LEFT JOIN ".TableOrderType::$TABLE_NAME." as ot ON o.".TableOrders::$COLUMN_ORDER_TYPE_ID_FK." = ot.".TableOrderType::$COLUMN_ORDER_TYPE_ID."
                                INNER JOIN ".TableOrderTracking::$TABLE_NAME." as order_tracking on order_tracking.".TableOrderTracking::$COLUMN_ORDER_TRACKING_ID." = o.".TableOrders::$COLUMN_ORDER_TRACKING_ID_FK."
                               ORDER BY o.".TableOrders::$COLUMN_ORDER_TRACKING_ID_FK." ASC, o.".TableOrders::$COLUMN_FULLNAME." ASC";

   $run_query=$this->link->prepare($query_get_orders);

                    if($run_query->execute()){
                        return $run_query->fetchAll();
                    }else{
                        return 0;
                    }
    }

public function get_details_order($order_id){

$query_get_orders_details="select 
                        od.".TableOrderDetails::$COLUMN_ORDER_DETAILS_ID."
                        ,od.".TableOrderDetails::$COLUMN_QUANTITY."
                        ,od.".TableOrderDetails::$COLUMN_COLOR."
                        ,od.".TableOrderDetails::$COLUMN_PRODUCT_ID_FK."
                        ,od.".TableOrderDetails::$COLUMN_PRODUCT_SIZE_ID_FK."
                        ,od.".TableOrderDetails::$COLUMN_COLOR."
                        ,p.".TableProducts::$COLUMN_PRICE."
                        ,p.".TableProducts::$COLUMN_TITLE."
                        ,p.".TableProducts::$COLUMN_DISCOUNT_PRICE."
                        ,ot.".TableOrderType::$COLUMN_AMOUNT."
                        ,ot.".TableOrderType::$COLUMN_ORDER_TYPE_NAME."

                        ,o.".TableOrders::$COLUMN_TRACKING_NUMBER."
                        ,o.".TableOrders::$COLUMN_PHONE_NUMBER."
                        ,o.".TableOrders::$COLUMN_ADDRESS."
                        ,o.".TableOrders::$COLUMN_COUNTRY."
                        ,o.".TableOrders::$COLUMN_REGION."
                        ,o.".TableOrders::$COLUMN_EMAIL."
                        ,o.".TableOrders::$COLUMN_FULLNAME."
                        ,o.".TableOrders::$COLUMN_CURRENCY_ID_FK."

                        from 
                        ".TableOrderDetails::$TABLE_NAME." 
                        od,
                        ".TableProducts::$TABLE_NAME." 
                        p ,
                        ".TableOrderType::$TABLE_NAME." 
                        ot,
                        ".TableOrders::$TABLE_NAME." 
                        o
                        WHERE
                        o.".TableOrders::$COLUMN_ORDER_ID."=od.".TableOrderDetails::$COLUMN_ORDER_ID_FK."
                        and
                        od.".TableOrderDetails::$COLUMN_PRODUCT_ID_FK."=p.".TableProducts::$COLUMN_PRODUCT_ID."
                        and 
                        o.".TableOrders::$COLUMN_ORDER_TYPE_ID_FK." = ot.".TableOrderType::$COLUMN_ORDER_TYPE_ID."
                        and
                        od.".TableOrderDetails::$COLUMN_ORDER_ID_FK."=?";

$run_query=$this->link->prepare($query_get_orders_details);
                    $run_query->bindParam(1,$order_id);
                    if($run_query->execute()){
                        return $run_query->fetchAll();
                    }else{
                        return 0;
                    }

}

public function check_if_in_sales($order_id){
    $query_select="
        SELECT 
            ".TableSales::$COLUMN_SALES_ID."
        FROM
            ".TableSales::$TABLE_NAME."
        WHERE
            ".TableSales::$COLUMN_ORDER_ID_FK." = ?
    ";
    $run_query_select=$this->link->prepare($query_select);
    $run_query_select->bindParam(1,$order_id);
    if($run_query_select->execute()){
        return $run_query_select->rowCount();
    }else{
        return 0;
    }
}

function get_product_image($order_details_id){
    $query_select="
        SELECT
            ".TableProductImages::$COLUMN_IMAGE." AS ".TableProductImages::$COLUMN_IMAGE."
        FROM 
            ".TableProductImages::$TABLE_NAME."
            INNER JOIN ".TableOrderDetails::$TABLE_NAME." AS ".TableOrderDetails::$TABLE_NAME." ON ".TableProductImages::$TABLE_NAME.".".TableProductImages::$COLUMN_PRODUCT_ID_FK."=".TableOrderDetails::$TABLE_NAME.".".TableOrderDetails::$COLUMN_PRODUCT_ID_FK."
        WHERE
            ".TableOrderDetails::$TABLE_NAME.".".TableOrderDetails::$COLUMN_ORDER_DETAILS_ID." = ?
    ";
    $run_query_select = $this->link->prepare($query_select);
    $run_query_select->bindParam(1,$order_details_id);
    if ($run_query_select->execute()) {
        return $run_query_select->fetchColumn();
    }
}

function get_user_id($order_id){
    $query_select="
        SELECT
            ".TableOrders::$COLUMN_USER_ID_FK." AS user_id
        FROM 
            ".TableOrders::$TABLE_NAME."
        WHERE
            ".TableOrders::$COLUMN_ORDER_ID." = ?
    ";
    $run_query_select = $this->link->prepare($query_select);
    $run_query_select->bindParam(1,$order_id);
    if ($run_query_select->execute()) {
        return $run_query_select->fetchColumn();
    }else{
        return "";
    }
}

function delete_order($order_id){
    $query_delete="
        DELETE FROM 
            ".TableOrders::$TABLE_NAME." 
        WHERE 
            ".TableOrders::$COLUMN_ORDER_ID."=?
    ";
    $run_query1=$this->link->prepare($query_delete);
    $run_query1->bindParam(1,$order_id);
    if($run_query1->execute()){
        $query_delete_details="
            DELETE FROM 
                ".TableOrderDetails::$TABLE_NAME." 
            WHERE 
                ".TableOrderDetails::$COLUMN_ORDER_ID_FK."=?
        ";         
        $run_query2=$this->link->prepare($query_delete_details);
        $run_query2->bindParam(1,$order_id);
        if($run_query2->execute()){
            return 1;
        }else{
            return 0;
        }
    }else{
        return 0;
    }
}

}



















?>