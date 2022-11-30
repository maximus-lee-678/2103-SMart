<?php

session_start();
include "helper-functions.php";

$db = make_mongo_connection();

//$search_with_wildcard = "Kallang";
//
//$queryFind = array("address_info.address" => new \MongoDB\BSON\Regex(""));
//$queryProj = array("projection" => array("id" => 1, "last_name" => 1, "first_name" => 1));
//$resultCust = $db->Customer->find($queryFind, $queryProj)->toArray();
//
//$custArr = array();
//foreach ($resultCust as $rs) {
//    array_push($custArr, "'" . $rs["id"] . "-" . $rs["address_info"][0]["address_id"] . "'");
//}
//$custStr = join(",", $custArr);
$staff_id = 43;

$conn = make_connection();

$query = 'SELECT os.order_id, o.cust_id, o.address_id FROM Order_Status AS os 
                    INNER JOIN (SELECT order_id, MAX(status_id) AS status_id FROM Order_Status GROUP BY order_id) AS os2 ON os.order_id = os2.order_id AND os.status_id = os2.status_id AND os.status_id = 2 AND os.created_by = ?
                    LEFT JOIN SMart.Order AS o ON os.order_id=o.id';
$result = payload_deliver($conn, $query, "i", $params = array($staff_id));
$row = mysqli_fetch_assoc($result);

$mongo = $db->Customer->find(array("id" => (int) $row["cust_id"], "address_info.address_id" => (int) $row["address_id"]))->toArray();
echo "<pre>";
print_r($mongo["address_info"][0]["address_id"]);
print_r($mongo);
echo "</pre>";

