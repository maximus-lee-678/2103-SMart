<?php
session_start();
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true) {
    $config = parse_ini_file('../../private/db-config.ini');
    $conn = new mysqli($config['servername'], $config['username'],
            $config['password'], $config['dbname']);

    $custId = (int) sanitize_input($_SESSION["id"]);

    if ($conn->connect_error) {
        echo "Error";
        $conn->close();
    } else {
        // Prepare the statement:        
        $stmtCust = $conn->prepare("SELECT * FROM Customer WHERE id = ?");
        $stmtCust->bind_param("i", $custId);
        $stmtAddress = $conn->prepare("SELECT * FROM Customer_Address WHERE cust_id = ?");
        $stmtAddress->bind_param("i", $custId);
        $stmtPayment = $conn->prepare("SELECT * FROM Customer_Payment WHERE cust_id = ?");
        $stmtPayment->bind_param("i", $custId);

        if (!$stmtCust->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            exit();
        } else {

            $resultCust = $stmtCust->get_result();
            if ($resultCust->num_rows > 0) {
                $rowCust = $resultCust->fetch_assoc();
            } else {
                echo "Customer information not found!";
                exit();
            }
            $stmtCust->close();
        }
    }
} else {
    header("Location: login.php");
    exit;
}

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
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

        

        <section class="contact" style="margin-top: 50px; margin-bottom: 650px;">

            <div class="tab">
                <button class="tablinks" onclick="openCity(event, 'myprofile')" id="defaultOpen">My Profile</button>
                <button class="tablinks" onclick="openCity(event, 'myaddress')">My Address</button>
                <button class="tablinks" onclick="openCity(event, 'changepassword')">Change Password</button>
                <button class="tablinks" onclick="openCity(event, 'addnewcard')">Add New Card</button>
                <button class="tablinks" onclick="openCity(event, 'deletemyaccount')">Delete My Account</button>
            </div>

            <div id="myprofile" class="tabcontent">
                <div class="row">


                    <form action="" class="register-form" method="post" name="myUserProfileForm" style="">
                        <h3>My User Profile</h3>

                        <div style="margin-bottom: 15px;">
                            <img src="image/pic-6.png" alt="Girl in a jacket" width="200" height="200" style="margin-top: 20px; margin-bottom: 20px;">
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">First Name: </label>
                                <label style="width: 49%">Last Name: </label>
                            </div>
                            <div class="inputBox">
                                <input readonly=false style="width: 49%" id="user_firstname" name="user_firstname" type="text" class="box" value="<?php echo $rowCust['first_name'] ?>">
                                <input readonly=false style="width: 49%" id="user_lastname" name="user_lastname" type="text" class="box" value="<?php echo $rowCust['last_name'] ?>">
                            </div>
                        </div>

                        <div style="margin-bottom: 15px;">
                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">Phone No: </label>
                                <label style="width: 49%">Email: </label>
                            </div>
                            <div class="inputBox">
                                <input readonly=false style="width: 49%" id="user_phonenum" name="user_phonenum" type="text" class="box" value="<?php echo $rowCust['telephone'] ?>">
                                <input readonly=false style="width: 49%" id="user_email" name="user_email" type="email" class="box" value="<?php echo $rowCust['email'] ?>">
                            </div>
                        </div>

                        <!--                        <div style="margin-bottom: 15px;">
                                                    <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                                        <label style="width: 59%">Address: </label>
                                                        <label style="width: 39%">Postal Code: </label>
                                                    </div>
                                                    <div class="inputBox">
                                                        <input readonly="true" style="width: 59%" id="user_address1" name="user_address1" type="text" class="box" value="<?php echo $row['address'] ?>">
                                                        <input readonly="true" style="width: 39%" id="user_postalcode1" name="user_postalcode1" type="text" class="box" value="<?php echo $row['postal_code'] ?>">
                                                    </div>
                                                </div>-->

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

            <div id="myaddress" class="tabcontent">
                <div class="row">
                    <form action="#" class="register-form" method="post" name="myaddressForm">

                        <h3>My Address</h3>

                        <table id="myaddresstable" border="1" width="100%">
                            <tr id="tHeader" style="background: #6D6875; color: white;">
                                <th>Address</th>
                                <th>Floor Number</th>
                                <th>Unit Number</th>
                                <th>Postal Code</th>
                            </tr>
                            <tbody id="myaddressdata">
                                <?php
                                $stmtAddress->execute();
                                $resultAddress = $stmtAddress->get_result();
                                if ($resultAddress->num_rows > 0) {

                                    while ($rowAddress = $resultAddress->fetch_assoc()) {
                                        ?>
                                        <tr id = "address_<?php echo $rowAddress['id'] ?>" class="addressRow">
                                            <td class ="address_data"><?php echo $rowAddress['address'] ?></td>
                                            <td class ="postal_data"><?php echo $rowAddress['postal_code'] ?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                $stmtAddress->close();
                                ?>
                            </tbody>
                        </table>


                        <div style="margin-bottom: 20px; margin-top: 20px;">

                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 59%">Address: </label>
                                <label style="width: 39%">Postal Code: </label>
                            </div>
                            <div class="inputBox">
                                <input id="user_addressid1" name="user_addressid1" type="hidden">
                                <input required style="width: 59%" id="user_address1" name="user_address1" type="text" placeholder="Enter your Address" class="box" maxlength="250">
                                <input required style="width: 39%" id="user_postalcode1" name="user_postalcode1" type="text" placeholder="Enter Postal Code" class="box" maxlength="6" pattern="^[0-9]{6}$" >
                            </div>
                        </div>
                        
                        
                        <div style="margin-bottom: 20px; margin-top: 20px;">

                            <div class="inputBox" style="font-size: 1.4rem; color: #666;">
                                <label style="width: 49%">Floor Number: </label>
                                <label style="width: 49%">Unit Number: </label>
                            </div>
                            <div class="inputBox">
                                <input id="user_addressid1" name="user_addressid1" type="hidden">
                                <input required style="width: 49%" id="user_address1" name="user_floorno" type="text" placeholder="Enter Floor No" class="box" maxlength="20">
                                <input required style="width: 49%" id="user_postalcode1" name="user_unitno" type="text" placeholder="Enter Unit No" class="box" maxlength="3">
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
        

        <!-- footer section starts  -->
        <?php include "footer.php"; ?>
        <!-- footer section ends -->
    </body>
</html>
