



<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart - 4. E-Receipt</title>
        <?php
        include "head.php";
        ?>
        <body>
            <!-- header section starts  -->
            <?php include "nav.php"; ?>
            <!-- header section ends --> 
            
            <div class="heading">
                <h1>Cart</h1>
                <p> <a href="MyShoppingCart.php">My Shopping Cart >></a> Order Summary  </p>
            </div>
            
            
            
            <section class="myShoppingCart cartcontainer contact">
                
                <h1 class="carttitle">
                    <span>
                        <img src="image/location.png" alt="location" class="iconsize">Delivery Address  --  
                        <img src="image/clock.png" alt="location" class="iconsize">Time Slot  --  
                        <img src="image/credit-card.png" alt="location" class="iconsize">Payment Method  --  
                        <img src="image/receipt.png" alt="location" class="iconsize"><label style="color: #ffcdb2;">E-Receipt</label>
                    </span>
                </h1>
                
                
                <div class="row">
                    
                    <form action="process_paymentmethod.php" class="register-form" method="post" name="myDeliveryDetails">
                    
                        <h3>E-Receipt</h3>
                        
                        <br>

                        
                        <div style="font-size: 2.7rem; color: #666;">
                            <img src="image/successtick.png" alt="location" class="iconsize"><label>Your Purchases were Successful!</label>
                            <br><br>
                            <img src="image/failedcross.png" alt="location" class="iconsize"><label>Something went Wrong. Please Try Again.</label>
                        </div>
                        
                        
                        
                        <table class="carttable" style="font-size: 1.4rem; margin-top: 30px; margin-bottom: 10px; color: #666;">
                            <tr style="text-align: center; background: #6D6875; color: white;">
                                    <th colspan="2">Product</th>
                                    <th>Quantity</th>
                                    <th>Price (in $)</th>
                                    <th>Total (in $)</th>
                            </tr>
                            <tr>
                                <td>
                                    <img src="image/home-img-1.png" class="imagesize"/>
                                </td>
                                <td>
                                    Chocolate
                                </td>
                                <td>
                                    2
                                </td>
                                <td>$2.50</td>
                                <td>$5</td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="image/home-img-2.png" class="imagesize"/>
                                </td>
                                <td>
                                    Banana
                                </td>
                                <td>
                                    2
                                </td>
                                <td>$5.60</td>
                                <td>$11.20</td>
                            </tr>
                        </table>
                        
                        <table class="carttable" style="font-size: 1.4rem;">
                            <tr style="text-align: center; background: white;">
                                <td>Order No: </td>
                                <td>#123456</td>
                            </tr>
                            <tr style="text-align: center; background: white;">
                                <td>Purchase Date: </td>
                                <td>22 October 2022</td>
                            </tr>
                            <tr style="text-align: center; background: white;">
                                <td>Total Spent: </td>
                                <td>$150</td>
                            </tr>
                        </table>
                        
                        
                        <div class="inputBox">
                            <a href="home.php" style="width: 98%; margin-top: 40px; margin-bottom: 20px; text-align: center;" class="btn">Back to Home</a>
                        </div>
                        
                        
                    </form>
                    
                </div>
                                
            </section>
            
            
            
            
            <!-- footer section starts  -->
            <?php include "footer.php"; ?>
            <!-- footer section ends -->
        </body>
</html>