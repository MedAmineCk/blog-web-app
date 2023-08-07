<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/ClientPacks.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$clientPack = new ClientPack($db);
$order = new Order($db);
$crypt = new encryption_class();

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_client = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_pricing = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PRICING";

$min_length = 8;
$max_length = 8;

$clientPackObj = isset($_GET['_packObj']) ? $_GET['_packObj'] : die("no data passed!");

$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));
$id_clientEncrypted = $clientPackObj["id_user"];
$id_client = $crypt->decrypt($key_client, $id_clientEncrypted);
$clientPack->id_client = $id_client;
$clientPack->created_date = $datetime;
$clientPack->label = $clientPackObj["label"];
$clientName = $clientPackObj["user_name"];

if ($id_clientPack = intval($clientPack->createPack())) {
    //create orders with giving id_pack
    $orders_arr = $clientPackObj["pack"];
    $orders_str = "";
    $sqlQuery = "INSERT INTO `orders` (`store`, `product_id`, `product_title`, `product_link`, `order_date`, `items_quantity`, `price`, `buyer_name`, `buyer_phone`, `buyer_city`, `buyer_address`, `label` ,`id_client`, `id_area`, `id_client_pack`) VALUES ";

    foreach ($orders_arr as $orderObj) {
        $productObj = $orderObj["productObj"];
        $buyerObj = $orderObj["buyerObj"];
        $id_area = $crypt->decrypt($key_pricing, $buyerObj["id_area"]);

        $orders_str .= "(
            '" . $productObj["store"] . "',
            '" . $productObj["product_id"] . "',
            '" . $productObj["product_title"] . "',
            '" . $productObj["product_link"] . "',
            '" . $productObj["order_date"] . "',
            " . $productObj["items_quantity"] . ",
            " . $productObj["price"] . ",
            '" . $buyerObj["buyer_name"] . "',
            '" . $buyerObj["buyer_phone"] . "',
            '" . $buyerObj["buyer_city"] . "',
            '" . $buyerObj["buyer_address"] . "',
            '" . $orderObj["label"] . "',
            " . $id_client . ",
            " . $id_area . ",
            " . $id_clientPack . "
        ), ";
    }
    $orders_count = count($orders_arr);
    $orders_str = substr($orders_str, 0, -2);
    $sqlQuery .= $orders_str;

    $stmt = $db->prepare($sqlQuery);
    if ($stmt->execute()) {
        $sqlQuery = "INSERT INTO `notifications` (`id_target`,`target`, `content`, `datetime`) VALUES
                    ('1', 'admin', 'new Pack($orders_count) created by $clientName', '$datetime');";
        $stmt = $db->prepare($sqlQuery);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(array("message" => "pack created successfully", "status" => "success"));
        } else {
            echo false;
        }
    } else {
        http_response_code(503);
        echo json_encode(array("message" => "pack created failed"));
    }

} else {
    echo false;
}
