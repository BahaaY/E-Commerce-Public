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

    public function update_contact_details($address,$phone_number,$email)
    {

        if(Self::get_contact_details()){
            
            $query_update = "
                UPDATE
                    ".TableContactDetails::$TABLE_NAME."
                SET
                    ".TableContactDetails::$COLUMN_ADDRESS." = ?,
                    ".TableContactDetails::$COLUMN_PHONE_NUMBER." = ?,
                    ".TableContactDetails::$COLUMN_EMAIL." = ?
                WHERE
                    1
            ";
            $run_query_update = $this->link->prepare($query_update);
            $run_query_update->bindParam(1,$address);
            $run_query_update->bindParam(2,$phone_number);
            $run_query_update->bindParam(3,$email);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }

        }else{

            $query_insert = "
                INSERT
                INTO
                    ".TableContactDetails::$TABLE_NAME."
                (
                    ".TableContactDetails::$COLUMN_ADDRESS.",
                    ".TableContactDetails::$COLUMN_PHONE_NUMBER.",
                    ".TableContactDetails::$COLUMN_EMAIL."
                )
                VALUES
                (
                    ?,?,?
                )
            ";
            $run_query_insert = $this->link->prepare($query_insert);
            $run_query_insert->bindParam(1,$address);
            $run_query_insert->bindParam(2,$phone_number);
            $run_query_insert->bindParam(3,$email);
            if($run_query_insert->execute()){
                return 1;
            }else{
                return 0;
            }

        }

    }

}

?>