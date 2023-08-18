<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    header("HTTP/1.1 200 OK");
    exit;
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, DELETE, POST, GET, OPTIONS");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Auth.php';
include_once '../../models/Encryptions.php';
include_once '../../models/JWTHandler.php';

session_start();

$database = new Database();
$db = $database->getConnection();

$item = new Auth($db);
$crypt = new encryption_class();
//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-USERS";
$min_length = 8;
$max_length = 8;

// Validate incoming data
$data = json_decode(file_get_contents("php://input"));

if (!isset($data->email) || !isset($data->password)) {
    http_response_code(400);
    echo json_encode(array("message" => "Missing required fields."));
    exit;
}

$item->email = $data->email;
$item->password = $data->password;

if ($item->authentication()) {
    $id_user = $item->id_user;
    $role = $item->role;

    // Generate a JWT token
    $jwtHandler = new JWTHandler('your-secret-key');
    $token = $jwtHandler->generateToken($id_user);

    // Create array with user data
    $encrypted_id_user = $crypt->encrypt($key, $id_user, $min_length, $max_length);
    $emp_arr = array(
        "id_user" => $encrypted_id_user,
        "role" => $role,
        "permission" => true,
        "token" => $token
    );

    http_response_code(200);
    echo json_encode($emp_arr);
} else {
    http_response_code(401);
    echo json_encode(array("message" => "Authentication failed."));
}
