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

$id_packs_enc_arr = isset($_GET['id_packs']) ? $_GET['id_packs'] : die('you need to specify id_packs!');

$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));

$id_packs_decrypted = "(";
$i = 0;

foreach ($id_packs_enc_arr as $index => $id_pack) {
    // DECRYPT
    $id_pack_dec = $crypt->decrypt($key, $id_pack);
    if ($i != 0) {
        $id_packs_decrypted .= ", " . $id_pack_dec;
    } else {
        $id_packs_decrypted .= $id_pack_dec;
    }
    $i++;
}
$id_packs_decrypted .= ")";
//remove id_pack from orders first then remove the pack
$sqlQuery = "UPDATE orders SET id_deliverer_pack = null WHERE orders.id_deliverer_pack in " . $id_packs_decrypted;

$stmt = $db->prepare($sqlQuery);

if ($stmt->execute()) {
    //remove pack
    $sqlQuery = "DELETE from deliverer_packs WHERE deliverer_packs.id_deliverer_pack IN " . $id_packs_decrypted;
    $stmt = $db->prepare($sqlQuery);
    if ($stmt->execute()) {
        echo true;
    } else {
        echo false;
    }
} else {
    echo $sqlQuery;
}
