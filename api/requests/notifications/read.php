<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Notifications.php';
include_once '../../models/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

//hash configt
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;
$crypt = new encryption_class();

$profileObj = isset($_GET['profileObj']) ? $_GET['profileObj'] : die('you need to specify profileObj!');
$id_user = $profileObj['id_user'];
$target = $profileObj['target'];
// DECRYPT
$id_user = intval($crypt->decrypt($key_user, $id_user));

//profile notifications
$notify = new Notification($db);
$notify->target = $target;
$notify->id_target = $id_user;
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
            "content" => $content,
            "is_open" => $is_open,
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
