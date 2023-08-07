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

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-AREAS";
$min_length = 8;
$max_length = 8;

$idEnc = isset($_GET['id']) ? $_GET['id'] : die('you need to specify id!');
$id = $crypt->decrypt($key, $idEnc);

//remove pricing Area
$sqlQuery = "DELETE from areas WHERE id_area = $id";
$stmt = $db->prepare($sqlQuery);
if ($stmt->execute()) {
  echo true;
} else {
  echo false;
}
