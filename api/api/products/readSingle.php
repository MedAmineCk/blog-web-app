<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Products.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PRODUCTS";
$min_length = 8;
$max_length = 8;

//get the product id data
$encrypted_id_ = isset($_GET['id']) ? $_GET['id'] : die('you need to specify id!');

// DECRYPT
$id_dec = $crypt->decrypt($key, $encrypted_id_);
$id = intval($id_dec);

//profile info
//initial classes
$product = new Product($db);
$product->id_product = $id;
if ($dataRow = $product->getSingleProduct()) {
  $ProductArr["product"] = array(
    "id_product" => $dataRow['id_product'],
    "thumbnail" => $dataRow['thumbnail'],
    "title" => $dataRow['title'],
    "description" => $dataRow['description'],
    "price" => $dataRow['price'],
    "compared_price" => $dataRow['compared_price'],
    "quantity" => $dataRow['quantity'],
    "meta_title" => $dataRow['meta_title'],
    "meta_description" => $dataRow['meta_description'],
    "meta_keywords" => $dataRow['meta_keywords']
  );

  $stmt = $product->getProductImages();
  $itemCount = $stmt->rowCount();
  if($itemCount != 0){
    $ProductArr["images"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $e = array(
        "id_product_image" => $id_product_image,
        "image" => $image,
        "alt" => $alt,
        "id_product" => $id_product,
      );

      array_push($ProductArr["images"], $e);
    }
  }

  if ($dataRow['has_variants'] == 'yes') {
    $stmt = $product->getProductVariants();
    $itemCount = $stmt->rowCount();
    $ProductArr["variants"] = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      extract($row);

      $e = array(
        "id_variant" => $id_variant,
        "variant" => $variant,
        "quantity" => $quantity,
        "price" => $price,
        "image" => $image,
        "id_product" => $id_product,
      );

      array_push($ProductArr["variants"], $e);
    }
  }
} else {
  echo 'err';
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
