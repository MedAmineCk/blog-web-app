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
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

//get the profilObj data
$encrypted_id_client = isset($_GET['_id_client']) ? $_GET['_id_client'] : die('you need to specify _id_client!');

// DECRYPT
$id_client_dec = $crypt->decrypt($key, $encrypted_id_client);
$id_client = intval($id_client_dec);

//profile info
//initial classes
$client = new Client($db);
$client->id_client = $id_client;
$client->getSingleClient();
if ($client->phone_number) {
    $profile_obj = array(
        "profile_pic" => $client->profile_pic,
        "full_name" => $client->full_name,
        "phone_number" => $client->phone_number,
        "email" => $client->email,
        "password" => $client->password,
    );
} else {
    echo 'client err';
}

if (!empty($profile_obj)) {
    http_response_code(200);
    echo json_encode($profile_obj);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
