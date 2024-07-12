<?php

class Auth
{
    protected $link;
    protected $time_zone;

    public function __construct($link)
    {
        $this->link = $link;
        require_once '../../../config/helper.php';
    }

    public function email_login($email, $password, $time_zone)
    {
        $query_select = "
            SELECT 
                * 
            FROM 
                ".TableUsers::$TABLE_NAME." 
            WHERE 
                ".TableUsers::$COLUMN_EMAIL." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1, $email);
        $run_query_select->execute();
        $user = $run_query_select->fetch();

        if ($user) {

            $user_id = $user['user_id'];
            $hashed_password = $user['password'];
            $is_verified = $user['is_verified'];
            $registration_type_id_FK = $user['registration_type_id_FK'];
            $availability = $user['availability'];

            if (!Helper::string_verify($password, $hashed_password) || $registration_type_id_FK != 1) {
                return 2;
            }
    
            if ($is_verified != 1) {
                return 3;
            }
    
            if ($availability != 1) {
                return 4;
            }

            $login_available_at=Self::get_login_available_at($email);
            $dateTime = new DateTime();
            $current_datetime = $dateTime->format('Y-m-d H:i:s');

            if(strtotime($login_available_at) > strtotime($current_datetime)){
                return 5;
            }

            if(Self::update_user_data($user_id,$time_zone)){

                $user_id=Helper::string_hash($user_id);
                $time_zone_name_hashed=Helper::string_hash($time_zone);
                $_SESSION[Session::$KEY_EC_USERID] = $user_id;
                $_SESSION[Session::$KEY_EC_TOKEN] = Helper::generate_random_string(80,80);
                $_SESSION[Session::$KEY_EC_TIME_ZONE] = $time_zone_name_hashed;

                return 1;

            }else{
                return 0;
            }

        }
    }

    public function update_user_data($user_id,$time_zone){

        $dateTime = new DateTime();
        $logged_in_date_time = $dateTime->format('Y-m-d H:i:s');

        $query_update="
            UPDATE
                ".TableUsers::$TABLE_NAME."
            SET 
                ".TableUsers::$COLUMN_LOGIN_LIMIT." = 5,
                ".TableUsers::$COLUMN_LOGIN_BLOCK_COUNT." = 0,
                ".TableUsers::$COLUMN_LOGIN_BLOCKED_AT." = NULL,
                ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT." = NULL,
                ".TableUsers::$COLUMN_LOGGED_IN_AT." = ?,
                ".TableUsers::$COLUMN_TIME_ZONE." = ?
            WHERE
                ".TableUsers::$COLUMN_USER_ID." = ? 
        ";
        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1, $logged_in_date_time);
        $run_query_update->bindParam(2, $time_zone);
        $run_query_update->bindParam(3, $user_id);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    function check_login_limit($email){

        $query_select = "
            SELECT 
                ".TableUsers::$COLUMN_LOGIN_LIMIT." 
            FROM 
                ".TableUsers::$TABLE_NAME." 
            WHERE 
                ".TableUsers::$COLUMN_EMAIL." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1, $email);
        if($run_query_select->execute()){
            $login_limit=$run_query_select->fetchColumn();
            if($login_limit > 0){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }

    }

    function decrement_login_limit($email){

        $query_select = "
            SELECT 
                ".TableUsers::$COLUMN_LOGIN_LIMIT." 
            FROM 
                ".TableUsers::$TABLE_NAME." 
            WHERE 
                ".TableUsers::$COLUMN_EMAIL." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1, $email);
        if($run_query_select->execute()){
            $login_limit=$run_query_select->fetchColumn();
            $login_limit--;
            $query_update = "
                UPDATE 
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_LOGIN_LIMIT." = ? 
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
            ";
            $run_query_update = $this->link->prepare($query_update);
            $run_query_update->bindParam(1, $login_limit);
            $run_query_update->bindParam(2, $email);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }
        }else{
            return 0;
        }

    }

    function reset_login_limit($email){

        $query_update = "
            UPDATE 
                ".TableUsers::$TABLE_NAME."
            SET 
                ".TableUsers::$COLUMN_LOGIN_LIMIT." = 5
            WHERE 
                ".TableUsers::$COLUMN_EMAIL." = ?
        ";
        $run_query_update = $this->link->prepare($query_update);
        $run_query_update->bindParam(1, $email);
        if($run_query_update->execute()){
            return 1;
        }else{
            return 0;
        }

    }

    function increment_login_block($email){

        $query_select = "
            SELECT 
                ".TableUsers::$COLUMN_LOGIN_BLOCK_COUNT." 
            FROM 
                ".TableUsers::$TABLE_NAME." 
            WHERE 
                ".TableUsers::$COLUMN_EMAIL." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1, $email);
        if($run_query_select->execute()){

            $login_block_count=$run_query_select->fetchColumn();
            $login_block_count++;
            $dateTime = new DateTime();
            $current_datetime = $dateTime->format('Y-m-d H:i:s');
            $login_blocked_time = $current_datetime;
            
            $block_time = "15";
            $block_time = intval($block_time);
            $timestamp = strtotime($login_blocked_time);
            $increment_seconds = $login_block_count * $block_time * 60;
            $new_timestamp = $timestamp + $increment_seconds;
            $result_datetime = date("Y-m-d H:i:s", $new_timestamp);

            $login_available_at = $result_datetime;
                    
            $query_update = "
                UPDATE 
                    ".TableUsers::$TABLE_NAME."
                SET 
                    ".TableUsers::$COLUMN_LOGIN_BLOCK_COUNT." = ?,
                    ".TableUsers::$COLUMN_LOGIN_BLOCKED_AT." = ?,
                    ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT." = ?
                WHERE 
                    ".TableUsers::$COLUMN_EMAIL." = ?
            ";
                
            $run_query_update = $this->link->prepare($query_update);
            $run_query_update->bindParam(1, $login_block_count);
            $run_query_update->bindParam(2, $login_blocked_time);
            $run_query_update->bindParam(3, $login_available_at);
            $run_query_update->bindParam(4, $email);
            if($run_query_update->execute()){
                return 1;
            }else{
                return 0;
            }

        }

    }

    function get_login_available_at($email){

        $query_select = "
            SELECT 
                ".TableUsers::$COLUMN_LOGIN_AVAILABLE_AT." 
            FROM 
                ".TableUsers::$TABLE_NAME." 
            WHERE 
                ".TableUsers::$COLUMN_EMAIL." = ?
        ";
        $run_query_select = $this->link->prepare($query_select);
        $run_query_select->bindParam(1, $email);
        if($run_query_select->execute()){
            $login_available_at = $run_query_select->fetchColumn();
        }else{
            $login_available_at = NULL;
        }

        return $login_available_at;

    }
    
}

?>
