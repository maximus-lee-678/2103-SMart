<?php

session_start();
include "helper-functions.php";

$db = make_mongo_connection();

$retrieved = $_POST["reviews"];
$order_id = $_POST["order_id"];

for ($i = 0; $i < count($retrieved); $i++) {
    $content = $retrieved[$i];
    $retrieved[$i] = array(
        "prod_id" => (int) $content["prod_id"],
        "cust_id" => $_SESSION["id"],
        "rating" => (int) $content["stars"],
        "review" => $content["comment"]
    );
}

$db->Review->insertMany($retrieved);

$conn = make_connection();
$query = "UPDATE SMart.Order SET has_reviewed = 1 WHERE id = ?";
payload_deliver($conn, $query, "i", $params = array($order_id));


?>
