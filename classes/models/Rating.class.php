<?php

/**
 * Rating.
 */
class Rating {

    public $id;
    public $recipe_id;
    public $user_id;
    public $rating;
    public $comment;

    private $conn;
    const DB_TABLENAME = "ratings";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function toArray() {
        return [
            "id" => (int)$this->id,
            "recipe_id" => (int)$this->recipe_id,
            "user_id" => (int)$this->user_id,
            "rating" => (int)$this->rating,
            "comment" => $this->comment,
        ];
    }

    public function fetch() {
        $query = "SELECT * FROM {self::DB_TABLENAME}
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
        $this->recipe_id = (int)$row["recipe_id"];
        $this->user_id = (int)$row["user_id"];
        $this->rating = (int)$row["rating"];
        $this->comment = $row["comment"];

        return true;
    }

    public static function fetchAllForRecipeId($conn, $recipe_id) {
        $query = "SELECT * FROM {self::DB_TABLENAME}
                WHERE recipe_id = :id";

        $stmt = $conn->prepare($query);
        $recipe_id = htmlspecialchars(strip_tags($recipe_id));
        $stmt->bindParam(":id", $recipe_id);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if ($rows_count <= 0) {
            return [];
        }

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $rating = new Rating($conn);
            $rating->id = (int)$row["id"];
            $rating->recipe_id = (int)$row["recipe_id"];
            $rating->user_id = (int)$row["user_id"];
            $rating->rating = (int)$row["rating"];
            $rating->comment = $row["comment"];
            $result[] = $rating;
        }

        return $result;
    }
}

?>
