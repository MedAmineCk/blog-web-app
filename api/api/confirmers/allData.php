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
$confirmer_data->getAllData();

$confirmer_data_arr = array(
  "orders" => $confirmer_data->orders,
  "confirmations" => $confirmer_data->confirmations,
  "invoices" => $confirmer_data->invoices
);

echo json_encode($confirmer_data_arr);
