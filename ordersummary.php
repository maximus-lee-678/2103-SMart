
<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart - Order Summay</title>
        <?php
        include "head.php";
        ?>
        <body>
            <!-- header section starts  -->
            <?php include "nav.php"; ?>
            <!-- header section ends -->

            
            
            
            <section class="myShoppingCart cartcontainer">
                
                <h1 class="carttitle">
                    <span>My Shopping Cart > Order Summary </span>
                </h1>
                
                <table class="carttable" style="font-size: 1.4rem;">
                    <tr style="text-align: center; background: #6d6875; color: white;">
                        <th colspan="3"><b>Order Summary</b></th>
                    </tr>
                    <tr>
                        <td>
                            <img src="image/home-img-1.png" class="imagesize"/>
                        </td>
                        <td>
                            Chocolate
                            <br>
                            Qty: 2
                        </td>
                        <td>$5.60</td>
                    </tr>
                    <tr>
                        <td>
                            <img src="image/home-img-2.png" class="imagesize"/>
                        </td>
                        <td>
                            Banana
                            <br>
                            Qty: 3
                        </td>
                        <td>$3.60</td>
                    </tr>
                </table>
                
                
                <table class="carttable" style="font-size: 1.4rem; margin-top: 40px;">
                    <tr style="text-align: center; background: white;">
                        <td>Subtotal: </td>
                        <td colspan="2">$200</td>
                    </tr>
                    <tr style="text-align: center; background: white;">
                        <td>Delivery Fee: </td>
                        <td colspan="2">$12 (Standard Price)</td>
                    </tr>
                    <tr style="text-align: center; background: white;">
                        <td>Total: </td>
                        <td colspan="2">$212</td>
                    </tr>
                </table>
                
                <a href="ordersummary.php" style="width: 100%; margin-top: 40px; text-align: center;" class="btn">Confirm Order</a>
                
            </section>
            

            <!-- footer section starts  -->
            <?php include "footer.php"; ?>
            <!-- footer section ends -->
        </body>
</html>