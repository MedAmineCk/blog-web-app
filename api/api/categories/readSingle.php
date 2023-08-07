<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Categories.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CATEGORIES";
$min_length = 8;
$max_length = 8;

//get the product id data
$encrypted_id_ = isset($_GET['id_category']) ? $_GET['id_category'] : die('you need to specify id_category!');

// DECRYPT
$id_dec = $crypt->decrypt($key, $encrypted_id_);
$id = intval($id_dec);

//profile info
//initial classes
$category = new Category($db);
$category->id_category = $id;

if ($dataRow = $category->getSingleCategory()) {
  $categoryObj = array(
    "id_category" => $dataRow['id_category'],
    "category" => $dataRow['category'],
    "thumbnail" => $dataRow['thumbnail'],
    "description" => $dataRow['description']
  );
} else {
  echo 'err';
}

if (!empty($categoryObj)) {
  http_response_code(200);
  echo json_encode($categoryObj);
} else {
  http_response_code(404);
  echo json_encode(
    array("message" => "No record found.")
  );
}
