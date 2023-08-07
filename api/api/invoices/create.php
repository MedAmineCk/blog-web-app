<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Invoices.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$invoice = new Invoice($db);
$order = new Order($db);
$crypt = new encryption_class();

//hash config
$key_order = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

$invoiceObj = isset($_GET['invoiceObj']) ? $_GET['invoiceObj'] : die("no data passed!");
$id_user_enc = $invoiceObj["id_user"];
$id_user = intval($crypt->decrypt($key_user, $id_user_enc));
$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));

$orders_arr = $invoiceObj["orders"];
$id_orders_decrypted = "(";
$i = 0;

foreach ($orders_arr as $key => $id) {
    // DECRYPT
    $order_id = $id;
    $id_order_dec = $crypt->decrypt($key_order, $order_id);
    if ($i != 0) {
        $id_orders_decrypted .= ", " . $id_order_dec;
    } else {
        $id_orders_decrypted .= $id_order_dec;
    }
    $i++;
}
$id_orders_decrypted .= ")";


/*-------------   get the amount   ----------*/

$sqlQuery = "select
    SUM( if(orders.status = 'Deliver', orders.price, 0)) as credit,
    SUM( if(orders.status = 'Deliver', client_pricing.delivery_price, client_pricing.return_price)) as bill
from orders, client_pricing where orders.id_order in $id_orders_decrypted and client_pricing.id_area = orders.id_area and client_pricing.id_client = $id_user;";
$stmt = $db->prepare($sqlQuery);
if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $credit = $row["credit"];
        $bill = $row["bill"];

        $invoice->id_user = $id_user;
        $invoice->full_name = $invoiceObj["full_name"];
        $invoice->type = $invoiceObj["type"]; //client or deliverer
        $invoice->credit = $credit;
        $invoice->bill = $bill;
        $invoice->creation_date = $datetime;
        $invoice->comment = $invoiceObj["comment"];

        if($id_invoice = $invoice->createInvoice()){
            //after creation of invoice update orders id_invoice
            $sqlQuery = "update orders set id_invoice = $id_invoice where id_order in $id_orders_decrypted;";
            $stmt = $db->prepare($sqlQuery);
            if ($stmt->execute()){
              $id_invoice_enc = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
              echo json_encode(array("id_invoice" => $id_invoice_enc));
            }else{
                echo false;
            }
        }else{
            echo false;
        }


    }
} else {
    echo false;
}

/*
    $invoice->id_user = $id_user;
    $invoice->full_name = $invoiceObj["full_name"];
    $invoice->type = $invoiceObj["type"]; //client or deliverer
    $invoice->creation_date = $datetime;
    $invoice->comment = $invoiceObj["comment"];
*/


/*
 * //variable for notifications
$id_admin = $invoice->id_user;
$admin_type = $invoice->type;
if ($id_invoice = $invoice->createInvoice()) {
//update invoice id for each order

    $sqlQuery = "UPDATE orders SET id_invoice = $id_invoice WHERE id_order IN " . $id_orders_decrypted;
    $stmt = $db->prepare($sqlQuery);
    if ($stmt->execute()) {

        echo true;
    } else {
        echo $sqlQuery;
    }
} else {
    echo false;
}
*/
/*$id_invoice_enc = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
        $sqlQuery = "INSERT into notifications(id_target, target, content)
        VALUE ($id_admin, '$admin_type', 'you have new Invoice #$id_invoice_enc');";

        $stmt = $db->prepare($sqlQuery);

        if ($stmt->execute()) {
            echo true;
        } else {
            echo false;
        }*/
