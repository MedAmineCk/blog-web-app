<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

$id_notifys_arr = isset($_GET['id_notifys_arr']) ? $_GET['id_notifys_arr'] : die('you need to specify id_notifys_arr!');

$id_notifys = "(";
foreach ($id_notifys_arr as $id_notify) {
    $id_notifys .= $id_notify . ",";
}
$id_notifys = substr($id_notifys, 0, -1);
$id_notifys .= ")";

$sqlQuery = "UPDATE notifications SET is_open = true WHERE id_notification IN " . $id_notifys;

$stmt = $db->prepare($sqlQuery);

if ($stmt->execute()) {
    echo true;
} else {
    echo false;
}
