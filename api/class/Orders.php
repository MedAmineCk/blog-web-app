<?php

class Order
{

  // Connection

  private $conn;

  // Table
  private $db_table = "orders";

  // Columns
  public $id_order;
  public $id_client_pack;
  public $id_deliverer_pack;
  public $purchase_date;
  public $import_message;
  public $id_client;
  public $id_deliverer;
  //Data filter
  public $Total;
  public $Pending;
  public $Processing;
  public $Confirm;
  public $unreachable;
  public $Return;
  public $Deliver;
  public $status;
  public $id_invoice;
  public $data_count;
  public $data_limit;
  public $filter;
  public $area;
  public $search;
  public $isPacked;
  public $sort;

  public $id_area;
  public $client_name;
  public $client_phone;
  public $client_address;
  public $label;

  public $id_product;
  public $items_quantity;
  public $id_variant;
  public $total;

  // Db connection

  public function __construct($db)
  {
    $this->conn = $db;
  }

  // GET ALL
  public function getOrders()
  {
    $filter = $this->filter;
    $area = $this->area;
    $search = $this->search;
    $isPacked = $this->isPacked;
    $sort = $this->sort;

//    $sqlQuery = "SELECT * FROM orders where $filter and $area and $search and $isPacked ORDER BY id_order $sort LIMIT $this->data_count,$this->data_limit;";
    $sqlQuery = "
                  select
                    orders.id_order,
                    orders.created_at,
                    orders.items_quantity,
                    orders.total,
                    orders.status,
                    orders.id_deliverer_pack,
                    areas.area,
                    clients_packs.client_name
                  from orders, areas , clients_packs
                  where
                    orders.id_client_pack = clients_packs.id_client_pack
                  and
                    clients_packs.id_area = areas.id_area
                  and $area and $filter and $search and $isPacked ORDER BY id_order $sort LIMIT $this->data_count,$this->data_limit;
                  ";

    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
  }

  public function getOrdersBySearch()
  {
    $search = $this->search;
    $sqlQuery = "SELECT * FROM orders where id_order = $search";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
  }

  //get filter Data
  public function getOrdersDataCounts()
  {
    $area = $this->area;
    $search = $this->search;
    $isPacked = $this->isPacked;
    $sqlQuery = "SELECT COUNT(id_order) AS Total,
                           IFNULL(SUM(IF(status = 'Pending', 1, 0)), 0) AS Pending,
                           IFNULL(SUM(IF(status = 'Return', 1, 0)), 0) AS cancel,
                           IFNULL(SUM(IF(status = 'Deliver', 1, 0)), 0) AS Deliver
                    from orders  o
INNER JOIN clients_packs cp ON o.id_client_pack = cp.id_client_pack
INNER JOIN areas  ON cp.id_area = areas.id_area where $area and $search and $isPacked";

    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->Total = $dataRow['Total'];
    $this->Pending = $dataRow['Pending'];
    $this->Return = $dataRow['cancel'];
    $this->Deliver = $dataRow['Deliver'];
  }

  //Update order status
  public function updateOrderStatus($id_order, $status)
  {
    $sqlQuery = "UPDATE orders SET status = :status WHERE id_order = :id_order;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(":id_order", $id_order);
    $stmt->bindParam(":status", $status);
    if ($stmt->execute()) {
      return true;
    }
  }

  //Update orders delivery Pack id
  public function updateOrderDeliveryPackId($ordersSql, $id_deliverer_pack)
  {
    $sqlQuery = "UPDATE orders SET id_deliverer_pack = :id_deliverer_pack WHERE id_order IN " . $ordersSql . ";";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(":id_deliverer_pack", $id_deliverer_pack);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }

  // CREATE
  public function createOrder()
  {
    $sqlQuery = "INSERT INTO orders SET
                      id_product = :id_product,
                      items_quantity = :items_quantity,
                      id_client_pack = :id_client_pack,
                      total = :total";

    if (isset($this->id_variant)) {
      $sqlQuery .= ", id_variant = :id_variant";
    }

    $stmt = $this->conn->prepare($sqlQuery);

    // sanitize
    $this->id_product = htmlspecialchars(strip_tags($this->id_product));
    $this->items_quantity = htmlspecialchars(strip_tags($this->items_quantity));
    $this->id_client_pack = htmlspecialchars(strip_tags($this->id_client_pack));
    $this->total = htmlspecialchars(strip_tags($this->total));

    // bind data
    $stmt->bindParam(":id_product", $this->id_product);
    $stmt->bindParam(":items_quantity", $this->items_quantity);
    $stmt->bindParam(":id_client_pack", $this->id_client_pack);
    $stmt->bindParam(":total", $this->total);

    if (isset($this->id_variant)) {
      $stmt->bindParam(":id_variant", $this->id_variant);
    }

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  public function createClientPack()
  {
    $sqlQuery = "INSERT INTO clients_packs SET
                        id_area = :id_area,
                        client_name = :client_name,
                        client_phone = :client_phone,
                        client_address = :client_address,
                        label = :label
                        ";

    $stmt = $this->conn->prepare($sqlQuery);

    // sanitize
    $this->id_area = htmlspecialchars(strip_tags($this->id_area));
    $this->client_name = htmlspecialchars(strip_tags($this->client_name));
    $this->client_phone = htmlspecialchars(strip_tags($this->client_phone));
    $this->client_address = htmlspecialchars(strip_tags($this->client_address));
    $this->label = htmlspecialchars(strip_tags($this->label));

    // bind data
    $stmt->bindParam(":id_area", $this->id_area);
    $stmt->bindParam(":client_name", $this->client_name);
    $stmt->bindParam(":client_phone", $this->client_phone);
    $stmt->bindParam(":client_address", $this->client_address);
    $stmt->bindParam(":label", $this->label);

    if ($stmt->execute()) {
      return $this->conn->lastInsertId();
    }
    return false;
  }

  //GET variant id by combination id
  public function get_variant_id($options)
  {
    // Assuming $db is a PDO object representing the database connection
    $stmt = $this->conn->prepare("SELECT id_variant FROM variants WHERE variant = :options");
    $stmt->bindValue(":options", $options);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      return $row['id_variant'];
    } else {
      return null;
    }
  }

  // UPDATE ORDERS PACK ID
  public function updateOrderPackId()
  {
    $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        id_pack = IF(LENGTH(:id_pack)=0, id_pack, :id_pack)
                    WHERE
                        id_order = :id_order";

    $stmt = $this->conn->prepare($sqlQuery);

    // sanitize
    $this->id_order = htmlspecialchars(strip_tags($this->id_order));
    $this->id_pack = htmlspecialchars(strip_tags($this->id_pack));

    // bind data
    $stmt->bindParam(":id_order", $this->id_order);
    $stmt->bindParam(":id_pack", $this->id_pack);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // UPDATE
  public function getSingleOrder()
  {
    $sqlQuery = "SELECT
  orders.id_order,
  orders.items_quantity,
  orders.total,
  orders.status,
  orders.created_at,
  products.title,
  products.price,
  clients_packs.client_name,
  clients_packs.client_address,
  clients_packs.client_phone,
  clients_packs.label,
  areas.area
FROM
  orders
    JOIN products ON orders.id_product = products.id_product
    JOIN clients_packs ON orders.id_client_pack = clients_packs.id_client_pack
    JOIN areas on clients_packs.id_area = areas.id_area
where orders.id_order = ?;";

    $stmt = $this->conn->prepare($sqlQuery);

    $stmt->bindParam(1, $this->id_order);

    if ($stmt->execute()) {
      $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
      return $dataRow;
    } else {
      return false;
    }
  }

  // UPDATE
  public function updateOrder()
  {
    $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        purchase_date = IF(LENGTH(:purchase_date)=0, purchase_date, :purchase_date),
                        import_message = IF(LENGTH(:import_message)=0, import_message, :import_message),
                        id_client = IF(LENGTH(:id_client)=0, id_client, :id_client)
                    WHERE
                        id_order = :id_order";

    $stmt = $this->conn->prepare($sqlQuery);

    // sanitize
    $this->purchase_date = htmlspecialchars(strip_tags($this->purchase_date));
    $this->import_message = htmlspecialchars(strip_tags($this->import_message));
    $this->id_client = htmlspecialchars(strip_tags($this->id_client));

    // bind data
    $stmt->bindParam(":purchase_date", $this->purchase_date);
    $stmt->bindParam(":import_message", $this->import_message);
    $stmt->bindParam(":id_client", $this->id_client);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // DELETE
  public function deleteOrder()
  {
    $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_order = ?";
    $stmt = $this->conn->prepare($sqlQuery);

    $this->id_order = htmlspecialchars(strip_tags($this->id_order));

    $stmt->bindParam(1, $this->id_order);

    if ($stmt->execute()) {
      return true;
    }
    return false;
  }

  // GET ALL LINKED DELIVERER ORDERS
  public function getAllLinkedDelivererOrders()
  {
    //check if there is some deliveries?
    $sqlQuery = "select count(id_delivery) as count from deliveries where id_deliverer = ?;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id_deliverer);
    if ($stmt->execute()) {
      $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($dataRow["count"] > 0) {
        $sqlQuery = "SELECT orders.id_order, orders.created_at, orders.status
                            from orders, deliverer_packs
                            where
                                orders.id_order not in (select id_order from deliveries where isPaid = true)
                            and
                                    orders.id_deliverer_pack = deliverer_packs.id_deliverer_pack
                              and
                                    deliverer_packs.id_deliverer = ?
                            group by orders.id_order";
      } else {
        $sqlQuery = "SELECT orders.id_order, orders.created_at, orders.status
                            from orders, deliverer_packs
                            where
                                orders.id_deliverer_pack = deliverer_packs.id_deliverer_pack
                            and
                                deliverer_packs.id_deliverer = ?
                            group by orders.id_order;";
      }
      $stmt = $this->conn->prepare($sqlQuery);
      $stmt->bindParam(1, $this->id_deliverer);
      $stmt->execute();
      return $stmt;
    } else {
      return false;
    }
  }

  //GET ALL CLIENT ORDERS
  public function getAllNewOrders()
  {
    $sqlQuery = "SELECT * from orders where status = 'Pending';";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
  }

  //GET ALL CLIENT PACKS
  public function getAllClientPacks()
  {
    $sqlQuery = "SELECT * from client_packs where id_client = ?;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id_client);
    $stmt->execute();
    return $stmt;
  }

  //GET ALL DELIVERER PACKS
  public function getAllDelivererPacks()
  {
    $sqlQuery = "SELECT * from deliverer_packs where id_deliverer = ?;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id_deliverer);
    $stmt->execute();
    return $stmt;
  }

  //GET CLIENT PACKS ORDERS
  public function getOrdersFromClientPack()
  {
    $sqlQuery = "SELECT * from orders where id_client_pack = ?;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id_client_pack);
    $stmt->execute();
    return $stmt;
  }

  //GET DELIVERER PACKS ORDERS
  public function getOrdersFromDelivererPack()
  {
    $sqlQuery = "SELECT * from orders where id_deliverer_pack = ?;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id_deliverer_pack);
    $stmt->execute();
    return $stmt;
  }

  //GET ALL INVOICE ORDERS
  public function getInvoiceOrders()
  {
    $sqlQuery = "SELECT * from orders where id_invoice = ?;";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->bindParam(1, $this->id_invoice);
    $stmt->execute();
    return $stmt;
  }
}
