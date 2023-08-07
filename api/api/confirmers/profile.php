<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Deliverers.php';
include_once '../../class/Data.php';
include_once '../../class/Invoices.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial classes
$deliverer = new Deliverer($db);
//$orders = new CustomRead($db);
$invoices = new Invoice($db);

//initial hashing
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

//get the profileObj data
$profileObj = isset($_GET['profileObj']) ? $_GET['profileObj'] : die('you need to specify id_agent!');
$type = $profileObj["type"];
$encrypted_id_deliverer = $profileObj["id"];

// DECRYPT
$id_deliverer = $crypt->decrypt($key_user, $encrypted_id_deliverer);

$profile_obj_arr = array();
$profile_obj_arr["details"] = array();
$profile_obj_arr["data"] = array();
$profile_obj_arr["orders"] = array();

//profile details
$deliverer->id_deliverer = $id_deliverer;
$deliverer->getSingleDeliverer();

if ($deliverer->city != null) {
    $profile_details_arr = array(
        "profile_pic" => $deliverer->profile_pic,
        "first_name" => $deliverer->first_name,
        "last_name" => $deliverer->last_name,
        "phone_number" => $deliverer->phone_number,
        "CIN" => $deliverer->CIN,
        "city" => $deliverer->city,
        "address" => $deliverer->address,
        "price_delivered" => $deliverer->price_delivered,
        "price_returned" => $deliverer->price_returned,
        "email" => $deliverer->email,
        "password" => $deliverer->password,
    );
    $profile_obj_arr["details"] = $profile_details_arr;
} else {
    $profile_arr = array();
}
/*
//profile Data Stats
$data = new Deliverer($db);
$data->id_deliverer = $id_deliverer;
$data->getSingleDelivererData();
if ($data->price_delivered != null) {

$price_delivered = $data->price_delivered;
$price_returned = $data->price_returned;
$num_deliver = $data->num_deliver;
$num_return = $data->num_return;
$total = $data->total;

$cashOut = ($num_deliver * $price_delivered) + ($num_return * $price_returned);
$credit = $total - $cashOut;

$data_arr = array(
"num_deliver" => $data->num_deliver,
"num_return" => $data->num_return,
"total" => $data->total,
"cashOut" => $cashOut,
"credit" => $credit,
);
$profile_obj_arr["data"] = $data_arr;
} else {
$data_arr = array();
}

//profile orders
$orders->id_deliverer = $id_deliverer;
$stmt = $orders->getAllLinkedOrders();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
$LinkedOrdersArr = array();
$LinkedOrdersArr["body"] = array();
$LinkedOrdersArr["itemCount"] = $itemCount;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
extract($row);
$id_order = $crypt->encrypt($key, $id_order, $min_length, $max_length);
$e = array(
"id_order" => $id_order,
"purchase_date" => $purchase_date,
"full_name" => $full_name,
"items" => $items,
"total" => $total,
"city" => $city,
"status" => $status,
);

array_push($LinkedOrdersArr["body"], $e);
}
$profile_obj_arr["orders"] = $LinkedOrdersArr;
} else {
$LinkedOrdersArr = array();
}

//profile invoices
$invoices->type = $type;
$invoices->id_user = $id_deliverer;
$stmt = $invoices->getAllLinkedInvoices();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
$LinkedInvoicesArr = array();
$LinkedInvoicesArr["body"] = array();
$LinkedInvoicesArr["itemCount"] = $itemCount;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
extract($row);
$id_invoice = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
$e = array(
"id_invoice" => $id_invoice,
"id_user" => $id_user,
"full_name" => $full_name,
"type" => $type,
"amount" => $amount,
"last_update" => $last_update,
"comment" => $comment,
"status" => $status,
);

array_push($LinkedInvoicesArr["body"], $e);
}
$profile_obj_arr["invoices"] = $LinkedInvoicesArr;
} else {
$profile_obj_arr["invoices"] = array();
}
 */
if (!empty($profile_obj_arr)) {
    http_response_code(200);
    echo json_encode($profile_obj_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
