<?php
class Deliverer
{

    // Connection
    private $conn;

    // Table
    private $db_table = "deliverers";

    // Columns
    public $id_deliverer;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $CIN;
    public $id_area;
    public $address;
    public $email;
    public $password;
    public $profile_pic;
    public $price_delivered;
    public $price_returned;
    public $packages;
    public $orders;
    public $deliveries;
    public $invoices;
    public $id_invoice;
    public $isPaid;

    // Db connection

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getDeliverers()
    {
        $sqlQuery = "
                      SELECT
                  deliverers.id_deliverer,
                  deliverers.CIN,
                  deliverers.profile_pic,
                  CONCAT(deliverers.first_name, ' ',  deliverers.last_name) AS deliverer_name,
                  deliverers.phone_number,
                  users.email,
                  users.password,
                  areas.area
              FROM
                  deliverers
                  JOIN areas ON deliverers.id_area = areas.id_area
                  JOIN users ON deliverers.id_deliverer = users.id_user
              WHERE
                  users.role = 'deliverer'

              ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createDeliverer()
    {
        $sqlQuery = "INSERT INTO deliverers
                    SET
                        first_name = :first_name,
                        last_name = :last_name,
                        phone_number = :phone_number,
                        CIN = :CIN,
                        id_area = :id_area,
                        address = :address,
                        price_delivered = :price_delivered,
                        price_returned = :price_returned
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->CIN = htmlspecialchars(strip_tags($this->CIN));
        $this->id_area = htmlspecialchars(strip_tags($this->id_area));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->price_delivered = htmlspecialchars(strip_tags($this->price_delivered));
        $this->price_returned = htmlspecialchars(strip_tags($this->price_returned));

        // bind data
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":CIN", $this->CIN);
        $stmt->bindParam(":id_area", $this->id_area);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":price_delivered", $this->price_delivered);
        $stmt->bindParam(":price_returned", $this->price_returned);

        if ($stmt->execute()) {
            //return id of created deliverer
            return $this->conn->lastInsertId();
        } else {
            return "deliverer not created!";
        }
    }


    public function getSingleDeliverer()
    {
      $sqlQuery = "
            SELECT
                  deliverers.id_deliverer,
                  deliverers.profile_pic,
                  CONCAT(deliverers.first_name, ' ',  deliverers.last_name) AS deliverer_name,
                  deliverers.phone_number,
                  deliverers.address,
                  deliverers.price_delivered,
                  deliverers.price_returned,
                  deliverers.CIN,
                  users.email,
                  users.password,
                  areas.area
              FROM
                  deliverers
                  JOIN areas ON deliverers.id_area = areas.id_area
                  JOIN users ON deliverers.id_deliverer = users.id_user
              WHERE
                  users.role = 'deliverer'
                  and
                deliverers.id_deliverer = ? group by id_deliverer LIMIT 0,1
              ";
        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_deliverer);

        $stmt->execute();

      return $stmt;
    }

    // GET Single Delivery Data
    public function getSingleDelivererData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "SELECT
                           deliverers.price_delivered,
                           deliverers.price_returned,
                           IFNULL(SUM(IF(deliveries.delivery_status = 'Deliver', 1, 0)), 0) AS num_deliver,
                           ifnull(SUM(IF(deliveries.delivery_status = 'Return', 1, 0)), 0) AS num_return,
                           SUM(IF(deliveries.delivery_status = 'Deliver', orders.total, 0)) as total
                    from orders,
                         deliverers,
                         deliveries
                    where deliveries.isPaid = false
                      and orders.id_order = deliveries.id_order
                      and deliverers.id_deliverer = deliveries.id_deliverer
                      and deliverers.id_deliverer = ?;";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(1, $this->id_deliverer);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->price_delivered = $dataRow['price_delivered'];
        $this->price_returned = $dataRow['price_returned'];
        $this->num_deliver = $dataRow['num_deliver'];
        $this->num_return = $dataRow['num_return'];
        $this->total = $dataRow['total'];
    }

    public function getAllData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "select 'packages' AS type, count(id_deliverer_pack) as count from deliverer_packs where id_deliverer = :id
                    union
                    select 'orders' AS type, count(orders.id_order) as count from orders, deliverer_packs where orders.id_deliverer_pack = deliverer_packs.id_deliverer_pack and deliverer_packs.id_deliverer = :id
                    union
                    select 'deliveries' AS type, count(id_delivery) as count from deliveries where id_deliverer = :id
                    union
                    select 'invoices' AS type, count(id_invoice) as count from invoices where id_user = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":id", $this->id_deliverer);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $propertyMap = [
            'packages' => 'packages',
            'orders' => 'orders',
            'deliveries' => 'deliveries',
            'invoices' => 'invoices'
        ];

        foreach ($result as $row) {
            $this->{$propertyMap[$row['type']]} = $row['count'];
        }
    }

    // UPDATE
    public function updateDeliverer()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        first_name = IF(LENGTH(:first_name)=0, first_name, :first_name),
                        last_name = IF(LENGTH(:last_name)=0, last_name, :last_name),
                        phone_number = IF(LENGTH(:phone_number)=0, phone_number, :phone_number),
                        CIN = IF(LENGTH(:CIN)=0, CIN, :CIN),
                        city = IF(LENGTH(:city)=0, city, :city),
                        address = IF(LENGTH(:address)=0, address, :address),
                        price_delivered = IF(LENGTH(:price_delivered)=0, price_delivered, :price_delivered),
                        price_returned = IF(LENGTH(:price_returned)=0, price_returned, :price_returned),
                        profile_pic = IF(LENGTH(:profile_pic)=0, profile_pic, :profile_pic),
                    WHERE
                        id_deliverer = :id_deliverer";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->CIN = htmlspecialchars(strip_tags($this->CIN));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->price_delivered = htmlspecialchars(strip_tags($this->price_delivered));
        $this->price_returned = htmlspecialchars(strip_tags($this->price_returned));
        $this->profile_pic = htmlspecialchars(strip_tags($this->profile_pic));

        // bind data
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":CIN", $this->CIN);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":price_delivered", $this->price_delivered);
        $stmt->bindParam(":price_returned", $this->price_returned);
        $stmt->bindParam(":profile_pic", $this->profile_pic);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // UPDATE profile deliverer
    public function updateDelivererProfile()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        profile_pic = :profile_pic
                    WHERE
                        id_deliverer = :id_deliverer";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_deliverer = htmlspecialchars(strip_tags($this->id_deliverer));
        $this->profile_pic = htmlspecialchars(strip_tags($this->profile_pic));

        // bind data
        $stmt->bindParam(":id_deliverer", $this->id_deliverer);
        $stmt->bindParam(":profile_pic", $this->profile_pic);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteDeliverer()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_deliverer = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_deliverer = htmlspecialchars(strip_tags($this->id_deliverer));

        $stmt->bindParam(1, $this->id_deliverer);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
