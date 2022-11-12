<?php

include "helper-functions.php";

if (($_SERVER['REQUEST_METHOD'] != 'POST')) {
    header("refresh: 0; url=Employee_Home.php");
    exit;
}

if (!isset($_POST["operation"])) {
    exit;
}

$operation = sanitize_input($_POST["operation"]);

$config = parse_ini_file('../../private/db-config.ini');
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

// Check connection
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    exit();
}

// Form Parameters
$page = sanitize_input($_POST["page"]);
$search = sanitize_input($_POST["search"]);
$search_with_wildcard = "%{$search}%";
if (isset($_POST["addit_args"])) {
    $addit_args = sanitize_input($_POST["addit_args"]);
} else {
    $addit_args = 0;
}

$limit = 10;
$offset = ($page - 1) * $limit;

$staff_id = 5;

switch ($operation) {
    case "staff":

        // Print search bar
        echo '<div class="inputBox">
            <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Name..." value=' . $search . '>
            <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
            <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
        </div>';

        // 1. Get staff count, convert to number of pages
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Staff WHERE CONCAT(CONCAT(first_name, " "), last_name) LIKE ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("is", $limit, $search_with_wildcard);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $total_pages = $row["total_pages"];

        // Print Page Count
        if ($total_pages == 0) {
            echo '<span>Page 0 of 0</span>';
        } else {
            echo '<span>Page ';
            if ($page > 1) {
                echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
            }
            echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
            if ($page < $total_pages) {
                echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
            }
            echo '</span>';
        }

        // Print table headers
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th style="width:20%">Role</th>
                    <th colspan="2">Options</th>
                </tr>';

        // 2. Get staff
        $query = 'SELECT s.id AS staff_id, s.first_name, s.last_name, s.email, s.telephone, IFNULL(GROUP_CONCAT(sr.name ORDER BY sr.name ASC SEPARATOR ", "), "*None*") AS roles 
                FROM Staff AS s LEFT JOIN (SELECT * FROM RoleAssignment AS ra INNER JOIN Role AS r ON ra.role_id = r.id) AS sr ON sr.staff_id = s.id 
                WHERE CONCAT(CONCAT(s.first_name, " "), s.last_name) LIKE ? GROUP BY s.id LIMIT ? OFFSET ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        // No rows
        if ($result->num_rows == 0) {
            echo '<tr style="text-align: center;">
                <td colspan="6">No results!</td>
                </tr>';
        }
        // Print table rows
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                    <td>' . $row["staff_id"] . '</td>
                    <td>' . $row["first_name"] . ' ' . $row["last_name"] . '</td>
                    <td>' . $row["email"] . '</td>
                    <td>' . $row["telephone"] . '</td>
                    <td>' . $row["roles"] . '</td>
                    <td><a href="#" id="edit_staff_' . $row["staff_id"] . '" style="color: #bac34e;">Edit</a></td>
                    <td><a href="#" id="delete_staff_' . $row["staff_id"] . '" style="color: #bac34e;">Delete</a></td>
                </tr>';
            }
        }

        echo '</table>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "product":

        // Print search bar
        echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Product Name..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

        // 1. Get product count, convert to number of pages
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Product WHERE active = 1 AND name LIKE ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("is", $limit, $search_with_wildcard);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $total_pages = $row["total_pages"];

        // Print Page Count
        if ($total_pages == 0) {
            echo '<span>Page 0 of 0</span>';
        } else {
            echo '<span>Page ';
            if ($page > 1) {
                echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
            }
            echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
            if ($page < $total_pages) {
                echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
            }
            echo '</span>';
        }

        // Print Table Headers
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>ID</th>
                    <th colspan="2">Product</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Supermarket</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th colspan="2">Last Restocked</th>
                    <th colspan="2">Options</th>
                </tr>';

        // 2. Get products
        $query = 'SELECT p.id, p.image_url, p.name, p.display_unit, p.price, p.quantity, sm.name AS supermarket_name, c.name AS category_name, b.name AS brand_name, IFNULL(p.last_restocked_at, "NA")  AS last_restocked_at, IFNULL(CONCAT(CONCAT(s.first_name, " "), s.last_name),"NA") AS last_restocked_by 
            FROM Product AS p LEFT JOIN Supermarket AS sm ON p.sm_id=sm.id LEFT JOIN Category AS c ON p.cat_id=c.id LEFT JOIN Brand AS b on p.brand_id=b.id LEFT JOIN Staff AS s ON p.last_restocked_by=s.id
            WHERE p.active = 1 AND p.name LIKE ? LIMIT ? OFFSET ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        // No rows
        if ($result->num_rows == 0) {
            echo '<tr style="text-align: center;">
                <td colspan="12">No results!</td>
                </tr>';
        }
        // Print table rows
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                        <td>' . $row["id"] . '</td>
                        <td><image src="' . $row["image_url"] . '" width="32" height="32"></td>
                        <td>' . $row["name"] . '</td>
                        <td>' . $row["display_unit"] . '</td>
                        <td>$' . $row["price"] . '</td>
                        <td>' . $row["quantity"] . '</td>
                        <td>' . $row["supermarket_name"] . '</td>
                        <td>' . $row["category_name"] . '</td>
                        <td>' . $row["brand_name"] . '</td>
                        <td>' . $row["last_restocked_at"] . '</td>
                        <td>' . $row["last_restocked_by"] . '</td>
                        <td><a href="#" id="edit_product_' . $row["id"] . '" style="color: #bac34e;">Edit</a></td>
                        <td><a href="#" id="delete_product_' . $row["id"] . '" style="color: #bac34e;">Delete</a></td>
                </tr>';
            }
        }

        echo '</table>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "supermarket":

        // Print search bar
        echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Supermarket Name..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

        // 1. Get supermarket count, convert to number of pages
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Supermarket WHERE name LIKE ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("is", $limit, $search_with_wildcard);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $total_pages = $row["total_pages"];

        // Print Page Count
        if ($total_pages == 0) {
            echo '<span>Page 0 of 0</span>';
        } else {
            echo '<span>Page ';
            if ($page > 1) {
                echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
            }
            echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
            if ($page < $total_pages) {
                echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
            }
            echo '</span>';
        }

        // Print Table Headers
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>ID</th>
                    <th>Name</th>
                    <th colspan="2">Options</th>
                </tr>';

        // 2. Get products
        $query = 'SELECT id, name FROM Supermarket WHERE name LIKE ? LIMIT ? OFFSET ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        // No rows
        if ($result->num_rows == 0) {
            echo '<tr style="text-align: center;">
                <td colspan="4">No results!</td>
                </tr>';
        }
        // Print table rows
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                        <td>' . $row["id"] . '</td>
                        <td>' . $row["name"] . '</td>
                        <td><a href="#" id="edit_supermarket_' . $row["id"] . '" style="color: #bac34e;">Edit</a></td>
                        <td><a href="#" id="delete_supermarket_' . $row["id"] . '" style="color: #bac34e;">Delete</a></td>
                </tr>';
            }
        }

        echo '</table>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "category":

        // Print search bar
        echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Category Name..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

        // 1. Get category count, convert to number of pages
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Category WHERE name LIKE ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("is", $limit, $search_with_wildcard);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $total_pages = $row["total_pages"];

        // Print Page Count
        if ($total_pages == 0) {
            echo '<span>Page 0 of 0</span>';
        } else {
            echo '<span>Page ';
            if ($page > 1) {
                echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
            }
            echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
            if ($page < $total_pages) {
                echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
            }
            echo '</span>';
        }

        // Print Table Headers
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th colspan="2">Options</th>
                </tr>';

        // 2. Get products
        $query = 'SELECT id, name, description FROM Category WHERE name LIKE ? LIMIT ? OFFSET ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        // No rows
        if ($result->num_rows == 0) {
            echo '<tr style="text-align: center;">
                <td colspan="5">No results!</td>
                </tr>';
        }
        // Print table rows
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                        <td>' . $row["id"] . '</td>
                        <td>' . $row["name"] . '</td>
                        <td>' . $row["description"] . '</td>
                        <td><a href="#" id="edit_category_' . $row["id"] . '" style="color: #bac34e;">Edit</a></td>
                        <td><a href="#" id="delete_category_' . $row["id"] . '" style="color: #bac34e;">Delete</a></td>
                </tr>';
            }
        }

        echo '</table>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "brand":

        // Print search bar
        echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Brand Name..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

        // 1. Get category count, convert to number of pages
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Brand WHERE name LIKE ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("is", $limit, $search_with_wildcard);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $total_pages = $row["total_pages"];

        // Print Page Count
        if ($total_pages == 0) {
            echo '<span>Page 0 of 0</span>';
        } else {
            echo '<span>Page ';
            if ($page > 1) {
                echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
            }
            echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
            if ($page < $total_pages) {
                echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
            }
            echo '</span>';
        }

        // Print Table Headers
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>ID</th>
                    <th>Name</th>
                    <th colspan="2">Options</th>
                </tr>';

        // 2. Get products
        $query = 'SELECT id, name FROM Brand WHERE name LIKE ? LIMIT ? OFFSET ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        // No rows
        if ($result->num_rows == 0) {
            echo '<tr style="text-align: center;">
                <td colspan="4">No results!</td>
                </tr>';
        }
        // Print table rows
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                        <td>' . $row["id"] . '</td>
                        <td>' . $row["name"] . '</td>
                        <td><a href="#" id="edit_brand_' . $row["id"] . '" style="color: #bac34e;">Edit</a></td>
                        <td><a href="#" id="delete_brand_' . $row["id"] . '" style="color: #bac34e;">Delete</a></td>
                </tr>';
            }
        }

        echo '</table>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "stock":

        // Print Option Selection
        echo '<div style="margin-bottom: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label style="width: 32%">Quantity Sort By: </label>
                </div>
                <div class="inputBox" id="dropdown_stock">
                    <select required style="width: 48%; color: #666;" name="statusfilter" id="statusfilter" class="box type-dropdown">
                        <option value="0"' . (($addit_args == 0) ? ' selected' : '') . '>None</option>
                        <option value="1"' . (($addit_args == 1) ? ' selected' : '') . '>Ascending</option>
                        <option value="2"' . (($addit_args == 2) ? ' selected' : '') . '>Descending</option>
                    </select>
                </div>
            </div>';

        // Print search bar
        echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Product Name..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

        // 1. Get product count, convert to number of pages
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Product WHERE active = 1 AND name LIKE ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("is", $limit, $search_with_wildcard);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $total_pages = $row["total_pages"];

        // Print Page Count
        if ($total_pages == 0) {
            echo '<span>Page 0 of 0</span>';
        } else {
            echo '<span>Page ';
            if ($page > 1) {
                echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
            }
            echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
            if ($page < $total_pages) {
                echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
            }
            echo '</span>';
        }

        // Print Table Headers
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>ID</th>
                    <th colspan="2">Product</th>
                    <th>Unit</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Supermarket</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th colspan="2">Last Restocked</th>
                    <th>Options</th>
                </tr>';

        // 2. Get products
        $query = 'SELECT p.id, p.image_url, p.name, p.display_unit, p.price, p.quantity, sm.name AS supermarket_name, c.name AS category_name, b.name AS brand_name, IFNULL(p.last_restocked_at, "NA")  AS last_restocked_at, IFNULL(CONCAT(CONCAT(s.first_name, " "), s.last_name),"NA") AS last_restocked_by 
                FROM Product AS p LEFT JOIN Supermarket AS sm ON p.sm_id=sm.id LEFT JOIN Category AS c ON p.cat_id=c.id LEFT JOIN Brand AS b on p.brand_id=b.id LEFT JOIN Staff AS s ON p.last_restocked_by=s.id
                WHERE p.active = 1 AND p.name LIKE ? ' . (($addit_args != 0) ? (($addit_args == 1) ? 'ORDER BY p.quantity ASC' : 'ORDER BY p.quantity DESC') : '') . ' LIMIT ? OFFSET ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        // No rows
        if ($result->num_rows == 0) {
            echo '<tr style="text-align: center;">
                <td colspan="11">No results!</td>
                </tr>';
        }
        // Print table rows
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                        <td>' . $row["id"] . '</td>
                        <td><image src="' . $row["image_url"] . '" width="32" height="32"></td>
                        <td>' . $row["name"] . '</td>
                        <td>' . $row["display_unit"] . '</td>
                        <td>$' . $row["price"] . '</td>
                        <td>' . $row["quantity"] . '</td>
                        <td>' . $row["supermarket_name"] . '</td>
                        <td>' . $row["category_name"] . '</td>
                        <td>' . $row["brand_name"] . '</td>
                        <td>' . $row["last_restocked_at"] . '</td>
                        <td>' . $row["last_restocked_by"] . '</td>
                        <td><a href="#" id="edit_product_' . $row["id"] . '" style="color: #bac34e;">Edit</a></td>
                </tr>';
            }
        }

        echo '</table>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "pack":

        // 1. Check if current user has claimed a packing task
        $query = 'SELECT * FROM Order_Status WHERE status_id = 2 AND created_by = ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("i", $staff_id);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Print Table Headers (1)
            echo '<h4>Your Task: </h4>
                <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Order ID</th>
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                </tr>';

            // 2.1.1. Found claimed packing task, load info
            $query = 'SELECT os.order_id, CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, CONCAT(c.first_name, " ", c.last_name) AS cust_name FROM Order_Status AS os 
                    LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer AS c ON o.cust_id=c.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id 
                    WHERE os.status_id = 2 AND os.created_by = ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);

            // Bind & execute the query statement:
            $stmt->bind_param("i", $staff_id);
            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.1.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();
            $row = mysqli_fetch_assoc($result);

            //Store order ID
            $order_id = $row["order_id"];

            // Print table rows (1)
            echo '<tr style="text-align: center;">
                  <td>' . $row["order_id"] . '</td>
                    <td>' . $row["cust_address"] . '</td>
                    <td>' . $row["cust_name"] . '</td>  
                </tr>';

            echo '</table>';

            // Print Table Headers (2)
            echo '<br>
                <h4>Items to Pack: </h4>
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                    <tr style="text-align: center; background: #6D6875; color: white;">
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                    </tr>';

            // 2.1.2. Load order items
            $query = 'SELECT oi.prod_id, p.name, oi.quantity FROM Order_Items AS oi INNER JOIN Product AS p ON oi.prod_id = p.id WHERE oi.order_id = ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);
            // Bind & execute the query statement:
            $stmt->bind_param("i", $order_id);
            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.1.2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();

            // Print table rows (2)
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                    <td>' . $row["prod_id"] . '</td>
                    <td>' . $row["name"] . '</td>
                    <td>' . $row["quantity"] . '</td>
                </tr>';
            }
            echo '</table>
                
                <div class="inputBox">
                    <input type="button" style="width: 98%;" id="complete_packing_' . $order_id . '" value="Complete Packing Task" class="btn">
                </div>';
        } else {

            // Print search bar
            echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Address..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

            // 2.2.1. Get pack available count, convert to number of pages
            $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Order_Status AS os
                    LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id 
                    WHERE os.status_id = 1 AND CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) LIKE ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);

            // Bind & execute the query statement:
            $stmt->bind_param("is", $limit, $search_with_wildcard);
            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.2.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();
            $row = mysqli_fetch_assoc($result);
            $total_pages = $row["total_pages"];

            // Print Page Count
            if ($total_pages == 0) {
                echo '<span>Page 0 of 0</span>';
            } else {
                echo '<span>Page ';
                if ($page > 1) {
                    echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
                }
                echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
                if ($page < $total_pages) {
                    echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
                }
                echo '</span>';
            }

            // Print Table Headers
            echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Order ID</th>
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>';

            // 2.2.2 Load available tasks
            $query = 'SELECT os.order_id, CONCAT(c.first_name, " ", c.last_name) AS cust_name, CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, s.name AS status_name FROM Order_Status AS os 
                    LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer AS c ON o.cust_id=c.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id LEFT JOIN Status AS s ON os.status_id=s.id
                    WHERE os.status_id = 1 AND CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) LIKE ? LIMIT ? OFFSET ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);

            // Bind & execute the query statement:
            $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);

            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.2.2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();

            // No rows
            if ($result->num_rows == 0) {
                echo '<tr style="text-align: center;">
                <td colspan="11">No results!</td>
                </tr>';
            }
            // Print table rows
            else {
                // Print table rows
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr style="text-align: center;">
                    <td>' . $row["order_id"] . '</td>
                    <td>' . $row["cust_address"] . '</td>
                    <td>' . $row["cust_name"] . '</td>
                    <td>' . $row["status_name"] . '</td> 
                    <td><a href="#" id="edit_pack_' . $row["order_id"] . '" style="color: #bac34e;">Edit</a></td>
                </tr>';
                }
            }

            echo '</table>';
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "delivery":

        // 1. Check if current user has claimed a packing task
        $query = 'SELECT * FROM Order_Status WHERE status_id = 4 AND created_by = ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        $stmt->bind_param("i", $staff_id);
        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // Print Table Headers (1)
            echo '<h4>Your Task: </h4>
                <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Order ID</th>
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                </tr>';

            // 2.1.1. Found claimed delivery task, load info
            $query = 'SELECT os.order_id, CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, CONCAT(c.first_name, " ", c.last_name) AS cust_name FROM Order_Status AS os 
                    LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer AS c ON o.cust_id=c.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id 
                    WHERE os.status_id = 4 AND os.created_by = ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);

            // Bind & execute the query statement:
            $stmt->bind_param("i", $staff_id);
            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.1.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();
            $row = mysqli_fetch_assoc($result);

            //Store order ID
            $order_id = $row["order_id"];

            // Print table rows (1)
            echo '<tr style="text-align: center;">
                  <td>' . $row["order_id"] . '</td>
                    <td>' . $row["cust_address"] . '</td>
                    <td>' . $row["cust_name"] . '</td>  
                </tr>';

            echo '</table>';

            // Print Table Headers (2)
            echo '<br>
                <h4>Items to Pack: </h4>
                    <table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                    <tr style="text-align: center; background: #6D6875; color: white;">
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                    </tr>';

            // 2.1.2. Load delivery items
            $query = 'SELECT oi.prod_id, p.name, oi.quantity FROM Order_Items AS oi INNER JOIN Product AS p ON oi.prod_id = p.id WHERE oi.order_id = ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);
            // Bind & execute the query statement:
            $stmt->bind_param("i", $order_id);
            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.1.2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();

            // Print table rows (2)
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                    <td>' . $row["prod_id"] . '</td>
                    <td>' . $row["name"] . '</td>
                    <td>' . $row["quantity"] . '</td>
                </tr>';
            }
            echo '</table>
                
                <div class="inputBox">
                    <input type="button" style="width: 98%;" id="complete_packing_' . $order_id . '" value="Complete Packing Task" class="btn">
                </div>';
        } else {

            // Print search bar
            echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Address..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

            // 2.2.1. Get delivery available count, convert to number of pages
            $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Order_Status AS os
                    LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id 
                    WHERE os.status_id = 3 AND CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) LIKE ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);

            // Bind & execute the query statement:
            $stmt->bind_param("is", $limit, $search_with_wildcard);
            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.2.1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();
            $row = mysqli_fetch_assoc($result);
            $total_pages = $row["total_pages"];

            // Print Page Count
            if ($total_pages == 0) {
                echo '<span>Page 0 of 0</span>';
            } else {
                echo '<span>Page ';
                if ($page > 1) {
                    echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
                }
                echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
                if ($page < $total_pages) {
                    echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
                }
                echo '</span>';
            }

            // Print Table Headers
            echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>Order ID</th>
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>';

            // 2.2.2 Load available tasks
            $query = 'SELECT os.order_id, CONCAT(c.first_name, " ", c.last_name) AS cust_name, CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, s.name AS status_name FROM Order_Status AS os 
                    LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer AS c ON o.cust_id=c.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id LEFT JOIN Status AS s ON os.status_id=s.id
                    WHERE os.status_id = 3 AND CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) LIKE ? LIMIT ? OFFSET ?';

            // Prepare the statement:
            $stmt = $conn->prepare($query);

            // Bind & execute the query statement:
            $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);

            // execute the query statement:
            if (!$stmt->execute()) {
                echo "Failed while executing query [2.2.2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                $stmt->close();
                $conn->close();
                exit();
            }

            $result = $stmt->get_result();

            // No rows
            if ($result->num_rows == 0) {
                echo '<tr style="text-align: center;">
                <td colspan="11">No results!</td>
                </tr>';
            }
            // Print table rows
            else {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr style="text-align: center;">
                    <td>' . $row["order_id"] . '</td>
                    <td>' . $row["cust_address"] . '</td>
                    <td>' . $row["cust_name"] . '</td>
                    <td>' . $row["status_name"] . '</td> 
                    <td><a href="#" id="edit_delivery_' . $row["order_id"] . '" style="color: #bac34e;">Edit</a></td>
                </tr>';
                }
            }


            echo '</table>';
        }

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    case "order_all":
        // Print Option Selection
        echo '<div style="margin-bottom: 20px;">
                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                    <label style="width: 32%">Filter By: </label>
                </div>
                <div class="inputBox" id="dropdown_order_all">
                    <select required style="width: 48%; color: #666;" name="statusfilter" id="statusfilter" class="box type-dropdown">
                        <option value="0"' . (($addit_args == 0) ? ' selected' : '') . '>All</option>
                        <option value="1"' . (($addit_args == 1) ? ' selected' : '') . '>Waiting for Packer</option>
                        <option value="2"' . (($addit_args == 2) ? ' selected' : '') . '>Packing in Progress</option>
                        <option value="3"' . (($addit_args == 3) ? ' selected' : '') . '>Packing Completed</option>
                        <option value="4"' . (($addit_args == 4) ? ' selected' : '') . '>Delivery in Process</option>
                        <option value="5"' . (($addit_args == 5) ? ' selected' : '') . '>Delivery Completed</option>
                        <option value="6"' . (($addit_args == 6) ? ' selected' : '') . '>Order Completed</option>
                    </select>
                </div>
            </div>';

        // Print search bar
        echo '<div class="inputBox">
                <input type="text" class="box type-search" style="width: 48%;" name="search-field" placeholder="Enter Customer Address..." value=' . $search . '>
                <input type="button" class="btn" style="width: 22%;" name="search-button" value="Search">
                <input type="button" class="btn" style="width: 22%;" name="clear-button" value="Clear">
            </div>';

        // 1. Get order status count, convert to number of pages
        $query = 'SELECT CEILING(COUNT(*)/?) AS total_pages FROM Order_Status AS os
                LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id 
                WHERE ' . (($addit_args != 0) ? 'os.status_id = ? AND' : '') . ' CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) LIKE ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        if ($addit_args == 0) {
            $stmt->bind_param("is", $limit, $search_with_wildcard);
        } else {
            $stmt->bind_param("iis", $limit, $addit_args, $search_with_wildcard);
        }

        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [1]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();
        $row = mysqli_fetch_assoc($result);
        $total_pages = $row["total_pages"];

        // Print Page Count
        if ($total_pages == 0) {
            echo '<span>Page 0 of 0</span>';
        } else {
            echo '<span>Page ';
            if ($page > 1) {
                echo '<span><a class="prev-page" href="#" style="color: #0000ff;"><-</a></span>';
            }
            echo '<span class="current-page">' . $page . '</span> of <span id="pages">' . $total_pages . '</span>';
            if ($page < $total_pages) {
                echo '<span><a class="next-page" href="#" style="color: #0000ff;">-></a></span>';
            }
            echo '</span>';
        }

        // Print Table Headers
        echo '<table class="carttable" style="font-size: 1.4rem; margin-top: 30px;">
                <tr style="text-align: center; background: #6D6875; color: white;">
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Customer Address</th>
                    <th>Customer Name</th>
                    <th>Staff ID</th>
                    <th>Staff Name</th>
                    <th>Created At</th>
                    <th>Status</th>
                    <th>Options</th>
                </tr>';

        // 2. Get order status

        $query = 'SELECT os.id, os.order_id, CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) AS cust_address, CONCAT(c.first_name, " ", c.last_name) AS cust_name, 
                os.created_by AS staff_id, CONCAT(sta.first_name, " ", sta.last_name) AS staff_name, os.created_at, stat.name AS status_name
                FROM Order_Status AS os
                LEFT JOIN SMart.Order AS o ON os.order_id=o.id LEFT JOIN Customer AS c ON o.cust_id=c.id LEFT JOIN Customer_Address AS ca ON o.address_id=ca.id 
                LEFT JOIN Status AS stat ON os.status_id=stat.id LEFT JOIN Staff AS sta ON os.created_by=sta.id
                WHERE ' . (($addit_args != 0) ? 'os.status_id = ? AND' : '') . ' CONCAT(ca.address, ", ", ca.unit_no, ", ", ca.postal_code) LIKE ? ORDER BY os.status_id ASC LIMIT ? OFFSET ?';

        // Prepare the statement:
        $stmt = $conn->prepare($query);

        // Bind & execute the query statement:
        if ($addit_args == 0) {
            $stmt->bind_param("sii", $search_with_wildcard, $limit, $offset);
        } else {
            $stmt->bind_param("isii", $addit_args, $search_with_wildcard, $limit, $offset);
        }

        // execute the query statement:
        if (!$stmt->execute()) {
            echo "Failed while executing query [2]: Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit();
        }

        $result = $stmt->get_result();

        // No rows
        if ($result->num_rows == 0) {
            echo '<tr style="text-align: center;">
                <td colspan="11">No results!</td>
                </tr>';
        }
        // Print table rows
        else {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr style="text-align: center;">
                        <td>' . $row["id"] . '</td>
                        <td>' . $row["order_id"] . '</td>
                        <td>' . $row["cust_address"] . '</td>
                        <td>' . $row["cust_name"] . '</td>
                        <td>' . $row["staff_id"] . '</td>
                        <td>' . $row["staff_name"] . '</td>
                        <td>' . $row["created_at"] . '</td>
                        <td>' . $row["status_name"] . '</td>
                        <td><a href="#" id="edit_order_all_' . $row["id"] . '" style="color: #bac34e;">Edit</a></td>
                </tr>';
            }
        }

        echo '</table>';

        break;
////////////////////////////////////////////////////////////////////////////////////////////////////
    default:
        break;
}

$stmt->close();
$conn->close();
?>
