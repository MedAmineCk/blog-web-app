<?php
class Data
{

    // Connection
    private $conn;

    //variable
    public $id_deliverer;
    public $price_delivered;
    public $price_returned;
    public $num_orders;
    public $num_deliver;
    public $num_return;
    public $total;

    // Db connection
    public function __construct($db)
    {
        $this->conn = $db;
    }
}
