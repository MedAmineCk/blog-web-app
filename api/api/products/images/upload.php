<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$url = $_SERVER['DOCUMENT_ROOT'];

//decrypt
$file = $_FILES['file'];

// Upload directory
$upload_location = $url . "/uploads/products/";

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

        $fileNameNew = date('Y-m-d-H-i-s') . '-' . unique_id() . '.' . $ext;

        //the path of file location "/uploads/clients/.."
        $path = $upload_location . $fileNameNew;

        // Upload file
        if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
          echo json_encode($fileNameNew);
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

function unique_id()
{
  return substr(md5(uniqid(mt_rand(), true)), 0, 8);
}
