

<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart - 2. Time Slot</title>
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
                        <img src="image/clock.png" alt="location" class="iconsize"><label style="color: #ffcdb2;">Time Slot</label>  --  
                        <img src="image/credit-card.png" alt="location" class="iconsize">Payment Method  --  
                        <img src="image/receipt.png" alt="location" class="iconsize">E-Receipt
                    </span>
                </h1>
                
                
                <div class="row">
                    
                    <form action="process_timeslot.php" class="register-form" method="post" name="myDeliveryDetails" style="">
                    
                        <h3>Time Slot</h3>
                        
                        <br><br><br>
                        
                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 98%">Delivery Date: </label>
                            </div>
                            <div class="inputBox">
                                <input type="date" style="width: 98%; color: #666;" id="setTodayDate" name="deliverydate" class="box">
                            </div>
                        </div>
                        
                        
                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 98%">Delivery Timing: </label>
                            </div>
                            <div class="inputBox">
                                <select required style="width: 98%; color: #666;" name="selecttiming" id="selecttiming" class="box">
                                    <option value="">- Select Timing -</option>
                                    <option value="Address1">8am - 10am</option>
                                    <option value="Address2">10am - 12pm</option>
                                    <option value="Address2">12pm - 2pm</option>
                                    <option value="Address2">2pm - 4pm</option>
                                    <option value="Address2">4pm - 6pm</option>
                                    <option value="Address2">6pm - 8pm</option>
                                    <option value="Address2">8pm - 10pm</option>
                                </select>
                            </div>
                        </div>
                        
                        
                        
                        <div class="inputBox">
                            <!--<input type="submit" style="width: 98%" name="saveandcontinue" value="Save and Continue" class="btn">-->
                            <a href="PaymentMethod.php" style="width: 98%; margin-top: 40px; margin-bottom: 20px; text-align: center;" class="btn">Save and Continue</a>
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