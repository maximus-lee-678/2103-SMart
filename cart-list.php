<?php

session_start();
//
//if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
//    header("refresh: 0; url=shop.php");
//    exit;
//}
?>

<?php

//Functions
// Function to sanitize inputs
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<?php

if (isset($_SESSION["id"]) && isset($_POST["operation"])) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $captionText = "Connection failed: " . $conn->connect_error;
    } else {
        switch ($_POST["operation"]) {
            case "toolbar":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);

                // 1. Get number of rows and total associated with this user
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT SUM(c.quantity*p.price) as sum, COUNT(*) as count FROM SMart.Cart as c INNER JOIN SMart.Product as p ON c.prod_id = p.id WHERE c.cust_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $cust_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                    $row = mysqli_fetch_assoc($result);

                    $number_of_rows = $row["count"];
                    $total_cost = $row["sum"];
                }

                // 2. Get the actual content
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT c.prod_id, c.quantity, p.name, p.image_url, p.price FROM SMart.Cart as c INNER JOIN SMart.Product as p ON c.prod_id = p.id WHERE c.cust_id = ? ORDER BY c.id DESC LIMIT 3");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $cust_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="box" id="cartid_' . $row["prod_id"] . '">
                    <i class="fas fa-times remove-from-cart cart-minor"></i>
                    <img src="' . $row["image_url"] . '" alt="">
                    <div class="content">
                        <h3>' . $row["name"] . '</h3>
                        <a href="#"><img src="image/minus.png" alt="Decrement Item" class="iconsize minus-button cart-minor"></a>
                        <span class="quantity" > ' . $row["quantity"] . ' </span>
                        <a href="#"><img src="image/plus.png" alt="Increment Item" class="iconsize plus-button cart-minor"></a>
                        <span class="multiply">*</span>
                        <span class="price">$' . $row["price"] . '</span>
                    </div>
                </div>';
                        }

                        if ($number_of_rows > 3) {
                            echo '<h3>and ' . ($number_of_rows - 3) . ' more product(s).</h3>';
                        }

                        echo '<h3 class="total"> total : <span>$' . number_format($total_cost, 2, '.', '') . '</span> </h3>
                <a href="MyShoppingCart.php" class="btn">View My Cart</a>';
                    } else {
                        $captionText = "Cart is empty!";
                    }
                }
                break;

            case "cart-page":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);

                // Prepare the statement:
                $stmt = $conn->prepare("SELECT c.prod_id, p.name, p.image_url, c.quantity, p.price FROM SMart.Cart as c INNER JOIN SMart.Product as p ON c.prod_id = p.id WHERE c.cust_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("s", $cust_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $total_cost = 0.00;
                    $total_quantity = 0;

                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo '<table class="carttable">
                        <tr style="background: #6D6875; color: white;">
                            <th colspan="2">Product</th>
                            <th>Quantity</th>
                            <th>Price (in $)</th>
                            <th>Total (in $)</th>
                            <th>Remove</th>
                        </tr>';

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr id="cartid_' . $row["prod_id"] . '">
                            <td><img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" class="imagesize"></td>
                            <td>' . $row["name"] . '</td>
                            <td>
                                <a href="#"><img src="image/minus.png" alt="Decrement Item" class="iconsize minus-button cart-major"></a>
                                <a href="#"><img src="image/plus.png" alt="Increment Item" class="iconsize plus-button cart-major"></a>
                                <br>
                                <input class="numbertextbox" type="number" id="quantity-input" onkeypress="return event.charCode >= 48 && event.charCode <= 57"  min="1" max="100" value="' . $row["quantity"] . '" disabled>
                                <br>
                                <a href="#"><img src="image/edit.png" class="iconsize edit-quantity"></a>
                            </td>
                            <td>' . $row["price"] . '</td>
                            <td>' . number_format($row["price"] * $row["quantity"], 2, '.', '') . '</td>
                            <th>
                                <a href="#"><img src="image/bin.png" alt="Remove Item" class="iconsize remove-from-cart cart-major"></a>
                            </th>
                        </tr>';

                            $total_cost += $row["quantity"] * $row["price"];
                            $total_quantity += $row["quantity"];
                        }

                        echo '<tr>
                                    <td colspan="2">Total:</td>
                                    <td>' . $total_quantity . '</td>
                                    <td colspan="2">$' . number_format($total_cost, 2, '.', '') . '</td>
                                    <td></td>
                                    </tr>
                            </table>';
                    } else {
                        $captionText = "Cart is empty!";
                    }
                }
                break;

            case "summary-page":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);

                // Prepare the statement:
                $stmt = $conn->prepare("SELECT c.prod_id, p.name, p.image_url, c.quantity, p.price FROM SMart.Cart as c INNER JOIN SMart.Product as p ON c.prod_id = p.id WHERE c.cust_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("s", $cust_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $total_cost = 0;
                    $total_quantity = 0;
                    $service_charge_percent = 0.05;
                    $delivery_charge = 5;

                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo '<table class="carttable" style="font-size: 1.4rem;">
                            <tr style="text-align: center; background: #6D6875; color: white;">
                                <th colspan="2">Product</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Quantity</th>
                            </tr>';

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr id="cartid_' . $row["prod_id"] . '">
                            <td><img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" class="imagesize"></td>
                            <td>' . $row["name"] . '</td>
                            <td>$' . $row["price"] . '</td>
                            <td>$' . number_format($row["price"] * $row["quantity"], 2, '.', '') . '</td>
                            <td>' . $row["quantity"] . '</td>
                        </tr>';

                            $total_cost += $row["quantity"] * $row["price"];
                            $total_quantity += $row["quantity"];
                        }

                        echo '<tr>
                                     <td colspan="3" style="text-align: right;">Total: </td>
                                     <td>$' . number_format($total_cost, 2, '.', '') . '</td>
                                     <td>' . $total_quantity . '</td>
                                </tr>
                            </table>
                            <table class="carttable" style="font-size: 1.4rem; margin-top: 40px;">
                                <tr style="text-align: center; background: white;">
                                    <td colspan="2">Delivery Fee (' . $service_charge_percent * 100 . '%): </td>
                                    <td colspan="2">$' . number_format($total_cost * $service_charge_percent, 2, '.', '') . '</td>
                                </tr>
                                <tr style="text-align: center; background: white;">
                                    <td colspan="2">Service Fee: </td>
                                    <td colspan="2">$' . number_format($delivery_charge, 2, '.', '') . '</td>
                                </tr>
                                <tr style="text-align: center; background: white;">
                                    <td colspan="2">Final Cost: </td>
                                    <td colspan="2">$' . number_format(($total_cost * (1 + $service_charge_percent)) + $delivery_charge, 2, '.', '') . '</td>
                                </tr>
                            </table>
                            </div>';
                    } else {
                        $captionText = "Cart is empty!";
                    }
                }
                break;

            default:
                break;
        }
        $stmt->close();
        $conn->close();
    }
    echo "<caption>" . $captionText . "</caption>";
} else {
    echo '<div class ="cartBox" style="font-size: 1.4rem; color: #666;">Please login first before using of cart!</div>';
}
?>

