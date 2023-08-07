<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Categories.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CATEGORIES";
$min_length = 8;
$max_length = 8;

$stmt = $category->getCategories();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

  $categoriesArr = array();
  $categoriesArr["body"] = array();
  $categoriesArr["itemCount"] = $itemCount;

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $id_category = $crypt->encrypt($key, $id_category, $min_length, $max_length);
    $e = array(
      "id_category" => $id_category,
      "category" => $category,
      "thumbnail" => $thumbnail,
      "description" => $description
    );

    array_push($categoriesArr["body"], $e);
  }
  echo json_encode($categoriesArr);
} else {
  http_response_code(404);
  echo json_encode(
    array("message" => "No record found.")
  );
}
