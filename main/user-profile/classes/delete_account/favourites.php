<?php

    class Favourites {

        protected $link;

        public function __construct($link)
        {
            $this->link = $link;
        }

        public function delete_favourites($user_id){
            $query_delete="
                DELETE 
                FROM 
                    ".TableFavourites::$TABLE_NAME." 
                WHERE
                    ".TableFavourites::$COLUMN_USER_ID_FK." = ?
            ";
            $run_query_delete=$this->link->prepare($query_delete);
            $run_query_delete->bindParam(1,$user_id);
            if($run_query_delete->execute()){
                return 1;
            }else{
                return 0;
            }
        }

    }

    
?>