<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart - My Cart</title>
        <?php
        include "head.php";
        ?>
        <body>
            <!-- header section starts  -->
            <?php include "nav.php"; ?>
            <!-- header section ends -->
            
            <div class="heading">
                <h1>Cart</h1>
                <p> <a href="shop.php">Shop >></a> My Shopping Cart </p>
            </div>

            <section class="myShoppingCart cartcontainer">
                <h1 class="carttitle">
                    <span>My Shopping Cart</span> 
                    <a href="#" class="empty-cart">Empty Cart</a>
                </h1>
                <div class="box-container" id="cart-contents">
                </div>               
                
                <!--<input type="submit" style="width: 100%; margin-top: 30px;" name="proceedtocheckout" value="Proceed to Checkout" class="btn">-->
                <a href="ordersummary.php" style="width: 100%; margin-top: 30px; text-align: center;" class="btn">Proceed to Checkout</a>
            </section>

            <!-- footer section starts  -->
            <?php include "footer.php"; ?>
            <!-- footer section ends -->
        </body>
</html>