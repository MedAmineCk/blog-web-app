<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Invoices.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial hashing
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

//get the profilObj data
$encrypted_id_client = isset($_GET['id']) ? $_GET['id'] : die('you need to specify id!');
$type = 'client';
// DECRYPT
$id_client_dec = $crypt->decrypt($key, $encrypted_id_client);
$id_client = intval($id_client_dec);

//profile invoices
$invoices = new Invoice($db);
$invoices->type = $type;
$invoices->id_user = $id_client;
$stmt = $invoices->getAllLinkedInvoices();
$itemCount = $stmt->rowCount();
if ($itemCount > 0) {
    $LinkedBillsArr = array();
    $LinkedBillsArr["body"] = array();
    $LinkedBillsArr["itemCount"] = $itemCount;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_invoice = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
        $e = array(
            "id_invoice" => $id_invoice,
            "credit" => $credit,
            "bill" => $bill,
            "date" => $creation_date,
            "status" => $status,
        );

        array_push($LinkedBillsArr["body"], $e);
    }
}

if (!empty($LinkedBillsArr)) {
    http_response_code(200);
    echo json_encode($LinkedBillsArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
