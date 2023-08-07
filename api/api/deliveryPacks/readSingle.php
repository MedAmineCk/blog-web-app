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
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PACKS";
$keyOrders = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$min_length = 8;
$max_length = 8;

//get the order id data
$encrypted_id_= isset($_GET['id']) ? $_GET['id'] : die('you need to specify id!');

// DECRYPT
$id_dec = $crypt->decrypt($key, $encrypted_id_);
$id = intval($id_dec);

//profile info
//initial classes
$orders = new Order($db);
$orders->id_deliverer_pack = $id;
$stmt = $orders->getOrdersFromDelivererPack();
$itemCount = $stmt->rowCount();

if ($itemCount) {

    $LinkedOrdersArr = array();
    $LinkedOrdersArr["packOrders"] = array();
    $LinkedOrdersArr["itemCount"] = $itemCount;

    //get Orders
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_order = $crypt->encrypt($keyOrders, $id_order, $min_length, $max_length);
        $e = array(
            "id_order" => $id_order,
            "order_date" => $created_at,
            "status" => $status
        );

        array_push($LinkedOrdersArr["packOrders"], $e);
    }

    //ge the label
    $stmt = $db->prepare("SELECT label FROM deliverer_packs WHERE id_deliverer_pack = :pack_id");
    $stmt->bindParam(':pack_id', $id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $LinkedOrdersArr["label"] = $result['label'];

    http_response_code(200);
    echo json_encode($LinkedOrdersArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
