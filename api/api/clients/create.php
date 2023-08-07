<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Clients.php';
include_once '../../class/Users.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

//hash config
$crypt = new encryption_class();
$key_pricing = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PRICING";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$client = new Client($db);
$clientObj = isset($_GET['_clientObj']) ? $_GET['_clientObj'] : die("no data passed!");
$client->full_name = $clientObj["full_name"];
$client->phone_number = $clientObj["phone_number"];

if ($id_user = $client->createClient()) {
    $user = new User($db);
    $userObj = isset($_GET['_userObj']) ? $_GET['_userObj'] : die("no data passed!");
    $user->id_user = $id_user;
    $user->email = $userObj["email"];
    $user->password = $userObj["password"];
    $user->role = 'client';
    if ($user->createUser()) {
        $pricingArr = isset($_GET['_pricingArr']) ? $_GET['_pricingArr'] : die("no data passed!");
        $pricingValues = "";
        foreach ($pricingArr as $pricingObj) {
            $id_area_encrypt = $pricingObj['id_area'];
            $id_area = intval($crypt->decrypt($key_pricing, $id_area_encrypt));;
            $delivery_price = $pricingObj['delivery_price'];
            $return_price = $pricingObj['return_price'];
            $pricingValues .= "($id_user, $id_area, $delivery_price, $return_price),";
        }
        //delete last character ',';
        $pricingValues = substr($pricingValues, 0, -1);
        $sqlQuery = "INSERT INTO client_pricing(id_client, id_area, delivery_price, return_price) VALUES $pricingValues;";
        $stmt = $db->prepare($sqlQuery);
        if ($stmt->execute()) {
          $id_user_enc = $crypt->encrypt($key_user, $id_user, $min_length, $max_length);
          echo json_encode(array("id_user" => $id_user_enc));
        } else {
            echo $sqlQuery;
        }
    } else {
        echo 'createUser err';
    }
} else {
    echo 'createClient err';
}
