<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Deliverers.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

//get the profilObj data
$encrypted_id_deliverer = isset($_GET['id']) ? $_GET['id'] : die('you need to specify id!');

// DECRYPT
$id_deliverer = intval($crypt->decrypt($key, $encrypted_id_deliverer));

$profile_obj_arr = array();
$profile_obj_arr["details"] = array();

//profile details
//initial classes
$deliverer = new Deliverer($db);
$deliverer->id_deliverer = $id_deliverer;

$stmt = $deliverer->getSingleDeliverer();

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  extract($row);
  $id_deliverer = $crypt->encrypt($key, $id_deliverer, $min_length, $max_length);
  $obj = array(
    "id_deliverer" => $id_deliverer,
    "CIN" => $CIN,
    "deliverer_name" => $deliverer_name,
    "phone_number" => $phone_number,
    "area" => $area,
    "address" => $address,
    "email" => $email,
    "password" => $password,
    "profile_pic" => $profile_pic,
    "price_delivered" => $price_delivered,
    "price_returned" => $price_returned,
  );
  $profile_obj_arr["details"] = $obj;
}

if (!empty($profile_obj_arr)) {
    http_response_code(200);
    echo json_encode($profile_obj_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
