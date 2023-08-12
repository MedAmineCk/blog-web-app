<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Users.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$crypt = new encryption_class();

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

//get data as _agentObj
$userObj = isset($_GET['userObj']) ? $_GET['userObj'] : die('you need to specify userObj!');

$role = $userObj["role"];
$password = $userObj["password"];
$id_user_encrypt = $userObj["id_user"];
$id_user = intval($crypt->decrypt($key, $id_user_encrypt));

$user->id_user = $id_user;
$user->role = $role;
$user->password = $password;
if ($user->updateUser()) {
    echo true;
} else {
    echo "something went worong!";
}
