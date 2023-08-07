<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Clients.php';
include_once '../../class/Pricing.php';
include_once '../../class/Orders.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash configt
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_pricing = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-PRICING";
$key_orders = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$min_length = 8;
$max_length = 8;

//get the profilObj data
$encrypted_id_client = isset($_GET['_id_client']) ? $_GET['_id_client'] : die('you need to specify _id_client!');

// DECRYPT
$id_client_dec = $crypt->decrypt($key, $encrypted_id_client);
$id_client = intval($id_client_dec);

$profile_obj_arr = array();
$profile_obj_arr["info"] = array();
$profile_obj_arr["pricing"] = array();
$profile_obj_arr["orders"] = array();

//profile info
//initial classes
$client = new Client($db);
$client->id_client = $id_client;
$client->role = 'client';
$client->getSingleClient();
if ($client->phone_number) {
    $profile_info_arr = array(
        "profile_pic" => $client->profile_pic,
        "full_name" => $client->full_name,
        "phone_number" => $client->phone_number,
        "email" => $client->email,
        "password" => $client->password,
    );
    $profile_obj_arr["info"] = $profile_info_arr;
} else {
    echo 'client err';
}

//pricing
$pricing = new Pricing($db);
$pricing->id_client = $id_client;
$stmt = $pricing->getSingleClientPricing();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
    $profile_pricing_arr = array();
    while ($area = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //print_r($row);
        $areaObj =array(
            //"id_area" => $area["id_area"],
            "area" => $area["area"],
            "delivery_price" => $area["delivery_price"],
            "return_price" => $area["return_price"]
        );
        array_push($profile_pricing_arr, $areaObj);
    }
    $profile_obj_arr["pricing"] = $profile_pricing_arr;
} else {
    echo 'pricing err';
}

//get client orders
$orders = new Order($db);
$orders->id_client = $id_client;
$stmt = $orders->getAllClientOrders();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
    $profile_Orders_Arr = array();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_order = $crypt->encrypt($key_orders, $id_order, $min_length, $max_length);
        $e = array(
            "id_order" => $id_order,
            "order_date" => $order_date,
            "buyer_name" => $buyer_name,
            "items_quantity" => $items_quantity,
            "price" => $price,
            "buyer_city" => $buyer_city,
            "status" => $status,
            "id_deliverer_pack" => $id_deliverer_pack,
            "id_invoice" => $id_invoice,
            "isPaid" => $isPaid
        );

        array_push($profile_Orders_Arr, $e);
    }
    $profile_obj_arr["orders"] = $profile_Orders_Arr;

} else {
    $profile_obj_arr["orders"] = array();
}

if (!empty($profile_obj_arr)) {
    http_response_code(200);
    echo json_encode($profile_obj_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
