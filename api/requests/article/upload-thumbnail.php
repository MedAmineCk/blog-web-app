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


// Specify the directory where uploaded images will be stored
$uploadDirectory = '../../uploads/';
// Create the directory if it doesn't exist
if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if a file was uploaded
    if (isset($_FILES["thumbnail"]) && $_FILES["thumbnail"]["error"] === UPLOAD_ERR_OK) {
        $tempFilePath = $_FILES["thumbnail"]["tmp_name"];

        // Generate a unique filename
        $uniqueFilename = time() . '_' . uniqid() . '_' . $_FILES["thumbnail"]["name"];
        $targetFilePath = $uploadDirectory . $uniqueFilename;

        // Attempt to move the uploaded file
        if (move_uploaded_file($tempFilePath, $targetFilePath)) {
            // Successfully moved the file
            echo json_encode(array("thumbnailUrl" => $uniqueFilename));
        } else {
            $lastError = error_get_last();
            http_response_code(500);
            echo json_encode(array("error" => "Error moving uploaded file: " . $lastError["message"]));
        }
    } else {
        http_response_code(400); // Bad Request
        echo json_encode(array("error" => "No file uploaded or an error occurred."));
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(array("error" => "Only POST requests are allowed."));
}
