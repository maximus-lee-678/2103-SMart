<?php

session_start();

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    header("refresh: 0; url=shop.php");
    exit();
}

if (!(isset($_SESSION["id"]))) {
    exit();
}

include "helper-functions.php";

$cust_id = sanitize_input($_SESSION["id"]);
$type = sanitize_input($_POST["type"]);

$expiry_threshold = 14;

if (isset($_POST["page"])) {
    $page = sanitize_input($_POST["page"]);
    $limit = 10;
    $offset = ($page - 1) * $limit;
}

if (isset($_POST["id"])) {
    $order_item_id = sanitize_input($_POST["id"]);
}

$conn = make_connection();

switch ($type) {
    case "all":
        // 1. Get page count of all orders
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM SMart.Order WHERE cust_id = ?';
        $result = payload_deliver($conn, $query, "ii", $params = array($limit, $cust_id));

        $row = mysqli_fetch_assoc($result);

        // No pages, display no orders page, exit
        if ($row["total_pages"] == 0) {
            echo '<div class = "resultContainer2">
                <div class ="content">
                    <h2>No purchased history!</h2>
                    <h4>Press <a href="shop.php">ME</a> to start shopping now!</h4>
                </div>
            </div>';
            $conn->close();
            exit();
        }

        $total_pages = $row["total_pages"];

        print_page($page, $total_pages);

        // 2. Get all order IDs
        $query = 'SELECT oi.order_id, o.created_at,  
                SUM(IF(DATE_ADD(CAST(o.created_at AS DATE), INTERVAL p.expiry_duration DAY) < (DATE_ADD(CAST(NOW() AS DATE), INTERVAL ? DAY)) AND oi.expiry_ack = 0, 1, 0)) AS expiring_count
                FROM Order_Items AS oi
                INNER JOIN Product AS p ON oi.prod_id = p.id 
                INNER JOIN SMart.Order AS o ON oi.order_id = o.id
                WHERE o.cust_id = ?
                GROUP BY oi.order_id LIMIT ? OFFSET ?';
        $result = payload_deliver($conn, $query, "iiii", $params = array($expiry_threshold, $cust_id, $limit, $offset));

        // Store data into array
        $cust_orders = array();

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($cust_orders, $row);
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "expiring":
        // 1. Get page count of all expiring and unacknowledged orders
        $query = 'SELECT COUNT(*) FROM Order_Items AS oi
                INNER JOIN Product AS p ON oi.prod_id = p.id 
                INNER JOIN SMart.Order AS o ON oi.order_id = o.id
                WHERE (DATE_ADD(CAST(o.created_at AS DATE), INTERVAL p.expiry_duration DAY)) < (DATE_ADD(CAST(NOW() AS DATE), INTERVAL ? DAY)) AND oi.expiry_ack = 0 AND o.cust_id = ?
                GROUP BY oi.order_id';
        $result = payload_deliver($conn, $query, "ii", $params = array($expiry_threshold, $cust_id));

        $rows = mysqli_num_rows($result);

        // No pages, display no orders page, exit
        if ($rows == 0) {
            echo '<div class = "resultContainer2">
                <div class="content">
                    <h2>No expiring items!</h2>
                </div>
            </div>';
            $conn->close();
            exit();
        }

        print_page($page, ceil($rows / $limit));

        // 2. Get all order IDs with expiring items
        $query = 'SELECT oi.order_id, COUNT(oi.order_id) AS expiring_count, o.created_at FROM Order_Items AS oi
                INNER JOIN Product AS p ON oi.prod_id = p.id 
                INNER JOIN SMart.Order AS o ON oi.order_id = o.id
                WHERE (DATE_ADD(CAST(o.created_at AS DATE), INTERVAL p.expiry_duration DAY)) < (DATE_ADD(CAST(NOW() AS DATE), INTERVAL ? DAY)) AND oi.expiry_ack = 0 AND o.cust_id = ?
                GROUP BY oi.order_id LIMIT ? OFFSET ?';
        $result = payload_deliver($conn, $query, "iiii", $params = array($expiry_threshold, $cust_id, $limit, $offset));

        // Store data into array
        $cust_orders = array();

        while ($row = mysqli_fetch_assoc($result)) {
            array_push($cust_orders, $row);
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "update":
        // 1. Update acknowledgement
        $query = 'UPDATE Order_Items SET expiry_ack = 1 WHERE id = ?';
        payload_deliver($conn, $query, "i", $params = array($order_item_id));

        $conn->close();
        exit();
////////////////////////////////////////////////////////////////////////////////////////////////////    
    case "expire_summary":
        // 1. Update acknowledgement
        $query = 'SELECT COUNT(*) AS expiring_count FROM Order_Items AS oi
                INNER JOIN Product AS p ON oi.prod_id = p.id 
                INNER JOIN SMart.Order AS o ON oi.order_id = o.id
                WHERE (DATE_ADD(CAST(o.created_at AS DATE), INTERVAL p.expiry_duration DAY)) < (DATE_ADD(CAST(NOW() AS DATE), INTERVAL ? DAY)) AND oi.expiry_ack = 0 AND o.cust_id = ?';
        $result = payload_deliver($conn, $query, "ii", $params = array($expiry_threshold, $cust_id));

        $row = mysqli_fetch_assoc($result);

        echo 'You have ' . $row["expiring_count"] . ' items expiring in ' . $expiry_threshold . ' Days!';

        $conn->close();
        exit();
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "expire_number":
        // 1. Update acknowledgement
        $query = 'SELECT COUNT(*) AS expiring_count FROM Order_Items AS oi
                INNER JOIN Product AS p ON oi.prod_id = p.id 
                INNER JOIN SMart.Order AS o ON oi.order_id = o.id
                WHERE (DATE_ADD(CAST(o.created_at AS DATE), INTERVAL p.expiry_duration DAY)) < (DATE_ADD(CAST(NOW() AS DATE), INTERVAL ? DAY)) AND oi.expiry_ack = 0 AND o.cust_id = ?';
        $result = payload_deliver($conn, $query, "ii", $params = array($expiry_threshold, $cust_id));

        $row = mysqli_fetch_assoc($result);

        echo $row["expiring_count"];

        $conn->close();
        exit();
////////////////////////////////////////////////////////////////////////////////////////////////////
    default:
        break;
}

$current_date = date('Y-m-d', time());

foreach ($cust_orders as $cust_orders_row) {
    // Print Accordion Header
    echo '<button class="accordion" style="font-size: 1.4rem; margin-top: 20px;"><label style="float: left;">Order No: #' . $cust_orders_row["order_id"] .
    ' (' . $cust_orders_row["created_at"] . ')</label>' . ($cust_orders_row["expiring_count"] != 0 ? '<label style="font-weight: bold;">&nbsp;' . $cust_orders_row["expiring_count"] . ' Product(s) Expiring!</label>' : '') . '</button>';

    echo '<div class="panel" style="font-size: 1.4rem;">';

    // 3. Get order items, total order cost and quantity associated with this order ID
    $query = 'SELECT oi.id, oi.prod_id, p.name, p.image_url, oi.quantity, p.price, p.expiry_duration, 
                    DATE_ADD(CAST(o.created_at AS DATE), INTERVAL p.expiry_duration DAY) AS expiry_date, oi.expiry_ack FROM Order_Items as oi 
                    INNER JOIN Product AS p ON oi.prod_id = p.id 
                    INNER JOIN SMart.Order AS o ON oi.order_id = o.id
                    WHERE oi.order_id = ?';
    $result = payload_deliver($conn, $query, "i", $params = array($cust_orders_row["order_id"]));

    // Print Table Header
    echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 20px;">
                        <tr style="text-align: center; background: #6D6875; color: white;">
                            <th colspan="2">Product</th>
                            <th>Quantity</th>
                            <th colspan="3">Expiry Date</th>
                        </tr>';

    // Print Table Rows
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row["expiry_ack"] > -1) {
            $expires_in = (strtotime($row["expiry_date"]) - strtotime($current_date)) / (24 * 60 * 60);

            echo '<tr>
                <td><img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" class="imagesize"></td>
                <td>' . $row["name"] . '</td>
                <td>' . $row["quantity"] . '</td>
                <td>' . $row["expiry_date"] . '<br> Expires ' . $row["expiry_duration"] . ' days after order.</td>
                <td style=' . ($expires_in > $expiry_threshold ? '"color: green;">Expiring in ' . $expires_in . ' days' :
                    ($expires_in >= 0 ? '"color: orange;">Expiring in ' . $expires_in . ' days' :
                            '"color: red;">Expired ' . -$expires_in . ' days ago!')) . '</td>
                <td>' . ($row["expiry_ack"] == 0 ? '<button class="btn acknowledge" order_item_id="' . $row["id"] . '">Acknowledge</button>' : 'Acknowledged') . '</td>
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

