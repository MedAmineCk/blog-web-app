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


// Get JWT token from the query parameter
$authToken = $_GET['token'];

// Import JWTHandler class
include_once '../../class/JWTHandler.php';

// Initialize JWTHandler with your secret key
$jwtHandler = new JWTHandler('your-secret-key');

// Verify the token
if ($jwtHandler->verifyToken($authToken)) {
    // Token is valid, send a response indicating validity
    http_response_code(200);
    echo json_encode(array("valid" => true));
} else {
    // Token is invalid, send a response indicating invalidity
    http_response_code(401);
    echo json_encode(array("valid" => false));
}
