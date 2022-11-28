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
            <p> <a href="order-summary.php"> Order Summary >></a>Checkout</p>
        </div>

        <section class="myShoppingCart cartcontainer contact">                
            <div class="row">
                <form action="receipt.php" class="register-form" method="post" name="myDeliveryDetails">
                    <h3><img src="image/location.png" alt="location" class="iconsize">Delivery Address</h3>
                    <input type="hidden" id="address-id" name="address_id" value="">
                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 98%">Select a Saved Address Profile: </label>
                        </div>
                        <div class="inputBox">
                            <select required style="width: 100%; color: #666;" name="address_alias" id="alias-dropdown" class="box">
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 98%">Address: </label>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 100%" name="address_address" id="address-field" type="text" placeholder="Enter your Address" class="box" maxlength="250">
                        </div>
                    </div>
                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 49%">Unit Number: </label>
                            <label style="width: 49%">Postal Code: </label>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 49%" name="address_unit_number" id="unit-no-field" type="text" placeholder="Enter Unit No" class="box" maxlength="3">
                            <input required style="width: 49%" name="address_postal_code" id="postal-code-field" type="text" placeholder="Enter Postal Code" class="box" maxlength="6" pattern="^[0-9]{6}$" >
                        </div>
                    </div>

                    <h3><img src="image/credit-card.png" alt="location" class="iconsize">Payment Method</h3>
                    <input type="hidden" id="payment-id" name="payment_id" value="">
                    <div style="margin-bottom: 20px; margin-top: 20px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 98%">Select An Existing Card Profile: </label>
                        </div>
                        <div class="inputBox">
                            <select required style="width: 100%; color: #666;" name="payment_type" id="credit-dropdown" class="box">
                            </select>
                        </div>
                    </div>
                    <div style="margin-bottom: 15px;">                            
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <p>Owner: </p>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 100%" name="payment_owner" id="owner-field" type="text" placeholder="Enter your Card Name" class="box" maxlength="250" pattern="\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+">
                        </div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <p>Account No: </p>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 100%" name="payment_account_no" id="account-no-field" type="text" placeholder="Enter your Account Number" class="box" minlength="15" maxlength="16" pattern="^[0-9]{15,16}">
                        </div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 49%">Expiry Date: </label>
                            <label style="width: 49%">CVV: </label>
                        </div>
                        <div class="inputBox">
                            <input required style="width: 49%; font-size: 1.4rem; color: #666;" name="payment_expiry_date" id="expiry-date-field" type="text" placeholder="Enter your Expiry Date" class="box">
                            <input required style="width: 49%; font-size: 1.4rem; color: #666;" name="payment_cvv" id="cvv-field" type="password" placeholder="Enter your CVV" maxlength="3" pattern="^[0-9]{3}" class="box">
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