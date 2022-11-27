<?php
include "helper-functions.php";

session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {

    $conn = make_connection();
    $id = (int) sanitize_input($_SESSION["id"]);
    $is_Staff = isset($_SESSION["staff"]) && $_SESSION["staff"];

    if ($is_Staff) {

        $queryProfile = "SELECT * FROM Staff WHERE id = ?";
        $resultProfile = payload_deliver($conn, $queryProfile, "i", $params = array($id));

        if ($resultProfile->num_rows > 0) {
            $rowProfile = $resultProfile->fetch_assoc();
        } else {
            echo "Staff information not found!" . $id;
            exit();
        }
    } else {
        // Prepare the statement:        
        $queryProfile = "SELECT * FROM Customer WHERE id = ?";
        $queryAddress = "SELECT * FROM Customer_Address WHERE cust_id = ? and active = true";
        $queryPayment = "SELECT * FROM Customer_Payment WHERE cust_id = ? and active = true";

        $resultProfile = payload_deliver($conn, $queryProfile, "i", $params = array($id));
        $resultAddress = payload_deliver($conn, $queryAddress, "i", $params = array($id));
        $resultPayment = payload_deliver($conn, $queryPayment, "i", $params = array($id));

        if ($resultProfile->num_rows > 0) {
            $rowProfile = $resultProfile->fetch_assoc();
        } else {
            echo "Customer information not found!";
            exit();
        }
    }
} else {
    header("Location: login.php");
    exit;
}
?>
<html lang="en">
    <title>Edit Profile</title>
    <?php
    include "head.php";
    ?> 
    <script src="js/ajax-profile.js" type="text/javascript"></script>
    <body>
        <?php include "nav.php"; ?>
        <div class="heading">
            <h1>My Profile</h1>
        </div>



        <section class="contact" style="margin-top: 50px; margin-bottom: 800px;">

            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'myprofile')" id="defaultOpen">My Profile</button>
                <button class="tablinks" onclick="openCity(event, 'changepassword')">Change Password</button>
                <?php
                if (!$is_Staff) {
                    ?>
                    <button class="tablinks" onclick="openCity(event, 'myaddress')">My Address</button>                
                    <button class="tablinks" onclick="openCity(event, 'mypayment')">My Payment</button>
                    <?php
                }
                ?>
            </div>

            <div id="myprofile" class="tabcontent">
                <div class="row">


                    <form action="" class="register-form" method="post" name="myUserProfileForm" style="">
                        <h3>My User Profile</h3>

<!--                        <div style="margin-bottom: 15px;">
                            <img src="image/pic-6.png" alt="Girl in a jacket" width="200" height="200" style="margin-top: 20px; margin-bottom: 20px;">
                        </div>-->

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">First Name: </label>
                                <label style="width: 49%">Last Name: </label>
                            </div>
                            <div class="inputBox">
                                <input readonly=false style="width: 49%" id="user_firstname" name="user_firstname" type="text" class="box" value="<?php echo $rowProfile['first_name'] ?>">
                                <input readonly=false style="width: 49%" id="user_lastname" name="user_lastname" type="text" class="box" value="<?php echo $rowProfile['last_name'] ?>">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">Phone No: </label>
                                <label style="width: 49%">Email: </label>
                            </div>
                            <div class="inputBox">
                                <input readonly=false style="width: 49%" id="user_phonenum" name="user_phonenum" type="text" class="box" value="<?php echo $rowProfile['telephone'] ?>">
                                <input readonly=false style="width: 49%" id="user_email" name="user_email" type="email" class="box" value="<?php echo $rowProfile['email'] ?>">
                            </div>
                        </div>

                        <div class="inputBox" style="font-size: 1.4rem; color: #666; margin:5px">
                            <p hidden id="submission_feedback1"></p>
                        </div>

                        <div class="inputBox">
                            <input type="button" style="width: 98%" id="editmyprofileBtn" name="editmyprofile" value="Edit My Profile" class="btn">
                        </div>

                        <div class="inputBox">
                            <input type="hidden" style="width: 98%" id="updatemyprofileBtn" name="updatemyprofile" value="Update My Profile" class="btn">
                        </div>

                        <div class="inputBox">
                            <input type="hidden" style="width: 98%" id="cancelBtn" name="cancel" value="Cancel" class="btn">
                        </div>
                    </form>
                </div>
            </div>

            <div id="changepassword" class="tabcontent">
                <div class="row">
                    <form action="" class="register-form" method="post" name="myChangePasswordForm">

                        <h3>Change Password</h3>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>Current Password: </p>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 100%" id="user_old_password" name="user_old_password" type="password" placeholder="Enter your Password" class="box" maxlength="10" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>New Password: </p>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 100%" id="user_new_password" name="user_new_password" type="password" placeholder="Enter your Password" class="box" maxlength="10" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <p>Confirm New Password: </p>
                            </div>
                            <div class="inputBox">
                                <input required style="width: 100%" class="box" type="password" id="user_confirm_password" name="user_confirm_password" maxlength="10" placeholder="Confirm Password">
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

                        <div class="inputBox" style="font-size: 1.4rem; color: #666; margin:5px">
                            <p hidden id="submission_feedback3"></p>
                        </div>

                        <div class="inputBox">
                            <input type="button" style="width: 98%" id="changepasswordBtn" name="changepassword" value="Update" class="btn">
                        </div>
                    </form>
                </div>
            </div>

            <?php
            if (!$is_Staff) {
                ?>
                <div id="myaddress" class="tabcontent">
                    <div class="row">
                        <form action="#" class="register-form" method="post" name="myaddressForm">

                            <h3>My Address</h3>

                            <table id="myaddresstable" border="1" width="100%">
                                <tr id="tHeader" style="background: #6D6875; color: white;">
                                    <th>Alias</th>
                                    <th>Address</th>
                                    <th>Unit Number</th>
                                    <th>Postal Code</th>
                                </tr>
                                <tbody id="myaddressdata">
                                    <?php
                                    if ($resultAddress->num_rows > 0) {
                                        while ($rowAddress = $resultAddress->fetch_assoc()) {
                                            ?>
                                            <tr id = "address_<?php echo $rowAddress['id'] ?>" class="addressRow">
                                                <td class ="alias_data"><?php echo $rowAddress['alias'] ?></td>    
                                                <td class ="address_data"><?php echo $rowAddress['address'] ?></td>
                                                <td class ="unitno_data"><?php echo $rowAddress['unit_no'] ?></td>
                                                <td class ="postal_data"><?php echo $rowAddress['postal_code'] ?></td>                                            
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <div style="margin-bottom: 20px; margin-top: 20px;">

                                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                    <label style="width: 98%">Alias: </label>                                
                                </div>
                                <div class="inputBox">
                                    <input required style="width: 98%" id="user_alias" name="user_alias" type="text" placeholder="Enter your Alias (Eg: Home)" class="box" maxlength="250">
                                </div>
                            </div>

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
                                    <input id="user_addressid1" name="user_addressid1" type="hidden">
                                    <input required style="width: 49%" id="user_unitno" name="user_unitno" type="text" placeholder="Enter Unit No" class="box" maxlength="10">
                                    <input required style="width: 49%" id="user_postalcode1" name="user_postalcode1" type="text" placeholder="Enter Postal Code" class="box" maxlength="6" pattern="^[0-9]{6}$" >
                                </div>
                            </div>


                            <div class="inputBox" style="font-size: 1.4rem; color: #666; margin:5px">
                                <p hidden id="submission_feedback2"></p>
                            </div>

                            <div class="inputBox">
                                <input type="button" style="width: 32%" id="updateaddressBtn" name="updateaddressBtn" value="Update" class="btn">
                                <input type="button" style="width: 32%" id="addaddressBtn" name="addaddressBtn" value="Add" class="btn">
                                <input type="button" style="width: 32%" id="deleteaddressBtn" name="deleteaddressBtn" value="Delete" class="btn">
                            </div>


                        </form>
                    </div>
                </div>
                <div id="mypayment" class="tabcontent">
                    <div class="row">
                        <form action="" class="register-form" method="post" name="mynewcardForm">

                            <h3>My Card</h3>

                            <table id="mycardtable" border="1" width="100%" style="margin-bottom: 30px;">
                                <!--if got card-->
                                <tr id="tHeader" style="background: #6D6875; color: white;">
                                    <th>Payment Type</th>
                                    <th>Owner</th>
                                    <th>Account No.</th>
                                    <th>Expiry Date</th>
                                </tr>
                                <!--if no card-->
                                <div class="resultContainer3">
                                    <h4>There is no card added currently! Please add a card.</h4>
                                </div>
                                <tbody id="mycarddata">
                                    <?php
                                    if ($resultPayment->num_rows > 0) {
                                        while ($rowPayment = $resultPayment->fetch_assoc()) {
                                            ?>
                                            <tr id = "payment_<?php echo $rowPayment['id'] ?>" class="paymentRow">
                                                <td class ="paytype_data"><?php echo $rowPayment['payment_type'] ?></td>
                                                <td class ="owner_data"><?php echo $rowPayment['owner'] ?></td>
                                                <td class ="acc_data"><?php echo $rowPayment['account_no'] ?></td>
                                                <td class ="expiry_data"><?php echo $rowPayment['expiry'] ?></td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>

                            <div class="inputBox">
                                <input id="user_payid" name="user_payid" type="hidden">
                                <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                    <p>Payment Type: </p>
                                </div>
                                <select required style="width: 100%; color: #666;" name="user_payment_type" id="user_payment_type" class="box">
                                    <option value="">- Payment Type -</option>
                                    <option value="Visa">Visa</option>
                                    <option value="Master">Master</option>                                    
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

                            <div class="inputBox" style="font-size: 1.4rem; color: #666; margin:5px">
                                <p hidden id="submission_feedback4"></p>
                            </div>

                            <div class="inputBox">
                                <input type="button" style="width: 32%" id="updatecardBtn" name="updatecardBtn" value="Update" class="btn">
                                <input type="button" style="width: 32%" id="addcardBtn" name="addcardBtn" value="Add" class="btn">
                                <input type="button" style="width: 32%" id="deletecardBtn" name="deletecardBtn" value="Delete" class="btn">
                            </div>
                        </form>
                    </div>
                </div>
                <?php
            }
            ?>
        </section>


        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>
