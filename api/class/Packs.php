<?php
class Pack
{

    // Connection
    private $conn;

    // Table
    private $db_table = "deliverer_packs";

    // Columns
    public $id_pack;
    public $id_deliverer_pack;
    public $id_deliverer;
    public $label;
    public $created_date;
    public $status;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getPacks()
    {
        $sqlQuery = "
                    SELECT
                      deliverer_packs.id_deliverer_pack AS pack_id,
                      CONCAT(deliverers.first_name, ' ', deliverers.last_name) AS deliverer_name,
                      areas.area AS area_name,
                      COUNT(orders.id_order) AS order_count,
                      deliverer_packs.status AS pack_status,
                      deliverer_packs.created_at AS created_date
                    FROM
                      deliverer_packs
                        INNER JOIN deliverers ON deliverer_packs.id_deliverer = deliverers.id_deliverer
                        INNER JOIN areas ON deliverers.id_area = areas.id_area
                        LEFT JOIN orders ON deliverer_packs.id_deliverer_pack = orders.id_deliverer_pack
                    GROUP BY
                      deliverer_packs.id_deliverer_pack;
        ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createPack()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        id_deliverer = :id_deliverer,
                        label = :label,
                        created_date = :created_date
                        ";

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
    public function getSinglePack()
    {
        $sqlQuery = "SELECT * FROM deliverer_packs WHERE id_deliverer_pack = ? LIMIT 0,1";

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
    public function updatePack()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        id_deliverer = IF(LENGTH(:id_deliverer)=0, id_deliverer, :id_deliverer),
                        label = IF(LENGTH(:label)=0, label, :label),
                        created_date = IF(LENGTH(:created_date)=0, created_date, :created_date),
                        status = IF(LENGTH(:status)=0, status, :status)
                    WHERE
                        id_pack = :id_pack";

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
    public function deletePack()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_pack = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_pack = htmlspecialchars(strip_tags($this->id_pack));

        $stmt->bindParam(1, $this->id_pack);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
