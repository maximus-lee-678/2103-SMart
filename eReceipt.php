<!DOCTYPE html>
<?php
session_start();

include "helper-functions.php";

function is_cart_empty() {
    $cust_id = sanitize_input($_SESSION["id"]);

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
        return false;
    }

    // Prepare the statement:
    $stmt = $conn->prepare("SELECT id FROM Cart WHERE cust_id = ?");
    // Bind & execute the query statement:
    $stmt->bind_param("i", $cust_id);
    // execute the query statement:
    if (!$stmt->execute()) {
        echo "Failed while executing query: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return false;
    }

    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }

    $stmt->close();
    $conn->close();
    return false;
}

function handle_potential_updates($operation, $data_array) {
    $returnText = "";

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $returnText .= "Connection failed: " . $conn->connect_error;
        return $returnText;
    }

    switch ($operation) {
        case "address":
            // 1. Check if changes made to fields for row in [Customer_Address]
            // Prepare the statement:
            $stmt = $conn->prepare("SELECT id AS address_id, address, unit_no, postal_code FROM Customer_Address WHERE alias = ? AND cust_id = ? AND active = 1");
            // Bind & execute the query statement:
            $stmt->bind_param("si", $data_array["alias"], $data_array["user_id"]);
            // execute the query statement:
            if (!$stmt->execute()) {
                $returnText .= "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                return $returnText;
            }

            $result = $stmt->get_result();
            if ($result->num_rows != 1) {
                $returnText .= "Failed while executing query [1]: Incorrect rows received: " . $result->num_rows;
                $stmt->close();
                $conn->close();
                return $returnText;
            }
            $row = mysqli_fetch_assoc($result);

            // User made changes to address fields
            if ($row["address"] != $data_array["address"] || $row["unit_no"] != $data_array["unit_no"] || $row["postal_code"] != $data_array["postal_code"]) {
                //Store data for future use
                $address_id = $row["address_id"];

                // 2. Check if [Orders] table has the address id for any of its rows
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT id FROM SMart.Order WHERE address_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $address_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $returnText .= "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $stmt->close();
                    $conn->close();
                    return $returnText;
                }

                $result = $stmt->get_result();
                // No rows returned, no entries for this address in [Orders], just update the address
                if ($result->num_rows == 0) {
                    // 3.1. Make updates to corresponding [Customer_Address] row
                    // Prepare the statement:
                    $stmt = $conn->prepare("UPDATE Customer_Address SET address = ?, unit_no = ?, postal_code = ? WHERE id = ?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("ssii", $data_array["address"], $data_array["unit_no"], $data_array["postal_code"], $address_id);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $returnText .= "Failed while executing query [3.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $stmt->close();
                        $conn->close();
                        return $returnText;
                    }

                    $returnText .= "Address updated!";
                }
                // Rows returned, there exist entries for this address in [Orders], set the address to be inactive and add a new address
                else {
                    // 3.2.1. Set old [Customer_Address] row active = 0
                    // Prepare the statement:
                    $stmt = $conn->prepare("UPDATE Customer_Address SET active = 0 WHERE alias = ? AND cust_id = ?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("si", $data_array["alias"], $data_array["user_id"]);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $returnText .= "Failed while executing query [3.2.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $stmt->close();
                        $conn->close();
                        return $returnText;
                    }

                    // 3.2.2. Create new [Customer_Address] row
                    // Prepare the statement:
                    $stmt = $conn->prepare("INSERT INTO Customer_Address (cust_id, alias, address, unit_no, postal_code, active) VALUES (?,?,?,?,?,true)");
                    // Bind & execute the query statement:
                    $stmt->bind_param("issss", $data_array["user_id"], $data_array["alias"], $data_array["address"], $data_array["unit_no"], $data_array["postal_code"]);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $returnText .= "Failed while executing query [3.2.2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $stmt->close();
                        $conn->close();
                        return $returnText;
                    }

                    $returnText .= "Previous address deactivated, new address created!";
                }
            }
            // User did not make changes to address fields
            else {
                $returnText .= "No changes made.";
            }

            break;

        case "payment":
            // 1. Check if changes made to fields for row in [Customer_Payment]
            // Prepare the statement:
            $stmt = $conn->prepare("SELECT id AS payment_id, owner, account_no, expiry FROM Customer_Payment WHERE payment_type = ? AND cust_id = ? AND active = 1");
            // Bind & execute the query statement:
            $stmt->bind_param("si", $data_array["payment_type"], $data_array["user_id"]);
            // execute the query statement:
            if (!$stmt->execute()) {
                $returnText = "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                return $returnText;
            }

            $result = $stmt->get_result();
            if ($result->num_rows != 1) {
                $returnText .= "Failed while executing query [1]: Incorrect rows received: " . $result->num_rows;
                $stmt->close();
                $conn->close();
                return $returnText;
            }
            $row = mysqli_fetch_assoc($result);

            // User made changes to payment fields
            if ($row["owner"] != $data_array["owner"] || $row["account_no"] != $data_array["account_no"] || $row["expiry"] != $data_array["expiry"]) {
                //Store data for future use
                $payment_id = $row["payment_id"];

                // 2. Check if [Orders] table has the payment id for any of its rows
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT id FROM SMart.Order WHERE payment_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $payment_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $returnText .= "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $stmt->close();
                    $conn->close();
                    return $returnText;
                }

                $result = $stmt->get_result();

                // No rows returned, no entries for this payment in [Orders], just update the payment
                if ($result->num_rows == 0) {
                    // 3.1. Make updates to corresponding [Customer_Payment] row
                    // Prepare the statement:
                    $stmt = $conn->prepare("UPDATE Customer_Payment SET owner = ?, account_no = ?, expiry = ? WHERE id = ?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("sisi", $data_array["owner"], $data_array["account_no"], $data_array["expiry"], $payment_id);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $returnText .= "Failed while executing query [3.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $stmt->close();
                        $conn->close();
                        return $returnText;
                    }

                    $returnText .= "Payment method updated!";
                }
                // Rows returned, there exist entries for this payment in [Orders], set the payment to be inactive and add a new payment
                else {
                    // 3.2.1. Set old [Customer_Payment] row active = 0
                    // Prepare the statement:
                    $stmt = $conn->prepare("UPDATE Customer_Payment SET active = 0 WHERE payment_type = ? AND cust_id = ?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("si", $data_array["payment_type"], $data_array["user_id"]);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $returnText .= "Failed while executing query [3.2.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $stmt->close();
                        $conn->close();
                        return $returnText;
                    }

                    // 3.2.2. Create new [Customer_Payment] row
                    // Prepare the statement:
                    $stmt = $conn->prepare("INSERT INTO Customer_Payment (cust_id, payment_type, owner, account_no, expiry, active) VALUES (?,?,?,?,?,true)");
                    // Bind & execute the query statement:
                    $stmt->bind_param("issis", $data_array["user_id"], $data_array["payment_type"], $data_array["owner"], $data_array["account_no"], $data_array["expiry"]);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $returnText .= "Failed while executing query [3.2.2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $stmt->close();
                        $conn->close();
                        return $returnText;
                    }

                    $returnText .= "Previous payment method deactivated, new payment method created!";
                }
            }
            // User did not make changes to payment fields
            else {
                $returnText .= "No changes made.";
            }

            break;

        default:
            break;
    }
    $stmt->close();
    $conn->close();

    return $returnText;
}

function checkout($cust_id, $address_alias, $payment_type) {
    $returnText = "";
    $delivery_charge = 5;
    $staff_id = 1;
    $status_id = 1;

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $returnText .= "Connection failed: " . $conn->connect_error;
        return $returnText;
    }

    // 1. Create row in [Order]
    // Prepare the statement:
    $stmt = $conn->prepare("INSERT INTO SMart.Order(cust_id, address_id, payment_id, created_at, subtotal, service_charge, delivery_charge) 
                            VALUES 
                            (?, 
                            (SELECT id FROM Customer_Address WHERE alias = ? AND cust_id = ? AND active = 1), 
                            (SELECT id FROM Customer_Payment WHERE payment_type = ? AND cust_id = ? AND active = 1), 
                            NOW(), 
                            (SELECT SUM(c.quantity * p.price) AS subtotal FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?), 
                            ?,
                            (SELECT ROUND(0.05*SUM(c.quantity * p.price), 2) AS subtotal FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?)
                            )");

    // Bind & execute the query statement:
    $stmt->bind_param("isisiiii", $cust_id, $address_alias, $cust_id, $payment_type, $cust_id, $cust_id, $delivery_charge, $cust_id);
    // execute the query statement:
    if (!$stmt->execute()) {
        $returnText .= "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    $order_id = $conn->insert_id;

    // 2. Create row in [Order_Status]
    // Prepare the statement:
    $stmt = $conn->prepare("INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, ?, NOW(), ?)");
    // Bind & execute the query statement:
    $stmt->bind_param("iii", $order_id, $status_id, $staff_id);   // status_id = 1, created_by staff_id 1
    // execute the query statement:
    if (!$stmt->execute()) {
        $returnText .= "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    // 3. Get data needed for insertion into [Order_Items] from [Cart] and [Product]
    // Prepare the statement:
    $stmt = $conn->prepare("SELECT p.id AS product_id, c.quantity, p.price FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?");
    // Bind & execute the query statement:
    $stmt->bind_param("i", $cust_id);
    // execute the query statement:
    if (!$stmt->execute()) {
        $returnText .= "Failed while executing query [3]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    $result = $stmt->get_result();
    // Store data into array
    if ($result->num_rows > 0) {
        $cart_items = array();

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($cart_items, $row);
        }
    }

    foreach ($cart_items as $cart_row) {
        // 4. Insert data into [Order_Items]
        // Prepare the statement:
        $stmt = $conn->prepare("INSERT INTO Order_Items(order_id, prod_id, quantity, price) VALUES(?, ?, ?, ?)");

        $stmt->bind_param("iiid", $order_id, $cart_row["product_id"], $cart_row["quantity"], $cart_row["price"]);
        // execute the query statement:
        if (!$stmt->execute()) {
            $returnText .= "Failed while executing query [4]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            return $returnText;
        }
    }

    // 5. Remove corresponding [Cart] rows
    // Prepare the statement:
    $stmt = $conn->prepare("DELETE FROM Cart WHERE cust_id = ?");
    // Bind & execute the query statement:
    $stmt->bind_param("i", $cust_id);
    // execute the query statement:
    if (!$stmt->execute()) {
        $returnText .= "Failed while executing query [5]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    $returnText .= "Purchase Successful!";

    $stmt->close();
    $conn->close();

    return array($returnText, $order_id);
}

function print_eReceipt($order_id) {
    $returnText = "";
    $service_charge_percent = 5;

    $cust_id = sanitize_input($_SESSION["id"]);
    $order_id = sanitize_input($order_id);

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $returnText .= "Connection failed: " . $conn->connect_error;
        return $returnText;
    }

    // 1. Get all rows in [Order_Items] based on order_id and cust_id
    // Prepare the statement:
    $stmt = $conn->prepare("SELECT oi.id, oi.prod_id, p.name, p.image_url, oi.quantity, oi.price FROM Order_Items AS oi INNER JOIN Product AS p ON oi.prod_id = p.id INNER JOIN SMart.Order as o ON o.id = oi.order_id WHERE oi.order_id = ? AND o.cust_id = ?");

    // Bind & execute the query statement:
    $stmt->bind_param("ii", $order_id, $cust_id);
    // execute the query statement:
    if (!$stmt->execute()) {
        $returnText .= "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    // For whatever reason, no rows
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $returnText .= "Failed while executing query [1]: Incorrect rows received: " . $result->num_rows;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    echo '<table class="carttable" style="font-size: 1.4rem;">
            <tr style="text-align: center; background: #6D6875; color: white;">
                <th colspan="2">Product</th>
                <th>Price</th>
                <th>Total</th>
                <th>Quantity</th>
            </tr>';

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
    // Prepare the statement:
    $stmt = $conn->prepare("SELECT created_at, subtotal, service_charge, delivery_charge FROM SMart.Order WHERE id = ?");

    // Bind & execute the query statement:
    $stmt->bind_param("i", $order_id);
    // execute the query statement:
    if (!$stmt->execute()) {
        $returnText .= "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    // For whatever reason, no rows
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        $returnText .= "Failed while executing query [2]: Incorrect rows received: " . $result->num_rows;
        $stmt->close();
        $conn->close();
        return $returnText;
    }

    $row = mysqli_fetch_assoc($result);

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

    $stmt->close();
    $conn->close();

    $returnText = "Print complete.";

    return $returnText;
}

////////////////////////////////////////////

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

        if (is_array($temp)) {
            $checkoutMsg .= $temp[0];
            $order_id = $temp[1];
        } else {
            $checkoutMsg .= $temp;
        }
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