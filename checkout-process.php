<?php

session_start();

include "helper-functions.php";

//if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
//    header("refresh: 0; url=shop.php");
//    exit;
//}
?>


<?php

if (!(isset($_SESSION["id"]) && isset($_POST["operation"]))) {
    echo '<div class ="cartBox" style="font-size: 1.4rem; color: #666;">Please login first!</div>';
    exit();
}

switch ($_POST["operation"]) {
    case "address_alias":
        break;
    case "load-address":
        if (!isset($_POST['address_id'])) {
            echo "Missing fields!";
            exit;
        }
        break;
    case "credit_owner":
        break;
    case "load-card-details":
        if (!isset($_POST['payment_id'])) {
            echo "Missing fields!";
            exit;
        }
        break;
    default:
        break;
}

$conn = make_connection();
$db = make_mongo_connection();

switch ($_POST["operation"]) {
    case "address_alias":
        //form parameters
        $cust_id = (int) sanitize_input($_SESSION["id"]);

        // Prepare the statement:
        $query = array("id" => $cust_id, "address_info.active" => true);
        $result = $db->Customer->find($query)->toArray()[0];

        if (!empty($result["address_info"])) {
            echo '<option value="empty">- Select an Address -</option>';
            foreach ($result["address_info"] as $row) {
                if(!$row["active"]) continue;
                echo '<option identifier="' . $row["address_id"] . '" value="' . $row["alias"] . '">' . $row["alias"] . '</option>';
            }
        } 

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "load-address":
        //form parameters
        $cust_id = (int) sanitize_input($_SESSION["id"]);
        $address_id = (int) sanitize_input($_POST["address_id"]);

        // Prepare the statement:
        $query = array("id" => $cust_id, "address_info.address_id" => $address_id);
        $result = $db->Customer->find($query)->toArray()[0];

        foreach ($result["address_info"] as $row) {
            if ($row["address_id"] == $address_id){
                echo '{"address": "' . $row["address"] . '", "unit_no": "' . $row["unit_no"] . '", "postal_code": ' . $row["postal_code"] . '}';
                break;
            }
        }

//        echo '{"address": "' . $row["address"] . '", "unit_no": "' . $row["unit_no"] . '", "postal_code": ' . $row["postal_code"] . '}';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "credit_owner":
        //form parameters
        $cust_id = (int) sanitize_input($_SESSION["id"]);
        
        // Prepare the statement:
        $query = array("id" => $cust_id, "payment_info.active" => true);
        $result = $db->Customer->find($query)->toArray()[0];

        if (!empty($result["payment_info"])) {
            echo '<option value="empty">- Select a Card -</option>';
            foreach ($result["payment_info"] as $row) {
                if(!$row["active"]) continue;
                echo '<option identifier="' . $row["payment_id"] . '" value="' . $row["type"] . '">' . $row["type"] . '</option>';
            }
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "load-card-details":
        //form parameters
        $cust_id = (int) sanitize_input($_SESSION["id"]);
        $payment_id = (int) sanitize_input($_POST["payment_id"]);

        // Prepare the statement:
        $query = array("id" => $cust_id, "payment_info.payment_id" => $payment_id);
        $result = $db->Customer->find($query)->toArray()[0];
        
        foreach ($result["payment_info"] as $row) {
            if ($row["payment_id"] == $payment_id){
                echo '{"owner": "' . $row["owner"] . '", "account_no": ' . $row["account_no"] . ', "expiry": "' . $row["expiry"] . '"}';
                break;
            }
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    default:
        break;
}

$conn->close();
?>
