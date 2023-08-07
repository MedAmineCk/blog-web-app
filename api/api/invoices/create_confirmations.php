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
$confirmation = new Order($db);
$crypt = new encryption_class();
$notification = new Notification($db);

//hash config
$key_confirmation = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CONFIRMATIONS";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

$invoiceObj = isset($_GET['invoiceObj']) ? $_GET['invoiceObj'] : die("no data passed!");
$id_user_enc = $invoiceObj["id_user"];
$id_user = intval($crypt->decrypt($key_user, $id_user_enc));
$timezone = +0; //(GMT +0:00)
$datetime = gmdate("Y-m-d H:i:s", time() + 3600 * ($timezone + date("I")));

$confirmations_arr = $invoiceObj["confirmations"];
$id_confirmations_decrypted = "(";
$i = 0;

foreach ($confirmations_arr as $key => $id) {
    // DECRYPT
    $confirmation_id = $id;
    $id_confirmation_dec = $crypt->decrypt($key_confirmation, $confirmation_id);
    if ($i != 0) {
        $id_confirmations_decrypted .= ", " . $id_confirmation_dec;
    } else {
        $id_confirmations_decrypted .= $id_confirmation_dec;
    }
    $i++;
}
$id_confirmations_decrypted .= ")";


/*-------------   get the amount   ----------*/

$sqlQuery = "select
    SUM( if(confirmations.confirmation_status = 'Confirm', confirmers.price_confirm, if(confirmations.confirmation_status = 'Cancel', confirmers.price_cancel, 0))) as credit
from confirmations, confirmers where confirmations.id_confirmation in $id_confirmations_decrypted and confirmers.id_confirmer = confirmations.id_confirmer and confirmers.id_confirmer = $id_user;";
$stmt = $db->prepare($sqlQuery);
if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $credit = $row["credit"];

        $invoice->id_user = $id_user;
        $invoice->full_name = $invoiceObj["full_name"];
        $invoice->type = $invoiceObj["type"]; //client or deliverer or confirmer
        $invoice->credit = $credit;
        $invoice->bill = 0;
        $invoice->creation_date = $datetime;
        $invoice->comment = $invoiceObj["comment"];

        if($id_invoice = $invoice->createInvoice()){
            //after creation of invoice update confirmations id_invoice
            $sqlQuery = "update confirmations set id_invoice = $id_invoice where id_confirmation in $id_confirmations_decrypted;";
            $stmt = $db->prepare($sqlQuery);
            if ($stmt->execute()){
                $notification->target = 'Confirmer';
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
