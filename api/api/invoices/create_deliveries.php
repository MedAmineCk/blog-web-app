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
include_once '../../class/Notifications.php';

$database = new Database();
$db = $database->getConnection();

$invoice = new Invoice($db);
$delivery = new Order($db);
$crypt = new encryption_class();
$notification = new Notification($db);

//hash config
$key_delivery = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-DELIVERIES";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

$invoiceObj = isset($_GET['invoiceObj']) ? $_GET['invoiceObj'] : die("no data passed!");
$id_user_enc = $invoiceObj["id_user"];
$id_user = intval($crypt->decrypt($key_user, $id_user_enc));
$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));

$deliveries_arr = $invoiceObj["deliveries"];
$id_deliveries_decrypted = "(";
$i = 0;

foreach ($deliveries_arr as $key => $id) {
    // DECRYPT
    $delivery_id = $id;
    $id_delivery_dec = $crypt->decrypt($key_delivery, $delivery_id);
    if ($i != 0) {
        $id_deliveries_decrypted .= ", " . $id_delivery_dec;
    } else {
        $id_deliveries_decrypted .= $id_delivery_dec;
    }
    $i++;
}
$id_deliveries_decrypted .= ")";


/*-------------   get the amount   ----------*/

$sqlQuery = "select
    SUM( if(deliveries.delivery_status = 'Deliver', deliverers.price_delivered, if(deliveries.delivery_status = 'Return', deliverers.price_returned, 0))) as credit,
    SUM( if(deliveries.delivery_status = 'Deliver', orders.total, 0)) as bill
from deliveries, deliverers, orders where deliveries.id_order=orders.id_order and deliveries.id_delivery in $id_deliveries_decrypted and deliverers.id_deliverer = deliveries.id_deliverer and deliverers.id_deliverer = $id_user;";
$stmt = $db->prepare($sqlQuery);
if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $credit = $row["credit"];
        $bill = $row["bill"] - $credit;

        $invoice->id_user = $id_user;
        $invoice->full_name = $invoiceObj["full_name"];
        $invoice->type = $invoiceObj["type"]; //client or deliverer
        $invoice->credit = $credit;
        $invoice->bill = $bill;
        $invoice->creation_date = $datetime;
        $invoice->comment = $invoiceObj["comment"];

        if($id_invoice = $invoice->createInvoice()){
            //after creation of invoice update deliveries id_invoice
            $sqlQuery = "update deliveries set id_invoice = $id_invoice where id_delivery in $id_deliveries_decrypted;";
            $stmt = $db->prepare($sqlQuery);
            if ($stmt->execute()){
                $notification->target = 'Deliverer';
                $notification->id_target = $id_user;
                $notification->content = 'new Invoice Added to your profile';
                $notification->datetime = $datetime;

                if ($notification->createNotification()) {
                  $id_invoice_enc = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
                  echo json_encode(array("id_invoice" => $id_invoice_enc));
                }else{
                    echo false;
                }
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
