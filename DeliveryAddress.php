
<!DOCTYPE html>
<?php
session_start();
?>

<html lang="en">
    <title>My Shopping Cart - 1. Delivery Address</title>
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
                    <!--<span>My Shopping Cart > Order Summary </span>-->
                    <br><br>
                    <span>
                        <img src="image/location.png" alt="location" class="iconsize"><label style="color: #ffcdb2;">Delivery Address</label>  --  
                        <img src="image/clock.png" alt="location" class="iconsize">Time Slot  --  
                        <img src="image/credit-card.png" alt="location" class="iconsize">Payment Method  --  
                        <img src="image/receipt.png" alt="location" class="iconsize">E-Receipt
                    </span>
                </h1>
                
                
                <div class="row">
                    
                    <form action="process_deliverydetails.php" class="register-form" method="post" name="myDeliveryDetails" style="">
                    
                        <h3>Delivery Address</h3>
                        
                        <br><br><br>
                        
                        <h3>Add Delivery Address</h3>
                        
                        <div style="margin-bottom: 20px; margin-top: 20px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 98%">Select An Existing Address: </label>
                            </div>
                            <div class="inputBox">
                                <select required style="width: 100%; color: #666;" name="selectaddress" id="selectaddress" class="box">
                                    <option value="">- Select an Address -</option>
                                    <option value="Address1">Address 1</option>
                                    <option value="Address2">Address 2</option>
                                </select>
                            </div>
                        </div>
                                                
                        
                        <div style="margin-bottom: 20px; margin-top: 20px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 98%">Address: </label>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 98%" id="user_address1" name="user_address1" type="text" placeholder="Enter your Address" class="box" maxlength="250">
                            </div>
                        </div>
                        
                        
                        <div style="margin-bottom: 20px; margin-top: 20px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">Unit Number: </label>
                                <label style="width: 49%">Postal Code: </label>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 49%" id="user_postalcode1" name="user_unitno" type="text" placeholder="Enter Unit No" class="box" maxlength="3">
                                <input required style="width: 49%" id="user_postalcode1" name="user_postalcode1" type="text" placeholder="Enter Postal Code" class="box" maxlength="6" pattern="^[0-9]{6}$" >
                            </div>
                        </div>
                        
                        
                        
                        <br><br><br>
                        
                        
                        <h3>Recipient's Information</h3>
                        
                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 98%">Name: </label>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 98%" id="user_name" name="user_name" type="text" class="box">
                            </div>
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">Phone No: </label>
                                <label style="width: 49%">Email: </label>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 49%" id="user_phonenum" name="user_phonenum" type="text" class="box" value="<?php echo $rowCust['telephone'] ?>">
                                <input required style="width: 49%" id="user_email" name="user_email" type="email" class="box" value="<?php echo $rowCust['email'] ?>">
                            </div>
                        </div>
                        
                        <div class="inputBox">
                            <!--<input type="submit" style="width: 98%" name="saveandcontinue" value="Save and Continue" class="btn">-->
                            <a href="TimeSlot.php" style="width: 98%; margin-top: 40px; margin-bottom: 20px; text-align: center;" class="btn">Save and Continue</a>
                        </div>
                    
                    </form>
                    
                </div>
                                
            </section>
            
            <!-- footer section starts  -->
            <?php include "footer.php"; ?>
            <!-- footer section ends -->
        </body>
</html>