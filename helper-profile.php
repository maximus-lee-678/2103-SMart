<?php

require 'vendor/autoload.php';

////////////////////////////////////////////
// PROFILE
// --------------
// [Required Fields]
// UPDATE:  staff (bool), fname, lname, phone, email
///////////////////////////////////////////

function profileOperation($operation, $data) {
    switch ($operation) {
        case "update":
            $result = validateProfile($data);
            if ($result['success']) {
                $data = $result['data'];
                $result = checkIfUnique($data);
            }
            if ($result['success']) {
                $result = updateProfile($data);
            }
            if ($result['success']) {
                $newData = array(
                    "staff" => $data["staff"],
                    "fname" => $data["fname"],
                    "lname" => $data["lname"],
                    "phone" => $data["phone"],
                    "email" => $data["email"]
                );
                return array("success" => true, "data" => $newData, "extra" => $result["data"]);
            }
            break;
        default:
            $result = array("data" => null);
            break;
    }

    return array("success" => false, "data" => $result["data"]);
}

////////////////////////////////////////////
// ADDRESS
// --------------
// [Required Fields]
// UPDATE:  id, alias, address, unitno, postal
// ADD:     alias, address, unitno, postal
// DELETE:  id
///////////////////////////////////////////

function addressOperation($operation, $data) {
    switch ($operation) {
        case "update":
            $result = validateAddress($data);
            if ($result['success']) {
                $data = $result['data'];
                $result = ifChangeAddress($data);
            }
            if ($result['success']) {
                $result = validateUserByAddress($data);
            }
            if ($result['success']) {
                $result = updateAddress($data);
            }
            if ($result['success']) {
                $newData = array(
                    "id" => $result['data'],
                    "alias" => $data['alias'],
                    "address" => $data['address'],
                    "unitno" => $data['unitno'],
                    "postal" => $data['postal']
                );
                return array("success" => true, "data" => $newData);
            }
            break;
        case "add":
            $result = validateAddress($data);
            if ($result['success']) {
                $data = $result['data'];
                $result = addAddress($data);
            }
            if ($result['success']) {
                $newData = array(
                    "alias" => $data['alias'],
                    "address" => $data['address'],
                    "unitno" => $data['unitno'],
                    "postal" => $data['postal'],
                    "id" => $result['data']
                );
                return array("success" => true, "data" => $newData);
            }
            break;
        case "delete":
            $result = validateUserByAddress($data);
            if ($result['success']) {
                $result = deleteAddress($data);
            }
            if ($result['success']) {
                return array("success" => true, "data" => $newData);
            }
            break;
        default:
            $result = array("data" => null);
            break;
    }

    return array("success" => false, "data" => $result["data"]);
}

////////////////////////////////////////////
// PASSWORD
// --------------
// [Required Fields]
// UPDATE:  staff (bool), old_password, new_password, confirm_password
///////////////////////////////////////////

function passwordOperation($operation, $data) {
    switch ($operation) {
        case "update":
            $result = validateUserByPassword($data);
            if ($result['success']) {
                $result = validatePassword($data);
            }
            if ($result['success']) {
                $data = $result['data'];
                $result = updatePassword($data);
            }
            if ($result['success']) {
                return array("success" => true, "data" => null);
            }
            break;
        default:
            $result = array("data" => null);
            break;
    }

    return array("success" => false, "data" => $result["data"]);
}

////////////////////////////////////////////
// PAYMENT
// --------------
// [Required Fields]
// UPDATE:  id, paytype, owner, accno, expiry
// ADD:     paytype, owner, accno, expiry
// DELETE:  id
///////////////////////////////////////////

function paymentOperation($operation, $data) {
    switch ($operation) {
        case "update":
            $result = validateCard($data);
            if ($result['success']) {
                $data = $result['data'];
                $result = ifChangePayment($data);
            }
            if ($result['success']) {
                $result = validateUserByPayment($data);
            }
            if ($result['success']) {
                $result = updateCard($data);
            }
            if ($result['success']) {
                $newData = array(
                    "id" => $result['data'],
                    "paytype" => $data['paytype'],
                    "owner" => $data['owner'],
                    "accno" => $data['accno'],
                    "expiry" => $data['expiry']
                );
                return array("success" => true, "data" => $newData);
            }
            break;
        case "add":
            $result = validateCard($data);
            if ($result['success']) {
                $data = $result['data'];
                $result = addCard($data);
            }
            if ($result['success']) {
                $newData = array(
                    "id" => $result['data'],
                    "paytype" => $data['paytype'],
                    "owner" => $data['owner'],
                    "accno" => $data['accno'],
                    "expiry" => $data['expiry']
                );
                return array("success" => true, "data" => $newData);
            }
            break;
        case "delete":
            $result = validateUserByPayment($data);
            if ($result['success']) {
                $result = deleteCard($data);
            }
            if ($result['success']) {
                return array("success" => true, "data" => $newData);
            }
            break;
        default:
            break;
    }

    return array("success" => false, "data" => $result["data"]);
}

////////////////////////////////////////////
// PRIVATE FUNCTIONS
// --------------
// To support operations above
///////////////////////////////////////////

function validateProfile($data) {

    $errorMsg = "";
    $success = true;

    //First Name
    if (empty($data["fname"])) {       //Check if fname exists
        $errorMsg .= "First name is required.<br>";
        $success = false;
    } elseif (strlen($data["fname"]) > 255) { //Check if len of fname is more than 255
        $errorMsg .= "First name cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $data["fname"] = sanitize_input($data["fname"]);
    }

    //Last Name
    if (empty($data["lname"])) {       //Check if lname exists
        $errorMsg .= "Last name is required.<br>";
        $success = false;
    } elseif (strlen($data["lname"]) > 255) {   //Check if len of lname is more than 255
        $errorMsg .= "Last name cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $data["lname"] = sanitize_input($data["lname"]);
    }


    //Phone
    if (empty($data["phone"])) {       //Check if phone exists
        $errorMsg .= "Phone number is required.<br>";
        $success = false;
    } elseif (strlen($data["phone"]) != 8) {   //Check if len of phone is 8
        $errorMsg .= "Phone number must be 8 digits long.<br>";
        $success = false;
    } elseif (!is_numeric($data["phone"])) {   //Check if int
        $errorMsg .= "Phone number must be only contain numbers.<br>";
        $success = false;
    } else {
        $data["phone"] = sanitize_input($data["phone"]);
    }

    //Email
    if (empty($data["email"])) {       //Check if email exist
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else if (strlen($data["email"]) > 255) { //Check if len of email is more than 45
        $errorMsg .= "Email cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $data["email"] = sanitize_input($data["email"]);

        // Additional check to make sure e-mail address is well-formed.
        if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.";
            $success = false;
        }
    }

    return $success ? array("success" => $success, "data" => $data) : array("success" => $success, "data" => $errorMsg);
}

function validateAddress($data) {

    $errorMsg = "";
    $success = true;

    //Alias
    if (empty($data["alias"])) {       //Check if alias exists
        $errorMsg .= "Alias is required.<br>";
        $success = false;
    } elseif (strlen($data["alias"]) > 255) {   //Check if len of alias is more than 255
        $errorMsg .= "Alias cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $data["alias"] = sanitize_input($data["alias"]);
    }

    //Address
    if (empty($data["address"])) {       //Check if address exists
        $errorMsg .= "Address is required.<br>";
        $success = false;
    } elseif (strlen($data["address"]) > 255) {   //Check if len of address is more than 255
        $errorMsg .= "Address cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $data["address"] = sanitize_input($data["address"]);
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
        $data["postal"] = sanitize_input($data["postal"]);
    }

    //Unit No
    if (empty($data["unitno"])) {       //Check if unit no exists
        $errorMsg .= "Unit number is required.<br>";
        $success = false;
    } elseif (strlen($data["unitno"]) > 10) {   //Check if len of alias is more than 10
        $errorMsg .= "Unit number cannot be more than 10 characters.<br>";
        $success = false;
    } else {
        $data["unitno"] = sanitize_input($data["unitno"]);
    }

    return $success ? array("success" => $success, "data" => $data) : array("success" => $success, "data" => $errorMsg);
}

function validatePassword($data) {

    $errorMsg = "";
    $success = true;

    $number = preg_match('@[0-9]@', $data["new_password"]);         //Check if there are numbers
    $uppercase = preg_match('@[A-Z]@', $data["new_password"]);      //Check if there are uppercase
    $lowercase = preg_match('@[a-z]@', $data["new_password"]);      //Check if there are lowercase
    $special = preg_match('/[\'^£$%&!*()}{@#~?><>,|=_+¬-]/', $data["new_password"]);

    if (empty($data["new_password"])) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } elseif (!$number || !$uppercase || !$lowercase || !$special || strlen($data["new_password"]) < 8) {  //Check if contain at least one number, uppercase and lowercase letter, and at least 8 characters.
        $errorMsg .= "Password must contain at least one number, uppercase and lowercase letter, and at least 8 characters.<br>";
        $success = false;
    } elseif (array_key_exists("confirm_password", $data) && $data["new_password"] != $data["confirm_password"]) {     //Check if password confirmation matches password
        $errorMsg .= "Password do not match.<br>";
        $success = false;
    } else {
        $data["new_password"] = password_hash($data["new_password"], PASSWORD_DEFAULT);
    }

    return $success ? array("success" => $success, "data" => $data) : array("success" => $success, "data" => $errorMsg);
}

function validateCard($data) {

    $errorMsg = "";
    $success = true;
    $paytypeArr = array("Visa", "Master");

    //Payment Type
    if (empty($data["paytype"])) {       //Check if payment type exists
        $errorMsg .= "Payment Type is required.<br>";
        $success = false;
    } elseif (!in_array($data["paytype"], $paytypeArr)) {   //Check if card exist
        $errorMsg .= "We only allow Visa, Master, Amex and Paypal only.<br>";
        $success = false;
    } else {
        $data["paytype"] = sanitize_input($data["paytype"]);
    }

    //Owner
    if (empty($data["owner"])) {       //Check if owner exists
        $errorMsg .= "Owner is required.<br>";
        $success = false;
    } elseif (strlen($data["owner"]) > 255) {   //Check if len of lname is more than 255
        $errorMsg .= "Owner cannot be more than 255 characters.<br>";
        $success = false;
    } else {
        $data["owner"] = sanitize_input($data["owner"]);
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
        $data["accno"] = sanitize_input($data["accno"]);
    }

    //Expiry
    if (empty($data["expiry"])) {       //Check if owner exists
        $errorMsg .= "Owner is required.<br>";
        $success = false;
    } elseif (!strtotime($data["expiry"])) {   //Check if len of lname is more than 255
        $errorMsg .= "Date format not allowed.<br>";
        $success = false;
    } else {
        $data["expiry"] = sanitize_input($data["expiry"]);
    }

    $data["id"] = sanitize_input($data["id"]);

    return $success ? array("success" => $success, "data" => $data) : array("success" => $success, "data" => $errorMsg);
}

function validateUserByAddress($data) {

    $errorMsg = "";
    $success = true;
    $db = make_mongo_connection();

    $query = array('id' => (int) sanitize_input($_SESSION['id']), "address_info.address_id" => (int) sanitize_input($data['id']));
    $result = $db->Customer->count($query);

    if ($result == 0) {
        $errorMsg .= "Invalid customer id or address id!";
        $success = false;
    }

    return $success ? array("success" => $success) : array("success" => $success, "data" => $errorMsg);
}

function validateUserByPassword($data) {

    $errorMsg = "";
    $success = true;

    if (!array_key_exists("old_password", $data)) {
        return array("success" => $success);
    }

    $db = make_mongo_connection();

    $query = array("id" => (int) sanitize_input($_SESSION['id']));
    $result = $db->Customer->find($query);

    if ($result != null) {
        $row = $result->toArray()[0];
        $pwd_hashed = $row["password"];
        if (!password_verify($data["old_password"], $pwd_hashed)) {
            $errorMsg .= "Current password is incorrect!\n";
            $success = false;
        }
    } else {
        $errorMsg .= "Invalid customer id!";
        $success = false;
    }

    return $success ? array("success" => $success) : array("success" => $success, "data" => $errorMsg);
}

function validateUserByPayment($data) {

    $errorMsg = "";
    $success = true;
    $db = make_mongo_connection();

    $query = array('id' => (int) sanitize_input($_SESSION['id']), "payment_info.payment_id" => (int) sanitize_input($data['id']));
    $result = $db->Customer->count($query);

    if ($result == 0) {
        $errorMsg .= "Invalid customer id or payment id!";
        $success = false;
    }

    return $success ? array("success" => $success) : array("success" => $success, "data" => $errorMsg);
}

function checkIfUnique($data) {
    $errorMsg = "";
    $success = true;
    $db = make_mongo_connection();

    $queryCust = array("email" => $data['email']);
    $resultCust = $db->Customer->find($queryCust)->toArray()[0];

    if ($resultCust != null) {
        if ($resultCust['id'] != $_SESSION['id']) {
            $errorMsg .= "This email is already registered";
            $success = false;
        }
    }

    $conn = make_connection();
    $queryStaff = "SELECT id FROM Staff WHERE email=?";
    $resultStaff = payload_deliver($conn, $queryStaff, "s", $params = array($data['email']));
    $rowCountStaff = $resultStaff->num_rows;
    if ($rowCountStaff > 0) {
        $row = $resultStaff->fetch_assoc();
        if ($row['id'] != $_SESSION['id']) {
            $errorMsg .= "This email is already registered";
            $success = false;
        }
    }

    $conn->close();

    return $success ? array("success" => $success) : array("success" => $success, "data" => $errorMsg);
}

function updateProfile($data) {
    // Create database connection.        

    if ($data['staff']) {
        $conn = make_connection();
        $query = "UPDATE Staff SET first_name = ?, last_name = ?, email = ?, telephone = ?, modified_at = NOW() WHERE id = ?";
        $result = payload_deliver_verbose($conn, $query, "ssssi", $params = array($data['fname'], $data['lname'], $data['email'], $data['phone'], sanitize_input($_SESSION['id'])));
        $conn->close();
    } else {
        $db = make_mongo_connection();
        $queryFind = array("id" => (int) sanitize_input($_SESSION['id']));
        $queryUpdate = array('$set' => array("first_name" => $data['fname'], "last_name" => $data['lname'], "email" => $data['email'], "telephone" => $data['phone'], "modified_at" => date("Y-m-d h:i:s")));

        $db->Customer->updateOne($queryFind, $queryUpdate);
        $result = array("success" => true, "data" => null);
    }


    return $result;
}

function updateAddress($data) {

    $conn = make_connection();

    // 1. Check if [Orders] table has the address id for any of its rows
    $query = 'SELECT id FROM SMart.Order WHERE cust_id = ? AND address_id = ?';
    $resultSQL = payload_deliver($conn, $query, "ii", $params = array(sanitize_input($_SESSION['id']), $data["id"]));
    $conn->close();

    $db = make_mongo_connection();

    if ($resultSQL->num_rows == 0) {
        // 2.1. Make updates to corresponding [Customer_Address] row
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']), "address_info.address_id" => (int) $data["id"]);
        $queryUpdate = array('$set' => array('address_info.$.alias' => $data["alias"], 'address_info.$.address' => $data["address"], 'address_info.$.unit_no' => $data["unitno"], 'address_info.$.postal_code' => $data["postal"]));
        $db->Customer->updateOne($queryFind, $queryUpdate);

        return array("success" => true, "data" => $data['id']);
    }
    // Rows returned, there exist entries for this address in [Orders], set the address to be inactive and add a new address
    else {
        // 2.2.1. Set old [Customer_Address] row active = 0
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']), "address_info.address_id" => (int) $data["id"]);
        $queryUpdate = array('$set' => array("address_info.$.active" => false));
        $db->Customer->updateOne($queryFind, $queryUpdate);

        // 2.2.2. Create new [Customer_Address] row   
        $result = addAddress($data);

        return array("success" => true, "data" => $result["data"]);
    }
}

function addAddress($data) {

    $db = make_mongo_connection();

    $query = array("id" => (int) sanitize_input($_SESSION['id']));
    $queryProject = array('projection' => array("address_info" => array('$slice' => -1)));

    $result = $db->Customer->find($query, $queryProject)->toArray()[0];
    $newAddressId = $result["address_info"][0]["address_id"] + 1;

    $queryInsert = array('$push' => array("address_info" => array("address_id" => $newAddressId, "alias" => $data["alias"], "address" => $data["address"], "unit_no" => $data["unitno"], "postal_code" => $data["postal"], "active" => true)));
    $db->Customer->updateOne($query, $queryInsert);

    return array("success" => true, "data" => $newAddressId);
}

function deleteAddress($data) {

    $conn = make_connection();

    // 1. Check if [Orders] table has the address id for any of its rows
    $query = 'SELECT id FROM SMart.Order WHERE cust_id = ? AND address_id = ?';
    $resultSQL = payload_deliver($conn, $query, "ii", $params = array(sanitize_input($_SESSION['id']), $data["id"]));
    $conn->close();

    $db = make_mongo_connection();

    if ($resultSQL->num_rows == 0) {
        // 2.1. Delete corresponding [Customer_Address] row
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']));
        $queryUpdate = array('$pull' => array("address_info" => array("address_id" => (int) $data["id"])));

        $db->Customer->updateOne($queryFind, $queryUpdate);
    }
    // Rows returned, there exist entries for this address in [Orders], set the address to be inactive
    else {
        // 2.2.1. Set old [Customer_Address] row active = 0
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']), "address_info.address_id" => (int) $data["id"]);
        $queryUpdate = array('$set' => array("address_info.$.active" => false));

        $db->Customer->updateOne($queryFind, $queryUpdate);
    }

    return array("success" => true, "data" => null);
}

function updatePassword($data) {


    if ($data["staff"]) {
        $conn = make_connection();
        $query = "UPDATE Staff SET password = ?, modified_at = NOW() WHERE id = ?";
        $result = payload_deliver_verbose($conn, $query, "si", $params = array($data["new_password"], $data["staff_id"]));
        $conn->close();

        return $result;
    } else {
        $db = make_mongo_connection();

        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']));
        $queryUpdate = array('$set' => array("password" => $data["new_password"]));

        $db->Customer->updateOne($queryFind, $queryUpdate);

        return array("success" => true, "data" => null);
    }
}

function updateCard($data) {

    $conn = make_connection();

    // 1. Check if [Orders] table has the payment id for any of its rows
    $query = 'SELECT id FROM SMart.Order WHERE cust_id = ? AND payment_id = ?';
    $resultSQL = payload_deliver($conn, $query, "ii", $params = array(sanitize_input($_SESSION['id']), $data["id"]));

    $db = make_mongo_connection();

    // No rows returned, no entries for this payment in [Orders], just update the payment
    if ($resultSQL->num_rows == 0) {
        // 2.1. Make updates to corresponding [Customer_Payment] row
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']), "payment_info.payment_id" => (int) $data["id"]);
        $queryUpdate = array('$set' => array('payment_info.$.type' => $data["paytype"], 'payment_info.$.owner' => $data["owner"], 'payment_info.$.account_no' => $data["accno"], 'payment_info.$.expiry' => $data["expiry"]));

        $db->Customer->updateOne($queryFind, $queryUpdate);

        return array("success" => true, "data" => $data['id']);
    }
    // Rows returned, there exist entries for this payment in [Orders], set the payment to be inactive and add a new payment
    else {
        // 2.2.1. Set old [Customer_Payment] row active = 0
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']), "payment_info.payment_id" => (int) $data["id"]);
        $queryUpdate = array('$set' => array("payment_info.$.active" => false));
        $db->Customer->updateOne($queryFind, $queryUpdate);

        // 2.2.2. Create new [Customer_Payment] row   
        $result = addCard($data);

        return array("success" => true, "data" => $result["data"]);
    }
}

function addCard($data) {

    $db = make_mongo_connection();

    $query = array("id" => (int) sanitize_input($_SESSION['id']));
    $queryProject = array('projection' => array("payment_info" => array('$slice' => -1)));

    $result = $db->Customer->find($query, $queryProject)->toArray()[0];
    $newPaymentId = $result["payment_info"][0]["payment_id"] + 1;

    $queryInsert = array('$push' => array("payment_info" => array("payment_id" => $newPaymentId, "type" => $data["paytype"], "owner" => $data["owner"], "account_no" => $data["accno"], "expiry" => $data["expiry"], "active" => true)));
    $db->Customer->updateOne($query, $queryInsert);

    return array("success" => true, "data" => $newPaymentId);
}

function deleteCard($data) {

    $conn = make_connection();

    // 1. Check if [Orders] table has the payment id for any of its rows
    $query = 'SELECT id FROM SMart.Order WHERE cust_id = ? AND payment_id = ?';
    $resultSQL = payload_deliver($conn, $query, "ii", $params = array(sanitize_input($_SESSION['id']), $data["id"]));
    $conn->close();

    $db = make_mongo_connection();

    if ($resultSQL->num_rows == 0) {
        // 2.1. Delete corresponding [Customer_Payment] row
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']));
        $queryUpdate = array('$pull' => array("payment_info" => array("payment_id" => (int) $data["id"])));

        $db->Customer->updateOne($queryFind, $queryUpdate);
    }
    // Rows returned, there exist entries for this address in [Orders], set the address to be inactive
    else {
        // 2.2.1. Set old [Customer_Payment] row active = 0
        $queryFind = array('id' => (int) sanitize_input($_SESSION['id']), "payment_info.payment_id" => (int) $data["id"]);
        $queryUpdate = array('$set' => array("payment_info.$.active" => false));

        $db->Customer->updateOne($queryFind, $queryUpdate);
    }

    return array("success" => true, "data" => null);
}

function ifChangeAddress($data) {
    $db = make_mongo_connection();

    $query = array("id" => (int) sanitize_input($_SESSION['id']), "address_info.address_id" => (int) $data["id"]);
    $result = $db->Customer->find($query)->toArray()[0];

    if (empty($result)) {
        return array("success" => false, "data" => "Incorrect rows received");
    }

    foreach ($result["address_info"] as $row){
        if($row["address_id"] == $data["id"]) {
            break;
        }
    }
    
    if ($row["address"] == $data["address"] && $row["unit_no"] == $data["unitno"] && $row["postal_code"] == $data["postal"] && $row["alias"] == $data["alias"]) {
        return array("success" => false, "data" => "No changes made");
    }

    return array("success" => true, "data" => null);
}

function ifChangePayment($data) {

    $db = make_mongo_connection();

    $query = array("id" => (int) sanitize_input($_SESSION['id']), "payment_info.payment_id" => (int) $data["id"]);
    $result = $db->Customer->find($query)->toArray()[0];

    if (empty($result)) {
        return array("success" => false, "data" => "Incorrect rows received");
    }

    foreach ($result["payment_info"] as $row){
        if($row["payment_id"] == $data["id"]) {
            break;
        }
    }

    if ($row["owner"] == $data["owner"] && $row["account_no"] == $data["accno"] && $row["expiry"] == $data["expiry"] && $row["type"] == $data["paytype"]) {
        return array("success" => false, "data" => "No changes made");
    }

    return array("success" => true, "data" => null);
}
