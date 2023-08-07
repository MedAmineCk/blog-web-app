<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Packs.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';
include_once '../../class/Notifications.php';

$database = new Database();
$db = $database->getConnection();

$pack = new Pack($db);
$order = new Order($db);
$crypt = new encryption_class();
$notification = new Notification($db);

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_deliverer = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_pack = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PACKS";

$min_length = 8;
$max_length = 8;

$packObj = isset($_GET['_deliveryPackageObj']) ? $_GET['_deliveryPackageObj'] : die("no data passed!");

$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));
$id_deliverer_en = $packObj["_id_deliverer"];
$id_deliverer = intval($crypt->decrypt($key_deliverer, $id_deliverer_en));
$pack->id_deliverer = $id_deliverer;
$pack->label = $packObj["_label"];
$pack->created_date = $datetime;

if ($id_pack = $pack->createPack()) {
    //update pack id for each order
    $orders_arr = $packObj["_orders_id_arr"];
    $ordersSql = "(";
    foreach ($orders_arr as $order_id) {
        $ordersSql .= "" . $crypt->decrypt($key, $order_id) . ", ";
    }
    $ordersSql = substr($ordersSql, 0, -2);
    $ordersSql .= ")";
    $sql = "UPDATE orders SET id_deliverer_pack = " . $id_pack . " WHERE id_order IN " . $ordersSql;
    $stmt = $db->prepare($sql);

    if ($stmt->execute()) {
        $notification->target = 'Deliverer';
        $notification->id_target = $id_deliverer;
        $notification->content = 'new Orders Added to your profile';
        $notification->datetime = $datetime;

        if ($notification->createNotification()) {
            http_response_code(200);
            $id_pack_enc = $crypt->encrypt($key_pack, $id_pack, $min_length, $max_length);
            echo json_encode(array("id_pack" => $id_pack_enc));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create notification."));
        }
    } else {
        echo json_encode(array("message" => "error"));
    }

} else {
    echo false;
}
