<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../models/Encryptions.php';

$crypt = new encryption_class();

$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
// Min length of 8 for encrypted string
$min_length = 8;
$max_length = 8;

$order_id = isset($_GET['id_order']) ? $_GET['id_order'] : die('you need to specify id_product!');

$encrypt_result = $crypt->encrypt($key, $order_id, $min_length, $max_length);

//echo json_encode($encrypt_result);
print "Encrypted: " . $encrypt_result . PHP_EOL;
