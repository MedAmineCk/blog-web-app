<?php
class Delivery
{

    // Connection
    private $conn;

    // Table
    private $db_table = "deliveries";

    // Columns
    public $id_deliverer;
    public $id_client;
    public $id_delivery;
    public $id_order;
    public $role_deliverer;
    public $delivery_datetime;
    public $delivery_status;
    public $comment;
    public $id_invoice;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getDeliveries()
    {
        $sqlQuery = "select * from deliveries where role_deliverer = 'Deliverer' and id_deliverer = ?;";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id_deliverer);
        $stmt->execute();
        return $stmt;
    }

    public function getClientDeliveries()
    {
        $sqlQuery = "select * from deliveries, orders where deliveries.id_order = orders.id_order and orders.id_client = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id_client);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createDelivery()
    {
        $sqlQuery = "";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_deliverer = htmlspecialchars(strip_tags($this->id_deliverer));
        $this->label = htmlspecialchars(strip_tags($this->label));
        $this->created_date = htmlspecialchars(strip_tags($this->created_date));

        // bind data
        $stmt->bindParam(":id_deliverer", $this->id_deliverer);
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":created_date", $this->created_date);

        if ($stmt->execute()) {
            $id_pack = $this->conn->lastInsertId();
            return $id_pack;
        } else {
            return "pack not created!";
        }
    }

    // UPDATE
    public function getSingleDelivery()
    {
        $sqlQuery = "SELECT * FROM deliveries WHERE id_delivery = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_deliverer_pack);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id_deliverer = $dataRow['id_deliverer'];
        $this->label = $dataRow['label'];
        $this->created_date = $dataRow['created_date'];
        $this->status = $dataRow['status'];
    }

    // UPDATE
    public function updateDelivery()
    {
        $sqlQuery = "";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_deliverer = htmlspecialchars(strip_tags($this->id_deliverer));
        $this->label = htmlspecialchars(strip_tags($this->label));
        $this->created_date = htmlspecialchars(strip_tags($this->created_date));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // bind data
        $stmt->bindParam(":id_deliverer", $this->id_deliverer);
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":created_date", $this->created_date);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteDelivery()
    {
        $sqlQuery = "DELETE FROM deliveries WHERE id_delivery = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_pack = htmlspecialchars(strip_tags($this->id_pack));

        $stmt->bindParam(1, $this->id_pack);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getInvoiceDeliveries(){
        $sqlQuery = "SELECT * from deliveries where id_invoice = ?;";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id_invoice);
        $stmt->execute();
        return $stmt;
    }

}
