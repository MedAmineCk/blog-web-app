<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Areas.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$areas = new Area($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-AREAS";
$min_length = 8;
$max_length = 8;

$stmt = $areas->getAreas();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $AreasArr = array();
    $AreasArr["body"] = array();
    $AreasArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_area = $crypt->encrypt($key, $id_area, $min_length, $max_length);
        $e = array(
            "id" => $id_area,
            "name" => $area,
            "shipping" => $shipping,
            "return" => $return_price,
        );

        array_push($AreasArr["body"], $e);
    }
    echo json_encode($AreasArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
