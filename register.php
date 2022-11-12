<html lang="en">
    <<title>Register</title>
    <?php
    session_start();
    include "head.php";
    ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->

        <section class="contact" style="margin-top: 100px; margin-bottom: 20px;">
            <div class="row">

                <img src="image/registerimage1.webp" alt="Login and register Image" class="img2">

                <form action="process_register.php" class="register-form" method="post" name="myRegisterForm">
                    <h3>REGISTRATION FORM</h3>

                    <!--first and last name-->
                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 49%">First Name: </label>
                            <label style="width: 49%">Last Name: </label>
                        </div>

                        <div class="inputBox">
                            <input required style="width: 49%" id="user_firstname" name="user_firstname" type="text" placeholder="Enter your First Name" class="box" maxlength="45" pattern="\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+">
                            <input required style="width: 49%" id="user_lastname" name="user_lastname" type="text" placeholder="Enter your Last Name" class="box" maxlength="45" pattern="\b([A-ZÀ-ÿ][-,a-z. ']+[ ]*)+">
                        </div>
                    </div>

                    <!--phone and email-->
                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 49%">Phone No: </label>
                            <label style="width: 49%">Email: </label>
                        </div>

                        <div class="inputBox">
                            <input required style="width: 49%" id="user_phonenum" name="user_phonenum" type="text" placeholder="Enter your Phone No" class="box" minlength="8" maxlength="8" pattern="^[689]\d{7}$">
                            <input required style="width: 49%" id="user_email" name="user_email" type="email" placeholder="Enter your Email" class="box" maxlength="200" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$">
                        </div>
                    </div>

                    <!--Address and postal code-->
                    <div style="margin-bottom: 20px; margin-top: 20px;">

                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 98%">Address: </label>                                
                        </div>
                        <div class="inputBox">
                            <input id="user_addressid1" name="user_addressid1" type="hidden">
                            <input required style="width: 98%" id="user_address1" name="user_address1" type="text" placeholder="Enter your Address" class="box" maxlength="250">
                        </div>
                    </div>


                    <div style="margin-bottom: 20px; margin-top: 20px;">

                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <label style="width: 49%">Unit Number: </label>
                            <label style="width: 49%">Postal Code: </label>                                
                        </div>
                        <div class="inputBox">
                            <input required style="width: 49%" id="user_unitno" name="user_unitno" type="text" placeholder="Enter Unit No" class="box" maxlength="10">
                            <input required style="width: 49%" id="user_postalcode1" name="user_postalcode1" type="text" placeholder="Enter Postal Code" class="box" maxlength="6" pattern="^[0-9]{6}$" >
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <p>Password: </p>
                        </div>

                        <div class="inputBox">
                            <input required style="width: 100%" id="pwd" name="user_password" type="password" placeholder="Enter your Password" class="box" maxlength="10" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$">
                        </div>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                            <p>Confirm Password: </p>
                        </div>

                        <div class="inputBox">
                            <input required style="width: 100%" class="box" type="password" id="pwd_confirm" name="user_password_confirm" maxlength="10" placeholder="Confirm Password">
                        </div>
                    </div>

                    <!--            <div class="inputBox">
                                    <select required style="width: 100%; color: #666;" name="ismember" id="ismember" class="box">
                                        <option value="">- Are you a member? -</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>-->

                    <div class="inputBox" style="font-size: 1.4rem; color: #666; margin:20px">
                        <p>Password must Contain (8-10 Characters): 
                            <br> 1. At least one uppercase letter 
                            <br> 2. At least one lowercase letter
                            <br> 3. At least one number 
                            <br> 4. At least one special character
                        </p>
                    </div>

                    <div class="inputBox">
                        <input type="submit" name="registeraccount" value="Register Now" class="btn">
                    </div>

                    <div class="inputBox">
                        <input type="reset" value="Reset" class="btn" />
                    </div>

                    <div class="inputBox" style="font-size: 1.4rem; margin-top: 20px;">
                        <p>already have an account? <a href="login.php" style="color: #bac34e;">sign in</a></p>
                    </div>

                </form>
            </div>
        </section>

        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->

    </body>
</html>