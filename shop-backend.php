<?php

if (isset($_GET['lastId'])) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);

    $lastId = sanitize_input($_GET['lastId']);
    $category = sanitize_input($_GET['category']);
    $fetchData = fetch_data($lastId, $category);
    $displayData = display_data($fetchData);
    echo $displayData;
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function fetch_data($lastId, $category) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);
    // Check connection    
    if ($conn->connect_error) {
        return "Connection failed: " . $conn->connect_error;
    } else {
        if (!$category) {
            $stmt = $conn->prepare("SELECT * FROM Product WHERE id > ? and active = 1 ORDER BY id LIMIT 20");
            $stmt->bind_param("i", $lastId);
        } else {
            $stmt = $conn->prepare("SELECT * FROM Product WHERE id > ? and active = 1 and cat_id = ? ORDER BY id LIMIT 20");
            $stmt->bind_param("is", $lastId, $category);
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
        $lastId = 0;
        foreach ($displayData as $data) {
            $output .= '<div class="box" id="'. $data['id'] .'">
                            <div class="icons">
                                <a href="#" class="fas fa-shopping-cart"></a>
                                <a href="#" class="fas fa-heart"></a>
                                <a href="#" class="fas fa-eye"></a>
                            </div>
                        <div class="image">
                            <img src="' . $data['image_url'] . '" alt="">
                        </div>
                        <div class="content">
                            <h3>' . $data['name'] . '</h3>
                            <div class="price">$' . $data['price'] . '</div>
                            <div class="stars">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                        </div>
                    </div>';
            $lastId = $data['id'];
        }

        return json_encode(array("data" => $output, "lastId" => $lastId));
    }
    return;
}
?>