<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$crypt = new encryption_class();

//hash configt
$key_order = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$_updateStatusObj = isset($_GET['_updateStatusObj']) ? $_GET['_updateStatusObj'] : die('you need to specify id_order!');

$admin_role = $_updateStatusObj["_administrator"]; //Dashboard - Deliverer
$id_admin_encrypt = $_updateStatusObj["_id_admin"];
$id_order_encrypt = $_updateStatusObj["_id_order"];
$status = $_updateStatusObj["_status"];
$comment = $_updateStatusObj["_comment"];

$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));

// DECRYPT USER
$id_admin = intval($crypt->decrypt($key_user, $id_admin_encrypt));
$id_order = intval($crypt->decrypt($key_order, $id_order_encrypt));

if ($order->updateOrderStatus($id_order, $status)) {
  if($admin_role == 'confirmer'){
    $sqlQuery = "INSERT INTO `confirmations` (`id_order`, `id_confirmer`, `role_confirmer`, `created_at`, `confirmation_status`, `comment`) VALUES
    ($id_order, $id_admin, '$admin_role', '$datetime', '$status', '$comment');";
    $stmt = $db->prepare($sqlQuery);
  }else{
    $sqlQuery = "INSERT INTO `deliveries` (`id_order`, `id_deliverer`, `role_deliverer`, `created_at`, `delivery_status`, `comment`) VALUES
    ($id_order, $id_admin, '$admin_role', '$datetime', '$status', '$comment');";
    $stmt = $db->prepare($sqlQuery);
  }

    if ($stmt->execute()) {

        $sqlQuery = "INSERT INTO `notifications` ( `id_target`, `target`, `content`, `datetime`) VALUES
            (1, 'Admin', 'the Order #$id_order_encrypt have been $status by $admin_role', '$datetime');";
        $stmt = $db->prepare($sqlQuery);

        if ($stmt->execute()) {
            echo true;
        } else {
            echo false;
        }
    } else {
        echo false;
    }
} else {
    echo false;
}
