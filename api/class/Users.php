<?php
class User
{

    // Connection
    private $conn;

    // Table
    private $db_table = "users";

    // Columns
    public $id_user;
    public $email;
    public $password;
    public $role;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getUsers()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createUser()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        id_user = :id_user,
                        email = :email,
                        password = :password,
                        role = :role
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // bind data
        $stmt->bindParam(":id_user", $this->id_user);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // UPDATE
    public function getSingleUser()
    {
        $sqlQuery = "SELECT * FROM users WHERE id_user = ? AND role = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_user);
        $stmt->bindParam(2, $this->role);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->email = $dataRow['email'];
        $this->password = $dataRow['password'];
        $this->role = $dataRow['role'];
    }

    // UPDATE
    public function updateUser()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        password = :password
                    WHERE
                        id_user = :id_user AND role = :role";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // bind data
        $stmt->bindParam(":id_user", $this->id_user);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteUser()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_user = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_user = htmlspecialchars(strip_tags($this->id_user));

        $stmt->bindParam(1, $this->id_user);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
