<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = $_SERVER['DOCUMENT_ROOT'];
include_once $url . '/php-rest-api/config/database.php';
include_once $url . '/php-rest-api/class/Dashboard.php';

$database = new Database();
$db = $database->getConnection();

$dashboard = new Dashboard($db);

$file = $_FILES['file'];

// Upload directory
$upload_location = $url . "/img/logo/";

if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
    // File name
    $filename = $_FILES['file']['name'];

    // Get extension
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Valid image extension
    $valid_ext = array("png", "jpeg", "jpg");

    // Check extension
    if (in_array($ext, $valid_ext)) {
        if ($_FILES['file']['error'] === 0) {
            if ($_FILES['file']['size'] < 1000000) {

                $fileNameNew = 'logo.' . $ext;

                //the path of file location "/img/logo/.."
                $path = $upload_location . $fileNameNew;

                // Upload file
                if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                    $dashboard->logo = $fileNameNew;
                    if ($dashboard->updateAdminLogo()) {
                        echo true;
                    } else {
                        echo "something wen wrong!";
                    }
                } else {
                    echo "moving file not working";
                }
            } else {
                echo "Your file is too big!";
            }
        } else {
            echo "You have an error uploading your file!";
        }
    } else {
        echo "You cannot upload file of this type!";
    }
}
