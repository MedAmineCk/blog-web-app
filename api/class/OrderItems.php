<?php
class OrderItem
{

    // Connection
    private $conn;

    // Table
    private $db_table = "order_items";

    // Columns
    public $id_orderItem;
    public $id_product;
    public $titel;
    public $thumbnail;
    public $option;
    public $price;
    public $quantity;
    public $id_order;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getOrderItems()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE id_order = :id_order";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_order = htmlspecialchars(strip_tags($this->id_order));

        // bind data
        $stmt->bindParam(":id_order", $this->id_order);

        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createOrderItem()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        id_product = :id_product,
                        titel = :titel,
                        thumbnail = :thumbnail,
                        option = :option,
                        quantity = :quantity,
                        price = :price,
                        id_order = :id_order
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_product = htmlspecialchars(strip_tags($this->id_product));
        $this->titel = htmlspecialchars(strip_tags($this->titel));
        $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
        $this->option = htmlspecialchars(strip_tags($this->option));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->id_order = htmlspecialchars(strip_tags($this->id_order));

        // bind data
        $stmt->bindParam(":id_product", intval($this->id_product), PDO::PARAM_INT);
        $stmt->bindParam(":titel", $this->titel);
        $stmt->bindParam(":thumbnail", $this->thumbnail);
        $stmt->bindParam(":option", $this->option);
        $stmt->bindParam(":quantity", intval($this->quantity), PDO::PARAM_INT);
        $stmt->bindParam(":price", intval($this->price), PDO::PARAM_INT);
        $stmt->bindParam(":id_order", intval($this->id_order), PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // UPDATE
    public function getSingleOrderItem()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . " WHERE id_orderItem = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_orderItem);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id_product = $dataRow['id_product'];
        $this->titel = $dataRow['titel'];
        $this->thumbnail = $dataRow['thumbnail'];
        $this->option = $dataRow['option'];
        $this->quantity = $dataRow['quantity'];
        $this->price = $dataRow['price'];
        $this->id_order = $dataRow['id_order'];
    }

    // UPDATE
    public function updateOrderItem()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        id_product = IF(LENGTH(:id_product)=0, id_product, :id_product),
                        titel = IF(LENGTH(:titel)=0, titel, :titel),
                        thumbnail = IF(LENGTH(:thumbnail)=0, thumbnail, :thumbnail),
                        option = IF(LENGTH(:option)=0, option, :option),
                        quantity = IF(LENGTH(:quantity)=0, quantity, :quantity),
                        price = IF(LENGTH(:price)=0, price, :price),
                        id_order = IF(LENGTH(:id_order)=0, id_order, :id_order)
                    WHERE
                        id_orderItem = :id_orderItem";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_product = htmlspecialchars(strip_tags($this->id_product));
        $this->titel = htmlspecialchars(strip_tags($this->titel));
        $this->thumbnail = htmlspecialchars(strip_tags($this->thumbnail));
        $this->option = htmlspecialchars(strip_tags($this->option));
        $this->quantity = htmlspecialchars(strip_tags($this->quantity));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->id_order = htmlspecialchars(strip_tags($this->id_order));

        // bind data
        $stmt->bindParam(":id_product", $this->id_product);
        $stmt->bindParam(":titel", $this->titel);
        $stmt->bindParam(":thumbnail", $this->thumbnail);
        $stmt->bindParam(":option", $this->option);
        $stmt->bindParam(":quantity", $this->quantity);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":id_order", $this->id_order);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteOrderItem()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_orderItem = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_orderItem = htmlspecialchars(strip_tags($this->id_orderItem));

        $stmt->bindParam(1, $this->id_orderItem);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

}
