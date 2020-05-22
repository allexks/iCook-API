<?php

require_once "classes/models/User.class.php";

/**
 * Rating.
 */
class Rating {

    public $id;
    public $recipe_id;
    public $user_id;
    public $rating;
    public $comment;

    public $user_names;
    public $user_email;

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
            "user_names" => $this->user_names,
            "user_email" => $this->user_email,
        ];
    }

    public function create() {
        $tablename = self::DB_TABLENAME;

        $query = "INSERT INTO $tablename
                    (
                        `recipe_id`,
                        `user_id`,
                        `rating`,
                        `comment`
                    ) VALUES (
                        :recipeid,
                        :userid,
                        :rating,
                        :comment
                    )";

        $stmt = $this->conn->prepare($query);

        $this->recipe_id = htmlspecialchars(strip_tags($this->recipe_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bindParam(":recipeid", $this->recipe_id);
        $stmt->bindParam(":userid", $this->user_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":comment", $this->comment);

        return $stmt->execute();
    }

    public function update() {
        $tablename = self::DB_TABLENAME;

        $qry = "UPDATE $tablename
                SET
                    `recipe_id` = :recipeid,
                    `rating` = :rating,
                    `comment` = :comment,
                    `user_id` = :userid
                WHERE
                    `id` = :id";

        $stmt = $this->conn->prepare($qry);

        $this->recipe_id = htmlspecialchars(strip_tags($this->recipe_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $this->rating = htmlspecialchars(strip_tags($this->rating));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":recipeid", $this->recipe_id);
        $stmt->bindParam(":userid", $this->user_id);
        $stmt->bindParam(":rating", $this->rating);
        $stmt->bindParam(":comment", $this->comment);

        return $stmt->execute();
    }

    public function fetch() {
        $table = self::DB_TABLENAME;
        $userstable = User::DB_TABLENAME;

        $query = "SELECT r.*, u.firstname, u.lastname, u.email
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
        $this->recipe_id = (int)$row["recipe_id"];
        $this->user_id = (int)$row["user_id"];
        $this->rating = (int)$row["rating"];
        $this->comment = $row["comment"];
        $this->user_names = $row["firstname"] . " " . $row["lastname"];
        $this->user_email = $row["email"];

        return true;
    }

    public function fetchForUser() {
        $table = self::DB_TABLENAME;

        $query = "SELECT *
                  FROM $table
                  WHERE recipe_id = :rid AND user_id = :uid
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->recipe_id = htmlspecialchars(strip_tags($this->recipe_id));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));
        $stmt->bindParam(":rid", $this->recipe_id);
        $stmt->bindParam(":uid", $this->user_id);

        if (!$stmt->execute()) {
            return false;
        }

        $rows_count = $stmt->rowCount();

        if ($rows_count == 0) {
            unset($this->id);
            return true;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = (int)$row["id"];
        $this->recipe_id = (int)$row["recipe_id"];
        $this->user_id = (int)$row["user_id"];
        $this->rating = (int)$row["rating"];
        $this->comment = $row["comment"];

        return $this->id;
    }

    public static function fetchAllForRecipeId($conn, $recipe_id) {
        $table = self::DB_TABLENAME;
        $userstable = User::DB_TABLENAME;

        $query = "SELECT r.*, u.firstname, u.lastname, u.email
                  FROM $table r
                  JOIN $userstable u
                  ON r.user_id = u.id
                  WHERE r.recipe_id = :id";

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
            $rating->user_names = $row["firstname"] . " " . $row["lastname"];
            $rating->user_email = $row["email"];
            $result[] = $rating;
        }

        return $result;
    }
}

?>
