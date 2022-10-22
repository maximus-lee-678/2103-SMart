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
            <h1>product</h1>
            <p> <a href="shop.php">shop >></a> product </p>
        </div>

        <section class="individual">
            <h1 class="title"> product name </h1>

            <!--if got purchase history-->
            <div class="box-container">
                <div class="productimage">
                    <img src="image/tired.jpg" alt=""></img>
                </div>
                <div class="infobox">
                    <p class="mart"> which mart? </p>
                    <h2> organic food </h2>
                    <h3>$18.99</h3>
                    <h4>quantity | brand</h4>
                    <a href="#" class="fas fa-shopping-cart add-to-cart">  Add to cart</a>
                    <p><br>rating? review?</p>
                </div>
            </div>

        </section>
        <?php include "footer.php"; ?>
    </body>
</html>




