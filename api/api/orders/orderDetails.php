<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Encryptions.php';
include_once '../../class/Orders.php';

$database = new Database();
$db = $database->getConnection();

$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$min_length = 8;
$max_length = 8;

$encrypted_id_order = isset($_GET['id_order']) ? $_GET['id_order'] : die('you need to specify id_order!');
// DECRYPT
$id_order = intval($crypt->decrypt($key, $encrypted_id_order));

$order_obj_arr = array();

//order general data
$order = new Order($db);
$order->id_order = $id_order;
$order = $order->getSingleOrder();

if ($order) {
        extract($order);
        $orderDetails = array(
            "id_order" => $id_order,
            "product_title" => $title,
            "order_date" => $created_at,
            "items_quantity" => $items_quantity,
            "price" => $total,
            "label" => $label,
            "buyer_name" => $client_name,
            "buyer_phone" => $client_phone,
            "buyer_city" => $area,
            "buyer_address" => $client_address,
            "status" => $status,
        );
    echo json_encode($orderDetails);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
