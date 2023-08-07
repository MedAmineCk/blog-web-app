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
        //authotication
        $sqlQuery = "SELECT id_user, role FROM " . $this->db_table . " WHERE email = :email AND password = :password";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));

        // bind data
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id_user = $dataRow['id_user'];
        $this->role = $dataRow['role'];
    }
}
