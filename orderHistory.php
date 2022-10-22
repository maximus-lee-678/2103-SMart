<!DOCTYPE html>
<html lang="en">
    <title>Shop</title>
    <?php
    session_start();
    include "head.php";
    ?>    
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->
        <div class="heading">
            <h1>order history</h1>
            <p> <a href="home.php">home >></a> order history </p>
        </div>

        <section class="history">
            <h1 class="title"> order <span>history</span> </h1>

            <!--if got purchase history-->
<!--            <div class="box-container">

                <div class="box">
                    <div class="info">
                        <h3>Mart Name</h3>
                        <p>Purchased Date: </p>
                        <p>Purchased QTY: </p>
                        <p>Order Process: </p>
                    </div>
                    <div class="image">
                        <img src="image/product-1.jpg" alt="">
                    </div>
                    <div class="content">
                        <h3>organic food</h3>
                        <div class="price">$18.99</div>
                        <div class="stars">
                            <p> this one will lead user to the product page </p>
                            <a href="#">Buy Again</a>
                        </div>
                    </div>
                </div>-->

                <!--if no purchase history-->
                <div class = "resultContainer2">
                    <div class ="content">
                        <h2>no purchased history!</h2>
                        <h4>press <a href="shop.php"> ME </a>to start shopping now!</h4>
                        <p>can uncomment the top part from orderHistory.php if there is a purchased history</p>
                    </div>
                </div>

        </section>
        <?php include "footer.php"; ?>
    </body>
</html>


