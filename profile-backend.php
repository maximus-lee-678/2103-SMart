<?php

session_start();
$fname = $lname = $phone = $email = $errorMsg = "";
$addId = $alias = $address = $unitno = $postal = "";
$pwd_hashed = "";
$payId = $paytype = $owner = $accno = $expiry = "";

$success = true;
$newData = [];

if (isset($_POST['type'])) {
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] == false) {
        $errorMsg .= "Customer is not logged in.<br>";
        $success = false;
    }
    if ($success) {
        $data = $_POST['data'];

        switch ($_POST['type']) {
            case "profile":
                validateProfile($data);
                if ($success) checkIfUnique();
                if ($success) updateProfile();
                if ($success) {
                    $newData = array(
                        "fname" => $fname,
                        "lname" => $lname,
                        "phone" => $phone,
                        "email" => $email
                    );
                }
                break;
            case "address":
                switch ($_POST['mode']) {
                    case "update":
                        validateAddress($data);
                        if ($success) validateUserByAddress($data);
                        if ($success) updateAddress($data);
                        if ($success) {
                            $newData = array(
                                "alias" => $alias,
                                "address" => $address,
                                "unitno" => $unitno,
                                "postal" => $postal
                            );
                        }
                        break;
                    case "add":
                        validateAddress($data);
                        if ($success)addAddress($data);
                        if ($success) {
                            $newData = array(
                                "alias" => $alias,
                                "address" => $address,
                                "unitno" => $unitno,
                                "postal" => $postal,
                                "id" => $addId
                            );
                        }
                        break;
                    case "delete":
                        validateUserByAddress($data);
                        if ($success) deleteAddress($data);
                        break;
                    default:
                        break;
                }
                break;
            case "password":
                validateUserByPassword($data);
                if ($success) validatePassword($data);
                if ($success) updatePassword();
                break;
            case "card":
                switch ($_POST['mode']) {
                    case "update":
                        validateCard($data);
                        if ($success) validateUserByPayment($data);
                        if ($success) updateCard();
                        if ($success) {
                            $newData = array(
                                "paytype" => $paytype,
                                "owner" => $owner,
                                "accno" => $accno,
                                "expiry" => $expiry
                            );
                        }
                        break;
                    case "add":
                        validateCard($data);
                        if ($success) addCard();
                        if ($success) {
                            $newData = array(
                                "id" => $payId,
                                "paytype" => $paytype,
                                "owner" => $owner,
                                "accno" => $accno,
                                "expiry" => $expiry
                            );
                        }
                        break;
                    case "delete":
                        validateUserByPayment($data);
                        if ($success) {
                            deleteCard($data);
                        }
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }
    }


    if ($success) {
        echo json_encode(array("success" => $success, "data" => $newData));
    } else {
        echo json_encode(array("success" => $success, "message" => $errorMsg));
    }
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validateProfile($data) {

    global $fname, $lname, $phone, $email, $errorMsg, $success;

    //First Name
    if (empty($data["fname"])) {       //Check if fname exists
        $errorMsg .= "First name is required.<br>";
        $success = false;
    } elseif (strlen($data["fname"]) > 255) { //Check if len of fname is more than 255
        $errorMsg .= "First name cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $fname = sanitize_input($data["fname"]);
    }

    //Last Name
    if (empty($data["lname"])) {       //Check if lname exists
        $errorMsg .= "Last name is required.<br>";
        $success = false;
    } elseif (strlen($data["lname"]) > 255) {   //Check if len of lname is more than 255
        $errorMsg .= "Last name cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $lname = sanitize_input($data["lname"]);
    }


    //Phone
    if (empty($_POST["user_phonenum"])) {       //Check if phone exists
        $errorMsg .= "Phone number is required.<br>";
        $success = false;
    } elseif (strlen($_POST["user_phonenum"]) != 8) {   //Check if len of phone is 8
        $errorMsg .= "Phone number must be 8 digits long.<br>";
        $success = false;
    } elseif (!is_numeric($_POST["user_phonenum"])) {   //Check if int
        $errorMsg .= "Phone number must be only contain numbers.<br>";
        $success = false;
    } else {
        $phone = sanitize_input($_POST["user_phonenum"]);
    }

    //Email
    if (empty($data["email"])) {       //Check if email exist
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else if (strlen($data["email"]) > 255) { //Check if len of email is more than 45
        $errorMsg .= "Email cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $email = sanitize_input($data["email"]);

        // Additional check to make sure e-mail address is well-formed.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }
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
        $rowCount = $result->num_rows;
        //Check if customer is empty
        if ($rowCount > 0) {
            $row = $result->fetch_assoc();
            if ($row['id'] != $_SESSION['id']) {
                $errorMsg .= "This email is already registered";
                $success = false;
            }
        }
    }

    $conn->close();
}

function updateProfile() {
    global $fname, $lname, $phone, $email, $errorMsg, $success;
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
    $stmt = $conn->prepare("UPDATE Customer SET first_name = ?, last_name = ?, email = ?, telephone = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $fname, $lname, $email, $phone, sanitize_input($_SESSION['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $stmt->close();
    }

    $conn->close();
}

function validateAddress($data) {

    global $alias, $address, $unitno, $postal, $errorMsg, $success;

    //Alias
    if (empty($data["alias"])) {       //Check if alias exists
        $errorMsg .= "Alias is required.<br>";
        $success = false;
    } elseif (strlen($data["alias"]) > 255) {   //Check if len of alias is more than 255
        $errorMsg .= "Alias cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $alias = sanitize_input($data["alias"]);
    }
    
    //Address
    if (empty($data["address"])) {       //Check if address exists
        $errorMsg .= "Address is required.<br>";
        $success = false;
    } elseif (strlen($data["address"]) > 255) {   //Check if len of address is more than 255
        $errorMsg .= "Address cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $address = sanitize_input($data["address"]);
    }

    //Postal
    if (empty($data["postal"])) {       //Check if postal exists
        $errorMsg .= "Postal code is required.<br>";
        $success = false;
    } elseif (strlen($data["postal"]) != 6) {   //Check if len of postal is 6
        $errorMsg .= "Postal code must be 6 digits long.<br>";
        $success = false;
    } elseif (!is_numeric($data["postal"])) {   //Check if lint
        $errorMsg .= "Postal code must be only contain numbers.<br>";
        $success = false;
    } else {
        $postal = sanitize_input($data["postal"]);
    }
    
    //Unit No
    if (empty($data["unitno"])) {       //Check if unit no exists
        $errorMsg .= "Unit number is required.<br>";
        $success = false;
    } elseif (strlen($data["unitno"]) > 10) {   //Check if len of alias is more than 10
        $errorMsg .= "Unit number cannot be more than 10 characters.<br>";
        $success = false;
    } else {
        $unitno = sanitize_input($data["unitno"]);
    }
}

function validatePassword($data) {
    global $pwd_hashed, $errorMsg, $success;

    $number = preg_match('@[0-9]@', $data["new_password"]);         //Check if there are numbers
    $uppercase = preg_match('@[A-Z]@', $data["new_password"]);      //Check if there are uppercase
    $lowercase = preg_match('@[a-z]@', $data["new_password"]);      //Check if there are lowercase
    $special = preg_match('/[\'^£$%&!*()}{@#~?><>,|=_+¬-]/', $data["new_password"]);
    if (empty($data["new_password"]) || empty($data["confirm_password"])) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } elseif (!$number || !$uppercase || !$lowercase || !$special || strlen($data["new_password"]) < 8) {  //Check if contain at least one number, uppercase and lowercase letter, and at least 8 characters.
        $errorMsg .= "Password must contain at least one number, uppercase and lowercase letter, and at least 8 characters.<br>";
        $success = false;
    } elseif ($data["new_password"] != $data["confirm_password"]) {     //Check if password confirmation matches password
        $errorMsg .= "Password do not match.<br>";
        $success = false;
    } else {
        $pwd_hashed = password_hash($data["new_password"], PASSWORD_DEFAULT);
    }
}

function validateCard($data) {

    global $payId, $paytype, $owner, $accno, $expiry, $errorMsg, $success;
    $paytypeArr = array("Visa", "Master", "Amex", "PayPal");

    //Payment Type
    if (empty($data["paytype"])) {       //Check if payment type exists
        $errorMsg .= "Payment Type is required.<br>";
        $success = false;
    } elseif (!in_array($data["paytype"], $paytypeArr)) {   //Check if card exist
        $errorMsg .= "We only allow Visa, Master, Amex and Paypal only.<br>";
        $success = false;
    } else {
        $paytype = sanitize_input($data["paytype"]);
    }

    //Owner
    if (empty($data["owner"])) {       //Check if owner exists
        $errorMsg .= "Owner is required.<br>";
        $success = false;
    } elseif (strlen($data["owner"]) > 255) {   //Check if len of lname is more than 255
        $errorMsg .= "Owner cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $owner = sanitize_input($data["owner"]);
    }
    
    //Account No
    if (empty($data["accno"])) {       //Check if accno exists
        $errorMsg .= "Account Number is required.<br>";
        $success = false;
    } elseif (strlen($data["accno"]) > 16) {   //Check if len of accno is more than 255
        $errorMsg .= "Account No cannot be more than 16 digits.<br>";
        $success = false;
    } elseif (!is_numeric($data["accno"])) {   //Check if accno contains digits
        $errorMsg .= "Account No must only contain digits.<br>";
        $success = false;
    } else {
        $accno = sanitize_input($data["accno"]);
    }
    
    //Expiry
    if (empty($data["expiry"])) {       //Check if owner exists
        $errorMsg .= "Owner is required.<br>";
        $success = false;
    } elseif (!strtotime($data["expiry"])) {   //Check if len of lname is more than 255
        $errorMsg .= "Date format not allowed.<br>";
        $success = false;
    } else {
        $expiry = sanitize_input($data["expiry"]);
    }
    
    $payId = sanitize_input($data["id"]);
}

function validateUserByAddress($data) {

    global $address, $postal, $errorMsg, $success;
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
    $stmt = $conn->prepare("SELECT * FROM Customer_Address WHERE id = ? and cust_id = ?");
    $stmt->bind_param("ii", sanitize_input($data['id']), sanitize_input($_SESSION['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $errorMsg .= "Invalid customer id or address id!";
            $success = false;
        }

        $stmt->close();
    }

    $conn->close();
}

function validateUserByPayment($data) {

    global $address, $postal, $errorMsg, $success;
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
    $stmt = $conn->prepare("SELECT * FROM Customer_Payment WHERE id = ? and cust_id = ?");
    $stmt->bind_param("ii", sanitize_input($data['id']), sanitize_input($_SESSION['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $result = $stmt->get_result();
        if ($result->num_rows == 0) {
            $errorMsg .= "Invalid customer id or payment id!";
            $success = false;
        }

        $stmt->close();
    }

    $conn->close();
}

function validateUserByPassword($data) {

    global $errorMsg, $success;
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
    $stmt = $conn->prepare("SELECT password FROM Customer WHERE id = ?");
    $stmt->bind_param("i", sanitize_input($_SESSION['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pwd_hashed = $row["password"];
            if (!password_verify($data["old_password"], $pwd_hashed)) {
                $errorMsg .= "Current password is wrong!\n";
                $success = false;
            }
        } else {
            $errorMsg .= "Invalid customer id!";
            $success = false;
        }

        $stmt->close();
    }

    $conn->close();
}

function updateAddress($data) {
    global $alias, $address, $unitno, $postal, $errorMsg, $success;
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
    $stmt = $conn->prepare("UPDATE Customer_Address SET alias = ?, address = ?, unit_no = ?, postal_code = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $alias, $address, $unitno, $postal, sanitize_input($data['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $stmt->close();
    }

    $conn->close();
}

function addAddress($data) {
    global $addId, $alias, $address, $unitno, $postal, $errorMsg, $success;
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
    $stmt = $conn->prepare("INSERT INTO Customer_Address (cust_id, alias, address, unit_no, postal_code, active) VALUES (?, ?, ?, ?, ?, true)");
    $stmt->bind_param("sssss", sanitize_input($_SESSION['id']), $alias, $address, $unitno, $postal);

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $addId = (int) $conn->insert_id;
        $stmt->close();
    }

    $conn->close();
}

function deleteAddress($data) {
    global $errorMsg, $success;
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
    $stmt = $conn->prepare("DELETE FROM Customer_Address WHERE id = ? and cust_id = ?");
    $stmt->bind_param("ii", sanitize_input($data['id']), sanitize_input($_SESSION['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $stmt->close();
    }

    $conn->close();
}

function updatePassword() {
    global $pwd_hashed, $errorMsg, $success;
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
    $stmt = $conn->prepare("UPDATE Customer SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $pwd_hashed, sanitize_input($_SESSION['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $stmt->close();
    }

    $conn->close();
}

function addCard(){
    
    global $payId, $paytype, $owner, $accno, $expiry, $errorMsg, $success;
    
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
    $stmt = $conn->prepare("INSERT INTO Customer_Payment (cust_id, payment_type, owner, account_no, expiry) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", sanitize_input($_SESSION['id']), $paytype, $owner, $accno, $expiry);

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $payId = (int) $conn->insert_id;
        $stmt->close();
    }

    $conn->close();
    
}

function updateCard() {
    global $payId, $paytype, $owner, $accno, $expiry, $errorMsg, $success;
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
    $stmt = $conn->prepare("UPDATE Customer_Payment SET payment_type = ?, owner = ?, account_no = ?, expiry = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $paytype, $owner, $accno, $expiry, $payId);


    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $stmt->close();
    }

    $conn->close();
}

function deleteCard($data) {
    global $errorMsg, $success;
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
    $stmt = $conn->prepare("DELETE FROM Customer_Payment WHERE id = ? and cust_id = ?");
    $stmt->bind_param("ii", sanitize_input($data['id']), sanitize_input($_SESSION['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $stmt->close();
    }

    $conn->close();
}