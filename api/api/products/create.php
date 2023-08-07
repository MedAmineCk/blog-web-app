<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Products.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

//get all Order data as orderObj
$productObj = isset($_GET['productObj']) ? $_GET['productObj'] : die("no data passed!");

$product->title = $productObj["title"];
$product->sub_title = $productObj["sub_title"];
$product->type = $productObj["type"];
$product->description = $productObj["description"];
$product->thumbnail = $productObj["thumbnail"];
$product->price = $productObj["price"];
$product->compared_price = $productObj["compared_price"];
$product->quantity = $productObj["quantity"];
$product->has_variants = $productObj["has_variants"];


if($productObj["has_seo"] == "yes"){
  $seo = $productObj["seo"];
  $product->meta_title = $seo["title"];
  $product->meta_description = $seo["description"];
  $product->meta_keywords = $seo["keywords"];
}else{
  $product->meta_title = "";
  $product->meta_description = "";
  $product->meta_keywords = "";
}


$product->is_published = true;

if ($id_product = $product->createProduct()) {

  //------------insert into categories-------------------
  $categories_ids = $productObj['categories'];
  if(sizeof($categories_ids) != 0){
    // Convert the array of category IDs into a comma-separated string
    $category_ids_string = implode(',', $categories_ids);

    // Build the INSERT query with multiple rows using the comma-separated string of category IDs
    $values = array();
    foreach ($categories_ids as $id_category) {
      $values[] = "($id_product, $id_category)";
    }
    $values_clause = implode(',', $values);
    $query = "INSERT INTO products_categories (id_product, id_category) VALUES $values_clause";

    // Prepare and execute the INSERT query
    $stmt = $db->prepare($query);
    $stmt->execute();
  }


  //------------insert into product images-------------------
  $image_urls = $productObj['images'];
  if(sizeof($image_urls) != 0){
    // Build the VALUES clause of the INSERT query using the image URLs
    $values = array();
    foreach ($image_urls as $image_url) {
      $values[] = "($id_product, '$image_url')";
    }
    $values_clause = implode(',', $values);

    // Build the full INSERT query with the VALUES clause
    $query = "INSERT INTO product_images (id_product, image) VALUES $values_clause";

    // Prepare and execute the INSERT query
    $stmt = $db->prepare($query);
    $stmt->execute();
  }


  //------------insert into variants-------------------
  // Get the product ID and variant data from your form or wherever you're storing them

  if($productObj["has_variants"] == "yes"){
    $variants = $productObj['variants'];
    // Build the VALUES clause of the INSERT query using the variant data
    $values = array();
    foreach ($variants as $variant) {
      $image = $variant['image'];
      $options = $variant['options'];
      $price = $variant['price'];
      $quantity = $variant['quantity'];
      $values[] = "($id_product, '$options', '$image', $price, $quantity)";
    }
    $values_clause = implode(',', $values);

    // Build the full INSERT query with the VALUES clause
    $query = "INSERT INTO variants (id_product, variant, image, price, quantity) VALUES $values_clause";

    // Prepare and execute the INSERT query
    $stmt = $db->prepare($query);
    $stmt->execute();
  }

echo true;
} else {
  echo false;
}
