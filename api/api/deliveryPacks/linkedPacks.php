<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();
$packs = new Order($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PACKS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$id_deliverer = isset($_GET['id_user']) ? $_GET['id_user'] : die('you need to specify id_deliverer!');

// DECRYPT
$id_deliverer = intval($crypt->decrypt($key_user, $id_deliverer));
$packs->id_deliverer = $id_deliverer;
$stmt = $packs->getAllDelivererPacks();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $LinkedPacksArr = array();
    $LinkedPacksArr["linkedPacks"] = array();
    $LinkedPacksArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_pack = $crypt->encrypt($key, $id_deliverer_pack, $min_length, $max_length);
        $e = array(
            "id_pack" => $id_pack,
            "created_date" => $created_date,
            "label" => $label,
            "status" => $status,
        );

        array_push($LinkedPacksArr["linkedPacks"], $e);
    }
    http_response_code(200);
    echo json_encode($LinkedPacksArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
