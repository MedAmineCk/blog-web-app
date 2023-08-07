<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

$invoiceObj = isset($_GET['invoiceObj']) ? $_GET['invoiceObj'] : die('you need to specify invoiceObj!');
$id_invoices_enc = $invoiceObj["id_invoice"];
$id_invoice = $crypt->decrypt($key, $id_invoices_enc);

//if invoice type is client update orders invoice id
if($invoiceObj["type"] == 'client'){
    //remove id_invoice from orders first then remove the invoice
    $sqlQuery = "UPDATE orders SET id_invoice = null, isPaid = false WHERE id_invoice = $id_invoice";
}

//if invoice type is deliverer update deliveries id
if($invoiceObj["type"] == 'deliverer'){
    //remove id_invoice from orders first then remove the invoice
    $sqlQuery = "UPDATE deliveries SET id_invoice = null, isPaid = false WHERE id_invoice = $id_invoice";
}

$stmt = $db->prepare($sqlQuery);

if ($stmt->execute()) {
    //remove invoice
    $sqlQuery = "DELETE from invoices WHERE id_invoice = $id_invoice";
    $stmt = $db->prepare($sqlQuery);
    if ($stmt->execute()) {
        echo true;
    } else {
        echo false;
    }
} else {
    echo $sqlQuery;
}
