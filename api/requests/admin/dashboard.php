<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Dashboard.php';
include_once '../../models/Encryptions.php';

$conn = new Database();
$db = $conn->getConnection();

$dashboard = new Dashboard($db);
$crypt = new encryption_class();

//hash config
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

//arrays
$dashboardArr = array();
$dashboardArr['balances'] = array();
$dashboardArr['invoices'] = array();
$dashboardArr['data'] = array();
$dashboardArr['users'] = array();
$dashboardArr['admin'] = array();

//user balances
$dashboard->getUsersBalances();
$dashboardArr['balances'] = array(
    "confirmer_balance" => $dashboard->confirmer_balance,
    "deliverer_balance" => $dashboard->deliverer_balance,
    "admin_balance" => $dashboard->admin_balance
);

//invoices paid
$stmt = $dashboard->getInvoices();
$itemCount = $stmt->rowCount();
if($itemCount != 0){
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_invoice = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
        $id_user = $crypt->encrypt($key_user, $id_user, $min_length, $max_length);
        $e = array(
            "id_invoice" => $id_invoice,
            "full_name" => $full_name,
            "type" => $type,
            "credit" => $credit,
            "bill" => $bill,
            "creation_date" => $creation_date
        );

        array_push($dashboardArr['invoices'], $e);
    }
}

//account data
$dashboard->getAccountData();
$dashboardArr['data'] = array(
    "products" => $dashboard->products,
    "orders" => $dashboard->orders,
    "packed" => $dashboard->packed,
    "invoices" => $dashboard->invoices
);

//orders data
$dashboard->getOrdersData();
$dashboardArr['orders_status'] = array(
  "confirm" => $dashboard->confirm,
  "confirmations" => $dashboard->confirmations,
  "shipped" => $dashboard->shipped,
  "deliveries" => $dashboard->deliveries
);

//Admin Info
$dashboard->getAdminInfo();
$dashboardArr['admin'] = array(
    "email" => $dashboard->email,
    "password" => $dashboard->password,
    "logo" => $dashboard->logo,
    "name" => $dashboard->name
);

/*//users count
$dashboard->getUsersCount();
$dashboardArr['users'] = array(
    "clients" => $dashboard->clients,
    "deliverers" => $dashboard->deliverers
);
*/
echo json_encode($dashboardArr);
