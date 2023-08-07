<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PRODUCTS";
$min_length = 8;
$max_length = 8;

$id_product_enc_arr = isset($_GET['id_product']) ? $_GET['id_product'] : die('you need to specify id_product!');

$id_product_dec = $crypt->decrypt($key, $id_product_enc_arr);

//delete order_items that have id_order
$sqlQuery = "DELETE from products WHERE id_product = $id_product_dec";
$stmt = $db->prepare($sqlQuery);
if ($stmt->execute()) {
  echo true;
} else {
  echo false;
}
