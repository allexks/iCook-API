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
    private $db_tablename;

    public function __construct($db) {
        $this->conn = $db;
        $this->db_tablename = Settings::DB_USERS_TABLENAME;
    }

    public function create() {
        $query = "INSERT INTO {$this->db_tablename}
            SET
                firstname = :firstname,
                lastname = :lastname,
                email = :email,
                password = :password";

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
        $query = "SELECT id, firstname, lastname, password
                FROM {$this->db_tablename}
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
        $password_set = !empty($this->password) ? ", password = :password" : "";

        $query = "UPDATE {$this->db_tablename}
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
