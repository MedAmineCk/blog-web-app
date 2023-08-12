<?php
class Auth
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

    // Check
    public function authentication()
    {
        $query = "SELECT id_user, role FROM users WHERE email = :email AND password = :password";
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id_user = $row["id_user"];
                $this->role = $row["role"];
                return true;
            }
        }
        return false;
    }
}
