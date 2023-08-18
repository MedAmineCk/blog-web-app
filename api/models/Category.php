<?php
class Category {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getCategories() {
        $query = "SELECT id, name FROM categories";
        $result = $this->conn->query($query);

        $categories = array();

        if ($result->rowCount() > 0) { // Use rowCount() here
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $categories[] = $row;
            }
        }

        return $categories;
    }


    public function getCategory($categoryId) {
        $stmt = $this->conn->prepare("SELECT id, name FROM categories WHERE id = ?");
        $stmt->bind_param("i", $categoryId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $category = $result->fetch_assoc();
            $stmt->close();

            return $category;
        } else {
            return null; // Error occurred
        }
    }

    public function createCategory($name) {
        $stmt = $this->conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $name);

        if ($stmt->execute()) {
            $category_id = $stmt->insert_id;
            $stmt->close();
            return $category_id;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function updateCategory($categoryId, $newLabel) {
        $stmt = $this->conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $newLabel, $categoryId);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }

    public function deleteCategory($categoryId) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->bind_param("i", $categoryId);

        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    }
}
