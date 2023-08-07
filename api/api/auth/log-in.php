<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Auth.php';
include_once '../../class/Encryptions.php';

session_start();

$database = new Database();
$db = $database->getConnection();

$item = new Auth($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$authObj = isset($_GET['_authObj']) ? $_GET['_authObj'] : die("no data passed!");

$item->email = $authObj["email"];
$item->password = $authObj["password"];

$item->authentication();
if ($item->id_user != null) {

    $id_user = $item->id_user;
    $role = $item->role;

    $_SESSION['login_user_id'] = $id_user;
    $_SESSION['login_user_role'] = $role;

    $encrypted_id_user = $crypt->encrypt($key, $id_user, $min_length, $max_length);

    // create array
    $emp_arr = array(
        "id_user" => $encrypted_id_user,
        "role" => $role,
        "permission" => true,
    );

    http_response_code(200);
    echo json_encode($emp_arr);
} else {
    http_response_code(404);
    echo json_encode("user not found !.");
}
