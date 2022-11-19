<?php

include "helper-functions.php";
include "helper-profile.php";

session_start();

$newData = [];
$result;

if (isset($_POST['type'])) {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false) {
        echo json_encode(array("success" => false, "data" => "Customer is not logged in.<br>"));
    } else {
        $data = $_POST['data'];

        switch ($_POST['type']) {
            case "profile":
                $result = profileOperation("update", $data);
                break;
            case "address":
                $result = addressOperation($_POST['mode'], $data);
                break;
            case "password":
                $result = passwordOperation("update", $data);
                break;
            case "card":
                $result = paymentOperation($_POST['mode'], $data);
                break;
            default:
                break;
        }
        
        echo json_encode(array("success" => $result['success'], "data" => $result['data']));
    }

    
}

