<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
//database
include_once '../../config/database.php';
//classes
include_once '../../class/Invoices.php';
include_once '../../class/Deliverers.php';
include_once '../../class/Deliveries.php';
include_once '../../class/Confirmers.php';
include_once '../../class/Confirmations.php';
include_once '../../class/Orders.php';
//hashing
include_once '../../class/Encryptions.php';

//database
$database = new Database();
$db = $database->getConnection();

//initial classes
$invoice = new Invoice($db);
$orders = new Order($db);

//initial hashing
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$key_user = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_order = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_deliverer = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_delivery = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-DELIVERIES";
$key_confirmer = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$key_confirmation = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-CONFIRMATIONS";
$key_invoice = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-INVOICES";
$min_length = 8;
$max_length = 8;

//get the id_invoice
$encrypted_id_invoice = isset($_GET['id_invoice']) ? $_GET['id_invoice'] : die('you need to specify id_invoice!');

// DECRYPT
$id_invoice_dec = $crypt->decrypt($key_invoice, $encrypted_id_invoice);
$id_invoice = intval($id_invoice_dec);

$invoiceDetails_obj_arr = array();
$invoiceDetails_obj_arr["invoice"] = array();
$invoiceDetails_obj_arr["user"] = array();
$invoiceDetails_obj_arr["orders"] = array();
$invoiceDetails_obj_arr["deliveries"] = array();
$invoiceDetails_obj_arr["confirmations"] = array();

//invoice details
$invoice->id_invoice = $id_invoice;
$invoice->getSingleInvoice();
if ($invoice->id_user) {
  $id_user = $invoice->id_user;
  $type_user = $invoice->type;
  $id_user_enc = $crypt->encrypt($key_user, $id_user, $min_length, $max_length);
  $invoice_details_arr = array(
    "id_invoice" => $encrypted_id_invoice,
    "id_user" => $id_user_enc,
    "type" => $type_user,
    "credit" => $invoice->credit,
    "bill" => $invoice->bill,
    "comment" => $invoice->comment,
    "status" => $invoice->status,
    "creation_date" => $invoice->creation_date
  );
  $invoiceDetails_obj_arr["invoice"] = $invoice_details_arr;

  //user details
  if ($type_user == "Deliverer") {

    $deliverer = new Deliverer($db);
    $deliverer->id_deliverer = $id_user;
    $stmt = $deliverer->getSingleDeliverer();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $profile_details_arr = array(
        "deliverer_name" => $deliverer_name,
        "phone_number" => $phone_number,
      );
      $invoiceDetails_obj_arr["user"] = $profile_details_arr;
    } else {
      echo "can't get the deliverer data";
    }

    //invoiced Deliveries
    $delivery = new Delivery($db);
    $delivery->id_invoice = $id_invoice;
    $stmt = $delivery->getInvoiceDeliveries();
    $itemCount = $stmt->rowCount();
    if ($itemCount > 0) {
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_delivery = $crypt->encrypt($key_delivery, $id_delivery, $min_length, $max_length);
        $id_order = $crypt->encrypt($key_order, $id_order, $min_length, $max_length);
        $id_deliverer = $crypt->encrypt($key_deliverer, $id_deliverer, $min_length, $max_length);
        $id_invoice = $crypt->encrypt($key_invoice, $id_invoice, $min_length, $max_length);
        $obj = array(
          "id_delivery" => $id_delivery,
          "id_order" => $id_order,
          "id_deliverer" => $id_deliverer,
          "role_deliverer" => $role_deliverer,
          "delivery_datetime" => $created_at,
          "delivery_status" => $delivery_status,
          "comment" => $comment,
          "id_invoice" => $id_invoice
        );
        array_push($invoiceDetails_obj_arr["deliveries"], $obj);
      }
    } else {
      echo "can't get invoiced deliveries";
    }

  }


  if ($type_user == "Confirmer") {

    $confirmer = new Confirmer($db);
    $confirmer->id_confirmer = $id_user;
    $stmt = $confirmer->getSingleConfirmer();
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      extract($row);
      $profile_details_arr = array(
        "confirmer_name" => $confirmer_name,
        "phone_number" => $phone_number,
      );
      $invoiceDetails_obj_arr["user"] = $profile_details_arr;
    } else {
      echo "can't get the confirmer data";
    }

    //invoiced Confirmations
    $confirmation = new Confirmation($db);
    $confirmation->id_confirmer = $id_confirmer;
    $stmt = $confirmation->getConfirmations();
    $itemCount = $stmt->rowCount();
    if ($itemCount > 0) {
      $deliveries = array();
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
        array_push($invoiceDetails_obj_arr["confirmations"], $obj);
      }
    } else {
      echo "error!";
    }

  }
} else {
  echo "can't get the invoice data";
}

if (!empty($invoiceDetails_obj_arr)) {
  http_response_code(200);
  echo json_encode($invoiceDetails_obj_arr);
} else {
  http_response_code(404);
  echo json_encode(
    array("message" => "No record found.")
  );
}
