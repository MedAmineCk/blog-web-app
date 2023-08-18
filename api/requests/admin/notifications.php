<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../models/Notifications.php';

$database = new Database();
$db = $database->getConnection();

$data = array();

//profile notifications-count
$notify = new Notification($db);
$notify->target = "admin";
$notify->id_target = 1;

$notify->getSingleNotificationsCount();
$data["notifyCount"] = $notify->notifCount;

if (!empty($data)) {
  http_response_code(200);
  echo json_encode($data);
} else {
  http_response_code(404);
  echo json_encode(
    array("message" => "No record found.")
  );
}
