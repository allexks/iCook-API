<?php

require_once "classes/models/Rating.class.php";
require_once "classes/models/User.class.php";

/**
 * Recipe.
 */
class Recipe {

    public $id;
    public $dish_id;
    public $user_id;
    public $date_created;
    public $duration;
    public $steps;

    public $ratings;

    public $user_names;
    public $user_email;

    private $conn;
    const DB_TABLENAME = "recipes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function toArray() {
        return [
            "id" => (int)$this->id,
            "dish_id" => (int)$this->dish_id,
            "user_id" => (int)$this->user_id,
            "date_created" => (int)$this->date_created,
            "duration" => (int)$this->duration,
            "steps" => $this->steps,
            "user_names" => $this->user_names,
            "user_email" => $this->user_email,
            "ratings" => array_map(function ($r) {
                return $r->toArray();
            }, $this->ratings),
        ];
    }

    public function getAvgRating() {
        $ratings = $this->ratings;

        if (!isset($ratings) || empty($ratings)) {
            return null;
        }

        $ratings = array_map(function($r) { return $r->rating; }, $ratings);
        $result = (float)array_sum($ratings) / (float)count($ratings);

        return $result;
    }

    public function fetch() {
        $table = self::DB_TABLENAME;
        $userstable = User::DB_TABLENAME;

        $query = "SELECT
                    r.*, u.firstname, u.lastname, u.email,
                    UNIX_TIMESTAMP(r.date_created) AS recipe_date
                  FROM $table r
                  JOIN $userstable u
                  ON r.user_id = u.id
                  WHERE r.id = :id
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
        $this->dish_id = (int)$row["dish_id"];
        $this->user_id = (int)$row["user_id"];
        $this->date_created = (int)$row["recipe_date"];
        $this->duration = (int)$row["duration"];
        $this->steps = $row["steps"];

        $this->fetchAllRatings();

        $this->user_names = $row["firstname"] . $row["lastname"];
        $this->user_email = $row["email"];

        return true;
    }

    public function fetchAllRatings() {
        $this->ratings = Rating::fetchAllForRecipeId($this->conn, $this->id);
    }

    public static function fetchAllForDishId($conn, $dish_id) {
        $table = self::DB_TABLENAME;
        $userstable = User::DB_TABLENAME;

        $query = "SELECT
                    r.*, u.firstname, u.lastname, u.email,
                    UNIX_TIMESTAMP(r.date_created) AS recipe_date
                  FROM $table r
                  JOIN $userstable u
                  ON r.user_id = u.id
                  WHERE r.dish_id = :id";

        $stmt = $conn->prepare($query);
        $dish_id = htmlspecialchars(strip_tags($dish_id));
        $stmt->bindParam(":id", $dish_id);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if ($rows_count <= 0) {
            return [];
        }

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $recipe = new Recipe($conn);
            $recipe->id = (int)$row["id"];
            $recipe->dish_id = (int)$row["dish_id"];
            $recipe->user_id = (int)$row["user_id"];
            $recipe->date_created = (int)$row["recipe_date"];
            $recipe->duration = (int)$row["duration"];
            $recipe->steps = $row["steps"];
            $recipe->fetchAllRatings();
            $recipe->user_names = $row["firstname"] . $row["lastname"];
            $recipe->user_email = $row["email"];
            $result[] = $recipe;
        }

        return $result;
    }
}

?>
