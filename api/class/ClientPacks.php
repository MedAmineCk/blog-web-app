<?php
class ClientPack
{

    // Connection
    private $conn;

    // Table
    private $db_table = "client_packs";

    // Columns
    public $id_pack;
    public $id_client;
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
        $sqlQuery = "select client_packs.id_pack,
        (select CONCAT_WS(\" \", `first_name`, `last_name`) as deliverer from deliverers where deliverers.id_client = client_packs.id_client) as deliverer,
        (select city as city from deliverers where deliverers.id_client = client_packs.id_client) as city,
        client_packs.created_date,
        (select count(orders.id_order) as items from orders where orders.id_pack = client_packs.id_pack) as items,
        client_packs.status
        from orders, client_packs where orders.id_pack = client_packs.id_pack group by id_pack;";
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
                        id_client = :id_client,
                        created_date = :created_date,
                        label = :label
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_client = htmlspecialchars(strip_tags($this->id_client));
        $this->label = htmlspecialchars(strip_tags($this->label));
        $this->created_date = htmlspecialchars(strip_tags($this->created_date));

        // bind data
        $stmt->bindParam(":id_client", $this->id_client);
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
        $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE id_pack = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_pack);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id_client = $dataRow['id_client'];
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
                        id_client = IF(LENGTH(:id_client)=0, id_client, :id_client),
                        label = IF(LENGTH(:label)=0, label, :label),
                        created_date = IF(LENGTH(:created_date)=0, created_date, :created_date),
                        status = IF(LENGTH(:status)=0, status, :status)
                    WHERE
                        id_pack = :id_pack";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_client = htmlspecialchars(strip_tags($this->id_client));
        $this->label = htmlspecialchars(strip_tags($this->label));
        $this->created_date = htmlspecialchars(strip_tags($this->created_date));
        $this->status = htmlspecialchars(strip_tags($this->status));

        // bind data
        $stmt->bindParam(":id_client", $this->id_client);
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
