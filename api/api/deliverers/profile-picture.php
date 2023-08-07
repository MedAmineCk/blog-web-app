<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = $_SERVER['DOCUMENT_ROOT'];
include_once $url . '/php-rest-api/config/database.php';
include_once $url . '/php-rest-api/class/Deliverers.php';
include_once $url . '/php-rest-api/class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$deliverer = new Deliverer($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

//get data as _clientObj
$profileIdEncrypted = $_POST["profileId"];
//decrypt
$profileId = intval($crypt->decrypt($key, $profileIdEncrypted));
$file = $_FILES['file'];

// Upload directory
$upload_location = $url . "/uploads/deliverers/";

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

                $fileNameNew = date('Y-m-d-H-i-s') . '-' . $profileId . '-' . unique_id() . '.' . $ext;

                //check if the user has aleardy profile pic, if so then return the srcName than repalce it with the new one!
                $sqlQuery = "SELECT profile_pic from deliverers WHERE id_deliverer =  '$profileId'";
                $stmt = $db->prepare($sqlQuery);
                if ($stmt->execute()) {
                    $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (isset($dataRow["profile_pic"])) {
                        $fileNameNew = $dataRow["profile_pic"];
                    }
                } else {
                    printf("Error: %s.\n", $stmt->error);
                }

                //the path of file location "/uploads/clients/.."
                $path = $upload_location . $fileNameNew;

                // Upload file
                if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
                    $deliverer->id_deliverer = $profileId;
                    $deliverer->profile_pic = $fileNameNew;
                    if ($deliverer->updateDelivererProfile()) {
                        echo true;
                    } else {
                        echo "something wen worong!";
                    }
                } else {
                    echo "mobing file not working";
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

function unique_id()
{
    return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}
