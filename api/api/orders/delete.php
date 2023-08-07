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

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$min_length = 8;
$max_length = 8;

$id_orders_enc_arr = isset($_GET['_tracking_numbers']) ? $_GET['_tracking_numbers'] : die('you need to specify _tracking_numbers!');

$id_orders_decrypted = "(";
$i = 0;

foreach ($id_orders_enc_arr as $index => $id_order) {
    // DECRYPT
    $id_order_dec = $crypt->decrypt($key, $id_order);
    if ($i != 0) {
        $id_orders_decrypted .= ", " . $id_order_dec;
    } else {
        $id_orders_decrypted .= $id_order_dec;
    }
    $i++;
}
$id_orders_decrypted .= ")";

//delete order_items that have id_order
$sqlQuery = "DELETE from order_items WHERE id_order IN " . $id_orders_decrypted;
$stmt = $db->prepare($sqlQuery);
if ($stmt->execute()) {
    //delete orders has id_order
    $sqlQuery = "DELETE from orders WHERE id_order IN " . $id_orders_decrypted;
    $stmt = $db->prepare($sqlQuery);
    if ($stmt->execute()) {
        echo true;
    } else {
        echo false;
    }
} else {
    echo false;
}
