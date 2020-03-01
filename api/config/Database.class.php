<?php

/**
 * Class used for obtaining SQL connection.
 */
class Database {
    /**
     * Replace following information with configuration options for your server.
     */
    private $host = "127.0.0.1";
    private $db_name = "phpapitutorial";
    private $username = "root";
    private $password = "";

    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
        } catch (PDOException $exception) {
            echo "Database connection unsucessful: " . $exception->getMessage();
        }

        return $this->conn;
    }
}

?>
