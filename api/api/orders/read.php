<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../../config/database.php';
include_once '../../class/Orders.php';
include_once '../../class/Encryptions.php';

$database = new Database();
$db = $database->getConnection();

$orders = new Order($db);
$crypt = new encryption_class();

//hash config
$key = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-ORDERS";
$key_area = "A-COMPLETELY-RANDOM-KEY-THAT-I-HAVE-USED-FOR-AREAS";
$min_length = 8;
$max_length = 8;

$orders_obj_arr = array();
$orders_obj_arr["data"] = array();
$orders_obj_arr["orders"] = array();

//pagination

$data_table_configObj = isset($_GET['_config']) ? $_GET['_config'] : die('you need to specify _config!');
$data_limit = $data_table_configObj["dataLimit"];
$page_index = $data_table_configObj["page_index"];
$filter = $data_table_configObj["filter"];
$id_area = $data_table_configObj["id_area"];
$search = $data_table_configObj["search"];
$isPackedBool = $data_table_configObj["isPacked"];
$sortBool = $data_table_configObj["sort"];

// DECRYPT
if($search != ''){
    $search = $crypt->decrypt($key, $search);
    $search = intval($search);
    $search = 'id_order = '.$search;
}else{
    $search = true;
}

if($id_area != 0){
  $id_area = intval($crypt->decrypt($key_area, $id_area));
  $area = 'areas.id_area = '.$id_area;
//  echo $area;
}else{
  $area = true;
}

$data_count = ($page_index - 1)*$data_limit;

$filter = ($filter != 'All') ? 'status = \''.$filter.'\'' : true ;
$isPacked = ($isPackedBool != "is note") ? 'id_deliverer_pack is null' : true ;
$sort = ($sortBool != "is note") ? 'ASC' : 'DESC';

$orders->filter = $filter;
$orders->data_count = $data_count;
$orders->data_limit = $data_limit;
$orders->area = $area;
$orders->search = $search;
$orders->isPacked = $isPacked;
$orders->sort = $sort;

$stmt = $orders->getOrders();
$itemCount = $stmt->rowCount();

if ($itemCount > 0) {

    $OrderArr = array();
    $OrderArr["body"] = array();
    $OrderArr["itemCount"] = $itemCount;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $id_order = $crypt->encrypt($key, $id_order, $min_length, $max_length);
        $e = array(
            "id_order" => $id_order,
            "order_date" => $created_at,
            "buyer_name" => $client_name,
            "items_quantity" => $items_quantity,
            "price" => $total,
            "buyer_city" => $area,
            "status" => $status,
            "id_deliverer_pack" => $id_deliverer_pack,
            "isPacked" => $isPacked
        );

        array_push($OrderArr["body"], $e);
    }
    $orders_obj_arr["orders"] = $OrderArr;
}

$orders->getOrdersDataCounts();
if ($orders->Pending != null) {
    $data_arr = array(
        "Total" => $orders->Total,
        "Deliver" => $orders->Deliver,
        "Return" => $orders->Return,
        "Pending" => $orders->Pending,
    );
    $orders_obj_arr["data"] = $data_arr;
}

if (!empty($orders_obj_arr)) {
    http_response_code(200);
    echo json_encode($orders_obj_arr);
} else {
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
