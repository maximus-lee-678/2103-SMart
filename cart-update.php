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

if (isset($_POST['prod_id']) && isset($_POST['quantity'])) {
    //form parameters
    $cust_id = sanitize_input($_SESSION["id"]);
    $prod_id = sanitize_input($_POST['prod_id']);
    $quantity = sanitize_input($_POST['quantity']);

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    // Check connection
    if ($conn->connect_error) {
        $captionText = "Connection failed: " . $conn->connect_error;
    } else {
        // 1. Check if row exists in db
        // Prepare the statement:
        $stmt = $conn->prepare("SELECT quantity FROM Cart WHERE cust_id = ? AND prod_id = ?");
        // Bind & execute the query statement:
        $stmt->bind_param("ss", $cust_id, $prod_id);
        // execute the query statement:
        if (!$stmt->execute()) {
            $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // 2.1. No rows, add new
            // Prepare the statement:
            $stmt = $conn->prepare("INSERT INTO Cart (cust_id, prod_id, quantity) VALUES (?,?,?)");
            // Bind & execute the query statement:
            $stmt->bind_param("sss", $cust_id, $prod_id, $quantity);
            if (!$stmt->execute()) {
                $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                $captionText .= "Added to cart!";
            }
        } else {
            // 2.2. Row found, update
            $row = mysqli_fetch_assoc($result);
            $quantity = $quantity + $row["quantity"];
            echo $quantity;
            // Prepare the statement: 
            $stmt = $conn->prepare("UPDATE Cart SET quantity=? WHERE cust_id=? AND prod_id=?");
            // Bind & execute the query statement:
            $stmt->bind_param("sss", $quantity, $cust_id, $prod_id);
            if (!$stmt->execute()) {
                $captionText .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            } else {
                $captionText .= "Successfully updated!";
            }
        }

        $stmt->close();
    }
    $conn->close();
    echo $captionText;
}

if (isset($_POST['remove'])) {
    //form parameters
    $cust_id = sanitize_input($_SESSION["id"]);
    $product_id = sanitize_input($_POST['remove']);

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
    // Check connection
    if ($conn->connect_error) {
        $captionText = "Connection failed: " . $conn->connect_error;
    } else {
        echo "--> " . $product_name . " " . $cust_id;
        // Prepare the statement:
        $stmt = $conn->prepare("DELETE FROM Cart as c WHERE c.prod_id = ? AND c.cust_id = ?");
        // Bind & execute the query statement:
        $stmt->bind_param("ss", $product_id, $cust_id);
        // execute the query statement:
        if (!$stmt->execute()) {
            $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $captionText = $product_name . " successfully removed from cart.";
        }
        $stmt->close();
    }
    $conn->close();

    echo $captionText;
}