<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Orders.php';
include_once '../../models/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$orders = new Order($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$min_length = 8;
$max_length = 8;

$orders_obj_arr = array();
$orders_obj_arr["orders"] = array();

$searchEnc = isset($_GET['search']) ? $_GET['search'] : die('you need to specify search!');

// DECRYPT
$search = $crypt->decrypt($key, $searchEnc);
$search = intval($search);

$orders->search  = $search;

$stmt = $orders->getOrdersBySearch();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

  $OrderArr = array();
  $OrderArr["body"] = array();
  $OrderArr["itemCount"] = $itemCount;

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $id_order = $crypt->encrypt($key, $id_order, $min_length, $max_length);
    $e = array(
      "id_order" => $id_order,
      "order_date" => $order_date,
      "buyer_name" => $buyer_name,
      "items_quantity" => $items_quantity,
      "price" => $price,
      "buyer_city" => $buyer_city,
      "status" => $status
    );

    array_push($OrderArr["body"], $e);
  }
  $orders_obj_arr["orders"] = $OrderArr;
}

if (!empty($orders_obj_arr)) {
  http_response_code(200);
  echo json_encode($orders_obj_arr);
} else {
  http_response_code(404);
  echo json_encode(
    array("message" => "No record found.")
  );
}
