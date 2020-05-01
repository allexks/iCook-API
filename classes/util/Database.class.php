<?php

require_once "classes/config/Settings.class.php";

/**
 * Class used for obtaining SQL connection.
 */
class Database {

    private $conn;

    public function getNewConnection() {
        $this->conn = null;

        $host = Settings::HOST;
        $db_name = Settings::DB_NAME;
        $db_username = Settings::DB_USERNAME;
        $db_password = Settings::DB_PASSWORD;

        try {
            $this->conn = new PDO(
                "mysql:host=$host;dbname=$db_name;charset=utf8",
                $db_username,
                $db_password
            );
        } catch (PDOException $exception) {
            error_log("[!!] FATAL: Database connection unsucessful: "
                . $exception->getMessage());
        }

        return $this->conn;
    }
}

?>
