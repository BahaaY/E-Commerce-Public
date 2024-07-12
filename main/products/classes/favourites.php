<?php

class Favourites
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function check_favourite($user_id, $product_id)
    {
        $query_select_favourites = 'select * from ' . TableFavourites::$TABLE_NAME . ' WHERE ' . TableFavourites::$COLUMN_USER_ID_FK . ' =? AND ' . TableFavourites::$COLUMN_PRODUCT_ID_FK . '=?';
        $run_query_select = $this->link->prepare($query_select_favourites);
        $run_query_select->bindParam(1, $user_id);
        $run_query_select->bindParam(2, $product_id);
        if ($run_query_select->execute()) {
            return $run_query_select->rowCount();
        } else {
            return 0;
        }
    }

    public function add_to_favourites($user_id,$product_id){
    
        $query_insert="Insert into ".TableFavourites::$TABLE_NAME."(".TableFavourites::$COLUMN_USER_ID_FK.",".TableFavourites::$COLUMN_PRODUCT_ID_FK.") values(?,?)";
        $run_query_insert=$this->link->prepare($query_insert);
        $run_query_insert->bindParam(1,$user_id);
        $run_query_insert->bindParam(2,$product_id);
      
        if($run_query_insert->execute()){
            return 1;
        }
        else{
            return 0;
        }

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
