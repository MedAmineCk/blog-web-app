<?php
class Invoice
{

    // Connection
    private $conn;

    // Table
    private $db_table = "invoices";

    // Columns
    public $id_invoice;
    public $id_user;
    public $full_name;
    public $type;
    public $credit;
    public $bill;
    public $comment;
    public $is_paid;
    public $status;
    public $creation_date;
    //data counts
    public $Total;
    public $Paid;
    public $unPaid;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getInvoices()
    {
        $sqlQuery = "SELECT * from invoices;";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    //get filter Data
    public function getInvoicesDataCounts()
    {
        $sqlQuery = "SELECT COUNT(id_invoice) AS Total,
                            SUM(IF(status = 'Paid', 1, 0)) AS Paid,
                            SUM(IF(status = 'unPaid', 1, 0)) AS unPaid
                    from " . $this->db_table . ";";

        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->Total = $dataRow['Total'];
        $this->Paid = $dataRow['Paid'];
        $this->unPaid = $dataRow['unPaid'];
    }

    public function getAllLinkedInvoices()
    {
        $sqlQuery = "SELECT * from invoices where type = :type and id_user = :id_user";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));

        // bind data
        $stmt->bindParam(':type', $this->type);
        $stmt->bindParam(':id_user', $this->id_user);

        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createInvoice()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        id_user = :id_user,
                        full_name = :full_name,
                        type = :type,
                        credit = :credit,
                        bill = :bill,
                        creation_date = :creation_date,
                        comment = :comment
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->credit = htmlspecialchars(strip_tags($this->credit));
        $this->bill = htmlspecialchars(strip_tags($this->bill));
        $this->comment = htmlspecialchars(strip_tags($this->comment));
        $this->creation_date = htmlspecialchars(strip_tags($this->creation_date));

        // bind data
        $stmt->bindParam(":id_user", $this->id_user);
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":credit", $this->credit);
        $stmt->bindParam(":bill", $this->bill);
        $stmt->bindParam(":comment", $this->comment);
        $stmt->bindParam(":creation_date", $this->creation_date);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return "invoice not created!";
        }
    }

    // UPDATE
    public function getSingleInvoice()
    {
        $sqlQuery = "SELECT * FROM invoices WHERE id_invoice = ?";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_invoice);

        if($stmt->execute()){
            while($dataRow = $stmt->fetch(PDO::FETCH_ASSOC)){
                $this->id_user = $dataRow['id_user'];
                $this->type = $dataRow['type'];
                $this->credit = $dataRow['credit'];
                $this->bill = $dataRow['bill'];
                $this->comment = $dataRow['comment'];
                $this->status = $dataRow['status'];
                $this->creation_date = $dataRow['creation_date'];
            }
        }else{
            echo false;
        }
    }

    // UPDATE
    public function updateInvoice()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        id_user = IF(LENGTH(:id_user)=0, id_user, :id_user),
                        type = IF(LENGTH(:type)=0, type, :type),
                        credit = IF(LENGTH(:credit)=0, credit, :credit),
                        bill = IF(LENGTH(:bill)=0, bill, :bill),
                        status = IF(LENGTH(:status)=0, status, :status)
                    WHERE
                        id_invoice = :id_invoice";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_user = htmlspecialchars(strip_tags($this->id_user));
        $this->type = htmlspecialchars(strip_tags($this->type));
        $this->credit = htmlspecialchars(strip_tags($this->credit));
        $this->bill = htmlspecialchars(strip_tags($this->bill));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // bind data
        $stmt->bindParam(":id_user", $this->id_user);
        $stmt->bindParam(":type", $this->type);
        $stmt->bindParam(":credit", $this->credit);
        $stmt->bindParam(":bill", $this->bill);
        $stmt->bindParam(":status", $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteInvoice()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_invoice = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_invoice = htmlspecialchars(strip_tags($this->id_invoice));

        $stmt->bindParam(1, $this->id_invoice);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateInvoiceStatus()
    {
        $sqlQuery = "UPDATE invoices SET status = :status WHERE id_invoice = :id_invoice;";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id_invoice = htmlspecialchars(strip_tags($this->id_invoice));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $stmt->bindParam(":id_invoice", $this->id_invoice);
        $stmt->bindParam(":status", $this->status);
        if ($stmt->execute()) {
            return true;
        }else{
            return false;
        }
    }

}
