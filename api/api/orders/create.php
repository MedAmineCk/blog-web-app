<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-id_client: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$order = new Order($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_area = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-AREAS";
$key_product = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PRODUCTS";
$min_length = 8;
$max_length = 8;

//get all Order data as orderObj
$client_pack = isset($_GET['client_pack']) ? $_GET['client_pack'] : die("no data passed!");

//client
$clientObj = $client_pack["client"];
$order->client_name = $clientObj["client_name"];
$order->client_phone = $clientObj["client_phone"];
$id_area_encrypt = $clientObj["id_area"];
$id_area = intval($crypt->decrypt($key_area, $id_area_encrypt));
$order->id_area = $id_area;
$order->client_address = $clientObj["client_address"];
$order->label = $clientObj["label"];

if ($id_client_pack = $order->createClientPack()) {
  //order
  $order->id_client_pack = $id_client_pack;
  $orders_arr = $client_pack["orders"];
  $err = 0;
  $order_arr["body"] = array();
  foreach ($orders_arr as $order_item) {

    $order->id_product = intval($crypt->decrypt($key_product, $order_item["id_product"]));
    $order->items_quantity = $order_item["quantity"];
    $order->total = $order_item["total"];

    if(isset($order_item['variants'])){
      $options = $order_item["variants"];
      //get id_variant from combination of options
      $optionObject = new stdClass();
      foreach ($options as $option) {
        $keyValue = explode(': ', $option);
        $optionObject->{$keyValue[0]} = $keyValue[1];
      }
      $optionJson = json_encode($optionObject);
      $order->id_variant = $order->get_variant_id($optionJson);
    }

    ($order->createOrder()) ? $err = 0 : $err++;
  }

  if ($err == 0) {
    $tracking_number = $crypt->encrypt($key, $id_client_pack, $min_length, $max_length);
    echo json_encode($tracking_number);
  } else {
    echo 'Order Items could not be created.';
  }

} else {
  echo 'Client pack could not be created.';
}
