<?php
class Client
{

    // Connection
    private $conn;

    // Table
    private $db_table = "clients";

    // Columns
    public $id_client;
    public $full_name;
    public $phone_number;
    public $city;
    public $address;
    public $email;
    //data variables
    public $num_delivered;
    public $num_returned;
    public $bill;
    public $profile_pic;
    public $password;
    // Db connection


    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getClients()
    {
        $sqlQuery = "SELECT * FROM " . $this->db_table . "";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    //get all data
    public function getAllData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "
        SELECT 'packages' AS type, COUNT(id_client_pack) AS count FROM client_packs WHERE id_client = :id
        UNION
        SELECT 'orders' AS type, COUNT(id_order) AS count FROM orders WHERE id_client = :id
        UNION
        SELECT 'deliveries' AS type, COUNT(id_delivery) AS count FROM deliveries, orders WHERE deliveries.id_order = orders.id_order AND orders.id_client = :id
        UNION
        SELECT 'invoices' AS type, COUNT(id_invoice) AS count FROM invoices WHERE id_user = :id AND type='client'";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":id", $this->id_client);
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


    // CREATE
    public function createClient()
    {
        $sqlQuery = "INSERT INTO
                        " . $this->db_table . "
                    SET
                        full_name = :full_name,
                        phone_number = :phone_number
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));

        // bind data
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":phone_number", $this->phone_number);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        } else {
            return "client not created!";
        }
    }

    // UPDATE
    public function getSingleClient()
    {
        $sqlQuery = "SELECT * FROM clients, users WHERE clients.id_client = users.id_user and id_client = ? and users.role = 'client';";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_client);

        $stmt->execute();

        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->profile_pic = $dataRow['profile_pic'];
        $this->full_name = $dataRow['full_name'];
        $this->phone_number = $dataRow['phone_number'];
        $this->email = $dataRow['email'];
        $this->password = $dataRow['password'];
    }

    // UPDATE
    public function updateClient()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        full_name = IF(LENGTH(:full_name)=0, full_name, :full_name),
                        phone_number = IF(LENGTH(:phone_number)=0, phone_number, :phone_number),
                        city = IF(LENGTH(:city)=0, city, :city),
                        address = IF(LENGTH(:address)=0, address, :address),
                        email = IF(LENGTH(:email)=0, email, :email)
                    WHERE
                        id_client = :id_client";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->full_name = htmlspecialchars(strip_tags($this->full_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // bind data
        $stmt->bindParam(":full_name", $this->full_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":email", $this->email);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // UPDATE profile client
    public function updateClientProfile()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        profile_pic = :profile_pic
                    WHERE
                        id_client = :id_client";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_client = htmlspecialchars(strip_tags($this->id_client));
        $this->profile_pic = htmlspecialchars(strip_tags($this->profile_pic));

        // bind data
        $stmt->bindParam(":id_client", $this->id_client);
        $stmt->bindParam(":profile_pic", $this->profile_pic);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteClient()
    {
        $sqlQuery = "DELETE FROM clients WHERE id_client = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_client = htmlspecialchars(strip_tags($this->id_client));

        $stmt->bindParam(1, $this->id_client);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    //GET CLIENT DATA
    // GET Single Delivery Data
    public function getSingleClientData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "SELECT IFNULL(SUM(IF(orders.status = 'Deliver', 1, 0)), 0) as num_delivered,
                               IFNULL(SUM(IF(orders.status = 'Return', 1, 0)), 0) as num_returned,
                               (select IFNULL(SUM(IF(orders.status = 'Deliver', orders.price - client_pricing.delivery_price, 0)) -
                                              SUM(IF(orders.status = 'Return', client_pricing.return_price, 0)), 0) as bill) as bill
                        FROM orders,
                             clients,
                             client_pricing
                        WHERE
                            orders.id_client = clients.id_client
                            and orders.id_area = client_pricing.id_area
                            and client_pricing.id_client = :id_client
                            and orders.isPaid = false
                            and clients.id_client = :id_client
                        ;";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":id_client", $this->id_client);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->num_delivered = $dataRow['num_delivered'];
        $this->num_returned = $dataRow['num_returned'];
        $this->bill = $dataRow['bill'];
    }

}
