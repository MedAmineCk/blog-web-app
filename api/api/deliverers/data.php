<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Deliverers.php';
include_once '../../class/Encryptions.php';

$deliverer_database = new Database();
$db = $deliverer_database->getConnection();

//hash configt
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
$deliverer_data->getSingleDelivererData();

if ($deliverer_data->price_delivered != null) {
    $deliverer_data_arr = array(
        "price_delivered" => $deliverer_data->price_delivered,
        "price_returned" => $deliverer_data->price_returned,
        "num_deliver" => $deliverer_data->num_deliver,
        "num_return" => $deliverer_data->num_return,
        "total" => $deliverer_data->total,
    );
} else {
    $deliverer_data_arr = array(
        "price_delivered" => 0,
        "price_returned" => 0,
        "num_deliver" => 0,
        "num_return" => 0,
        "total" => 0,
    );
}

echo json_encode($deliverer_data_arr);
