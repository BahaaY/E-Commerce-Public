

<?php
class validation{

    protected $link;

    public function __construct($link){
        $this->link = $link;
    }

    public function check_email($email){
    $get_email= "
        SELECT 
            ".TableUsers::$COLUMN_USER_ID." 
        FROM 
            ".TableUsers::$TABLE_NAME."  
        WHERE 
            ".TableUsers::$COLUMN_EMAIL." = ? 
    ";
    $check_email=$this->link->prepare($get_email);
    $check_email->bindParam(1,$email);
    if($check_email->execute()){
        $data=$check_email->rowCount();
        if($data!=1){
            return 1;
        }else{
            return 0;
        }
    }
}
}

?>