<?php

include "helper-functions.php";

if (isset($_GET['offset'])) {
    $offset = sanitize_input($_GET['offset']);
    $category = sanitize_input($_GET['category']);
    $filter = sanitize_input($_GET['filter']);
    $filterWild = "%{$filter}%";
    $fetchData = fetch_data($offset, $category, $filterWild);
    $displayData = display_data($fetchData);
    echo $displayData;
}

function fetch_data($offset, $category, $filter) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);
    // Check connection    
    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    } else {
        if (!$category) {
            $stmt = $conn->prepare("SELECT * FROM Product WHERE name LIKE ? and active = 1 and quantity <> 0 ORDER BY id DESC LIMIT 20 OFFSET ?");
            $stmt->bind_param("si", $filter, $offset);
        } else {
            $stmt = $conn->prepare("SELECT * FROM Product "
                    . "WHERE cat_id = ? and name LIKE ? and active = 1 and quantity <> 0 "
                    . "ORDER BY id DESC LIMIT 20 "
                    . "OFFSET ?");
            $stmt->bind_param("ssi", $category, $filter, $offset);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_all(MYSQLI_ASSOC);
            return $row;
        } else {
            return "No records found";
        }
    }
}

function display_data($displayData) {
    if (is_array($displayData)) {
        $output = "";
        $lastId = $displayData[0]['id'];
        $offset = sizeof($displayData);
        foreach ($displayData as $data) {
            $output .= '<div class="box" id="' . $data['id'] . '">
                            <div class="icons">
                                <a href="#" class="fas fa-shopping-cart add-to-cart"></a>
                                <a href="#" class="fas fa-heart"></a>
                                <a href="product.php?id=' . $data['id'] . '" class="fas fa-eye"></a>
                            </div>
                        <div class="image">
                            <img src="' . $data['image_url'] . '" alt="">
                        </div>
                        <div class="content">
                            <h3>' . $data['name'] . '</h3>
                            <div class="price">$' . $data['price'] . '</div>
                        </div>
                    </div>';
        }

        return json_encode(array("data" => $output, "lastId" => $lastId, "offset" => $offset));
    }
    return;
}

?>