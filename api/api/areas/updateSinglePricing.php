<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-description: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Areas.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$pricing = new Area($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-AREAS";
$min_length = 8;
$max_length = 8;

$pricingObj = isset($_GET['pricingObj']) ? $_GET['pricingObj'] : die('you need to specify pricingObj!');
$id_area_enc = $pricingObj['id_area'];
$id_area = intval($crypt->decrypt($key, $id_area_enc));

$area= $pricingObj['area'];
$shipping= $pricingObj['shipping'];
$return_price= $pricingObj['return_price'];

$pricing->id_area = $id_area;
$pricing->area = $area;
$pricing->shipping = $shipping;
$pricing->return_price = $return_price;

if ($pricing->updateArea()) {
 echo true;
} else {
    echo false;
}

