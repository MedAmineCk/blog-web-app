<?php
class Category
{

    // Connection
    private $conn;

    // Table
    private $db_table = "categories";

    // Columns
    public $id_category;
    public $category;
    public $thumbnail;
    public $description;
    public $is_published;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getCategories()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createCategory()
    {
        $sqlQuery = "INSERT INTO categories set
                        category = :category,
                        thumbnail = :thumbnail,
                        description = :description,
                        is_published = :is_published
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->is_published = htmlspecialchars(strip_tags($this->is_published));

        // bind data
        $stmt->bindParam(":category", $this->category);
        $stmt->bindParam(":thumbnail", $this->thumbnail);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":is_published", $this->is_published);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // UPDATE
    public function getSingleCategory()
    {
        $sqlQuery = "SELECT * FROM categories WHERE id_category = ?";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_category);

        if ($stmt->execute()) {

          return $stmt->fetch(PDO::FETCH_ASSOC);

        } else {
          return false;
        }
    }

    // UPDATE
    public function updateCategory()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        thumbnail = :thumbnail
                    WHERE
                        id_category = :id_category AND description = :description";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_category = htmlspecialchars(strip_tags($this->id_category));
        $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
        $this->description = htmlspecialchars(strip_tags($this->description));

        // bind data
        $stmt->bindParam(":id_category", $this->id_category);
        $stmt->bindParam(":thumbnail", $this->thumbnail);
        $stmt->bindParam(":description", $this->description);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteCategory()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_category = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_category = htmlspecialchars(strip_tags($this->id_category));

        $stmt->bindParam(1, $this->id_category);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
