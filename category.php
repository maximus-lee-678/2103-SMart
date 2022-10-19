<html lang="en">
    <?php
    session_start();
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>

        <div class="heading">
            <h1>Categories</h1>
            <p> <a href="shop.php">shop >></a> category </p>
        </div>

        <section class="category">
            <h1 class="title"> All <span>categories</span></h1>
            <div class="box-container">

                <?php
                $config = parse_ini_file('../../private/db-config.ini');
                $conn = new mysqli($config['servername'], $config['username'],
                        $config['password'], $config['dbname']);
                // Check connection    
                if ($conn->connect_error) {
                    echo "Connection failed: " . $conn->connect_error;
                } else {
                    $result = $conn->query("SELECT * FROM Category");
                    //Check if there are any results
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <a href="shop.php?category=<?php echo $row['id'] ?>" class="box">
                                <img src="image/category/<?php echo $row['id'] ?>.png" alt="">
                                <h3><?php echo $row['name'] ?></h3>
                            </a>
                            <?php
                        }
                    } else {
                        echo "Sorry nothing to show!";
                    }
                }
                ?>                
            </div>
        </section>
        <?php include "footer.php"; ?>
    </body>
</html>
