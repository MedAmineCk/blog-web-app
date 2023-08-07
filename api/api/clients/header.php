<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Clients.php';
include_once '../../class/Notifications.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$client = new Client($db);
$crypt = new encryption_class();

$id_client = isset($_GET['id_user']) ? $_GET['id_user'] : die('you need to specify id_user!');

// DECRYPT
$id_client = intval($crypt->decrypt($key_user, $id_client));

$profile_obj_arr = array();

//profile pic
$client->id_client = $id_client;
$client->getSingleClient();
$profile_obj_arr["profile_pic"] = $client->profile_pic;
$profile_obj_arr["fullName"] = $client->full_name;

//profile notifications-count
$notify = new Notification($db);
$notify->target = "Client";
$notify->id_target = $id_client;
$notify->getSingleNotificationsCount();
$profile_obj_arr["notifyCount"] = $notify->notifCount;

if (!empty($profile_obj_arr)) {
    http_response_code(200);
    echo json_encode($profile_obj_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
