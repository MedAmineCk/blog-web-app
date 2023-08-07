<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Categories.php';

$database = new Database();
$db = $database->getConnection();

$category = new Category($db);

//get all Order data as orderObj
$categoryObj = isset($_GET['categoryObj']) ? $_GET['categoryObj'] : die("no data passed!");

$category->category = $categoryObj["title"];
$category->description = $categoryObj["sub_title"];
$category->thumbnail = $categoryObj["thumbnail"];
$category->is_published = true;

if ($category->createCategory()) {
  echo true;
} else {
  echo false;
}
