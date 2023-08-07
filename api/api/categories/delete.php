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
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CATEGORIES";
$min_length = 8;
$max_length = 8;

$id_category_enc_arr = isset($_GET['id_category']) ? $_GET['id_category'] : die('you need to specify id_category!');

$id_category_dec = $crypt->decrypt($key, $id_category_enc_arr);

//delete order_items that have id_order
$sqlQuery = "DELETE from products_categories WHERE id_category = $id_category_dec";
$stmt = $db->prepare($sqlQuery);
if ($stmt->execute()) {
  $sqlQuery = "DELETE from categories WHERE id_category = $id_category_dec";
  $stmt = $db->prepare($sqlQuery);
  if ($stmt->execute()) {
    echo true;
  } else {
    echo "can't delete category";
  }
} else {
  echo "can't delete from product_category";
}
