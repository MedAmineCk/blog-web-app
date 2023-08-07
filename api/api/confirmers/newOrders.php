<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();
$orders = new Order($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

// DECRYPT
$stmt = $orders->getAllNewOrders();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $LinkedOrdersArr = array();
    $LinkedOrdersArr["linkedOrders"] = array();
    $LinkedOrdersArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_order = $crypt->encrypt($key, $id_order, $min_length, $max_length);
        $e = array(
            "id_order" => $id_order,
            "order_date" => $created_at,
            "status" => $status,
        );

        array_push($LinkedOrdersArr["linkedOrders"], $e);
    }
    http_response_code(200);
    echo json_encode($LinkedOrdersArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
