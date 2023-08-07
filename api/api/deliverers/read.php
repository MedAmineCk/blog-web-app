<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Deliverers.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$items = new Deliverer($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$stmt = $items->getDeliverers();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $DelivererArr = array();
    $DelivererArr["body"] = array();
    $DelivererArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_deliverer = $crypt->encrypt($key, $id_deliverer, $min_length, $max_length);
        $e = array(
            "id_deliverer" => $id_deliverer,
            "deliverer_name" => $deliverer_name,
            "CIN" => $CIN,
            "phone_number" => $phone_number,
            "area" => $area,
            "email" => $email,
            "password" => $password,
            "profile_pic" => $profile_pic,
        );

        array_push($DelivererArr["body"], $e);
    }
    echo json_encode($DelivererArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
