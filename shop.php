<!DOCTYPE html>
<html lang="en">
    <?php session_start();
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
        <div class="heading">
            <h1>our shop</h1>
            <p> <a href="home.php">home >></a> shop </p>
        </div>

        <section class="category">
            <h1 class="title"> our <span>category</span> <a href="category.php">view all >></a> </h1>
            <div class="box-container">

                <a href="#" class="box">
                    <img src="image/cat8.png" alt="">
                    <h3>Fruits & Vegetables</h3>
                </a>

                <a href="#" class="box">
                    <img src="image/cat10.png" alt="">
                    <h3>Meat & Seafood</h3>
                </a>

                <a href="#" class="box">
                    <img src="image/cat4.png" alt="">
                    <h3>Dairy, Chilled & Frozen</h3>
                </a>

                <a href="#" class="box">
                    <img src="image/cat9.png" alt="">
                    <h3>Health & Beauty</h3>
                </a>

                <a href="#" class="box">
                    <img src="image/cat13.png" alt="">
                    <h3>Rice, Noodles & Pasta</h3>
                </a>
            </div>
        </section>

        <section class="products">
            <h1 class="title"> All <span>products</span> </h1>

            <div class="box-container">

                <?php
                $config = parse_ini_file('../../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname']);
                // Check connection    
                if ($conn->connect_error) {
                    echo "Connection failed: " . $conn->connect_error;
                } else {
                    $result = $conn->query("SELECT * FROM Product WHERE active = 1 ORDER BY RAND() LIMIT 100");

                    //Check if there are any results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <div class="box">
                                <div class="icons">
                                    <a href="#" class="fas fa-shopping-cart"></a>
                                    <a href="#" class="fas fa-heart"></a>
                                    <a href="#" class="fas fa-eye"></a>
                                </div>
                                <div class="image">
                                    <img src="<?php echo $row['image_url'] ?>" alt="">
                                </div>
                                <div class="content">
                                    <h3><?php echo $row['name'] ?></h3>
                                    <div class="price">$<?php echo $row['price'] ?></div>
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
                    } else {
                        echo "Sorry nothing to show!";
                    }
                }
                ?>
                <!--
                                <div class="box">
                                    <div class="icons">
                                        <a href="#" class="fas fa-shopping-cart"></a>
                                        <a href="#" class="fas fa-heart"></a>
                                        <a href="#" class="fas fa-eye"></a>
                                    </div>
                                    <div class="image">
                                        <img src="image/product-1.jpg" alt="">
                                    </div>
                                    <div class="content">
                                        <h3>organic food</h3>
                                        <div class="price">$18.99</div>
                                        <div class="stars">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="far fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                -->

            </div>
        </section>
<?php include "footer.php"; ?>
    </body>
</html>
