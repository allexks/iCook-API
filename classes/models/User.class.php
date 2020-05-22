<?php

require_once "classes/config/Settings.class.php";

/**
 * A basic system user.
 */
class User {

    public $id;
    public $firstname;
    public $lastname;
    public $email;
    public $password;

    private $conn;
    const DB_TABLENAME = Settings::DB_USERS_TABLENAME;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $tablename = self::DB_TABLENAME;

        $query = "INSERT INTO $tablename
                    (
                        `firstname`,
                        `lastname`,
                        `email`,
                        `password`
                    ) VALUES (
                        :firstname,
                        :lastname,
                        :email,
                        :passsword
                    )";

        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":firstname", $this->firstname);
        $stmt->bindParam(":lastname", $this->lastname);
        $stmt->bindParam(":email", $this->email);

        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);

        return $stmt->execute();
    }

    public function emailExists() {
        $tablename = self::DB_TABLENAME;

        $query = "SELECT id, firstname, lastname, password
                  FROM $tablename
                  WHERE email = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        $rows_count = $stmt->rowCount();

        if ($rows_count <= 0) {
            return false;
        }

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = (int)$row['id'];
        $this->firstname = $row['firstname'];
        $this->lastname = $row['lastname'];
        $this->password = $row['password'];

        return true;
    }

    public function toArray() {
        return [
            "id" => (int)$this->id,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname,
            "email" => $this->email,
       ];
    }

    public function update() {
        $tablename = self::DB_TABLENAME;

        $password_set = !empty($this->password) ? ", password = :password" : "";

        $query = "UPDATE $tablename
                  SET
                      firstname = :firstname,
                      lastname = :lastname,
                      email = :email
                      {$password_set}
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':email', $this->email);

        if(!empty($this->password)){
            $this->password = htmlspecialchars(strip_tags($this->password));
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        }

        $stmt->bindParam(':id', $this->id);

        return $stmt->execute();
    }
}

?>
