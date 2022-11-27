<!DOCTYPE html>
<?php
session_start();

include "helper-functions.php";
include "helper-profile.php";

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

function checkout($cust_id, $address_id, $payment_id) {
    global $delivery_charge_actual;
    global $service_charge_multiplier;
    $staff_id = 1;
    $status_id = 1;

    $conn = make_connection();
    
    // 1. Check if sufficient product stock (cart > product quantity)
    $query = 'SELECT c.prod_id, c.quantity, p.quantity AS p_quantity FROM Cart AS c
            LEFT JOIN Product AS p ON p.id=c.prod_id
            WHERE (c.quantity > p.quantity) AND cust_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($cust_id));
    
    if(mysqli_num_rows($result) != 0){
        $conn->close();
        return array("Some products are out of stock. Please try again later.", -1);
    }

    // 2. Create row in [Order]
    $query = 'INSERT INTO SMart.Order(cust_id, address_id, payment_id, created_at, subtotal, service_charge, delivery_charge) 
            VALUES 
            (?, 
            ?, 
            ?, 
            NOW(), 
            (SELECT SUM(c.quantity * p.price) AS subtotal FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?),
            (SELECT ROUND(?*SUM(c.quantity * p.price), 2) AS subtotal FROM Cart as c INNER JOIN Product as p ON c.prod_id = p.id WHERE c.cust_id = ?),
            ?
            )';
    payload_deliver($conn, $query, "iiiidii", $params = array($cust_id, $address_id, $payment_id, $cust_id, $service_charge_multiplier, $cust_id, $delivery_charge_actual));

    // Store ID of newly created [Order] entry
    $order_id = $conn->insert_id;

    // 3. Create row in [Order_Status]
    $query = 'INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, ?, NOW(), ?)';
    payload_deliver($conn, $query, "iii", $params = array($order_id, $status_id, $staff_id));

    // 4. Get data needed for insertion into [Order_Items] from [Cart] and [Product]
    $query = 'SELECT c.prod_id AS product_id, c.quantity, p.price, IF(ISNULL(p.expiry_duration), -1, 0) AS expiry_ack_initial FROM Cart as c 
            INNER JOIN Product as p ON c.prod_id = p.id
            WHERE c.cust_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($cust_id));

    // Store data into array
    $cart_items = array();

    while ($row = mysqli_fetch_assoc($result)) {
        array_push($cart_items, $row);
    }

    foreach ($cart_items as $cart_row) {
        // 5. Insert data into [Order_Items]
        $query = 'INSERT INTO Order_Items(order_id, prod_id, quantity, price, expiry_ack) VALUES(?, ?, ?, ?, ?)';
        payload_deliver($conn, $query, "iiidi", $params = array($order_id, $cart_row["product_id"], $cart_row["quantity"], $cart_row["price"], $cart_row["expiry_ack_initial"]));
        
        // 6. Update [Product] quantity
        $query = 'UPDATE Product SET quantity = quantity - ? WHERE id = ?';
        payload_deliver($conn, $query, "ii", $params = array($cart_row["quantity"], $cart_row["product_id"]));
    }

    // 7. Remove corresponding [Cart] rows
    $query = 'DELETE FROM Cart WHERE cust_id = ?';
    payload_deliver($conn, $query, "i", $params = array($cust_id));

    $conn->close();
    return array("Purchase Successful!", $order_id);
}

function print_eReceipt($order_id) {
    global $service_charge_multiplier;

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
               <td colspan="2">Delivery Fee (' . $service_charge_multiplier * 100 . '%): </td>
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
    if (isset($_SESSION["id"], $_POST["address_id"], $_POST["address_alias"], $_POST["address_address"], $_POST["address_unit_number"], $_POST["address_postal_code"],
                    $_POST["payment_id"], $_POST["payment_type"], $_POST["payment_owner"], $_POST["payment_account_no"], $_POST["payment_expiry_date"], $_POST["payment_cvv"])) {
        $address_array = array(
//            "user_id" => sanitize_input($_SESSION["id"]),
            "id" => sanitize_input($_POST["address_id"]),
            "alias" => sanitize_input($_POST["address_alias"]),
            "address" => sanitize_input($_POST["address_address"]),
            "unitno" => sanitize_input($_POST["address_unit_number"]),
            "postal" => sanitize_input($_POST["address_postal_code"])
        );
        $payment_array = array(
//            "user_id" => sanitize_input($_SESSION["id"]),
            "id" => sanitize_input($_POST["payment_id"]),
            "paytype" => sanitize_input($_POST["payment_type"]),
            "owner" => sanitize_input($_POST["payment_owner"]),
            "accno" => sanitize_input($_POST["payment_account_no"]),
            "expiry" => sanitize_input($_POST["payment_expiry_date"])
        );

        $updateMsg = $checkoutMsg = "";
        $updateMsgBool = $checkoutMsgBool = true;

        $result = addressOperation("update", $address_array);
        if (!$result['success']) {
            $resultText = $result['data'];
            if (!str_contains($resultText, "No changes made")) {
                $updateMsg .= $resultText;
                $updateMsgBool = false;
            }
        } else {
            $updateMsg .= "Address updated!";
        }

        $result = paymentOperation("update", $payment_array);
        if (!$result['success']) {
            $resultText = $result['data'];
            if (!str_contains($resultText, "No changes made")) {
                $updateMsg .= "\n" . $resultText;
                $updateMsgBool = false;
            }
        } else {
            $updateMsg .= "Payment method updated!";
        }
    } else {
        $updateMsg .= "Something went wrong when updating address/payment. Please Try Again.";
        $updateMsgBool = false;
    }


    if ($updateMsgBool) {
        if (isset($_SESSION["id"], $_POST["address_alias"], $_POST["payment_type"]) && !is_cart_empty()) {
            echo "3";
            $temp = checkout(sanitize_input($_SESSION["id"]), sanitize_input($_POST["address_id"]), sanitize_input($_POST["payment_id"]));
            $checkoutMsg .= $temp[0];
            if($checkoutMsg != "Purchase Successful!"){
                $checkoutMsgBool = false;
            }
            else{
               $order_id = $temp[1];
            }
        } else {
            $checkoutMsg .= "Something went wrong when checking out. Please Try Again.";
            $checkoutMsgBool = false;
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