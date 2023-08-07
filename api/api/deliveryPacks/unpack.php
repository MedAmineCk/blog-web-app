<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Packs.php';
include_once '../../class/Orders.php';
include_once '../../class/Deliverers.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash configt
$key_pack = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PACKS";
$key_order = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$min_length = 8;
$max_length = 8;

//get the profilObj data
$encrypted_id_pack = isset($_GET['_id_pack']) ? $_GET['_id_pack'] : die('you need to specify _id_pack!');

// DECRYPT
$id_pack_dec = $crypt->decrypt($key_pack, $encrypted_id_pack);
$id_pack = intval($id_pack_dec);

$packObj_arr = array();
$packObj_arr["deliverer"] = array();
$packObj_arr["info"] = array();
$packObj_arr["orders"] = array();

//profile info
//initial classes
$pack = new Pack($db);
$pack->id_deliverer_pack = $id_pack;
$pack->getSinglePack();
if ($id_deliverer = $pack->id_deliverer) {
  //get data from id_deliverer
  $deliverer = new Deliverer($db);
  $deliverer->id_deliverer = $id_deliverer;
  $stmt = $deliverer->getSingleDeliverer();
  if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    extract($row);
    $delivererObj = array(
      "deliverer_name" => $deliverer_name,
      "phone_number" => $phone_number,
      "area" => $area,
    );
    $packObj_arr["deliverer"] = $delivererObj;
  }

  //get info on pack such as pack label, deliverer, items number, date ..
  $packObj_info_arr = array(
    "id_deliverer" => $id_deliverer,
    "label" => $pack->label,
    "created_date" => $pack->created_date,
    "status" => $pack->status
  );
  $packObj_arr["info"] = $packObj_info_arr;

  //get all pack orders
  $sql = "SELECT
  orders.id_order,
  orders.created_at,
  orders.items_quantity,
  orders.total,
  orders.status,
  clients_packs.client_name,
  clients_packs.client_phone,
  clients_packs.client_address,
  areas.area,
  deliverer_packs.id_deliverer_pack
FROM orders
       INNER JOIN clients_packs ON orders.id_client_pack = clients_packs.id_client_pack
       INNER JOIN deliverer_packs ON orders.id_deliverer_pack = deliverer_packs.id_deliverer_pack
       INNER JOIN areas ON clients_packs.id_area = areas.id_area
where orders.id_deliverer_pack = " . $id_pack;
  $stmt = $db->prepare($sql);
  if ($stmt->execute()) {
    $itemCount = $stmt->rowCount();
    if ($itemCount > 0) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_order = $crypt->encrypt($key_order, $id_order, $min_length, $max_length);
        $e = array(
          "id_order" => $id_order,
          "order_date" => $created_at,
          "buyer_name" => $client_name,
          "buyer_phone" => $client_phone,
          "buyer_address" => $client_address,
          "items_quantity" => $items_quantity,
          "price" => $total,
          "buyer_city" => $area,
          "status" => $status,
          "id_deliverer_pack" => $id_deliverer_pack,
        );

        array_push($packObj_arr["orders"], $e);
      }
    }
  }
}

if (!empty($packObj_arr)) {
  http_response_code(200);
  echo json_encode($packObj_arr);
} else {
  http_response_code(404);
  echo json_encode(
    array("message" => "No record found.")
  );
}
