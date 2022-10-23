<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart - Order Summary</title>
    <?php
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->   

        <div class="heading">
            <h1>Cart</h1>
            <p> <a href="MyShoppingCart.php">My Shopping Cart >></a> Order Summary</p>
        </div>

        <section class="myShoppingCart cartcontainer">
            <h1 class="carttitle">
                <span>Order Summary </span>
            </h1>
            <div class="box-container" id="summary-contents" style="display: inline;">
            </div>    
        </section>      

        <section class="myShoppingCart">
            <a href="checkout.php" style="width: 100%; margin-top: 40px; text-align: center;" class="btn">Proceed to Checkout</a>
        </section>

        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>