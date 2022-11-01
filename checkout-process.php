<?php

session_start();

include "helper-functions.php";

//if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
//    header("refresh: 0; url=shop.php");
//    exit;
//}
?>


<?php

if (isset($_SESSION["id"]) && isset($_POST["operation"])) {
    switch ($_POST["operation"]) {
        case "address_alias":
            break;
        case "load-address":
            if (!isset($_POST['alias_string'])) {
                echo "Missing fields!";
                exit;
            }
            break;
        case "load-card-details":
            if (!isset($_POST['card_type'])) {
                echo "Missing fields!";
                exit;
            }
            break;
        default:
            break;
    }

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $captionText = "Connection failed: " . $conn->connect_error;
    } else {
        switch ($_POST["operation"]) {
            case "address_alias":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);

                // Prepare the statement:
                $stmt = $conn->prepare("SELECT alias FROM Customer_Address WHERE cust_id = ? AND active = 1");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $cust_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo '<option value="empty">- Select an Address -</option>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row["alias"] . '">' . $row["alias"] . '</option>';
                        }
                    }
                }
                break;

            case "load-address":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);
                $alias = sanitize_input($_POST["alias_string"]);

                // Prepare the statement:
                $stmt = $conn->prepare("SELECT address, unit_no, postal_code FROM Customer_Address WHERE cust_id = ? AND alias = ? AND active = 1");
                // Bind & execute the query statement:
                $stmt->bind_param("is", $cust_id, $alias);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo '{"address": "' . $row["address"] . '", "unit_no": "' . $row["unit_no"] . '", "postal_code": ' . $row["postal_code"] . '}';
                    }
                }
                break;

            case "credit_owner":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);

                // Prepare the statement:
                $stmt = $conn->prepare("SELECT payment_type FROM Customer_Payment WHERE cust_id = ? AND active = 1");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $cust_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo '<option value="empty">- Select a Card -</option>';
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<option value="' . $row["payment_type"] . '">' . $row["payment_type"] . '</option>';
                        }
                    }
                }
                break;

            case "load-card-details":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);
                $payment_type = sanitize_input($_POST["card_type"]);

                // Prepare the statement:
                $stmt = $conn->prepare("SELECT owner, account_no, expiry FROM Customer_Payment WHERE cust_id = ? AND payment_type = ? AND active = 1");
                // Bind & execute the query statement:
                $stmt->bind_param("is", $cust_id, $payment_type);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = mysqli_fetch_assoc($result);
                        echo '{"owner": "' . $row["owner"] . '", "account_no": ' . $row["account_no"] . ', "expiry": "' . $row["expiry"] . '"}';
                    }
                }
                break;

            default:
                break;
        }
        $stmt->close();
        $conn->close();
    }
    if ($captionText) {
        echo "<caption>" . $captionText . "</caption>";
    }
} else {
    echo '<div class ="cartBox" style="font-size: 1.4rem; color: #666;">Please login first!</div>';
}
?>
