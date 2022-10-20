<?php

session_start();
//
//if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
//    header("refresh: 0; url=shop.php");
//    exit;
//}
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
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT c.prod_id, c.quantity, p.name, p.image_url, p.price FROM SMart.Cart as c INNER JOIN SMart.Product as p ON c.prod_id = p.id WHERE c.cust_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("s", $_SESSION["id"]);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $total_cost = 0.00;

                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="box" id="cartid_' . $row["prod_id"] . '">
                    <i class="fas fa-times remove-from-cart"></i>
                    <img src="' . $row["image_url"] . '" alt="">
                    <div class="content">
                        <h3>' . $row["name"] . '</h3>
                        <a href="#"><img src="image/minus.png" alt="Decrement Item" class="iconsize minus-button"></a>
                        <span class="quantity" > ' . $row["quantity"] . ' </span>
                        <a href="#"><img src="image/plus.png" alt="Increment Item" class="iconsize plus-button"></a>
                        <span class="multiply">*</span>
                        <span class="price">$' . $row["price"] . '</span>
                    </div>
                </div>';

                            $total_cost += $row["quantity"] * $row["price"];
                        }

                        echo '<h3 class="total"> total : <span>$' . $total_cost . '</span> </h3>
                <a href="MyShoppingCart.php" class="btn">View My Cart</a>';
                    } else {
                        $captionText = "Cart is empty!";
                    }
                }
                break;
            case "cart-page":
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT c.prod_id, p.name, p.image_url, c.quantity, p.price FROM SMart.Cart as c INNER JOIN SMart.Product as p ON c.prod_id = p.id WHERE c.cust_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("s", $_SESSION["id"]);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $total_cost = 0.00;
                    $total_quantity = 0;

                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        echo '<table>
                        <tr style="background: #6D6875; color: white;">
                            <th>ID</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price (in $)</th>
                            <th>Total (in $)</th>
                            <th>Remove</th>
                        </tr>';

                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                            <td>' . $row["prod_id"] . '</td>
                            <td>'
                            . $row["name"] . '<br>
                                <img src="' . $row["image_url"] . '" alt="' . $row["name"] . '" class="imagesize">
                            </td>
                            <td>
                                <a href="#"><img src="image/minus.png" alt="Decrement Item" class="iconsize minus-button"></a>
                                <input class="numbertextbox" type="number" value="' . $row["quantity"] . '" min="1" max="100">
                                <a href="#"><img src="image/plus.png" alt="Increment Item" class="iconsize plus-button"></a>
                            </td>
                            <td>' . $row["price"] . '</td>
                            <td>' . ($row["price"] * $row["quantity"]) . '</td>
                            <th>
                                <a href="#"><img src="image/bin.png" alt="Remove Item" class="iconsize"></a>
                            </th>
                        </tr>
                        <tr>';

                            $total_cost += $row["quantity"] * $row["price"];
                            $total_quantity += $row["quantity"];
                        }

                        echo '<tr>
                                    <td colspan="2">Total:</td>
                                    <td>' . $total_quantity . '</td>
                                    <td colspan="2">' . $total_cost . '</td>
                                    <td></td>
                                    </tr>
                            </table>';
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

