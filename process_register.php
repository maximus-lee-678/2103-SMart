<?php
session_start();
$fname = $lname = $phone = $email = $address = $postal = $errorMsg = $pwd_hashed = "";
$success = true;

if (($_SERVER['REQUEST_METHOD'] == 'POST')) {

    //First Name
    if (empty($_POST["user_firstname"])) {       //Check if fname exists
        $errorMsg .= "First name is required.<br>";
        $success = false;
    } elseif (strlen($_POST["user_firstname"]) > 255) { //Check if len of fname is more than 255
        $errorMsg .= "First name cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $fname = sanitize_input($_POST["user_firstname"]);
    }

    //Last Name
    if (empty($_POST["user_lastname"])) {       //Check if lname exists
        $errorMsg .= "Last name is required.<br>";
        $success = false;
    } elseif (strlen($_POST["user_lastname"]) > 255) {   //Check if len of lname is more than 255
        $errorMsg .= "Last name cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $lname = sanitize_input($_POST["user_lastname"]);
    }


    //Phone
    if (empty($_POST["user_phonenum"])) {       //Check if phone exists
        $errorMsg .= "Phone number is required.<br>";
        $success = false;
    } elseif (strlen($_POST["user_phonenum"]) != 8) {   //Check if len of phone is 8
        $errorMsg .= "Phone number must be 8 digits long.<br>";
        $success = false;
    } else {
        $phone = sanitize_input($_POST["user_phonenum"]);
    }

    //Email
    if (empty($_POST["user_email"])) {       //Check if email exist
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else if (strlen($_POST["user_email"]) > 255) { //Check if len of email is more than 45
        $errorMsg .= "Email cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST["user_email"]);

        // Additional check to make sure e-mail address is well-formed.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }
    //Address
    if (empty($_POST["user_address1"])) {       //Check if address exists
        $errorMsg .= "Address is required.<br>";
        $success = false;
    } elseif (strlen($_POST["user_address1"]) > 255) {   //Check if len of address is more than 255
        $errorMsg .= "Address cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $address = sanitize_input($_POST["user_address1"]);
    }

    //Postal
    if (empty($_POST["user_postalcode1"])) {       //Check if postal exists
        $errorMsg .= "Postal code is required.<br>";
        $success = false;
    } elseif (strlen($_POST["user_postalcode1"]) != 6) {   //Check if len of postal is 6
        $errorMsg .= "Postal code must be 6 digits long.<br>";
        $success = false;
    } else {
        $postal = sanitize_input($_POST["user_postalcode1"]);
    }

    //Password (regex sucks)
    $number = preg_match('@[0-9]@', $_POST["user_password"]);         //Check if there are numbers
    $uppercase = preg_match('@[A-Z]@', $_POST["user_password"]);      //Check if there are uppercase
    $lowercase = preg_match('@[a-z]@', $_POST["user_password"]);      //Check if there are lowercase
    $special = preg_match('/[\'^£$%&*()}{@#~?><>,|=_+¬-]/', $_POST["user_password"]);
    if (empty($_POST["user_password"]) || empty($_POST["user_password_confirm"])) {
        echo "<p>" . $_POST["user_password"] . "|||" . $_POST["user_password_confirm"] . "|||" . $_POST["pwd_confirm"] . " </p>";
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } elseif (!$number || !$uppercase || !$lowercase || !$special || strlen($_POST["user_password"]) < 8) {  //Check if contain at least one number, uppercase and lowercase letter, and at least 8 characters.
        $errorMsg .= "Password must contain at least one number, uppercase and lowercase letter, and at least 8 characters.<br>";
        $success = false;
    } elseif ($_POST["user_password"] != $_POST["user_password_confirm"]) {     //Check if password confirmation matches password
        $errorMsg .= "Password do not match.<br>";
        $success = false;
    } else {
        $pwd_hashed = password_hash($_POST["user_password"], PASSWORD_DEFAULT);
    }

    //DB handling
    if ($success) {
        checkIfUnique();
    }

    if ($success) {
        saveMemberToDB();
    }
} else {
    header("Location: page_unavailable.php");
    exit;
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function saveMemberToDB() {
    global $fname, $lname, $phone, $email, $address, $postal, $pwd_hashed, $errorMsg, $success;
    // Create database connection.    
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);

    // Check connection                        
    if ($conn->connect_error) {
        $errorMsg .= "Connection failed: " . $conn->connect_error;
        $success = false;
        $conn->close();
        return;
    }
    // Prepare the statement:        
    $stmtCustomer = $conn->prepare(
            "INSERT INTO Customer (first_name, last_name, email, password, telephone, is_member, created_at, modified_at) "
            . "VALUES (?, ?, ?, ?, ?, false, NOW(), NOW())"
    );
    // Bind & execute the query statement:        
    $stmtCustomer->bind_param("sssss", $fname, $lname, $email, $pwd_hashed, $phone);
    if (!$stmtCustomer->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $lastid = (int) $conn->insert_id;
        // Prepare the statement:        
        $stmtAddress = $conn->prepare(
                "INSERT INTO Customer_Address (cust_id, address, postal_code) "
                . "VALUES (?, ?, ?)"
        );
        // Bind & execute the query statement:        
        $stmtAddress->bind_param("iss", $lastid, $address, $postal);
        if (!$stmtAddress->execute()) {
            $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $success = false;
        }
        $stmtAddress->close();
    }
    $stmtCustomer->close();

    $conn->close();
}

function checkIfUnique() {
    global $email, $errorMsg, $success;
    // Create database connection.    
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);

    // Check connection                        
    if ($conn->connect_error) {
        $errorMsg .= "Connection failed: " . $conn->connect_error;
        $success = false;
        $conn->close();
        return;
    }
    // Prepare the statement: 
    $stmt = $conn->prepare("SELECT * FROM Customer WHERE email=?");
    // Bind & execute the query statement:        
    $stmt->bind_param("s", $email);

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $result = $stmt->get_result();
        $stmt->close();
        //Check if cart is empty
        if ($result->num_rows > 0) {
            $errorMsg .= "Email already exist";
            $success = false;
        }
    }

    $conn->close();
}
?>

<html>
    <title>Registration Results</title>
    <?php include "head.php"; ?>
    <body>
        <?php include "nav.php"; ?>
        <main class = "resultContainer">
            <div class ="content">
                <?php
                if ($success) {
                    echo "<h2>Your registration is successful!</h2>";
                    echo "<h4>Thank you for signing up, " . $fname . " " . $lname . ".</h4>";
                    echo "<p>Redirecting you to login page.</p>";
                    echo "<meta http-equiv=\"refresh\" content=\"3;URL=login.php\">";
                } else {
                    echo "<h2>Oops!</h2>";
                    echo "<h4>The following input errors were detected:</h4>";
                    echo "<p>" . $errorMsg . "</p>";
                    echo "<a href='Registration.php' class='btn btn-danger'>Return to Sign Up</a>";
                }
                ?>
                <br>
            </div>
        </main>
    </body>
</html>
