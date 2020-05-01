<?php

require_once "classes/models/Recipe.class.php";

/**
 * Dish.
 */
class Dish {

    public $id;
    public $name;
    public $description;
    public $image_url;

    public $recipes;

    private $conn;
    const DB_TABLENAME = "dishes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function toArray() {
        return [
            "id" => (int)$this->id,
            "name" => $this->name,
            "description" => $this->description,
            "image_url" => $this->image_url,
            "recipes" => array_map(function ($r) {
                return $r->toArray();
            }, $this->recipes),
        ];
    }

    public function randomId() {
        $tablename = self::DB_TABLENAME;

        $query = "SELECT id FROM $tablename ORDER BY RAND() LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = (int)$row["id"];
        $this->id = $id;
        return $id;
    }

    public function fetch() {
        $tablename = self::DB_TABLENAME;

        $query = "SELECT * FROM $tablename WHERE id = :id LIMIT 0,1";

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

        $this->fetchAllRecipes();

        return true;
    }

    public function fetchAllRecipes() {
        $this->recipes = Recipe::fetchAllForDishId($this->conn, $this->id);
    }

    public static function fetchAllMatching($searchTerm, $conn) {
        $tablename = self::DB_TABLENAME;

        $query = "SELECT * FROM $tablename WHERE name LIKE :term";

        $stmt = $conn->prepare($query);
        $searchTerm = "%".htmlspecialchars(strip_tags($searchTerm))."%";
        $stmt->bindParam(":term", $searchTerm);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if ($rows_count <= 0) {
            return false;
        }

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dish = new Dish($conn);
            $dish->id = (int)$row["id"];
            $dish->name = $row["name"];
            $dish->description = $row["description"];
            $dish->image_url = $row["image_url"];
            $dish->recipes = []; // TODO: consider changing this
            $result[] = $dish;
        }

        return $result;
    }
}

?>
