<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../class/Encryptions.php';

$crypt = new encryption_class();

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$order_id = isset($_GET['id_product']) ? $_GET['id_product'] : die('you need to specify id_product!');

// DECRYPT
$decrypt_result = $crypt->decrypt($key, $encrypt_result);

echo json_encode($decrypt_result);
