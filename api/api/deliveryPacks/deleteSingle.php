<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$crypt = new encryption_class();

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PACKS";
$min_length = 8;
$max_length = 8;

$id_packs_enc = isset($_GET['_id_pack']) ? $_GET['_id_pack'] : die('you need to specify id_packs!');
$id_pack = $crypt->decrypt($key, $id_packs_enc);
//remove id_pack from orders first then remove the pack
$sqlQuery = "UPDATE orders SET id_deliverer_pack = null WHERE orders.id_deliverer_pack = ".$id_pack;

$stmt = $db->prepare($sqlQuery);

if ($stmt->execute()) {
    //remove pack
    $sqlQuery = "DELETE from deliverer_packs WHERE deliverer_packs.id_deliverer_pack = ".$id_pack;
    $stmt = $db->prepare($sqlQuery);
    if ($stmt->execute()) {
        echo true;
    } else {
        echo false;
    }
} else {
    echo $sqlQuery;
}
