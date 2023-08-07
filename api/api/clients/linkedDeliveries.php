<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Deliveries.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash config
$key_order = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_deliverer = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_delivery = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-DELIVERIES";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

//get the profilObj data
$encrypted_id_client = isset($_GET['id']) ? $_GET['id'] : die('you need to specify id_client!');
// DECRYPT
$id_client_dec = $crypt->decrypt($key_deliverer, $encrypted_id_client);
$id_client = intval($id_client_dec);

//get deliveries
$delivery = new Delivery($db);
$delivery->id_client = $id_client;
$stmt = $delivery->getClientDeliveries();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
    $deliveries = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $id_delivery = $crypt->encrypt($key_delivery, $id_delivery, $min_length, $max_length);
        $id_order = $crypt->encrypt($key_order, $id_order, $min_length, $max_length);
        $id_invoice = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
        $obj = array(
            "id_delivery" => $id_delivery,
            "id_order" => $id_order,
            "role_deliverer" => $role_deliverer,
            "delivery_datetime" => $delivery_datetime,
            "delivery_status" => $delivery_status,
            "comment" => $comment,
            "id_invoice" => $id_invoice
        );
        array_push($deliveries , $obj);
    }
} else {
    echo "error!";
}

if (!empty($deliveries)) {
    http_response_code(200);
    echo json_encode($deliveries);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
