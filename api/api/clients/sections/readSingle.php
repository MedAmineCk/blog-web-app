<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../../config/database.php';
//classes
include_once '../../../class/Clients.php';
include_once '../../../class/Pricing.php';
//hashing
include_once '../../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CLIENT";
$min_length = 8;
$max_length = 8;

//get the profilObj data
$encrypted_id_client = isset($_GET['_id_client']) ? $_GET['_id_client'] : die('you need to specify _id_client!');

// DECRYPT
$id_client_dec = $crypt->decrypt($key, $encrypted_id_client);
$id_client = intval($id_client_dec);

$profile_obj_arr = array();
$profile_obj_arr["info"] = array();

//profile info
//initial classes
$client = new Client($db);
$client->id_client = $id_client;
$client->getSingleClient();
if ($client->phone_number) {
    $profile_info_arr = array(
        "profile_pic" => $client->profile_pic,
        "full_name" => $client->full_name,
        "phone_number" => $client->phone_number,
        "email" => $client->email,
        "password" => $client->password,
    );
    $profile_obj_arr["info"] = $profile_info_arr;
} else {
    echo 'client err';
}

//pricing
$pricing = new Pricing($db);
$pricing->id_client = $id_client;
$stmt = $pricing->getSingleClientPricing();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
    $profile_pricing_arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $column => $value) {
            array_push($profile_pricing_arr, (object) ["$column" => $value]);
        }
    }
    $profile_obj_arr["pricing"] = $profile_pricing_arr;
} else {
    echo 'pricing err';
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
