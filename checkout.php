<!DOCTYPE html>
<?php
session_start();
?>
<script defer src="js/checkout.js"></script>

<html lang="en">
    <title>Checkout</title>
    <?php
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends --> 

        <div class="heading">
            <h1>Cart</h1>
            <p> <a href="ordersummary.php"> Order Summary >></a>Checkout</p>
        </div>

        <section class="myShoppingCart cartcontainer contact">                
            <div class="row">
                <form action="#" class="register-form" method="post" name="myDeliveryDetails">
                    <h3><img src="image/location.png" alt="location" class="iconsize">Delivery Address</h3>

                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 98%">Select a Saved Address Profile: </label>
                        </div>
                        <div class="inputBox">
                            <select required style="width: 100%; color: #666;" name="selectaddress" id="alias-dropdown" class="box">
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 98%">Address: </label>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 100%" id="address-field" type="text" placeholder="Enter your Address" class="box" maxlength="250">
                        </div>
                    </div>
                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 49%">Unit Number: </label>
                            <label style="width: 49%">Postal Code: </label>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 49%" id="unit-no-field" type="text" placeholder="Enter Unit No" class="box" maxlength="3">
                            <input required style="width: 49%" id="postal-code-field" type="text" placeholder="Enter Postal Code" class="box" maxlength="6" pattern="^[0-9]{6}$" >
                        </div>
                    </div>

                    <h3><img src="image/credit-card.png" alt="location" class="iconsize">Payment Method</h3>

                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 98%">Select An Existing Card Profile: </label>
                        </div>
                        <div class="inputBox">
                            <select required style="width: 100%; color: #666;" name="selectaddress" id="credit-dropdown" class="box">
                                <option value="">- Select a Card -</option>
                                <option value="Card1">Card 1</option>
                                <option value="Card2">Card 2</option>
                            </select>
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">                            
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <p>Owner: </p>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 100%" id="owner-field" name="user_owner" type="text" placeholder="Enter your Card Name" class="box" maxlength="250" pattern="\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+">
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <p>Account No: </p>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 100%" id="account-no-field" name="user_accountno" type="text" placeholder="Enter your Account Number" class="box" minlength="15" maxlength="16" pattern="^[0-9]{15,16}">
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 49%">Expiry Date: </label>
                            <label style="width: 49%">CVV: </label>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 49%; font-size: 1.4rem; color: #666;" id="expiry-date-field" name="user_cardexpirydate" type="text" placeholder="Enter your Expiry Date" class="box">
                            <input required style="width: 49%; font-size: 1.4rem; color: #666;" id="cvv-field" name="user_ccv" type="password" placeholder="Enter your CVV" maxlength="3" pattern="^[0-9]{3}" class="box">
                        </div>
                    </div>
                    <div style="margin-bottom: 15px;">
                    <input type="submit" value="Submit" style="width: 100%; margin-top: 40px; text-align: center;" class="btn">
                    </div>
                </form>
            </div>
        </section>

        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>