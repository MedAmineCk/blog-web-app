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
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-BILLS";
$min_length = 8;
$max_length = 8;

$billUpdateObj = isset($_GET['billUpdateObj']) ? $_GET['billUpdateObj'] : die('you need to specify status!');

$id_bills_enc_arr = $billUpdateObj["id_bills"];
$status = $billUpdateObj["status"];

$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));

$id_bills_decrypted = "(";
$i = 0;

foreach ($id_bills_enc_arr as $index => $id_bill) {
    // DECRYPT
    $id_bill_dec = $crypt->decrypt($key, $id_bill);
    if ($i != 0) {
        $id_bills_decrypted .= ", " . $id_bill_dec;
    } else {
        $id_bills_decrypted .= $id_bill_dec;
    }
    $i++;
}
$id_bills_decrypted .= ")";

$sqlQuery = "UPDATE bills SET status = '$status', last_update = '$datetime' WHERE id_bill IN " . $id_bills_decrypted;

$stmt = $db->prepare($sqlQuery);

if ($stmt->execute()) {
    echo true;
} else {
    echo false;
}
