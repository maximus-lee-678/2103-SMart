<?php

session_start();

include "helper-functions.php";

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    header("refresh: 0; url=shop.php");
    exit();
}

if (!(isset($_SESSION["id"]) && isset($_POST["operation"]))) {
    exit();
}

switch ($_POST["operation"]) {
    case "add-new":
        if (!isset($_POST['prod_id'])) {
            echo "Missing fields!";
            exit;
        }
        break;
    case "remove-item":
        if (!isset($_POST['prod_id'])) {
            echo "Missing fields!";
            exit;
        }
        break;
    case "decrement-item":
        if (!isset($_POST['prod_id'])) {
            echo "Missing fields!";
            exit;
        }
    case "increment-item":
        if (!isset($_POST['prod_id'])) {
            echo "Missing fields!";
            exit;
        }
    case "empty-cart":
        break;
    case "modify-item-count":
        if (!isset($_POST['prod_id']) || !isset($_POST['quantity'])) {
            echo "Missing fields!";
            exit;
        }
        break;
    default:
        break;
}

$conn = make_connection();

switch ($_POST["operation"]) {
    case "add-new":

        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);
        $prod_id = sanitize_input($_POST['prod_id']);

        // 1. Check if row exists in db
        $query = 'SELECT p.name, c.quantity FROM Cart as c
                LEFT JOIN Product AS p ON c.prod_id=p.id
                WHERE cust_id = ? AND prod_id = ?';
        $result = payload_deliver($conn, $query, "ii", $params = array($cust_id, $prod_id));

        if ($result->num_rows == 0) {
            // 2.1. No rows, add new
            $query = 'INSERT INTO Cart (cust_id, prod_id, quantity) VALUES (?,?,1)';
            $result = payload_deliver($conn, $query, "ii", $params = array($cust_id, $prod_id));

            echo '<caption>Added to cart!</caption>';
        } else {
            // Row found, add 1
            $row = mysqli_fetch_assoc($result);
            $quantity = $row["quantity"] + 1;
            $name = $row["name"];

            // 2.2. Add 1 to quantity of product in cart
            $query = 'UPDATE Cart SET quantity = ? WHERE cust_id = ? AND prod_id = ?';
            $result = payload_deliver($conn, $query, "iii", $params = array($quantity, $cust_id, $prod_id));

            echo '<caption>Added another ' . $name . ' to the cart!</caption>';
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "remove-item":

        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);
        $prod_id = sanitize_input($_POST['prod_id']);

        // 1. Quantity + 1
        $query = 'DELETE FROM Cart WHERE cust_id = ? AND prod_id = ?';
        payload_deliver($conn, $query, "ii", $params = array($cust_id, $prod_id));

        echo "<caption>Product successfully removed from cart.</caption>";

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "decrement-item":

        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);
        $prod_id = sanitize_input($_POST['prod_id']);

        // 1. Check if quantity is 1
        $query = 'SELECT p.name, c.quantity FROM Cart as c
                LEFT JOIN Product AS p ON c.prod_id=p.id
                WHERE cust_id = ? AND prod_id = ?';
        $result = payload_deliver($conn, $query, "ii", $params = array($cust_id, $prod_id));

        $row = mysqli_fetch_assoc($result);
        $quantity = $row["quantity"];
        $name = $row["name"];

        if ($quantity != 1) {
            // 2.1. Quantity > 1, subtract 1
            $query = 'UPDATE Cart SET quantity = quantity - 1 WHERE cust_id = ? AND prod_id = ?';
            $result = payload_deliver($conn, $query, "ii", $params = array($cust_id, $prod_id));

            echo '<caption>Removed 1 ' . $name . ' from the cart!</caption>';
        } else {
            // 2.2. Quantity == 1, remove from cart
            $query = 'DELETE FROM Cart WHERE cust_id = ? AND prod_id = ?';
            $result = payload_deliver($conn, $query, "ii", $params = array($cust_id, $prod_id));

            echo '<caption>Removed ' . $name . ' from the cart!</caption>';
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "increment-item":

        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);
        $prod_id = sanitize_input($_POST['prod_id']);

        // 1. Quantity + 1
        $query = 'UPDATE Cart SET quantity = quantity + 1 WHERE cust_id = ? AND prod_id = ?';
        payload_deliver($conn, $query, "ii", $params = array($cust_id, $prod_id));

        echo '<caption>Quantity successfully incremented by 1.</caption>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "modify-item-count":

        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);
        $prod_id = sanitize_input($_POST['prod_id']);
        $quantity = sanitize_input($_POST['quantity']);

        // 1. Change item quantity directly
        $query = 'UPDATE Cart SET quantity = ? WHERE cust_id = ? AND prod_id = ?';
        payload_deliver($conn, $query, "iii", $params = array($quantity, $cust_id, $prod_id));

        echo '<caption>Quantity successfully updated!</caption>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "empty-cart":

        //form parameters
        $cust_id = sanitize_input($_SESSION["id"]);

        // 1. Empty cart by customer ID
        $query = 'DELETE FROM Cart WHERE cust_id = ?';
        payload_deliver($conn, $query, "i", $params = array($cust_id));

        echo '<caption>Cart successfully cleared!</caption>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////

    default:
        break;
}

$conn->close();

echo "<caption>" . $captionText . "</caption>";
