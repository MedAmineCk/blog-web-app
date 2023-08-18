<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-address: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/Dashboard.php';

$database = new Database();
$db = $database->getConnection();

$dashboard = new Dashboard($db);

//get data as _agentObj
$dashboardObj = isset($_GET['_adminObj']) ? $_GET['_adminObj'] : die('you need to specify adminObj!');

$name = $dashboardObj["name"];
$dashboard->name = $name;
if($dashboard->updateConfig()){
    //update authentication
    $email = $dashboardObj["email"];
    $password = $dashboardObj["password"];
    $dashboard->email = $email;
    $dashboard->password = $password;
    if ($dashboard->updateAdminAuth()) {
        echo true;
    } else {
        echo "updateAdminAuth err!";
    }
} else {
    echo "updateConfig err!";
}


