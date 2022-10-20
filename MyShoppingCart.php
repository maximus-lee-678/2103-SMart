<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart</title>
        <?php
        include "head.php";
        ?>
        <body>
            <!-- header section starts  -->
            <?php include "nav.php"; ?>
            <!-- header section ends -->

            <section class="myShoppingCart cartcontainer">
                <h1 class="carttitle"><span>My Shopping Cart</span> <a href="#">Empty Cart</a> </h1>
                <div class="box-container" id="cart-contents">
                </div>
                <input type="submit" style="width: 100%; margin-top: 30px;" name="proceedtocheckout" value="Proceed to Checkout" class="btn">
            </section>

            <!-- footer section starts  -->
            <?php include "footer.php"; ?>
            <!-- footer section ends -->
        </body>
</html>