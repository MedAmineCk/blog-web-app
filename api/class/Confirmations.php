<?php
class Confirmation
{

    // Connection
    private $conn;

    // Table
    private $db_table = "confirmations";

    // Columns
    public $id_confirmation;
    public $id_confirmer;
    public $id_order;
    public $role_confirmer;
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
    public function getConfirmations()
    {
        $sqlQuery = "select * from confirmations where role_confirmer = 'Confirmer' and id_confirmer = ?;";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id_confirmer);
        $stmt->execute();
        return $stmt;
    }


    // CREATE
    public function createConfirmation()
    {
        $sqlQuery = "";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_confirmation = htmlspecialchars(strip_tags($this->id_confirmation));
        $this->label = htmlspecialchars(strip_tags($this->label));
        $this->created_date = htmlspecialchars(strip_tags($this->created_date));

        // bind data
        $stmt->bindParam(":$this->id_confirmation", $this->id_confirmation);
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
    public function getSingleConfirmation()
    {
        $sqlQuery = "SELECT * FROM confirmations WHERE id_confirmation = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_confirmation_pack);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id_confirmation = $dataRow['$this->id_confirmation'];
        $this->label = $dataRow['label'];
        $this->created_date = $dataRow['created_date'];
        $this->status = $dataRow['status'];
    }

    // UPDATE
    public function updateConfirmation()
    {
        $sqlQuery = "";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_confirmation = htmlspecialchars(strip_tags($this->id_confirmation));
        $this->label = htmlspecialchars(strip_tags($this->label));
        $this->created_date = htmlspecialchars(strip_tags($this->created_date));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // bind data
        $stmt->bindParam(":$this->id_confirmation", $this->id_confirmation);
        $stmt->bindParam(":label", $this->label);
        $stmt->bindParam(":created_date", $this->created_date);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteConfirmation()
    {
        $sqlQuery = "DELETE FROM confirmations WHERE id_confirmation = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_pack = htmlspecialchars(strip_tags($this->id_pack));

        $stmt->bindParam(1, $this->id_pack);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getInvoiceConfirmations(){
        $sqlQuery = "SELECT * from confirmations where id_invoice = ?;";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->bindParam(1, $this->id_invoice);
        $stmt->execute();
        return $stmt;
    }

}
