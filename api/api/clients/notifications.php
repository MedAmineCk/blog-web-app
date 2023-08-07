<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Notifications.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

//hash configt
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;
$crypt = new encryption_class();

$id_deliverer = isset($_GET['id_user']) ? $_GET['id_user'] : die('you need to specify id_user!');

// DECRYPT
$id_deliverer = intval($crypt->decrypt($key_user, $id_deliverer));

$profile_obj_arr = array();

//profile notifications
$notify = new Notification($db);
$notify->target = "Client";
$notify->type_admin = "deliverer";
$notify->id_admin = $id_deliverer;
$stmt = $notify->getLinkedNotifications();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
    $LinkedNotificationsArr = array();
    $LinkedNotificationsArr["body"] = array();
    $LinkedNotificationsArr["itemCount"] = $itemCount;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $e = array(
            "id_notification" => $id_notification,
            "notification" => $notification,
            "is_read" => $is_read,
        );

        array_push($LinkedNotificationsArr["body"], $e);
    }
} else {
    echo "some thing wen wrong!";
}

if (!empty($LinkedNotificationsArr)) {
    http_response_code(200);
    echo json_encode($LinkedNotificationsArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
