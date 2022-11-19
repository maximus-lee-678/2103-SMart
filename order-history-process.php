<?php

session_start();

include "helper-functions.php";

$page = sanitize_input($_POST["page"]);
$type = sanitize_input($_POST["type"]);
$cust_id = sanitize_input($_SESSION["id"]);

$status_strings = array(
    'pack' => '(os.status_id = 1 OR os.status_id = 2 OR os.status_id = 3)',
    'deliver' => '(os.status_id = 4 OR os.status_id = 5)',
    'complete' => '(os.status_id = 6)'
);

$service_charge_percent = 0.05;
$limit = 10;
$offset = ($page - 1) * $limit;

$conn = make_connection();

// 1. Get page count of all, check if user has an order history
$query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM SMart.Order WHERE cust_id = ?';
$result = payload_deliver($conn, $query, "is", $params = array($limit, $cust_id));

$row = mysqli_fetch_assoc($result);

// No pages, display no orders page, exit
if ($row["total_pages"] == 0) {
    echo '<div class = "resultContainer2">
                <div class ="content">
                    <h2>No purchased history!</h2>
                    <h4>Press <a href="shop.php"> ME </a>to start shopping now!</h4>
                </div>
            </div>';
    $conn->close();
    exit();
}

// 2. Get page count of respective category
$query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM SMart.Order AS o
        LEFT JOIN Order_Status AS os ON o.id=os.order_id
        INNER JOIN (SELECT order_id, MAX(status_id) AS status_id FROM Order_Status GROUP BY order_id) AS os2 ON os.order_id = os2.order_id AND os.status_id = os2.status_id
        AND ' . $status_strings[$type] . '
        LEFT JOIN Status AS s ON os.status_id=s.id
        WHERE cust_id = ?';
$result = payload_deliver($conn, $query, "is", $params = array($limit, $cust_id));

$row = mysqli_fetch_assoc($result);

$total_pages = $row["total_pages"];

print_page($page, $total_pages);

// 3. Get all orders for respective category
$query = 'SELECT o.id, o.created_at, s.name AS status, o.subtotal, o.service_charge, o.delivery_charge FROM SMart.Order AS o
        LEFT JOIN Order_Status AS os ON o.id=os.order_id
        INNER JOIN (SELECT order_id, MAX(status_id) AS status_id FROM Order_Status GROUP BY order_id) AS os2 ON os.order_id = os2.order_id AND os.status_id = os2.status_id
        AND ' . $status_strings[$type] . '
        LEFT JOIN Status AS s ON os.status_id=s.id
        WHERE cust_id = ?
        ORDER BY created_at DESC
        LIMIT ? OFFSET ?';
$result = payload_deliver($conn, $query, "iii", $params = array($cust_id, $limit, $offset));

// Store data into array
$cust_orders = array();

// No pages, exit
if ($result->num_rows == 0) {
    echo '<div class = "resultContainer2">
                <div class ="content">
                    <h2>No ' . $type . ' history!</h2>
                </div>
            </div>';
    $conn->close();
    exit();
}

while ($row = mysqli_fetch_assoc($result)) {
    array_push($cust_orders, $row);
}

foreach ($cust_orders as $cust_orders_row) {
    //Open Accordion
    echo '<div class="accordion-item">
                    <label class="accordion-header">';

    // Print Accordion Header
    echo '<table class="historyIDTitle">
                    <tr style="text-align: right; background: white;">
                        <td>Order ID: ' . $cust_orders_row["id"] . '</td>
                        <td>Order Status: ' . $cust_orders_row["status"] . '</td>
                        <td>Purchased Date/Time: ' . $cust_orders_row["created_at"] . '</td>
                        ' . ($type == 'complete'?'<td><span><a href="productReview.php">Review</a></span></td>':'') . '
                    </tr>
                </table>
                <span></span>';

    echo '</label>
                <div class="accordion-body">';

    // 4. Get order items, total order cost and quantity associated with this order ID
    $query = 'SELECT oi.prod_id, p.name, p.image_url, oi.quantity, p.price FROM Order_Items as oi 
                    INNER JOIN Product as p ON oi.prod_id = p.id WHERE oi.order_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($cust_orders_row["id"]));

    $total_quantity = 0;

    // Print Table Header
    echo '<table class="historyTable">
                    <tr style="background: #6D6875; color: white;">
                        <th colspan="3">Product</th>
                        <th>Quantity</th>
                        <th>Price (in $)</th>
                        <th>Total (in $)</th>
                    </tr>';

    // Print Table Rows
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr id="cartid_' . $row["prod_id"] . '">
                        <td><img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" width="64" height="64"></td>
                        <td><a href="productPage.php?id=' . $row["prod_id"] . '">Product Page</a></td>
                        <td>' . $row["name"] . '</td>
                        <td>' . $row["quantity"] . '</td>
                        <td>$' . $row["price"] . '</td>
                        <td>$' . number_format($row["price"] * $row["quantity"], 2, '.', '') . '</td>
                    </tr>';

        $total_quantity += $row["quantity"];
    }

    // Print Table Summary
    echo '<tr>
            <td colspan="3" style="text-align: right;">Total: </td>
            <td>' . $total_quantity . '</td>
            <td colspan="2">$' . number_format($cust_orders_row["subtotal"], 2, '.', '') . '</td>                    
       </tr>
   </table>';

    // Print Order Summary
    echo '<table class="historyPayTable" style="font-size: 1.4rem; margin-top: 40px;">
               <tr style="text-align: center; background: white;">
                   <td colspan="2">Delivery Fee (' . $service_charge_percent * 100 . '%): </td>
                   <td colspan="2">$' . number_format($cust_orders_row["delivery_charge"], 2, '.', '') . '</td>
               </tr>
               <tr style="text-align: center; background: white;">
                   <td colspan="2">Service Fee: </td>
                   <td colspan="2">$' . number_format($cust_orders_row["service_charge"], 2, '.', '') . '</td>
               </tr>
               <tr style="text-align: center; background: white;">
                   <td colspan="2">Final Cost: </td>
                   <td colspan="2">$' . number_format($cust_orders_row["subtotal"] + $cust_orders_row["delivery_charge"] + $cust_orders_row["service_charge"], 2, '.', '') . '</td>
               </tr>
           </table>';

    // Close Off Accordion
    echo '</div>
        </div>';
}

$conn->close();
?>