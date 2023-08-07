<?php
class Confirmer
{

    // Connection
    private $conn;

    // Table
    private $db_table = "confirmers";

    // Columns
    public $id_confirmer;
    public $first_name;
    public $last_name;
    public $phone_number;
    public $CIN;
    public $id_area;
    public $address;
    public $email;
    public $password;
    public $profile_pic;
    public $price_confirm;
    public $price_cancel;
    public $packages;
    public $orders;
    public $confirmations;
    public $invoices;
    public $id_invoice;
    public $isPaid;

    // Db connection

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // GET ALL
    public function getConfirmers()
    {
        $sqlQuery = "
                      SELECT
                  confirmers.id_confirmer,
                  confirmers.CIN,
                  confirmers.profile_pic,
                  CONCAT(confirmers.first_name, ' ',  confirmers.last_name) AS confirmer_name,
                  confirmers.phone_number,
                  users.email,
                  users.password
              FROM
                  confirmers
                  JOIN users ON confirmers.id_confirmer = users.id_user
              WHERE
                  users.role = 'confirmer'

              ";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    // CREATE
    public function createConfirmer()
    {
        $sqlQuery = "INSERT INTO confirmers
                    SET
                        first_name = :first_name,
                        last_name = :last_name,
                        phone_number = :phone_number,
                        CIN = :CIN,
                        address = :address,
                        price_confirm = :price_confirm,
                        price_cancel = :price_cancel
                        ";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->CIN = htmlspecialchars(strip_tags($this->CIN));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->price_confirm = htmlspecialchars(strip_tags($this->price_confirm));
        $this->price_cancel = htmlspecialchars(strip_tags($this->price_cancel));

        // bind data
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":CIN", $this->CIN);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":price_confirm", $this->price_confirm);
        $stmt->bindParam(":price_cancel", $this->price_cancel);

        if ($stmt->execute()) {
            //return id of created confirmer
            return $this->conn->lastInsertId();
        } else {
            return "confirmer not created!";
        }
    }


    public function getSingleConfirmer()
    {
      $sqlQuery = "
            SELECT
                  confirmers.id_confirmer,
                  confirmers.profile_pic,
                  CONCAT(confirmers.first_name, ' ',  confirmers.last_name) AS confirmer_name,
                  confirmers.phone_number,
                  confirmers.address,
                  confirmers.price_confirm,
                  confirmers.price_cancel,
                  confirmers.CIN,
                  users.email,
                  users.password
              FROM
                  confirmers
                  JOIN users ON confirmers.id_confirmer = users.id_user
              WHERE
                  users.role = 'confirmer'
                  and
                confirmers.id_confirmer = ? group by id_confirmer LIMIT 0,1
              ";
        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->bindParam(1, $this->id_confirmer);

        $stmt->execute();

      return $stmt;
    }

    // GET Single Delivery Data
    public function getSingleConfirmerData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "SELECT
                           confirmers.price_confirm,
                           confirmers.price_cancel,
                           IFNULL(SUM(IF(confirmations.confirmation_status = 'Confirm', 1, 0)), 0) AS num_confirm,
                           ifnull(SUM(IF(confirmations.confirmation_status = 'Cancel', 1, 0)), 0) AS num_cancel
                    from confirmers,
                         confirmations
                    where confirmations.isPaid = false
                      and confirmers.id_confirmer = confirmations.id_confirmer
                      and confirmers.id_confirmer = ?;";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(1, $this->id_confirmer);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->price_confirm = $dataRow['price_confirm'];
        $this->price_cancel = $dataRow['price_cancel'];
        $this->num_confirm = $dataRow['num_confirm'];
        $this->num_cancel = $dataRow['num_cancel'];
        $this->total = (($dataRow['price_confirm']*$dataRow['num_confirm']) + ($dataRow['price_cancel']*$dataRow['num_cancel']));
    }

    public function getAllData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "select 'orders' AS type, count(orders.id_order) as count from orders where orders.status='Pending'
                    union
                    select 'confirmations' AS type, count(id_confirmation) as count from confirmations where id_confirmer = :id
                    union
                    select 'invoices' AS type, count(id_invoice) as count from invoices where id_user = :id";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":id", $this->id_confirmer);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $propertyMap = [
            'orders' => 'orders',
            'confirmations' => 'confirmations',
            'invoices' => 'invoices'
        ];

        foreach ($result as $row) {
            $this->{$propertyMap[$row['type']]} = $row['count'];
        }
    }

    // UPDATE
    public function updateConfirmer()
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
                        price_confirm = IF(LENGTH(:price_confirm)=0, price_confirm, :price_confirm),
                        price_cancel = IF(LENGTH(:price_cancel)=0, price_cancel, :price_cancel),
                        profile_pic = IF(LENGTH(:profile_pic)=0, profile_pic, :profile_pic),
                    WHERE
                        id_confirmer = :id_confirmer";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->phone_number = htmlspecialchars(strip_tags($this->phone_number));
        $this->CIN = htmlspecialchars(strip_tags($this->CIN));
        $this->city = htmlspecialchars(strip_tags($this->city));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->price_confirm = htmlspecialchars(strip_tags($this->price_confirm));
        $this->price_cancel = htmlspecialchars(strip_tags($this->price_cancel));
        $this->profile_pic = htmlspecialchars(strip_tags($this->profile_pic));

        // bind data
        $stmt->bindParam(":first_name", $this->first_name);
        $stmt->bindParam(":last_name", $this->last_name);
        $stmt->bindParam(":phone_number", $this->phone_number);
        $stmt->bindParam(":CIN", $this->CIN);
        $stmt->bindParam(":city", $this->city);
        $stmt->bindParam(":address", $this->address);
        $stmt->bindParam(":price_confirm", $this->price_confirm);
        $stmt->bindParam(":price_cancel", $this->price_cancel);
        $stmt->bindParam(":profile_pic", $this->profile_pic);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // UPDATE profile confirmer
    public function updateConfirmerProfile()
    {
        $sqlQuery = "UPDATE
                        " . $this->db_table . "
                    SET
                        profile_pic = :profile_pic
                    WHERE
                        id_confirmer = :id_confirmer";

        $stmt = $this->conn->prepare($sqlQuery);

        // sanitize
        $this->id_confirmer = htmlspecialchars(strip_tags($this->id_confirmer));
        $this->profile_pic = htmlspecialchars(strip_tags($this->profile_pic));

        // bind data
        $stmt->bindParam(":id_confirmer", $this->id_confirmer);
        $stmt->bindParam(":profile_pic", $this->profile_pic);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // DELETE
    public function deleteConfirmer()
    {
        $sqlQuery = "DELETE FROM " . $this->db_table . " WHERE id_confirmer = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->id_confirmer = htmlspecialchars(strip_tags($this->id_confirmer));

        $stmt->bindParam(1, $this->id_confirmer);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
