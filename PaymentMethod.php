


<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart - 3. Payment Method</title>
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
                        <img src="image/credit-card.png" alt="location" class="iconsize"><label style="color: #ffcdb2;">Payment Method</label>  --  
                        <img src="image/receipt.png" alt="location" class="iconsize">E-Receipt
                    </span>
                </h1>
                
                
                <div class="row">
                    
                    <form action="process_paymentmethod.php" class="register-form" method="post" name="myDeliveryDetails" style="">
                    
                        <h3>Payment Method</h3>
                        
                        <br><br><br>
                        
                        <div style="margin-bottom: 35px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 98%">Select An Existing Card: </label>
                            </div>
                            <div class="inputBox">
                                <select required style="width: 100%; color: #666;" name="selectaddress" id="selectaddress" class="box">
                                    <option value="">- Select an Card -</option>
                                    <option value="Card1">Card 1</option>
                                    <option value="Card2">Card 2</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div style="margin-bottom: 15px;">
                            <input id="user_payid" name="user_payid" type="hidden">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>Payment Type: </p>
                            </div>
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <select required style="width: 100%; color: #666;" name="user_payment_type" id="user_payment_type" class="box">
                                    <option value="">- Payment Type -</option>
                                    <option value="Visa">Visa</option>
                                    <option value="Master">Master</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        <div style="margin-bottom: 15px;">                            
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>Owner: </p>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 100%" id="user_owner" name="user_owner" type="text" placeholder="Enter your Card Name" class="box" maxlength="250" pattern="\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>Account No: </p>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 100%" id="user_accountno" name="user_accountno" type="text" placeholder="Enter your Account Number" class="box" maxlength="16" pattern="">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">Expiry Date: </label>
                                <label style="width: 49%">CCV: </label>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 49%; font-size: 1.4rem; color: #666;" id="user_cardexpirydate" name="user_cardexpirydate" type="text" placeholder="Enter your Expiry Date" class="box">
                                <input required style="width: 49%; font-size: 1.4rem; color: #666;" id="user_ccv" name="user_ccv" type="password" placeholder="Enter your CCV" class="box">
                            </div>
                        </div>
                        
                        
                        
                        <div class="inputBox">
                            <a href="#" style="width: 98%; margin-top: 40px; margin-bottom: 20px; text-align: center;" class="btn">Pay Grocery Fees</a>
                        </div>
                    
                    </form>
                    
                </div>
                                
            </section>
            
            
            <script>
                var today = new Date().toISOString().split('T')[0];
                document.getElementsByName("setTodaysDate")[0].setAttribute('min', today);
            </script>
            
            
            <!-- footer section starts  -->
            <?php include "footer.php"; ?>
            <!-- footer section ends -->
        </body>
</html>