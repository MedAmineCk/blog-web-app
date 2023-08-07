<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Confirmers.php';
include_once '../../class/Confirmations.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash config
$key_order = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_confirmer = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_confirmation = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CONFIRMATIONS";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

//get the confirmer id
$encrypted_id_confirmer = isset($_GET['id']) ? $_GET['id'] : die('you need to specify id!');

// DECRYPT
$id_confirmer = $crypt->decrypt($key_confirmer, $encrypted_id_confirmer);

$profile_obj_arr = array();
$profile_obj_arr["details"] = array();
$profile_obj_arr["confirmations"] = array();

//profile details
$confirmer = new Confirmer($db);
$confirmer->id_confirmer = $id_confirmer;
$stmt = $confirmer->getSingleConfirmer();

if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
  extract($row);
  $id_confirmer_enc = $crypt->encrypt($key_confirmer, $id_confirmer, $min_length, $max_length);
  $obj = array(
    "id_confirmer" => $id_confirmer_enc,
    "CIN" => $CIN,
    "confirmer_name" => $confirmer_name,
    "phone_number" => $phone_number,
    "address" => $address,
    "email" => $email,
    "password" => $password,
    "profile_pic" => $profile_pic,
    "price_confirm" => $price_confirm,
    "price_cancel" => $price_cancel,
  );
    $profile_obj_arr["details"] = $obj;
}

//get confirmations
$confirmation = new Confirmation($db);
$confirmation->id_confirmer = $id_confirmer;
$stmt = $confirmation->getConfirmations();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $id_confirmation = $crypt->encrypt($key_confirmation, $id_confirmation, $min_length, $max_length);
        $id_order = $crypt->encrypt($key_order, $id_order, $min_length, $max_length);
        $id_confirmer = $crypt->encrypt($key_confirmer, $id_confirmer, $min_length, $max_length);
        $id_invoice = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
        $obj = array(
            "id_confirmation" => $id_confirmation,
            "id_order" => $id_order,
            "id_confirmer" => $id_confirmer,
            "role_confirmer" => $role_confirmer,
            "confirmation_datetime" => $created_at,
            "confirmation_status" => $confirmation_status,
            "comment" => $comment,
            "id_invoice" => $id_invoice,
            "isPaid" => $isPaid
        );
        array_push($profile_obj_arr["confirmations"] , $obj);
    }
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
