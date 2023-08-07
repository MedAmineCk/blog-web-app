<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Confirmers.php';
include_once '../../class/Encryptions.php';

$confirmer_database = new Database();
$db = $confirmer_database->getConnection();

//hash config
$key_orders = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$crypt = new encryption_class();
$confirmer_data = new Confirmer($db);

$id_confirmer = isset($_GET['id_user']) ? $_GET['id_user'] : die('you need to specify id_user!');

// DECRYPT
$id_confirmer = intval($crypt->decrypt($key, $id_confirmer));

//profile data

$confirmer_data->id_confirmer = $id_confirmer;
$confirmer_data->getSingleConfirmerData();

if ($confirmer_data->price_confirm != null) {
    $confirmer_data_arr = array(
        "price_confirm" => $confirmer_data->price_confirm,
        "price_cancel" => $confirmer_data->price_cancel,
        "num_confirm" => $confirmer_data->num_confirm,
        "num_cancel" => $confirmer_data->num_cancel,
        "total" => $confirmer_data->total,
    );
} else {
    $confirmer_data_arr = array(
        "price_confirm" => 0,
        "price_cancel" => 0,
        "num_confirm" => 0,
        "num_cancel" => 0,
        "total" => 0,
    );
}

echo json_encode($confirmer_data_arr);
