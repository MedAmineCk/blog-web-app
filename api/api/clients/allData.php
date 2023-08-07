<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Clients.php';
include_once '../../class/Encryptions.php';

$client_database = new Database();
$db = $client_database->getConnection();

//hash config
$key_orders = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$crypt = new encryption_class();
$client_data = new Client($db);

$id_client = isset($_GET['id_user']) ? $_GET['id_user'] : die('you need to specify id_user!');

// DECRYPT
$id_client = intval($crypt->decrypt($key, $id_client));

//profile data

$client_data->id_client = $id_client;
$client_data->getAllData();

if ($client_data->packages != null) {
    $client_data_arr = array(
        "packages" => $client_data->packages,
        "orders" => $client_data->orders,
        "deliveries" => $client_data->deliveries,
        "invoices" => $client_data->invoices
    );
} else {
    $client_data_arr = array(
        "packages" => 0,
        "orders" => 0,
        "deliveries" => 0,
        "invoices" => 0
    );
}

echo json_encode($client_data_arr);
