<?php
class Dashboard
{

    // Connection
    private $conn;

    // Columns
    //balance
    public $orders_total;
    public $confirmer_balance;
    public $deliverer_balance;
    public $admin_balance;
    //account data
    public $orders;
    public $packed;
    public $delivered;
    public $returned;
    public $invoices;
    public $products;
    // users count
    public $clients;
    public $deliverers;
    //admin info
    public $email;
    public $password;
    public $logo;
    public $name;
    // orders status
    public $confirm;
    public $confirmations;
    public $shipped;
    public $deliveries;


    // Db connection


    public function __construct($db)
    {
        $this->conn = $db;
    }

    //get all data
    public function getUsersBalances()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "
select 'orders_total' as type, IFNULL(sum(total), 0) as balance from orders where status = 'Deliver'
union
select 'deliverer_balance' as type, IFNULL(sum(invoices.credit), 0) as balance from invoices where status = 'Paid' and type = 'deliverer'
union
select 'confirmer_balance' as type, IFNULL(sum(invoices.credit), 0) as balance from invoices where status = 'Paid' and type = 'confirmer';";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $propertyMap = [
            'orders_total' => 'orders_total',
            'deliverer_balance' => 'deliverer_balance',
            'confirmer_balance' => 'confirmer_balance'
        ];

        foreach ($result as $row) {
            $this->{$propertyMap[$row['type']]} = $row['balance'];
        }

        $this->admin_balance = $this->orders_total - ($this->deliverer_balance + $this->confirmer_balance);
    }

    //get the last 3 invoices
    public function getInvoices()
    {
        $sqlQuery = "select * from invoices where status = 'Paid' order by id_invoice DESC LIMIT 3;";

        $stmt = $this->conn->prepare($sqlQuery);

        if($stmt->execute()){
            return $stmt;
        }
    }

    //get account data
    public function getAccountData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "
                    select 'products' as type, count(id_product) as number from products
                    union
                    select 'orders' as type, count(id_order) as number from orders
                    union
                    select 'packed' as type, count(id_order) as number from orders where id_deliverer_pack is not null
                    union
                    select 'invoices' as type, count(id_invoice) as number from invoices
                    ";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $propertyMap = [
            'products' => 'products',
            'orders' => 'orders',
            'packed' => 'packed',
            'invoices' => 'invoices'
        ];

        foreach ($result as $row) {
            $this->{$propertyMap[$row['type']]} = $row['number'];
        }
    }

    //get account data
    public function getOrdersData()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "
                    select 'confirm' as type, count(id_confirmation) as number from confirmations where confirmation_status = 'Confirm'
                    union
                    select 'confirmations' as type, count(id_confirmation) as number from confirmations
                    union
                    select 'shipped' as type, count(id_delivery) as number from deliveries where delivery_status = 'Deliver'
                    union
                    select 'deliveries' as type, count(id_delivery) as number from deliveries
                    ";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $propertyMap = [
            'confirm' => 'confirm',
            'confirmations' => 'confirmations',
            'shipped' => 'shipped',
            'deliveries' => 'deliveries'
        ];

        foreach ($result as $row) {
            $this->{$propertyMap[$row['type']]} = $row['number'];
        }
    }

    //get users count
    public function getUsersCount()
    {
        //get prices for orders: (deliver/return) , number of orders: (deliver/return)
        $sqlQuery = "select 'clients' as type, count(id_client) as number from clients
                    union
                    select 'deliverers' as type, count(id_deliverer) as number from deliverers";

        $stmt = $this->conn->prepare($sqlQuery);

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $propertyMap = [
            'clients' => 'clients',
            'deliverers' => 'deliverers'
        ];

        foreach ($result as $row) {
            $this->{$propertyMap[$row['type']]} = $row['number'];
        }
    }

    //get Admin info
    public function getAdminInfo()
    {
        $sqlQuery = "select * from users where role = 'admin'";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->email = $dataRow['email'];
        $this->password = $dataRow['password'];

        $sqlQuery = "select logo, name from config";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->logo = $dataRow['logo'];
        $this->name = $dataRow['name'];
    }

    //update Admin info
    public function updateConfig(){
        $sqlQuery = "UPDATE config
                    SET
                        name = :name
                    WHERE
                        id_config = 1";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":name", $this->name);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateAdminAuth(){
        $sqlQuery = "UPDATE users
                    SET
                        password = :password,
                        email = :email
                    WHERE
                        id_user = 1 AND role = 'admin'";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateAdminLogo(){
        $sqlQuery = "UPDATE config
                    SET
                        logo = :logo
                    WHERE
                        id_config = 1";

        $stmt = $this->conn->prepare($sqlQuery);

        // bind data
        $stmt->bindParam(":logo", $this->logo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
