<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Invoices.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$invoices = new Invoice($db);
$crypt = new encryption_class();

//hash config
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$stmt = $invoices->getInvoices();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $InvoiceArr = array();
    $InvoiceArr["body"] = array();
    $InvoiceArr["itemCount"] = $itemCount;

    /*$InvoiceArr["data"] = array();
    $invoices->getInvoicesDataCounts();
    if ($invoices->Total != null) {
        $data_arr = array(
            "Total" => $invoices->Total,
            "Paid" => $invoices->Paid,
            "unPaid" => $invoices->unPaid,
        );
        array_push($InvoiceArr["data"], $data_arr);
    } else {
        echo "invoices data!";
    }*/

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_invoice = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
        $id_user = $crypt->encrypt($key_user, $id_user, $min_length, $max_length);
        $e = array(
            "id_invoice" => $id_invoice,
            "id_user" => $id_user,
            "full_name" => $full_name,
            "type" => $type,
            "credit" => $credit,
            "bill" => $bill,
            "creation_date" => $creation_date,
            "comment" => $comment,
            "status" => $status
        );

        array_push($InvoiceArr["body"], $e);
    }
    echo json_encode($InvoiceArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
