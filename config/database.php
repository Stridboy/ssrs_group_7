<?php
if (!class_exists('Database')) {
    class Database {
        // Database credentials
        private $host = "localhost"; // Ensure this matches your database host
        private $db_name = "ssrs_db"; // Ensure this matches your database name
        private $username = "root"; // Ensure this matches your database username
        private $password = ""; // Ensure this matches your database password
        public $conn;

        public function getConnection() {
            $this->conn = null;

            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            } catch(PDOException $exception) {
                echo "Connection error: " . $exception->getMessage();
            }

            return $this->conn;
        }
    }
}
?>
