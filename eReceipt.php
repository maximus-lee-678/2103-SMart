<!DOCTYPE html>
<?php
session_start();

include "helper-functions.php";

//[FUNCTIONS]///////////////////////////////////////////////////////////////////////////////////////
function is_cart_empty() {
    $cust_id = sanitize_input($_SESSION["id"]);

    $conn = make_connection();

    // 1. Selects rows from cart
    $query = 'SELECT id FROM Cart WHERE cust_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($cust_id));

    // No rows, cart empty
    if ($result->num_rows == 0) {
        $conn->close();
        return true;
    }

    // Rows found
    $conn->close();
    return false;
}

function handle_potential_updates($operation, $data_array) {
    $conn = make_connection();

    switch ($operation) {
        case "address":
            // 1. Check if changes made to fields for row in [Customer_Address]
            $query = 'SELECT id AS address_id, address, unit_no, postal_code FROM Customer_Address WHERE alias = ? AND cust_id = ? AND active = 1';
            $result = payload_deliver($conn, $query, "si", $params = array($data_array["alias"], $data_array["user_id"]));

            if ($result->num_rows != 1) {
                $conn->close();
                return "Incorrect rows received: " . $result->num_rows;
            }

            $row = mysqli_fetch_assoc($result);

            // User made changes to address fields
            if ($row["address"] != $data_array["address"] || $row["unit_no"] != $data_array["unit_no"] || $row["postal_code"] != $data_array["postal_code"]) {
                //Store data for future use
                $address_id = $row["address_id"];

                // 2. Check if [Orders] table has the address id for any of its rows
                $query = 'SELECT id FROM SMart.Order WHERE address_id = ?';
                $result = payload_deliver($conn, $query, "i", $params = array($address_id));

                // No rows returned, no entries for this address in [Orders], just update the address
                if ($result->num_rows == 0) {
                    // 3.1. Make updates to corresponding [Customer_Address] row
                    $query = 'UPDATE Customer_Address SET address = ?, unit_no = ?, postal_code = ? WHERE id = ?';
                    payload_deliver($conn, $query, "ssii", $params = array($data_array["address"], $data_array["unit_no"], $data_array["postal_code"], $address_id));

                    $conn->close();
                    return "Address updated!";
                }
                // Rows returned, there exist entries for this address in [Orders], set the address to be inactive and add a new address
                else {
                    // 3.2.1. Set old [Customer_Address] row active = 0
                    $query = 'UPDATE Customer_Address SET active = 0 WHERE alias = ? AND cust_id = ?';
                    payload_deliver($conn, $query, "si", $params = array($data_array["alias"], $data_array["user_id"]));

                    // 3.2.2. Create new [Customer_Address] row
                    $query = 'INSERT INTO Customer_Address (cust_id, alias, address, unit_no, postal_code, active) VALUES (?,?,?,?,?,true)';
                    payload_deliver($conn, $query, "issss", $params = array($data_array["user_id"], $data_array["alias"], $data_array["address"], $data_array["unit_no"], $data_array["postal_code"]));

                    $conn->close();
                    return "Previous address deactivated, new address created!";
                }
            }
            // User did not make changes to address fields
            else {
                $conn->close();
                return "No changes made.";
            }

            break;
////////////////////////////////////////////////////////////////////////////////////////////////////
        case "payment":
            // 1. Check if changes made to fields for row in [Customer_Payment]
            $query = 'SELECT id AS payment_id, owner, account_no, expiry FROM Customer_Payment WHERE payment_type = ? AND cust_id = ? AND active = 1';
            $result = payload_deliver($conn, $query, "si", $params = array($data_array["payment_type"], $data_array["user_id"]));

            if ($result->num_rows != 1) {
                $conn->close();
                return "Incorrect rows received: " . $result->num_rows;
            }

            $row = mysqli_fetch_assoc($result);

            // User made changes to payment fields
            if ($row["owner"] != $data_array["owner"] || $row["account_no"] != $data_array["account_no"] || $row["expiry"] != $data_array["expiry"]) {
                //Store data for future use
                $payment_id = $row["payment_id"];

                // 2. Check if [Orders] table has the payment id for any of its rows
                $query = 'SELECT id FROM SMart.Order WHERE payment_id = ?';
                $result = payload_deliver($conn, $query, "i", $params = array($payment_id));

                // No rows returned, no entries for this payment in [Orders], just update the payment
                if ($result->num_rows == 0) {
                    // 3.1. Make updates to corresponding [Customer_Payment] row
                    $query = 'UPDATE Customer_Payment SET owner = ?, account_no = ?, expiry = ? WHERE id = ?';
                    payload_deliver($conn, $query, "sisi", $params = array($data_array["address"], $data_array["unit_no"], $data_array["postal_code"], $address_id));

                    $conn->close();
                    return "Payment method updated!";
                }
                // Rows returned, there exist entries for this payment in [Orders], set the payment to be inactive and add a new payment
                else {
                    // 3.2.1. Set old [Customer_Payment] row active = 0
                    $query = 'UPDATE Customer_Payment SET active = 0 WHERE payment_type = ? AND cust_id = ?';
                    payload_deliver($conn, $query, "si", $params = array($data_array["payment_type"], $data_array["user_id"]));

                    // 3.2.2. Create new [Customer_Payment] row
                    $query = 'INSERT INTO Customer_Payment (cust_id, payment_type, owner, account_no, expiry, active) VALUES (?,?,?,?,?,true)';
                    payload_deliver($conn, $query, "issis", $params = array($data_array["user_id"], $data_array["payment_type"], $data_array["owner"], $data_array["account_no"], $data_array["expiry"]));

                    $conn->close();
                    return "Previous payment method deactivated, new payment method created!";
                }
            }
            // User did not make changes to payment fields
            else {
                $conn->close();
                return "No changes made.";
            }

            break;
////////////////////////////////////////////////////////////////////////////////////////////////////
        default:
            $conn->close();
            break;
    }

    return;
}

function checkout($cust_id, $address_alias, $payment_type) {
    $delivery_charge = 5;
    $staff_id = 1;
    $status_id = 1;

    $conn = make_connection();

    // 1. Create row in [Order]
    $query = 'INSERT INTO SMart.Order(cust_id, address_id, payment_id, created_at, subtotal, service_charge, delivery_charge) 
            VALUES 
            (?, 
            (SELECT id FROM Customer_Address WHERE alias = ? AND cust_id = ? AND active = 1), 
            (SELECT id FROM Customer_Payment WHERE payment_type = ? AND cust_id = ? AND active = 1), 
            NOW(), 
            (SELECT SUM(c.quantity * p.price) AS subtotal FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?), 
            ?,
            (SELECT ROUND(0.05*SUM(c.quantity * p.price), 2) AS subtotal FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?)
            )';
    payload_deliver($conn, $query, "isisiiii", $params = array($cust_id, $address_alias, $cust_id, $payment_type, $cust_id, $cust_id, $delivery_charge, $cust_id));

    // Store ID of newly created [Order] entry
    $order_id = $conn->insert_id;

    // 2. Create row in [Order_Status]
    $query = 'INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, ?, NOW(), ?)';
    payload_deliver($conn, $query, "iii", $params = array($order_id, $status_id, $staff_id));

    // 3. Get data needed for insertion into [Order_Items] from [Cart] and [Product]
    $query = 'SELECT p.id AS product_id, c.quantity, p.price FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($cust_id));

    // Store data into array
    $cart_items = array();

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cart_items, $row);
    }

    foreach ($cart_items as $cart_row) {
        // 4. Insert data into [Order_Items]
        $query = 'INSERT INTO Order_Items(order_id, prod_id, quantity, price) VALUES(?, ?, ?, ?)';
        payload_deliver($conn, $query, "iiid", $params = array($order_id, $cart_row["product_id"], $cart_row["quantity"], $cart_row["price"]));
    }

    // 5. Remove corresponding [Cart] rows
    $query = 'DELETE FROM Cart WHERE cust_id = ?';
    payload_deliver($conn, $query, "i", $params = array($cust_id));

    $conn->close();
    return array("Purchase Successful!", $order_id);
}

function print_eReceipt($order_id) {
    $service_charge_percent = 5;

    $cust_id = sanitize_input($_SESSION["id"]);
    $order_id = sanitize_input($order_id);

    $conn = make_connection();

    // 1. Get all rows in [Order_Items] based on order_id and cust_id
    $query = 'SELECT oi.id, oi.prod_id, p.name, p.image_url, oi.quantity, oi.price FROM Order_Items AS oi 
            INNER JOIN Product AS p ON oi.prod_id = p.id 
            INNER JOIN SMart.Order as o ON o.id = oi.order_id 
            WHERE oi.order_id = ? AND o.cust_id = ?';
    $result = payload_deliver($conn, $query, "ii", $params = array($order_id, $cust_id));

    // For whatever reason, no rows
    if ($result->num_rows == 0) {
        $conn->close();
        return "Incorrect rows received: " . $result->num_rows;
    }

    $total_quantity = 0;

    // Print table headers (1)
    echo '<table class="carttable" style="font-size: 1.4rem;">
            <tr style="text-align: center; background: #6D6875; color: white;">
                <th colspan="2">Product</th>
                <th>Price</th>
                <th>Total</th>
                <th>Quantity</th>
            </tr>';

    // Print table rows (1)
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr id="' . $row["prod_id"] . '">
                <td><img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" class="imagesize"></td>
                <td>' . $row["name"] . '</td>
                <td>$' . $row["price"] . '</td>
                <td>$' . number_format($row["price"] * $row["quantity"], 2, '.', '') . '</td>
                <td>' . $row["quantity"] . '</td>
            </tr>';

        $total_quantity += $row["quantity"];
    }

    // 2. Get [Order] information
    $query = 'SELECT created_at, subtotal, service_charge, delivery_charge FROM SMart.Order WHERE id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($order_id));

    // For whatever reason, no rows
    if ($result->num_rows == 0) {
        $conn->close();
        return "Incorrect rows received: " . $result->num_rows;
    }

    $row = mysqli_fetch_assoc($result);

    // Print overall order information
    echo '<tr>
            <td colspan="3" style="text-align: right;">Total: </td>
            <td>$' . number_format($row["subtotal"], 2, '.', '') . '</td>
            <td>' . $total_quantity . '</td>
       </tr>
       </table>
       <table class="carttable" style="font-size: 1.4rem; margin-top: 40px;">
           <tr style="text-align: center; background: white;">
               <td colspan="2">Delivery Fee (' . $service_charge_percent . '%): </td>
               <td colspan="2">$' . number_format($row["service_charge"], 2, '.', '') . '</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Service Fee: </td>
               <td colspan="2">$' . number_format($row["delivery_charge"], 2, '.', '') . '</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Final Cost: </td>
               <td colspan="2">$' . number_format($row["subtotal"] + $row["service_charge"] + $row["delivery_charge"], 2, '.', '') . '</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Order ID: </td>
               <td colspan="2">' . $order_id . '</td>
           </tr>
           <tr style="text-align: center; background: white;">
               <td colspan="2">Purchase Date: </td>
               <td colspan="2">' . $row["created_at"] . '</td>
           </tr>
       </table>
       </div>';

    $conn->close();

    return "Print complete.";
}

////////////////////////////////////////////////////////////////////////////////////////////////////

if (($_SERVER['REQUEST_METHOD'] == 'GET')) {
    
}

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {
    if (isset($_SESSION["id"], $_POST["address_alias"], $_POST["address_address"], $_POST["address_unit_number"], $_POST["address_postal_code"],
                    $_POST["payment_type"], $_POST["payment_owner"], $_POST["payment_account_no"], $_POST["payment_expiry_date"], $_POST["payment_cvv"])) {
        $address_array = array("user_id" => sanitize_input($_SESSION["id"]),
            "alias" => sanitize_input($_POST["address_alias"]),
            "address" => sanitize_input($_POST["address_address"]),
            "unit_no" => sanitize_input($_POST["address_unit_number"]),
            "postal_code" => sanitize_input($_POST["address_postal_code"])
        );
        $payment_array = array("user_id" => sanitize_input($_SESSION["id"]),
            "payment_type" => sanitize_input($_POST["payment_type"]),
            "owner" => sanitize_input($_POST["payment_owner"]),
            "account_no" => sanitize_input($_POST["payment_account_no"]),
            "expiry" => sanitize_input($_POST["payment_expiry_date"])
        );

        $updateMsg = $checkoutMsg = $resultText = "";
        $updateMsgBool = $checkoutMsgBool = true;

        $resultText = handle_potential_updates("address", $address_array);
        if (!str_contains($resultText, "No changes made.")) {
            $updateMsg .= $resultText;
        }

        $resultText = handle_potential_updates("payment", $payment_array);
        if (!str_contains($resultText, "No changes made.")) {
            $updateMsg .= "\n" . $resultText;
        }
    } else {
        $updateMsg .= "Something went wrong when updating address/payment. Please Try Again.";
        $updateMsgBool = false;
    }

    if (isset($_SESSION["id"], $_POST["address_alias"], $_POST["payment_type"]) && !is_cart_empty()) {

        $temp = checkout(sanitize_input($_SESSION["id"]), sanitize_input($_POST["address_alias"]), sanitize_input($_POST["payment_type"]));
        $checkoutMsg .= $temp[0];
        $order_id = $temp[1];
    } else {
        $checkoutMsg .= "Something went wrong when checking out. Please Try Again.";
        $checkoutMsgBool = false;
    }
}
?>

<html lang="en">
    <title>E-Receipt</title>
    <?php
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends --> 

        <div class="heading">
            <h1>E-Receipt</h1>
        </div>


        <section class="myShoppingCart cartcontainer">
            <h1 class="carttitle">
                <span>E-Receipt</span>
            </h1>

            <br>
            <div style="font-size: 2.7rem; color: #666;">
                <?php
                if (!empty($updateMsg)) {
                    if ($updateMsgBool) {
                        echo '<img src="image/successtick.png" alt="location" class="iconsize">';
                    } else {
                        echo '<img src="image/failedcross.png" alt="location" class="iconsize">';
                    }
                    echo '<label>' . $updateMsg . '</label>
                    <br><br>';
                }
                if (!empty($checkoutMsg)) {
                    if ($checkoutMsgBool) {
                        echo '<img src="image/successtick.png" alt="location" class="iconsize">';
                    } else {
                        echo '<img src="image/failedcross.png" alt="location" class="iconsize">';
                    }
                    echo '<label>' . $checkoutMsg . '</label>
                    <br><br>';
                }
                ?>
            </div>

            <div class="box-container" id="summary-contents" style="display: inline;">
                <?php
                if (isset($order_id)) {
                    print_eReceipt($order_id);
                }
                ?>
            </div>    
        </section>

        <section class="myShoppingCart">
            <a href="home.php" style="width: 100%; margin-top: 40px; text-align: center;" class="btn">Back to Home</a>
            <a href="orderHistory.php" style="width: 100%; margin-top: 40px; text-align: center;" class="btn">Order History</a>
        </section>

        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>