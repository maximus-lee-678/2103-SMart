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

            <div class="box-container" id = "product-list">

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
            <div class = "product-list-tail" id = "0" style='display: none;'></div>

        </section>
        <?php include "footer.php"; ?>
    </body>
</html>
