<?php

session_start();

include "helper-functions.php";

$cust_id = sanitize_input($_SESSION["id"]);
$page = sanitize_input($_POST["page"]);
$limit = 10;
$offset = ($page - 1) * $limit;

$conn = make_connection();

// 1. Get page count of all orders
$query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM SMart.Order WHERE cust_id = ?';
$result = payload_deliver($conn, $query, "ii", $params = array($limit, $cust_id));

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

$total_pages = $row["total_pages"];

print_page($page, $total_pages);

// 2. Get all order IDs
$query = 'SELECT id, created_at FROM SMart.Order WHERE cust_id = ? LIMIT ? OFFSET ?';
$result = payload_deliver($conn, $query, "iii", $params = array($cust_id, $limit, $offset));

// Store data into array
$cust_orders = array();

while ($row = mysqli_fetch_assoc($result)) {
    array_push($cust_orders, $row);
}

$current_date = date('Y-m-d', time());

foreach ($cust_orders as $cust_orders_row) {
    // Print Accordion Header
    echo '<button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Order No: #' . $cust_orders_row["id"] . ' (' . $cust_orders_row["created_at"] . ')</label></button>';

    echo '<div class="panel" style="font-size: 1.4rem;">';

    // 3. Get order items, total order cost and quantity associated with this order ID
    $query = 'SELECT oi.prod_id, p.name, p.image_url, oi.quantity, p.price, p.expiry_duration FROM Order_Items as oi 
                    INNER JOIN Product as p ON oi.prod_id = p.id WHERE oi.order_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($cust_orders_row["id"]));

    // Print Table Header
    echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 20px;">
                        <tr style="text-align: center; background: #6D6875; color: white;">
                            <th colspan="2">Product</th>
                            <th>Quantity</th>
                            <th colspan="3">Expiry Date</th>
                        </tr>';

    // Print Table Rows
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["expiry_duration"] != null) {
            $expiry_date = date('Y-m-d', strtotime(substr($cust_orders_row["created_at"], 0, 10) . ' + ' . $row["expiry_duration"] . ' days'));
            $expires_in = (strtotime($expiry_date) - strtotime($current_date)) / (24 * 60 * 60);

            echo '<tr>
                <td><img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" class="imagesize"></td>
                <td>' . $row["name"] . '</td>
                <td>' . $row["quantity"] . '</td>
                <td>' . $expiry_date . '<br> Expires after: ' . $row["expiry_duration"] . ' days from order</td>
                <td style=' . ($expires_in > 0 ? '"color: green;">Expiring in ' . $expires_in . ' days' : '"color: red;">Expired ' . -$expires_in . ' days ago!') . '</td>
                <td><button class="btn">Acknowledge</button></td>
            </tr>';
        } else {
            echo '<tr>
                <td><img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" class="imagesize"></td>
                <td>' . $row["name"] . '</td>
                <td>' . $row["quantity"] . '</td>
                <td colspan="3">No Expiry</td>
            </tr>';
        }
    }

    echo '</table>
        </div>';
}

$conn->close();
?>

