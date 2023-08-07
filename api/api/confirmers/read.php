<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Confirmers.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$items = new Confirmer($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED";
$min_length = 8;
$max_length = 8;

$stmt = $items->getConfirmers();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $ConfirmerArr = array();
    $ConfirmerArr["body"] = array();
    $ConfirmerArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_confirmer = $crypt->encrypt($key, $id_confirmer, $min_length, $max_length);
        $e = array(
            "id_confirmer" => $id_confirmer,
            "confirmer_name" => $confirmer_name,
            "CIN" => $CIN,
            "phone_number" => $phone_number,
            "email" => $email,
            "password" => $password,
            "profile_pic" => $profile_pic,
        );

        array_push($ConfirmerArr["body"], $e);
    }
    echo json_encode($ConfirmerArr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
