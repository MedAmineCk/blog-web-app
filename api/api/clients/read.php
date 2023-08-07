<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Clients.php';
include_once '../../class/Users.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$client = new Client($db);
$crypt = new encryption_class();

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$stmt = $client->getClients();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $clientsArr = array();
    $clientsArr["body"] = array();
    $clientsArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $user = new User($db);
        $user->id_user = $id_client;
        $user->role = 'client';
        $user->getSingleUser();
        $id_client = $crypt->encrypt($key, $id_client, $min_length, $max_length);
        $e = array(
            "id_client" => $id_client,
            "full_name" => $full_name,
            "phone_number" => $phone_number,
            "email" => $user->email,
            "password" => $user->password,
            "profile_pic" => $profile_pic,
        );

        array_push($clientsArr["body"], $e);
    }
    echo json_encode($clientsArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
