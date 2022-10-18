<html lang="en">
    <title>Edit Profile</title>
    <?php
    session_start();
    include "head.php";
    ?> 
    <body>
        <?php include "nav.php"; ?>
        <div class="heading">
            <h1>My Profile</h1>
        </div>

        <br><br><br><br><br><br><br><br>

        <section class="contact" style="margin-bottom: 20px;">

            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'myprofile')" id="defaultOpen">My Profile</button>
                <button class="tablinks" onclick="openCity(event, 'editaccount')">Add / Edit Address</button>
                <button class="tablinks" onclick="openCity(event, 'changepassword')">Change Password</button>
                <button class="tablinks" onclick="openCity(event, 'addnewcard')">Add New Card</button>
                <button class="tablinks" onclick="openCity(event, 'deletemyaccount')">Delete My Account</button>
            </div>

            <div id="myprofile" class="tabcontent">
                <div class="row">


                    <form action="EditMyProfile.php" class="register-form" method="post" name="myUserProfileForm" style="">
                        <h3>My User Profile</h3>

                        <div style="margin-bottom: 15px;">
                            <img src="image/blog-2.jpg" alt="Girl in a jacket" width="200" height="200" style="margin-top: 20px; margin-bottom: 20px;">
                        </div>


                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 98%">Full Name: </label>
                            </div>
                            <div class="inputBox">
                                <input readonly="true" style="width: 98%" id="user_fullname" name="user_fullname" type="text" class="box">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">Phone No: </label>
                                <label style="width: 49%">Email: </label>
                            </div>
                            <div class="inputBox">
                                <input readonly="true" style="width: 49%" id="user_phonenum" name="user_phonenum" type="text" class="box">
                                <input readonly="true" style="width: 49%" id="user_email" name="user_email" type="email" class="box">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 59%">Address: </label>
                                <label style="width: 39%">Postal Code: </label>
                            </div>
                            <div class="inputBox">
                                <input readonly="true" style="width: 59%" id="user_address1" name="user_address1" type="text" class="box">
                                <input readonly="true" style="width: 39%" id="user_postalcode1" name="user_postalcode1" type="text" class="box">
                            </div>
                        </div>


                        <div class="inputBox">
                            <input type="submit" style="width: 98%" name="editmyprofile" value="Edit My Profile" class="btn">
                        </div>

                    </form>
                </div>
            </div>

            <div id="editaccount" class="tabcontent">
                <div class="row">
                    <form action="#" class="register-form" method="post" name="mynewaddressForm">

                        <h3>Add / Edit Address</h3>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 59%">Address: </label>
                                <label style="width: 39%">Postal Code: </label>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 59%" id="user_address1" name="user_address1" type="text" placeholder="Enter your Address" class="box" maxlength="250">
                                <input required style="width: 39%" id="user_postalcode1" name="user_postalcode1" type="text" placeholder="Enter Postal Code" class="box" maxlength="6" pattern="^[0-9]{6}$">
                            </div>
                        </div>

                        <div class="inputBox">
                            <input type="submit" style="width: 98%" name="updateaddress" value="Update" class="btn">
                        </div>

                    </form>
                </div>
            </div>

            <div id="changepassword" class="tabcontent">
                <div class="row">
                    <form action="process_UpdatePassword.php" class="register-form" method="post" name="myChangePasswordForm">

                        <h3>Change Password</h3>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>Password: </p>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 100%" id="user_password" name="user_password" type="password" placeholder="Enter your Password" class="box" maxlength="10" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$">
                            </div>
                        </div>


                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>Confirm Password: </p>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 100%" class="box" type="password" id="pwd_confirm" name="pwd_confirm" maxlength="10" placeholder="Confirm Password">
                            </div>
                        </div>

                        <div class="inputBox" style="font-size: 1.4rem; color: #666; margin:20px">
                            <p>Password must Contain (8-10 Characters): 
                                <br> 1. At least one uppercase letter 
                                <br> 2. At least one lowercase letter
                                <br> 3. At least one number 
                                <br> 4. At least one special character
                            </p>
                        </div>

                        <div class="inputBox">
                            <input type="submit" style="width: 98%" name="changepassword" value="Update" class="btn">
                        </div>
                    </form>
                </div>
            </div>

            <div id="addnewcard" class="tabcontent">
                <div class="row">
                    <form action="process_addnewcard.php" class="register-form" method="post" name="mynewcardForm">

                        <h3>Add New Card</h3>

                        <div class="inputBox">
                            <select required style="width: 100%; color: #666;" name="ismember" id="ismember" class="box">
                                <option value="">- Payment Type -</option>
                                <option value="Visa">Visa</option>
                                <option value="Master">Master</option>
                                <option value="Amex">Amex</option>
                                <option value="PayPal">PayPal</option>
                            </select>
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
                                <label style="width: 98%">Expiry Date: </label>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 98%; font-size: 1.4rem; color: #666;" id="user_cardexpirydate" name="user_cardexpirydate" type="date" placeholder="Enter your Expiry Date" class="box">
                            </div>
                        </div>


                        <div class="inputBox">
                            <input type="submit" style="width: 98%" name="addnewcard" value="Add" class="btn">
                        </div>
                    </form>
                </div>
            </div>

            <div id="deletemyaccount" class="tabcontent">
                <div class="row">
                    <form action="process_deleteaccount.php" class="register-form" method="post" name="deleteaccountform">

                        <h3>Delete My Account</h3>

                        <div class="inputBox">
                            <input type="submit" style="width: 98%" name="deleteaccount" value="Delete" class="btn">
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>
        <br><br><br><br><br><br><br><br><br><br>

        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>
