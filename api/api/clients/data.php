<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Clients.php';
include_once '../../class/Encryptions.php';

$conn = new Database();
$db = $conn->getConnection();

//hash configt
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
$client_data->getSingleClientData();

if ($client_data->num_delivered != null) {
    $client_data_arr = array(
        "num_delivered" => $client_data->num_delivered,
        "num_returned" => $client_data->num_returned,
        "bill" => $client_data->bill,
    );
} else {
    $client_data_arr = array(
        "num_delivered" => 0,
        "num_returned" => 0,
        "bill" => 0,
    );
}

echo json_encode($client_data_arr);
