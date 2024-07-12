<?php
    class Orders
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function insert_order($reference_number,$user_id,$fullname,$username,$email,$address,$country,$region,$phone_number,$tracking_number,$date,$order_type,$order_tracking_id_FK,$currency_id)
        {
            $query_insert = "
                INSERT INTO 
                    ".TableOrders::$TABLE_NAME."
                    (
                        ".TableOrders::$COLUMN_REFERENCE_NUMBER.",
                        ".TableOrders::$COLUMN_USER_ID_FK.",
                        ".TableOrders::$COLUMN_FULLNAME.",
                        ".TableOrders::$COLUMN_USERNAME.",
                        ".TableOrders::$COLUMN_EMAIL.",
                        ".TableOrders::$COLUMN_ADDRESS.",
                        ".TableOrders::$COLUMN_COUNTRY.",
                        ".TableOrders::$COLUMN_REGION.",
                        ".TableOrders::$COLUMN_PHONE_NUMBER.",
                        ".TableOrders::$COLUMN_TRACKING_NUMBER.",
                        ".TableOrders::$COLUMN_DATE.",
                        ".TableOrders::$COLUMN_ORDER_TYPE_ID_FK.",
                        ".TableOrders::$COLUMN_ORDER_TRACKING_ID_FK.",
                        ".TableOrders::$COLUMN_CURRENCY_ID_FK."
                    )
                    VALUES
                    (
                        ?,?,?,?,?,?,?,?,?,?,?,?,?,?
                    )
            ";
            $run_query_insert = $this->link->prepare($query_insert);
            $run_query_insert->bindParam(1, $reference_number);
            $run_query_insert->bindParam(2, $user_id);
            $run_query_insert->bindParam(3, $fullname);
            $run_query_insert->bindParam(4, $username);
            $run_query_insert->bindParam(5, $email);
            $run_query_insert->bindParam(6, $address);
            $run_query_insert->bindParam(7, $country);
            $run_query_insert->bindParam(8, $region);
            $run_query_insert->bindParam(9, $phone_number);
            $run_query_insert->bindParam(10, $tracking_number);
            $run_query_insert->bindParam(11, $date);
            $run_query_insert->bindParam(12, $order_type);
            $run_query_insert->bindParam(13, $order_tracking_id_FK);
            $run_query_insert->bindParam(14, $currency_id);
            if ($run_query_insert->execute()) {
                $order_id=$this->link->lastInsertId();
                return $order_id;
            } else {
                return 0;
            }
        }

        public function get_order_user_info($user_id){

            $query_select= "
                SELECT 
                    ".TableOrders::$COLUMN_FULLNAME." AS fullname,
                    ".TableOrders::$COLUMN_USERNAME." AS username,
                    ".TableOrders::$COLUMN_EMAIL." AS email,
                    ".TableOrders::$COLUMN_COUNTRY." AS country,
                    ".TableOrders::$COLUMN_REGION." AS region,
                    ".TableOrders::$COLUMN_ADDRESS." AS address,
                    ".TableOrders::$COLUMN_PHONE_NUMBER." AS phone_number
                FROM 
                    ".TableOrders::$TABLE_NAME."
                WHERE 
                    ".TableOrders::$COLUMN_USER_ID_FK." = ?
                ORDER BY ".TableOrders::$COLUMN_ORDER_ID." DESC
                LIMIT 1
            ";
    
            $run_query_select=$this->link->prepare($query_select);
            $run_query_select->bindParam(1,$user_id);
            if($run_query_select->execute()){
                return $run_query_select->fetch();
            }
    
        }


    }

?>
