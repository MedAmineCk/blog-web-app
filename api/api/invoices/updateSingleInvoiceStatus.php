<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Invoices.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$invoice = new Invoice($db);
$crypt = new encryption_class();

//hash config
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

$_updateStatusObj = isset($_GET['invoiceObj']) ? $_GET['invoiceObj'] : die('you need to specify invoiceObj!');

$id_invoice_enc = $_updateStatusObj["id_invoice"];
$status = $_updateStatusObj["status"];
$invoiceType = $_updateStatusObj["invoiceType"];

// DECRYPT USER
$id_invoice = intval($crypt->decrypt($key_invoice, $id_invoice_enc));
$invoice->id_invoice = $id_invoice;
$invoice->status = $status;
if ($invoice->updateInvoiceStatus()) {
  if ($invoiceType == "Deliverer") {
    $sqlQuery = "update deliveries set isPaid = true where id_invoice = $id_invoice";
  }
  if ($invoiceType == "Confirmer") {
    $sqlQuery = "update confirmations set isPaid = true where id_invoice = $id_invoice";
  }

  $stmt = $db->prepare($sqlQuery);
  if ($stmt->execute()) {
    echo true;
  } else {
    echo false;
  }

} else {
  echo false;
}
?>
