<?php
    
    class DBController
    {
        
        private $hostname = "localhost";
        private $username = "root";
        private $password = "";
        private $db = "db_bulk_upload";
        
        public function connect()
        {
            $conn = new mysqli($this->hostname, $this->username, $this->password, $this->db) or die("Database connection error." . $conn->connect_error);
            return $conn;
        }
        
        public function close($conn)
        {
            $conn->close();
        }
        
    }

?>