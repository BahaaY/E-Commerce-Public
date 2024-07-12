<?php

class Favourites
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function remove_from_favourites($user_id,$product_id){

        $query_delete="delete from ".TableFavourites::$TABLE_NAME." where ".TableFavourites::$COLUMN_USER_ID_FK." =? AND ".TableFavourites::$COLUMN_PRODUCT_ID_FK." = ?";
        $run_query_delete=$this->link->prepare($query_delete);
        $run_query_delete->bindParam(1,$user_id);
        $run_query_delete->bindParam(2,$product_id);
    
        if($run_query_delete->execute()){
            return 1;
        }
        else{
            return 0;
        }
        
    }

}

?>
