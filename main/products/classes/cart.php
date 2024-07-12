<?php
    class Cart
    {
        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        function add_to_cart($user_id,$product_id){
            $query_insert = "
                INSERT INTO 
                    ".TableCart::$TABLE_NAME."
                    (
                        ".TableCart::$COLUMN_USER_ID_FK.",
                        ".TableCart::$COLUMN_PRODUCT_ID_FK."
                    )
                VALUES 
                    (?,?)
            ";
            $run_query_insert = $this->link->prepare($query_insert);
            $run_query_insert->bindParam(1, $user_id);
            $run_query_insert->bindParam(2, $product_id);
            if ($run_query_insert->execute()) {
                return 1;
            } else {
                return 0;
            }
        }

        function remove_from_cart($user_id,$product_id){
            $query_delete = "
                DELETE
                FROM 
                    ".TableCart::$TABLE_NAME."
                WHERE
                    ".TableCart::$COLUMN_USER_ID_FK." = ?
                    AND ".TableCart::$COLUMN_PRODUCT_ID_FK." = ?
            ";
            $run_query_delete = $this->link->prepare($query_delete);
            $run_query_delete->bindParam(1, $user_id);
            $run_query_delete->bindParam(2, $product_id);
            if ($run_query_delete->execute()) {
                return 1;
            } else {
                return 0;
            }
        }

        public function check_cart($user_id, $product_id)
        {
            $query_select_favourites = 'select * from ' . TableCart::$TABLE_NAME . ' WHERE ' . TableCart::$COLUMN_USER_ID_FK . ' =? AND ' . TableCart::$COLUMN_PRODUCT_ID_FK . '=?';
            $run_query_select = $this->link->prepare($query_select_favourites);
            $run_query_select->bindParam(1, $user_id);
            $run_query_select->bindParam(2, $product_id);
            if ($run_query_select->execute()) {
                return $run_query_select->rowCount();
            } else {
                return 0;
            }
        }

    }

?>
