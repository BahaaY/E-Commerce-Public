<?php

class OrderTracking
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_all_order_tracking()
    {
        $query_select = "
            SELECT 
                ".TableOrderTracking::$COLUMN_ORDER_TRACKING_ID." AS order_tracking_id,
                ".TableOrderTracking::$COLUMN_ORDER_TRACKING_NAME." AS order_tracking_name,
                ".TableOrderTracking::$COLUMN_DISPLAYED_TEXT_COLOR." AS displayed_text_color
            FROM 
                ".TableOrderTracking::$TABLE_NAME." 
            WHERE  
                ".TableOrderTracking::$COLUMN_AVAILABILITY." = 1
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetchAll();
        }else{
            return [];
        }

    }
    
}

?>
