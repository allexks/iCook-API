<?php

/**
 * Dish.
 */
class Dish {

    public $id;
    public $name;
    public $description;
    public $image_url;

    private $conn;
    private $db_tablename;

    public function __construct($db) {
        $this->conn = $db;
        $this->db_tablename = "dishes";
    }

    public function toArray() {
        return [
            "id" => (int)$this->id,
            "name" => $this->name,
            "description" => $this->description,
            "image_url" => $this->image_url
        ];
    }

    public function randomId() {
        $query = "SELECT id FROM {$this->db_tablename} ORDER BY RAND() LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = (int)$row["id"];
        $this->id = $id;
        return $id;
    }

    public function fetch() {
        $query = "SELECT * FROM {$this->db_tablename}
                WHERE id = :id
                LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if ($rows_count <= 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = (int)$row["id"];
        $this->name = $row["name"];
        $this->description = $row["description"];
        $this->image_url = $row["image_url"];

        return true;
    }
}


?>
