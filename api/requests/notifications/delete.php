<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Notifications.php';

$database = new Database();
$db = $database->getConnection();

$id_notify = isset($_GET['id']) ? $_GET['id'] : die('you need to specify id_notify!');

$notification = new Notification($db);
$notification->id_notification = $id_notify;
if ($notification->deleteNotification()) {
    echo true;
} else {
    echo false;
}
