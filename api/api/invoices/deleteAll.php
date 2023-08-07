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
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-BILLS";
$min_length = 8;
$max_length = 8;

$id_invoices_enc_arr = isset($_GET['id_invoices']) ? $_GET['id_invoices'] : die('you need to specify status!');

$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));

$id_invoices_decrypted = "(";
$i = 0;

foreach ($id_invoices_enc_arr as $index => $id_invoice) {
    // DECRYPT
    $id_invoice_dec = $crypt->decrypt($key, $id_invoice);
    if ($i != 0) {
        $id_invoices_decrypted .= ", " . $id_invoice_dec;
    } else {
        $id_invoices_decrypted .= $id_invoice_dec;
    }
    $i++;
}
$id_invoices_decrypted .= ")";
//remove id_invoice from orders first then remove the invoice
$sqlQuery = "UPDATE orders SET id_invoice = null WHERE id_invoice in " . $id_invoices_decrypted;

$stmt = $db->prepare($sqlQuery);

if ($stmt->execute()) {
    //remove invoice
    $sqlQuery = "DELETE from invoices WHERE id_invoice IN " . $id_invoices_decrypted;
    $stmt = $db->prepare($sqlQuery);
    if ($stmt->execute()) {
        echo true;
    } else {
        echo false;
    }
} else {
    echo $sqlQuery;
}
