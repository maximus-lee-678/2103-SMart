<html lang="en">
    <title>Shop</title>
    <?php
    session_start();

    include "helper-functions.php";
    include "head.php";

    if (!isset($_GET["id"])) {
        header("refresh: 0; url=shop.php");
        exit;
    }

    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        $captionText = "Connection failed: " . $conn->connect_error;
    } else {
        //form parameters
        $product_id = sanitize_input($_GET["id"]);

        // Prepare the statement:
        $stmt = $conn->prepare("SELECT p.name AS product_name, p.display_unit, p.price, p.image_url, p.quantity, p.cat_id, c.name AS category_name, s.name AS supermarket_name, b.name AS brand_name FROM Product AS p INNER JOIN Supermarket AS s ON p.sm_id = s.id INNER JOIN Category AS c ON p.cat_id = c.id INNER JOIN Brand AS b ON p.brand_id = b.id WHERE p.id = ? AND p.active = 1");
        // Bind & execute the query statement:
        $stmt->bind_param("i", $product_id);
        // execute the query statement:
        if (!$stmt->execute()) {
            $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        } else {
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = mysqli_fetch_assoc($result);
                $product_name = $row["product_name"];
                $display_unit = $row["display_unit"];
                $price = $row["price"];
                $image_url = $row["image_url"];
                $category_name = $row["category_name"];
                $quantity = $row["quantity"];
                $category_id = $row["cat_id"];
                $supermarket_name = $row["supermarket_name"];
                $brand_name = $row["brand_name"];

                $stmt->close();

                $stmt = $conn->prepare("SELECT * FROM Product_Test WHERE MATCH(name) AGAINST (? IN NATURAL LANGUAGE MODE) AND cat_id = ? ORDER BY RAND() LIMIT 5;");

                $stmt->bind_param("ss", $product_name, $category_id);

                if (!$stmt->execute()) {
                    $captionText = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                } else {
                    $result = $stmt->get_result();
                }
            }
            $stmt->close();
        }
    }
    $conn->close();
    ?>    
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
        <div class="heading">
            <h1>Product</h1>
            <p> <a href="shop.php">Shop >></a> Product </p>
        </div>

        <section class="individual">
            <h1 class="title" id="<?php echo $product_id; ?>"><?php echo $product_name; ?></h1> <!--Product Name-->

            <div class="box-container">
                <div class="productimage">
                    <img src="<?php echo $image_url; ?>" alt=""></img> <!--Image-->
                </div>
                <div class="infobox">
                    <p class="mart"><?php echo $supermarket_name; ?></p> <!--Supermarket-->
                    <h2><?php echo $category_name; ?></h2> <!--Category-->
                    <h3>$<?php echo number_format($price, 2, '.', ''); ?></h3> <!--Price-->
                    <h4>
                        Unit: <?php echo $display_unit; ?> <!--Unit-->
                        <br>
                        Quantity: <?php echo $quantity; ?> <!--Quantity-->
                        <br>
                        Brand: <?php echo $brand_name; ?> <!--Brand-->
                    </h4> <!--Brand-->
                    <a href="#" class="fas fa-shopping-cart add-to-cart">Add to cart</a>
                    <p><br>rating? review?</p> <!--idk lol-->
                    <!--if got purchase history-->
                </div>


            </div>

        </section>
        <section class ="products">
            <div class="box-container" id = "product-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <div class="box" id="<?= $row["id"] ?>">
                            <div class="icons">
                                <a href="#" class="fas fa-shopping-cart add-to-cart"></a>
                                <a href="#" class="fas fa-heart"></a>
                                <a href="productPage.php?id=<?= $row["id"] ?>" class="fas fa-eye"></a>
                            </div>
                            <div class="image">
                                <img src="<?= $row["image_url"] ?>" alt="">
                            </div>
                            <div class="content">
                                <h3><?= $row["name"] ?></h3>
                                <div class="price">$<?= $row["price"] ?></div>
                                <div class="stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="far fa-star"></i>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>

            </div>
        </section>
        <?php include "footer.php"; ?>
    </body>
</html>




