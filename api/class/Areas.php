<?php
class Area
{

    // Connection

    private $conn;

    // Table
    private $db_table = "areas";

    // Columns
    public $id_area;
    public $area;
    public $shipping;
    public $return_price;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getAreas()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createArea()
    {
        //insert to pricing and client_pricing
        $sqlQuery = "INSERT INTO areas SET
                        area = :area,
                        shipping = :shipping,
                        return_price = :return_price";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->area = htmlspecialchars(strip_tags($this->area));
        $this->shipping = htmlspecialchars(strip_tags($this->shipping));
        $this->return_price = htmlspecialchars(strip_tags($this->return_price));

        // bind data
        $stmt->bindParam(":area", $this->area);
        $stmt->bindParam(":shipping", $this->shipping);
        $stmt->bindParam(":return_price", $this->return_price);

        if ($stmt->execute()) {
          return true;
        } else {
            return false;
        }
    }

    public function getSingleArea()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE id_area = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_area);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->area = $dataRow['area'];
        $this->price = $dataRow['price'];
    }

    // UPDATE
    public function updateArea()
    {
        $sqlQuery = "UPDATE areas SET
                        area = :area,
                        shipping = :shipping,
                        return_price = :return_price
                    WHERE
                        id_area = :id_area";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_area = htmlspecialchars(strip_tags($this->id_area));
        $this->area = htmlspecialchars(strip_tags($this->area));
        $this->shipping = htmlspecialchars(strip_tags($this->shipping));
        $this->return_price = htmlspecialchars(strip_tags($this->return_price));

        // bind data
        $stmt->bindParam(":id_area", $this->id_area);
        $stmt->bindParam(":area", $this->area);
        $stmt->bindParam(":shipping", $this->shipping);
        $stmt->bindParam(":return_price", $this->return_price);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteArea()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_area = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_area = htmlspecialchars(strip_tags($this->id_area));

        $stmt->bindParam(1, $this->id_area);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
