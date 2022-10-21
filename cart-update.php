<?php

session_start();

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    header("refresh: 0; url=shop.php");
    exit;
}
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

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $captionText = "Connection failed: " . $conn->connect_error;
    } else {
        switch ($_POST["operation"]) {
            case "add-new":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);
                $prod_id = sanitize_input($_POST['prod_id']);

                // 1. Check if row exists in db
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT quantity FROM Cart WHERE cust_id = ? AND prod_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("ii", $cust_id, $prod_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                $result = $stmt->get_result();

                if ($result->num_rows == 0) {
                    // 2.1. No rows, add new
                    $quantity = 1;
                    // Prepare the statement:
                    $stmt = $conn->prepare("INSERT INTO Cart (cust_id, prod_id, quantity) VALUES (?,?,?)");
                    // Bind & execute the query statement:
                    $stmt->bind_param("iii", $cust_id, $prod_id, $quantity);
                    if (!$stmt->execute()) {
                        $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        $captionText .= "Added to cart!";
                    }
                } else {
                    // 2.2. Row found, add 1
                    $row = mysqli_fetch_assoc($result);
                    $quantity = $row["quantity"] + 1;
                    // Prepare the statement: 
                    $stmt = $conn->prepare("UPDATE Cart SET quantity=? WHERE cust_id=? AND prod_id=?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("iii", $quantity, $cust_id, $prod_id);
                    if (!$stmt->execute()) {
                        $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        $captionText .= "Successfully updated!";
                    }
                }
                break;

            case "remove-item":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);
                $prod_id = sanitize_input($_POST['prod_id']);

                // Prepare the statement:
                $stmt = $conn->prepare("DELETE FROM Cart WHERE cust_id = ? AND prod_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("ii", $cust_id, $prod_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $captionText .= "Product successfully removed from cart.";
                }
                break;

            case "decrement-item":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);
                $prod_id = sanitize_input($_POST['prod_id']);

                // 1. Check if quantity is 1
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT quantity FROM Cart WHERE cust_id = ? AND prod_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("ii", $cust_id, $prod_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                $result = $stmt->get_result();
                $row = mysqli_fetch_assoc($result);

                if ($row["quantity"] != 1) {
                    // 2.1. Quantity > 1, subtract 1
                    // Prepare the statement:
                    $stmt = $conn->prepare("UPDATE Cart SET quantity = quantity - 1 WHERE cust_id = ? AND prod_id = ?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("ii", $cust_id, $prod_id);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        $captionText .= "Quantity successfully decremented by 1.";
                    }
                } else {
                    // 2.2. Quantity == 1, remove from cart
                    // Prepare the statement:
                    $stmt = $conn->prepare("DELETE FROM Cart WHERE cust_id = ? AND prod_id = ?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("ii", $cust_id, $prod_id);
                    // execute the query statement:
                    if (!$stmt->execute()) {
                        $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    } else {
                        $captionText .= "Product successfully removed from cart.";
                    }
                }
                break;

            case "increment-item":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);
                $product_id = sanitize_input($_POST['prod_id']);

                // Prepare the statement:
                $stmt = $conn->prepare("UPDATE Cart SET quantity = quantity + 1 WHERE cust_id = ? AND prod_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("ii", $cust_id, $product_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $captionText .= "Quantity successfully incremented by 1.";
                }
                break;

            case "empty-cart":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);

                // Prepare the statement:
                $stmt = $conn->prepare("DELETE FROM Cart WHERE cust_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $cust_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $captionText .= "Cart successfully cleared!";
                }
                break;
                
            case "modify-item-count":
                //form parameters
                $cust_id = sanitize_input($_SESSION["id"]);
                $prod_id = sanitize_input($_POST['prod_id']);
                $quantity = sanitize_input($_POST['quantity']);

                // Prepare the statement:
                $stmt = $conn->prepare("UPDATE Cart SET quantity = ? WHERE cust_id = ? AND prod_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("iii", $quantity, $cust_id, $prod_id);
                // execute the query statement:
                if (!$stmt->execute()) {
                    $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $captionText .= "Quanitity successfully updated!";
                }
                break;

            default:
                break;
        }
        $stmt->close();
        $conn->close();
    }
    echo "<caption>" . $captionText . "</caption>";
}