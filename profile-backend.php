<?php

session_start();
$fname = $lname = $phone = $email = $address = $postal = $errorMsg = $addId = "";
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
                if ($success) {
                    checkIfUnique();
                }
                if ($success) {
                    updateProfile();
                }
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
                        if ($success) {
                            validateUser($data);
                        }
                        if ($success) {
                            updateAddress($data);
                        }
                        if ($success) {
                            $newData = array(
                                "address" => $address,
                                "postal" => $postal
                            );
                        }
                        break;
                    case "add":
                        validateAddress($data);
                        if ($success) {
                            addAddress($data);
                        }
                        if ($success) {
                            $newData = array(
                                "address" => $address,
                                "postal" => $postal,
                                "id" => $addId
                            );
                        }
                        break;
                    case "delete":
                        validateUser($data);
                        if ($success) {
                            deleteAddress($data);
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

    global $address, $postal, $errorMsg, $success;

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
}

function validateUser($data) {

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

function updateAddress($data) {
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
    $stmt = $conn->prepare("UPDATE Customer_Address SET address = ?, postal_code = ? WHERE id = ?");
    $stmt->bind_param("ssi", $address, $postal, sanitize_input($data['id']));

    if (!$stmt->execute()) {
        $errorMsg .= "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        $success = false;
    } else {
        $stmt->close();
    }

    $conn->close();
}

function addAddress($data) {
    global $address, $postal, $addId, $errorMsg, $success;
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
    $stmt = $conn->prepare("INSERT INTO Customer_Address (cust_id, address, postal_code) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", sanitize_input($_SESSION['id']), $address, $postal);

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