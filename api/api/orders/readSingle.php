<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Orders.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$min_length = 8;
$max_length = 8;

//get the order id data
$encrypted_id_= isset($_GET['id']) ? $_GET['id'] : die('you need to specify id!');

// DECRYPT
$id_dec = $crypt->decrypt($key, $encrypted_id_);
$id = intval($id_dec);

//profile info
//initial classes
$order = new Order($db);
$order->id_order = $id;
if ($dataRow = $order->getSingleOrder()) {
    $orderObj = array(
        "id_order" => $dataRow['id_order'],
        "store" => $dataRow['store'],
        "product_id" => $dataRow['product_id'],
        "product_title" => $dataRow['product_title'],
        "product_link" => $dataRow['product_link'],
        "order_date" => $dataRow['order_date'],
        "items_quantity" => $dataRow['items_quantity'],
        "price" => $dataRow['price'],
        "buyer_name" => $dataRow['buyer_name'],
        "buyer_phone" => $dataRow['buyer_phone'],
        "buyer_city" => $dataRow['buyer_city'],
        "buyer_address" => $dataRow['buyer_address'],
        "label" => $dataRow['label'],
        "status" => $dataRow['status'],
    );
} else {
    echo 'err';
}

if (!empty($orderObj)) {
    http_response_code(200);
    echo json_encode($orderObj);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
