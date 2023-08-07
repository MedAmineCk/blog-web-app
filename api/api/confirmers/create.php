<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Confirmers.php';
include_once '../../class/Users.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

//hashing
$crypt = new encryption_class();

//hash config
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$confirmer = new Confirmer($db);
$user = new User($db);

//get all Order data as orderObj
$confirmerObj = isset($_GET['_confirmerObj']) ? $_GET['_confirmerObj'] : die("no data passed!");

$confirmer->first_name = $confirmerObj["first_name"];
$confirmer->last_name = $confirmerObj["last_name"];
$confirmer->phone_number = $confirmerObj["phone_number"];
$confirmer->CIN = $confirmerObj["CIN"];
$confirmer->address = $confirmerObj["address"];
$confirmer->price_confirm = $confirmerObj["price_confirm"];
$confirmer->price_cancel = $confirmerObj["price_cancel"];

if ($id_user = $confirmer->createConfirmer()) {
    //now insert email and password into users table using id_user getting from creatAgent()
    $user->id_user = $id_user;
    $user->email = $confirmerObj["email"];
    $user->password = $confirmerObj["password"];
    $user->role = "confirmer";

    if ($user->createUser()) {
      $id_user_enc = $crypt->encrypt($key_user, $id_user, $min_length, $max_length);
      echo json_encode(array("id_user" => $id_user_enc));
    } else {
        echo false;
    }
} else {
    echo false;
}
