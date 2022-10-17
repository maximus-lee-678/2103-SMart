<!DOCTYPE html>
<html lang="en">
    <?php session_start();
    include "head.php"; ?>
    <body>
        <!-- header section starts  -->
        <?php include "nav.php"; ?>
        <!-- header section ends -->


        <section class="contact" style="margin-top: 100px; margin-bottom: 20px;">


            <div class="row">

                <img src="image/loginpicture1.jpg" alt="Login and register Image" class="map" style="width: 50%;">


                <form action="process_login.php" class="login-form" method="post" name="myLoginForm">
                    <h3>LOGIN FORM</h3>


                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                        <p>Email: </p>
                    </div>
                    <div class="inputBox">
                        <input required id="user_emailaddress" name="user_emailaddress" type="email" placeholder="Enter your email" class="box" maxlength="250">
                    </div>


                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                        <p>Password: </p>
                    </div>
                    <div class="inputBox">
                        <input required id="user_password" name="user_password" type="password" placeholder="Enter your password" class="box" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$">
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
                        <input type="submit" name="loginaccount" value="Login" class="btn" onclick="myFunction()">
                    </div>

                    <div class="inputBox">
                        <input type="reset" value="Reset" class="btn" />
                    </div>

                    <div class="inputBox" style="font-size: 1.4rem; margin-top: 20px;">
                        <p>forget password? <a href="#" style="color: #bac34e;">click here</a></p>
                    </div>
                    <div class="inputBox" style="font-size: 1.4rem;">
                        <p>don't have an account? <a href="register.php" style="color: #bac34e;">create one</a></p>
                    </div>

                </form>


            </div>

        </section>

        <!-- footer section starts  -->
<?php include "footer.php"; ?>
        <!-- footer section ends -->


    </body>
</html>