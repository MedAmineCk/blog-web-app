<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Packs.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$items = new Pack($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PACKS";
$min_length = 8;
$max_length = 8;

$stmt = $items->getPacks();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $PackArr = array();
    $PackArr["body"] = array();
    $PackArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $pack_id = $crypt->encrypt($key, $pack_id, $min_length, $max_length);
        $e = array(
            "id_deliverer_pack" => $pack_id,
            "deliverer" => $deliverer_name,
            "city" => $area_name,
            "created_date" => $created_date,
            "items" => $order_count,
            "status" => $pack_status,
        );

        array_push($PackArr["body"], $e);
    }
    echo json_encode($PackArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
