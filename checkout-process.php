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

switch ($_POST["operation"]) {
    case "address_alias":
        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);

        // Prepare the statement:
        $query = 'SELECT id, alias FROM Customer_Address WHERE cust_id = ? AND active = 1';
        $result = payload_deliver($conn, $query, "i", $params = array($cust_id));

        if ($result->num_rows > 0) {
            echo '<option value="empty">- Select an Address -</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option identifier="' . $row["id"] . '" value="' . $row["alias"] . '">' . $row["alias"] . '</option>';
            }
        } else {
            
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "load-address":
        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);
        $address_id = sanitize_input($_POST["address_id"]);

        // Prepare the statement:
        $query = 'SELECT address, unit_no, postal_code FROM Customer_Address WHERE cust_id = ? AND id = ? AND active = 1';
        $result = payload_deliver($conn, $query, "is", $params = array($cust_id, $address_id));

        $row = mysqli_fetch_assoc($result);
        echo '{"address": "' . $row["address"] . '", "unit_no": "' . $row["unit_no"] . '", "postal_code": ' . $row["postal_code"] . '}';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "credit_owner":
        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);

        // Prepare the statement:
        $query = 'SELECT id, payment_type FROM Customer_Payment WHERE cust_id = ? AND active = 1';
        $result = payload_deliver($conn, $query, "i", $params = array($cust_id));

        if ($result->num_rows > 0) {
            echo '<option value="empty">- Select a Card -</option>';
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option identifier="' . $row["id"] . '" value="' . $row["payment_type"] . '">' . $row["payment_type"] . '</option>';
            }
        } else {
            
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "load-card-details":
        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);
        $payment_id = sanitize_input($_POST["payment_id"]);

        // Prepare the statement:
        $query = 'SELECT owner, account_no, expiry FROM Customer_Payment WHERE cust_id = ? AND id = ? AND active = 1';
        $result = payload_deliver($conn, $query, "is", $params = array($cust_id, $payment_id));

        $row = mysqli_fetch_assoc($result);
        echo '{"owner": "' . $row["owner"] . '", "account_no": ' . $row["account_no"] . ', "expiry": "' . $row["expiry"] . '"}';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    default:
        break;
}

$conn->close();
?>
