<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Products.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$products = new Product($db);
$crypt = new encryption_class();

//hash config
$key_category = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CATEGORIES";
$key_product= "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PRODUCTS";
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CATEGORIES";
$min_length = 8;
$max_length = 8;

$id_category_enc_arr = isset($_GET['id_category']) ? $_GET['id_category'] : die('you need to specify id_category!');

$id_category_dec = $crypt->decrypt($key_category, $id_category_enc_arr);


$products->id_category = $id_category_dec;
$stmt = $products->getCategoryProducts();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

  $ProductArr = array();
  $ProductArr["body"] = array();
  $ProductArr["itemCount"] = $itemCount;

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $id_product = $crypt->encrypt($key_product, $id_product, $min_length, $max_length);

    $percentage = getDiscountPercentage($price, $compared_price);

    $e = array(
      "id_product" => $id_product,
      "thumbnail" => $thumbnail,
      "title" => $title,
      "price" => $price,
      "compared_price" => $compared_price,
      "sold" => $percentage . "%",
      "quantity" => $quantity
    );

    array_push($ProductArr["body"], $e);
  }
}

if (!empty($ProductArr)) {
  http_response_code(200);
  echo json_encode($ProductArr);
} else {
  http_response_code(404);
  echo json_encode(
    array("message" => "No record found.")
  );
}

function getDiscountPercentage($product_price, $compared_price) {
  $discount_percentage = floor((1 - ($compared_price / $product_price)) * 100);
  return $discount_percentage;
}
