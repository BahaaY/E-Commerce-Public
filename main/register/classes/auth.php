<?php

class Auth
{
    protected $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function register($username, $email, $password_hashed, $registration_type,$country)
    {
        $query_insert = "
            INSERT 
            INTO 
            ".TableUsers::$TABLE_NAME." (
                ".TableUsers::$COLUMN_USERNAME.",
                ".TableUsers::$COLUMN_EMAIL.",
                ".TableUsers::$COLUMN_PASSWORD.",
                ".TableUsers::$COLUMN_REGISTRATION_TYPE_ID_FK.",
                ".TableUsers::$COLUMN_COUNTRY."
            ) 
            VALUES (?,?,?,?,?)
        ";
        $register_user = $this->link->prepare($query_insert);
        $register_user->bindParam(1, $username);
        $register_user->bindParam(2, $email);
        $register_user->bindParam(3, $password_hashed);
        $register_user->bindParam(4, $registration_type);
        $register_user->bindParam(5, $country);
        if ($register_user->execute()) {
            $user_id = $this->link->lastInsertId();
            return $user_id;
        } else {
            return 0;
        }
    }
}

?>
