<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Orders.php';

$database = new Database();
$db = $database->getConnection();

$orders = new Order($db);

$min_length = 8;
$max_length = 8;

$orders->getOrdersDataCounts();
if ($orders->Pending != null) {
    $data_arr = array(
        "Total" => $orders->Total,
        "Pending" => $orders->Pending,
        "Processing" => $orders->Processing,
        "Confirm" => $orders->Confirm,
        "unreachable" => $orders->unreachable,
        "Cancel" => $orders->Cancel,
        "Deliver" => $orders->Deliver,
    );
    http_response_code(200);
    echo json_encode($data_arr);
} else {
    http_response_code(404);
    echo json_encode(array("message" => "No data found."));
}
