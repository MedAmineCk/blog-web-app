<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Deliverers.php';
include_once '../../class/Encryptions.php';

$deliverer_database = new Database();
$db = $deliverer_database->getConnection();

//hash config
$key_orders = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$crypt = new encryption_class();
$deliverer_data = new Deliverer($db);

$id_deliverer = isset($_GET['id_user']) ? $_GET['id_user'] : die('you need to specify id_user!');

// DECRYPT
$id_deliverer = intval($crypt->decrypt($key, $id_deliverer));

//profile data

$deliverer_data->id_deliverer = $id_deliverer;
$deliverer_data->getAllData();

if ($deliverer_data->packages != null) {
    $deliverer_data_arr = array(
        "packages" => $deliverer_data->packages,
        "orders" => $deliverer_data->orders,
        "deliveries" => $deliverer_data->deliveries,
        "invoices" => $deliverer_data->invoices
    );
} else {
    $deliverer_data_arr = array(
        "packages" => 0,
        "orders" => 0,
        "deliveries" => 0,
        "invoices" => 0
    );
}

echo json_encode($deliverer_data_arr);
