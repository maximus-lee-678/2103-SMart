<?php

session_start();
//
//if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
//    header("refresh: 0; url=shop.php");
//    exit;
//}
?>

<?php

if (isset($_SESSION["id"])) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    // Check connection
    if ($conn->connect_error) {
        $captionText = "Connection failed: " . $conn->connect_error;
    } else {
        // Prepare the statement:
        $stmt = $conn->prepare("SELECT c.prod_id, c.quantity, p.name, p.image_url, p.price FROM SMart.Cart as c INNER JOIN SMart.Product as p ON c.prod_id = p.id WHERE c.cust_id = ?");
        // Bind & execute the query statement:
        $stmt->bind_param("s", $_SESSION["id"]);
        // execute the query statement:
        if (!$stmt->execute()) {
            $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $total_cost = 0.00;

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $captionText = "Retrieved " . $result->num_rows . " rows.";
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<div class="box" id="cartid_' . $row["prod_id"] . '">
                    <i class="fas fa-times remove-from-cart"></i>
                    <img src="' . $row["image_url"] . '" alt="">
                    <div class="content">
                        <h3>' . $row["name"] . '</h3>
                        <span class="quantity">' . $row["quantity"] . '</span>
                        <span class="multiply">*</span>
                        <span class="price">$' . $row["price"] . '</span>
                    </div>
                </div>';
                
                $total_cost += $row["quantity"] * $row["price"];
            }

            echo '<h3 class="total"> total : <span>$' . $total_cost . '</span> </h3>
                <a href="#" class="btn">checkout cart</a>';
        } else {
            $captionText = "Cart is empty!";
        }
        $stmt->close();
    }
    $conn->close();
    echo "<caption>" . $captionText . "</caption>";
} else {
    echo "Login to make use of cart!";
}
?>

