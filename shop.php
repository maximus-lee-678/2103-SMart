<!DOCTYPE html>
<html lang="en">
    <title>Shop</title>
    <?php
    session_start();
    include "head.php";
    ?>    
    <script src="js/ajax-shop.js" type="text/javascript"></script>
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

                <a href="shop.php?category=fruit-vegetable" class="box">
                    <img src="image/category/fruit-vegetable.png" alt="">
                    <h3>Fruits & Vegetables</h3>
                </a>

                <a href="shop.php?category=meat-seafood" class="box">
                    <img src="image/category/meat-seafood.png" alt="">
                    <h3>Meat & Seafood</h3>
                </a>

                <a href="shop.php?category=dairy-chilled-frozen" class="box">
                    <img src="image/category/dairy-chilled-frozen.png" alt="">
                    <h3>Dairy, Chilled & Frozen</h3>
                </a>

                <a href="shop.php?category=health-beauty" class="box">
                    <img src="image/category/health-beauty.png" alt="">
                    <h3>Health & Beauty</h3>
                </a>

                <a href="shop.php?category=rice-noodles-pasta" class="box">
                    <img src="image/category/rice-noodles-pasta.png" alt="">
                    <h3>Rice, Noodles & Pasta</h3>
                </a>
            </div>
        </section>

        <section class="products">
            <h1 class="title"> All <span>products</span> </h1>

            <div class="inputBox">
                <input required style="width: 75%" id="product_search" name="product_search" type="text" placeholder="Search for products" class="box" maxlength="250">
                <input type="button" style="width: 12%" id="product_searchBtn" name="product_searchBtn" value="Search" class="btn">
                <input type="button" style="width: 12%" id="product_clearBtn" name="product_clearBtn" value="Clear" class="btn">
            </div>
            <br>

            <div class="box-container" id = "product-list"></div>
            <div class = "product-list-tail" id = "0" style='display: none;'></div>

        </section>
        <?php include "footer.php"; ?>
    </body>
</html>
