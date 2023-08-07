<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../class/Areas.php';

$database = new Database();
$db = $database->getConnection();

$pricing = new Area($db);

//get all Pricing data as pricingArr
$pricingArr = isset($_GET['_pricingArr']) ? $_GET['_pricingArr'] : die("no data passed!");
//for each area create pricing and client pricing
$err = 0;
foreach ($pricingArr as $pricingObj) {
    $pricing->area = $pricingObj['area'];
    $pricing->shipping = $pricingObj['delivery_price'];
    $pricing->return_price = $pricingObj['return_price'];
    ($pricing->createArea()) ? $err = 0 : $err++;
}

if($err == 0){
    echo true;
}else{
    echo false;
}
