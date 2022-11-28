<?php

include "helper-functions.php";
include "helper-profile.php";

//[FUNCTIONS]///////////////////////////////////////////////////////////////////////////////////////
// Print Close Button
function print_close_button() {
    echo '<div style="float:right;">
        <a style="font-size:15px;" href="#" id="close-view">X</a>
    </div>';
}

// Print Header
function print_header($msg, $args = null) {
    if ($args != null) {
        echo '<h4 style="margin-top: 15px; font-size: 2.4rem;">' . $msg . ' [ <span class="popup-id">' . $args . '</span> ]</h4>';
    } else {
        echo '<h4 style="margin-top: 15px; font-size: 2.4rem;">' . $msg . '</span></h4>';
    }
}

// Print Confirmation Button
function print_confirmation($operation, $text) {
    echo '</div>
            <div class="inputBox" style="margin-top: 20px;">
                <input type="button" style="padding: 12px 20px; margin-left: 50%; transform: translate(-50%, 0%);" operation="' . $operation . '" class="btn" name="confirm-button" value="' . $text . '">
        </div>';
}

////////////////////////////////////////////////////////////////////////////////////////////////////

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    header("refresh: 0; url=staff-home.php");
    exit;
}

if (!isset($_POST["operation"])) {
    exit;
}

$operation = sanitize_input($_POST["operation"]);

$staff_id = sanitize_input($_SESSION["id"]);

$conn = make_connection();

switch ($operation) {
    case "staff-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Get staff details
        $query = 'SELECT * FROM Staff WHERE id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $row = mysqli_fetch_assoc($result);

        print_close_button();
        print_header("Editing Staff ID", $id);

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label style="width: 49%; display: inline-block;">First Name</label>
                    <label style="width: 49%; display: inline-block;">Last Name</label>
                </div>
                <div>
                    <input type="text" class="box popup-input-field" style="width: 49%;" value="' . $row["first_name"] . '">
                    <input type="text" class="box popup-input-field" style="width: 49%;" value="' . $row["last_name"] . '">
                </div>
            </div>    
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Email</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["email"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Change Password</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Telephone</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["telephone"] . '">
                </div>
            </div>';

        // 2. Get staff roles
        $query = 'SELECT r.id, r.name, IF(ISNULL(ra.staff_id), 0, 1) AS exist FROM Role AS r
                LEFT JOIN RoleAssignment AS ra ON r.id=ra.role_id AND ra.staff_id = ?
                ORDER BY r.id ASC';
        $result = payload_deliver($conn, $query, "i", array($id));

        echo '<div>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<input type="checkbox" class="checkbox-field" id="role_' . $row["id"] . '" value="' . $row["id"] . '"' . (($row["exist"] > 0) ? ' checked' : '') . '>
                    <label for="role_' . $row["id"] . '">' . $row["name"] . '</label><br>';
        }

        print_confirmation($operation, "Commit Changes");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "staff-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        // Update password
        if ($args[4] != "") {
            $password_op = passwordOperation("update", array("staff" => 1, "staff_id" => $args[0], "new_password" => $args[4]));

            if (!$password_op["success"]) {
                echo "Password update failed: " . $password_op["data"];
                $conn->close();
                exit();
            }
        }

        // 1. Update rest of staff details
        $query = 'UPDATE Staff
                SET first_name = ?, last_name = ?, email = ?, telephone = ?, modified_at = NOW()
                WHERE id = ?';
        payload_deliver($conn, $query, "sssii", array($args[1], $args[2], $args[3], $args[5], $args[0]));

        $query = 'DELETE FROM RoleAssignment WHERE staff_id = ?';
        payload_deliver($conn, $query, "i", array($args[0]));

        for ($x = 6; $x < count($args); $x++) {
            $query = 'INSERT INTO RoleAssignment VALUES (?,?)';
            payload_deliver($conn, $query, "ii", array($args[0], $args[$x]));
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "staff-delete":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Delete staff
        $query = 'DELETE FROM Staff WHERE id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $response = array('response' => $conn->affected_rows == -1 ? 'Successfully removed ID ' . $id . '.' : 'Failed to remove ID ' . $id . '!');
        echo json_encode($response);

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "staff-add-staging":

        print_close_button();
        print_header("Adding New Staff");

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label style="width: 49%; display: inline-block;">First Name</label>
                    <label style="width: 49%; display: inline-block;">Last Name</label>
                </div>
                <div>
                    <input type="text" class="box popup-input-field" style="width: 49%;">
                    <input type="text" class="box popup-input-field" style="width: 49%;">
                </div>
            </div>    
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Email</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Password</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Telephone</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>';

        // 1. Get staff roles
        $query = 'SELECT id, name FROM Role';
        $result = payload_deliver($conn, $query);

        echo '<div>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<input type="checkbox" class="checkbox-field" id="role_' . $row["id"] . '" value="' . $row["id"] . '">
                    <label for="role_' . $row["id"] . '">' . $row["name"] . '</label><br>';
        }

        print_confirmation($operation, "Add Staff");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "staff-add-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, $x);
        }

        $password_op = validatePassword(array("new_password" => $args[4]));

        if (!$password_op["success"]) {
            echo "Account Creation failed: " . $password_op["data"];
            $conn->close();
            exit();
        }

        // 1. Add Staff
        $query = 'INSERT INTO Staff(first_name, last_name, email, password, telephone, created_at, modified_at) VALUES(?, ?, ?, ?, ?, NOW(), NOW())';
        $result = payload_deliver($conn, $query, "ssssi", $params = array($args[1], $args[2], $args[3], $password_op["data"]["new_password"], $args[5]));

        $id = $conn->insert_id;

        for ($x = 6; $x < count($args); $x++) {
            $query = 'INSERT INTO RoleAssignment VALUES (?,?)';
            payload_deliver($conn, $query, "ii", array($id, $args[$x]));
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "product-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Get brand details
        $query = 'SELECT p.id, p.image_url, p.name, p.display_unit, p.price, sm.name AS supermarket_name, c.name AS category_name, b.name AS brand_name 
                FROM Product AS p 
                LEFT JOIN Supermarket AS sm ON p.sm_id=sm.id 
                LEFT JOIN Category AS c ON p.cat_id=c.id 
                LEFT JOIN Brand AS b on p.brand_id=b.id
                WHERE p.active = 1 AND p.id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $row = mysqli_fetch_assoc($result);

        print_close_button();
        print_header("Editing Product ID", $id);

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["name"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Image URL</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["image_url"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Display Unit</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["display_unit"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Price</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["price"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Supermarket</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" list="supermarket-names" style="width: 100%;" value="' . $row["supermarket_name"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Category</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" list="category-names" style="width: 100%;" value="' . $row["category_name"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Brand</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" list="brand-names" style="width: 100%;" value="' . $row["brand_name"] . '">
                </div>
            </div>';

        // 2. Get supermarket names
        $query = 'SELECT name FROM Supermarket';
        $result = payload_deliver($conn, $query);

        echo '<datalist id="supermarket-names">';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["name"] . '">';
        }

        echo '</datalist>
        ';

        // 3. Get category names
        $query = 'SELECT name FROM Category';
        $result = payload_deliver($conn, $query);

        echo '<datalist id="category-names">';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["name"] . '">';
        }

        echo '</datalist>
        ';

        // 4. Get brand names
        $query = 'SELECT name FROM Brand';
        $result = payload_deliver($conn, $query);

        echo '<datalist id="brand-names">';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["name"] . '">';
        }

        echo '</datalist>
        ';

        print_confirmation($operation, "Commit Changes");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "product-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        // 1. Update brand details
        $query = 'UPDATE Product SET name = ?, display_unit = ?, price = ?, image_url = ?, 
                sm_id = (SELECT id FROM Supermarket WHERE name = ?), 
                cat_id = (SELECT id FROM Category WHERE name = ?), 
                brand_id = (SELECT id FROM Brand WHERE name = ?),
                modified_at = NOW(), modified_by = ?
                WHERE id = ?';
        payload_deliver($conn, $query, "sssssssii", array($args[1], $args[3], number_format($args[4], 2, '.', ''), $args[2], $args[5], $args[6], $args[7], $staff_id, $args[0]));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "product-delete":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Delete staff
        $query = 'UPDATE Product SET active = 0 WHERE id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $response = array('response' => $conn->affected_rows == -1 ? 'Successfully deactivated ID ' . $id . '.' : 'Failed to deactivate ID ' . $id . '!');
        echo json_encode($response);

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "product-add-staging":

        print_close_button();
        print_header("Adding Product");

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Image URL</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Display Unit</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Price</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Supermarket</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" list="supermarket-names" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Category</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" list="category-names" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Brand</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" list="brand-names" style="width: 100%;">
                </div>
            </div>';

        // 1. Get supermarket names
        $query = 'SELECT name FROM Supermarket';
        $result = payload_deliver($conn, $query);

        echo '<datalist id="supermarket-names">';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["name"] . '">';
        }

        echo '</datalist>
        ';

        // 2. Get category names
        $query = 'SELECT name FROM Category';
        $result = payload_deliver($conn, $query);

        echo '<datalist id="category-names">';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["name"] . '">';
        }

        echo '</datalist>
        ';

        // 3. Get brand names
        $query = 'SELECT name FROM Brand';
        $result = payload_deliver($conn, $query);

        echo '<datalist id="brand-names">';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row["name"] . '">';
        }

        echo '</datalist>
        ';

        print_confirmation($operation, "Add Product");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "product-add-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'INSERT INTO Product(name, display_unit, price, image_url, active, quantity, sm_id, cat_id, brand_id, created_at, modified_at, modified_by) 
                VALUES(?, ?, ?, ?, 1, 0, (SELECT id FROM Supermarket WHERE name = ?), (SELECT id FROM Category WHERE name = ?), (SELECT id FROM Brand WHERE name = ?), NOW(), NOW(), ?);';
        payload_deliver($conn, $query, "sssssssi", array($args[1], $args[3], number_format($args[4], 2, '.', ''), $args[2], $args[5], $args[6], $args[7], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////    
    case "supermarket-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Get supermarket details
        $query = 'SELECT name FROM Supermarket WHERE id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $row = mysqli_fetch_assoc($result);

        print_close_button();
        print_header("Editing Supermarket ID", $id);

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["name"] . '">
                </div>
            </div>';

        print_confirmation($operation, "Commit Changes");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "supermarket-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        // 1. Update supermarket details
        $query = 'UPDATE Supermarket
                SET name = ?, modified_at = NOW(), modified_by = ?
                WHERE id = ?';
        payload_deliver($conn, $query, "sii", array($args[1], $staff_id, $args[0]));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "supermarket-delete":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Delete staff
        $query = 'DELETE FROM Supermarket WHERE id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $response = array('response' => $conn->affected_rows == -1 ? 'Successfully removed ID ' . $id . '.' : 'Failed to remove ID ' . $id . '!');
        echo json_encode($response);

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "supermarket-add-staging":

        print_close_button();
        print_header("Adding Supermarket");

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>';

        print_confirmation($operation, "Add Supermarket");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "supermarket-add-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'INSERT INTO Supermarket(name, created_at, modified_at, modified_by) VALUES(?, NOW(), NOW(), ?)';
        $result = payload_deliver($conn, $query, "si", $params = array($args[1], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////    
    case "category-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Get category details
        $query = 'SELECT name, description FROM Category WHERE id = ?';
        $result = payload_deliver($conn, $query, "s", $params = array($id));

        $row = mysqli_fetch_assoc($result);

        print_close_button();
        print_header("Editing Category ID", $id);

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["name"] . '">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Description</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["description"] . '">
                </div>
            </div>';

        print_confirmation($operation, "Commit Changes");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "category-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        // 1. Update supermarket details
        $query = 'UPDATE Category
                SET name = ?, description = ?, modified_at = NOW(), modified_by = ?
                WHERE id = ?';
        payload_deliver($conn, $query, "ssss", array($args[1], $args[2], $staff_id, $args[0]));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "category-delete":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Delete category
        $query = 'DELETE FROM Category WHERE id = ?';
        $result = payload_deliver($conn, $query, "s", $params = array($id));

        $response = array('response' => $conn->affected_rows == -1 ? 'Successfully removed ID ' . $id . '.' : 'Failed to remove ID ' . $id . '!');
        echo json_encode($response);

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "category-add-staging":

        print_close_button();
        print_header("Adding Category");

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Category ID</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>
            <div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Description</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>';

        print_confirmation($operation, "Add Category");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "category-add-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        // 1. Add Category
        $query = 'INSERT INTO Category(id, name, description, created_at, modified_at, modified_by) VALUES(?, ?, ?, NOW(), NOW(), ?)';
        $result = payload_deliver($conn, $query, "sssi", $params = array($args[1], $args[2], $args[3], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////    
    case "brand-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Get brand details
        $query = 'SELECT name FROM Brand WHERE id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $row = mysqli_fetch_assoc($result);

        print_close_button();
        print_header("Editing Brand ID", $id);

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;" value="' . $row["name"] . '">
                </div>
            </div>';

        print_confirmation($operation, "Commit Changes");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "brand-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        // 1. Update brand details
        $query = 'UPDATE Brand
                SET name = ?, modified_at = NOW(), modified_by = ?
                WHERE id = ?';
        payload_deliver($conn, $query, "sii", array($args[1], $staff_id, $args[0]));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "brand-delete":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        // 1. Delete category
        $query = 'DELETE FROM Brand WHERE id = ?';
        $result = payload_deliver($conn, $query, "s", $params = array($id));

        $response = array('response' => $conn->affected_rows == -1 ? 'Successfully removed ID ' . $id . '.' : 'Failed to remove ID ' . $id . '!');
        echo json_encode($response);

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "brand-add-staging":

        print_close_button();
        print_header("Adding Brand");

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Name</label>
                </div>
                <div class="inputBox">
                    <input type="text" class="box popup-input-field" style="width: 100%;">
                </div>
            </div>';

        print_confirmation($operation, "Add Brand");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "brand-add-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        // 1. Add Brand
        $query = 'INSERT INTO Brand(name, created_at, modified_at, modified_by) VALUES(?, NOW(), NOW(), ?)';
        $result = payload_deliver($conn, $query, "si", $params = array($args[1], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////    
    case "stock-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        print_close_button();
        print_header("Restocking Product ID", $id);

        // 1. Get product and its stock details
        $query = 'SELECT p.image_url, p.name, b.name AS brand_name, sm.name AS supermarket_name, p.quantity, 
                IFNULL(p.last_restocked_at, "NA") AS last_restocked_at, IFNULL(CONCAT(CONCAT(s.first_name, " "), s.last_name),"NA") AS last_restocked_by FROM Product AS p 
                LEFT JOIN Supermarket AS sm ON p.sm_id=sm.id 
                LEFT JOIN Brand AS b on p.brand_id=b.id 
                LEFT JOIN Staff AS s ON p.last_restocked_by=s.id
                WHERE p.active = 1 AND p.id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        $row = mysqli_fetch_assoc($result);

        // Print Table Headers (1)
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th colspan="2">Product</th>
                    <th>Brand</th>
                    <th>Supermarket</th>
                </tr>';

        echo '<tr style="text-align: center;"">
                <td><image src="' . $row["image_url"] . '" width="32" height="32"></td>
                <td>' . $row["name"] . '</td>
                <td>' . $row["brand_name"] . '</td>
                <td>' . $row["supermarket_name"] . '</td>
            </tr>';

        echo '</table>';

        // Print Table Headers (2)
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Current Quantity</th>
                    <th>Last Restocked At</th>
                    <th>Last Restocked By</th>
                </tr>';

        echo '<tr style="text-align: center;"">
                <td style="font-weight: bold;">' . $row["quantity"] . '</td>
                <td>' . $row["last_restocked_at"] . '</td>
                <td>' . $row["last_restocked_by"] . '</td>
            </tr>';

        echo '</table>';

        echo '<div style="margin-bottom: 20px; margin-top: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label>Add Quantity</label>
                </div>
                <div class="inputBox">
                    <input type="number" class="box popup-input-field" onkeypress="return event.charCode >= 48 && event.charCode <= 57" min="0" max="100" style="width: 100%;" value="0">
                </div>
            </div>';

        print_confirmation($operation, "Commit Restock");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "stock-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'UPDATE Product '
                . 'SET quantity = quantity + ?, last_restocked_at = NOW(), last_restocked_by = ? '
                . 'WHERE id = ?';
        $result = payload_deliver($conn, $query, "iii", $params = array($args[1], $staff_id, $args[0]));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "pack-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        print_close_button();
        print_header("Viewing Packing List for Order", $id);

        // 1. Get customer details for specified order id
        $query = 'SELECT CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, CONCAT(c.first_name, " ", c.last_name) AS cust_name FROM SMart.Order AS o
                LEFT JOIN Customer AS c ON o.cust_id=c.id
                LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id
                WHERE o.id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (1)
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                </tr>';

        $row = mysqli_fetch_assoc($result);

        echo '<tr style="text-align: center;"">
            <td>' . $row["cust_address"] . '</td>
            <td>' . $row["cust_name"] . '</td>
        </tr>';

        echo '</table>
            <br>';

        // 2. Get order items for specified order id
        $query = 'SELECT oi.prod_id, p.image_url, p.name AS product_name, b.name AS brand_name, sm.name AS supermarket_name, oi.quantity FROM Order_Items AS oi
                LEFT JOIN Product AS p ON oi.prod_id = p.id 
                LEFT JOIN SMart.Order as o ON o.id = oi.order_id 
                LEFT JOIN Brand AS b on p.brand_id=b.id 
                LEFT JOIN Supermarket AS sm ON p.sm_id=sm.id
                WHERE oi.order_id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (2)
        echo '<div style="max-height: 300px; overflow: auto;"><table class="carttable" style="font-size: 1.4rem;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Product ID</th>
                    <th colspan="2">Product</th>
                    <th>Brand</th>
                    <th>Supermarket</th>
                    <th>Quantity</th>
                </tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr style="text-align: center;"">
                <td>' . $row["prod_id"] . '</td>
                <td><image src="' . $row["image_url"] . '" width="32" height="32"></td>
                <td>' . $row["product_name"] . '</td>
                <td>' . $row["brand_name"] . '</td>
                <td>' . $row["supermarket_name"] . '</td>
                <td>' . $row["quantity"] . '</td>
            </tr>';
        }

        echo '</table></div>';

        print_confirmation($operation, "Accept Packing Task");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "pack-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, 2, NOW(), ?)';
        $result = payload_deliver($conn, $query, "ii", $params = array($args[0], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "packed-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, 3, NOW(), ?)';
        $result = payload_deliver($conn, $query, "ii", $params = array($args[0], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "delivery-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        print_close_button();
        print_header("Viewing Delivery List for Order", $id);

        // 1. Get customer details for specified order id
        $query = 'SELECT CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, CONCAT(c.first_name, " ", c.last_name) AS cust_name FROM SMart.Order AS o
                LEFT JOIN Customer AS c ON o.cust_id=c.id
                LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id
                WHERE o.id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (1)
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                </tr>';

        $row = mysqli_fetch_assoc($result);

        echo '<tr style="text-align: center;"">
            <td>' . $row["cust_address"] . '</td>
            <td>' . $row["cust_name"] . '</td>
        </tr>';

        echo '</table>
            <br>';

        // 2. Get order items for specified order id
        $query = 'SELECT oi.prod_id, p.image_url, p.name AS product_name, b.name AS brand_name, sm.name AS supermarket_name, oi.quantity FROM Order_Items AS oi
                LEFT JOIN Product AS p ON oi.prod_id = p.id 
                LEFT JOIN SMart.Order as o ON o.id = oi.order_id 
                LEFT JOIN Brand AS b on p.brand_id=b.id 
                LEFT JOIN Supermarket AS sm ON p.sm_id=sm.id
                WHERE oi.order_id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (2)
        echo '<div style="max-height: 300px; overflow: auto;"><table class="carttable" style="font-size: 1.4rem;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Product ID</th>
                    <th colspan="2">Product</th>
                    <th>Brand</th>
                    <th>Supermarket</th>
                    <th>Quantity</th>
                </tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr style="text-align: center;"">
                <td>' . $row["prod_id"] . '</td>
                <td><image src="' . $row["image_url"] . '" width="32" height="32"></td>
                <td>' . $row["product_name"] . '</td>
                <td>' . $row["brand_name"] . '</td>
                <td>' . $row["supermarket_name"] . '</td>
                <td>' . $row["quantity"] . '</td>
            </tr>';
        }

        echo '</table></div>';

        print_confirmation($operation, "Accept Delivery Task");

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "delivery-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, 4, NOW(), ?)';
        $result = payload_deliver($conn, $query, "ii", $params = array($args[0], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "delivered-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, 5, NOW(), ?)';
        $result = payload_deliver($conn, $query, "ii", $params = array($args[0], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    // Yes, the semantics don't make sense (view != edit). No, I'm not changing it.
    case "order_all-edit-staging":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        print_close_button();
        print_header("Viewing ALL Statuses for Order", $id);

        // 1. Get customer details for specified order id
        $query = 'SELECT CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, CONCAT(c.first_name, " ", c.last_name) AS cust_name FROM SMart.Order AS o
                LEFT JOIN Customer AS c ON o.cust_id=c.id
                LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id
                WHERE o.id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (1)
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                </tr>';

        $row = mysqli_fetch_assoc($result);

        echo '<tr style="text-align: center;"">
            <td>' . $row["cust_address"] . '</td>
            <td>' . $row["cust_name"] . '</td>
        </tr>';

        echo '</table>
            <br>';

        // 2. Get order items for specified order id
        $query = 'SELECT os.id, stat.name AS status_name, CONCAT(sta.first_name, " ", sta.last_name) AS staff_name, os.created_at FROM Order_Status AS os 
                LEFT JOIN Status AS stat ON os.status_id=stat.id 
                LEFT JOIN Staff AS sta ON os.created_by=sta.id
                WHERE os.order_id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (2)
        echo '<div style="max-height: 300px; overflow: auto;"><table class="carttable" style="font-size: 1.4rem;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Status ID</th>
                    <th>Status</th>
                    <th>Staff</th>
                    <th>Created At</th>
                </tr>';

        $status_id = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr style="text-align: center;"">
                <td>' . $row["id"] . '</td>
                <td>' . $row["status_name"] . '</td>
                <td>' . $row["staff_name"] . '</td>
                <td>' . $row["created_at"] . '</td>
            </tr>';
            $status_id++;
        }

        echo '</table></div>';

        if ($status_id == 5) {
            print_confirmation($operation, "Complete and Close Task");
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    // Yes, the semantics don't make sense (update != edit). No, I'm not changing it.
    case "order_all-edit-commit":

        // Form Parameters
        $args = array();
        foreach ($_POST["args"] as $x) {
            array_push($args, sanitize_input($x));
        }

        $query = 'INSERT INTO Order_Status(order_id, status_id, created_at, created_by) VALUES (?, 6, NOW(), ?)';
        $result = payload_deliver($conn, $query, "ii", $params = array($args[0], $staff_id));

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    // Yes, the semantics don't make sense (view != delete). No, I'm not changing it.
    case "order_all-delete":

        // Form Parameters
        $id = sanitize_input($_POST["args"]);

        print_close_button();
        print_header("Viewing Order Details for Order", $id);

        // 1. Get customer details for specified order id
        $query = 'SELECT CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, CONCAT(c.first_name, " ", c.last_name) AS cust_name,
                o.created_at, cp.payment_type, o.subtotal, o.service_charge, o.delivery_charge, (o.subtotal + o.service_charge + o.delivery_charge) AS total_charge FROM SMart.Order AS o
                LEFT JOIN Customer AS c ON o.cust_id=c.id
                LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id
                LEFT JOIN Customer_Payment AS cp ON o.payment_id=cp.id
                WHERE o.id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (1)
        echo '<h4>Customer Details: </h4>
            <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                    <th>Created At</th>
                </tr>';

        $row = mysqli_fetch_assoc($result);

        echo '<tr style="text-align: center;"">
            <td>' . $row["cust_address"] . '</td>
            <td>' . $row["cust_name"] . '</td>
            <td>' . $row["created_at"] . '</td>
        </tr>';

        echo '</table>
            <br>';

        // Print Table Headers (2)
        echo '<h4>Payment Details: </h4>
            <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Payment Type</th>
                    <th>Subtotal</th>
                    <th>Service Charge</th>
                    <th>Delivery Charge</th>
                    <th>Total</th>
                </tr>';

        echo '<tr style="text-align: center;"">
            <td>' . $row["payment_type"] . '</td>
            <td>$' . number_format($row["subtotal"], 2, '.', '') . '</td>
            <td>$' . number_format($row["service_charge"], 2, '.', '') . '</td>
            <td>$' . number_format($row["delivery_charge"], 2, '.', '') . '</td>
            <td>$' . number_format($row["total_charge"], 2, '.', '') . '</td>
        </tr>';

        echo '</table>
            <br>';

        // 2. Get order items for specified order id
        $query = 'SELECT oi.prod_id, p.image_url, p.name AS product_name, b.name AS brand_name, sm.name AS supermarket_name, oi.quantity, p.price FROM Order_Items AS oi
                LEFT JOIN Product AS p ON oi.prod_id = p.id 
                LEFT JOIN SMart.Order as o ON o.id = oi.order_id 
                LEFT JOIN Brand AS b on p.brand_id=b.id 
                LEFT JOIN Supermarket AS sm ON p.sm_id=sm.id
                WHERE oi.order_id = ?';
        $result = payload_deliver($conn, $query, "i", $params = array($id));

        // Print Table Headers (3)
        echo '<h4>Order Item Details: </h4>
            <div style="max-height: 300px; overflow: auto;"><table class="carttable" style="font-size: 1.4rem;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Product ID</th>
                    <th colspan="2">Product</th>
                    <th>Brand</th>
                    <th>Supermarket</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                </tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr style="text-align: center;"">
                <td>' . $row["prod_id"] . '</td>
                <td><image src="' . $row["image_url"] . '" width="32" height="32"></td>
                <td>' . $row["product_name"] . '</td>
                <td>' . $row["brand_name"] . '</td>
                <td>' . $row["supermarket_name"] . '</td>
                <td>' . $row["quantity"] . '</td>
                <td>$' . number_format($row["price"], 2, '.', '') . '</td>
            </tr>';
        }

        echo '</table></div>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////        
    default:
        break;
}

$conn->close();
?>
