<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Deliverers.php';
include_once '../../class/Users.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

//hashing
$crypt = new encryption_class();

//hash config
$key_areas = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-AREAS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$deliverer = new Deliverer($db);
$user = new User($db);

//get all Order data as orderObj
$delivererObj = isset($_GET['_delivererObj']) ? $_GET['_delivererObj'] : die("no data passed!");

$deliverer->first_name = $delivererObj["first_name"];
$deliverer->last_name = $delivererObj["last_name"];
$deliverer->phone_number = $delivererObj["phone_number"];
$deliverer->CIN = $delivererObj["CIN"];
$deliverer->id_area = $crypt->decrypt($key_areas, $delivererObj["id_area"]);
$deliverer->address = $delivererObj["address"];
$deliverer->price_delivered = $delivererObj["price_delivered"];
$deliverer->price_returned = $delivererObj["price_returned"];

if ($id_user = $deliverer->createDeliverer()) {
    //now insert email and password into users table using id_user getting from creatAgent()
    $user->id_user = $id_user;
    $user->email = $delivererObj["email"];
    $user->password = $delivererObj["password"];
    $user->role = "deliverer";

    if ($user->createUser()) {
      $id_user_enc = $crypt->encrypt($key_user, $id_user, $min_length, $max_length);
      echo json_encode(array("id_user" => $id_user_enc));
    } else {
        echo false;
    }
} else {
    echo false;
}
