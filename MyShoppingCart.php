<!DOCTYPE html>
<html lang="en">
    <title>Login</title>
    <?php
    session_start();
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->

        

        
        <section class="myShoppingCart cartcontainter">

    <h1 class="title"><span>My Shopping Cart</span> <a href="#">Empty Cart</a> </h1>

    <div class="box-container">

        
        <table>
            <tr style="background: #6D6875; color: white;">
                <th>ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price (in $)</th>
                <th>Total (in $)</th>
                <th>Remove</th>
            </tr>
            <tr>
                <td>1</td>
                <td>
                    Chocolate<br>
                    <img src="image/home-img-1.png" alt="Edit Item" class="imagesize"/>
                </td>
                <td>
                    <input readonly="true" class="numbertextbox" type="number" value="2" min="1" max="100">
                </td>
                <td>$2.80</td>
                <td>$5.60</td>
                <th>
                    <a href="#"><img src="image/edit.png" alt="Edit Item" class="iconsize"/></a>
                    <a href="#"><img src="image/bin.png" alt="Remove Item" class="iconsize"/></a>
                </th>
            </tr>
            <tr>
                <td>2</td>
                <td>
                    Mushrooms<br>
                    <img src="image/home-img-2.png" alt="Edit Item" class="imagesize"/>
                </td>
                <td><input readonly="true" class="numbertextbox" type="number" value="3" min="1" max="100"></td>
                <td>$3.40</td>
                <td>$10.20</td>
                <th>
                    <a href="#"><img src="image/edit.png" alt="Edit Item" class="iconsize"/></a>
                    <a href="#"><img src="image/bin.png" alt="Remove Item" class="iconsize"/></a>
                </th>
            </tr>
            <tr>
                <td colspan="2">Total:</td>
                <td>5</td>
                <td colspan="2">$15.80</td>
                <td></td>
            </tr>
        </table>
        
        

        
    </div>
    
    <input type="submit" style="width: 100%; margin-top: 30px;" name="proceedtocheckout" value="Proceed to Checkout" class="btn">


</section>

        
        
        
        

        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>