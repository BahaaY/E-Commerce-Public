<?php

class ContactDetails{


    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function get_contact_details()
    {
        
        $query_select = "
            SELECT
                ".TableContactDetails::$COLUMN_ADDRESS." AS address,
                ".TableContactDetails::$COLUMN_PHONE_NUMBER." AS phone_number,
                ".TableContactDetails::$COLUMN_EMAIL." AS email
            FROM
                ".TableContactDetails::$TABLE_NAME."
            ORDER BY ".TableContactDetails::$COLUMN_CONTACT_DETAILS_ID." DESC
            LIMIT 1
        ";
        $run_query_select = $this->link->prepare($query_select);
        if($run_query_select->execute()){
            return $run_query_select->fetch();
        }

    }

}

?>