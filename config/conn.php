<?php

    require_once 'variables.php';

    class Conn {

        private $hostname;
        private $username;
        private $dbpass;
        private $dbname;
        private $link;

        public function set_hostname($hostname) {
            $this->hostname = $hostname;
        }

        public function set_username($username) {
            $this->username = $username;
        }

        public function set_dbpass($dbpass) {
            $this->dbpass = $dbpass;
        }

        public function set_dbname($dbname) {
            $this->dbname = $dbname;
        }

        public function connect() {
            try {
                $this->link = new PDO("mysql:host=$this->hostname;dbname=$this->dbname;charset=utf8",$this->username,$this->dbpass);
            }catch(PDOException $ex) {
                die("Could not connect to database");
            }
        }

        public function get_link() {
            return $this->link;
        }
    }

    $db_conn = new Conn();
    $db_conn->set_dbname(DatabaseName::$DATABASE_NAME);
    $db_conn->set_hostname(DatabaseInfo::$DATABASE_HOSTNAME);
    $db_conn->set_username(DatabaseInfo::$DATABASE_USERNAME);
    $db_conn->set_dbpass(DatabaseInfo::$DATABASE_PASSWORD);
    $db_conn->connect();
    
?>
